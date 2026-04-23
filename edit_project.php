<?php
// so i started building this edit page and immediately realized we need to 
// track who is actually logged in. standard session stuff here.
session_start();
require_once 'includes/db.php';

// first roadblock: people were just typing /edit_project.php into the url bar
// and breaking the page because there was no user. so if you aren't logged in, 
// i'm just kicking you right back to the login page.
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit;
}

// okay, this part drove me absolutely crazy. originally i just checked for $_GET['id']. 
// but when you hit the "save" button, the form submits via POST, and the GET parameter 
// vanishes. the page would just crash and boot me to the dashboard. 
// workaround: check for the id in BOTH places so the page survives a form submission.
if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: dashboard.php");
    exit;
}

// using the null coalescing operator here because it's cleaner. 
// grabs the id from get first, falls back to post.
$project_id = $_GET['id'] ?? $_POST['id'];
$user_id = $_SESSION['uid'];
$error = '';
$success = '';

// if the form was actually submitted, let's process the update.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // stripping out extra spaces because someone pasted a title with like 
    // 5 empty lines at the end and it messed up the css later.
    $title = trim($_POST['title']);
    $start_date = trim($_POST['start_date']);
    
    // HUGE WORKAROUND HERE: mysql completely panicked when i tried to save an empty string 
    // into a date column. it kept throwing fatal "incorrect date value" errors. 
    // so if they leave end_date blank, i explicitly force it to be php's null instead 
    // of an empty string. took me an embarrassing amount of time to debug.
    $end_date = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
    $short_description = trim($_POST['short_description']);
    $phase = $_POST['phase'];

    // basic validation so we don't end up with ghost projects in the db
    if (empty($title) || empty($start_date) || empty($phase)) {
        $error = 'Title, Start Date, and Phase are required.';
    } else {
        // the actual save logic. notice the "AND uid = ?" part! 
        // i totally forgot that at first, and realized any logged-in user could 
        // just change the url id and edit SOMEONE ELSE'S project. huge security flaw. 
        // patched that up real quick.
        $stmt = $pdo->prepare("UPDATE projects SET title = ?, start_date = ?, end_date = ?, short_description = ?, phase = ? WHERE pid = ? AND uid = ?");
        if ($stmt->execute([$title, $start_date, $end_date, $short_description, $phase, $project_id, $user_id])) {
            $success = 'Project updated successfully!';
        } else {
            $error = 'Failed to update project.';
        }
    }
}

// now we fetch the project data to populate the html form. 
// yeah, i know it feels backward to do the select AFTER the update logic. 
// i originally did it at the top of the file, but then when you saved a change, 
// the form would reload displaying the OLD data instead of what you just typed. 
// doing it down here ensures the form always shows the freshest db values.
$stmt = $pdo->prepare("SELECT * FROM projects WHERE pid = ? AND uid = ?");
$stmt->execute([$project_id, $user_id]);
$project = $stmt->fetch();

// just in case they tried to guess a project id that doesn't exist (or belongs to someone else)
if (!$project) {
    header("Location: dashboard.php");
    exit;
}

require_once 'includes/header.php';
?>

<h2>Edit Project</h2>

<?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>
<?php if ($success): ?>
    <p class="success"><?php echo $success; ?></p>
<?php endif; ?>

<form method="POST" action="edit_project.php" style="max-width: 500px;">
    
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($project['pid']); ?>">
    
    <label for="title">Project Title *</label>
    <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($project['title']); ?>" required>

    <label for="start_date">Start Date *</label>
    <input type="date" id="start_date" name="start_date" value="<?php echo htmlspecialchars($project['start_date']); ?>" required>

    <label for="end_date">End Date</label>
    <input type="date" id="end_date" name="end_date" value="<?php echo htmlspecialchars($project['end_date'] ?? ''); ?>">

    <label for="phase">Current Phase *</label>
    <select id="phase" name="phase" required>
        <option value="design" <?php echo $project['phase'] === 'design' ? 'selected' : ''; ?>>Design</option>
        <option value="development" <?php echo $project['phase'] === 'development' ? 'selected' : ''; ?>>Development</option>
        <option value="testing" <?php echo $project['phase'] === 'testing' ? 'selected' : ''; ?>>Testing</option>
        <option value="deployment" <?php echo $project['phase'] === 'deployment' ? 'selected' : ''; ?>>Deployment</option>
        <option value="complete" <?php echo $project['phase'] === 'complete' ? 'selected' : ''; ?>>Complete</option>
    </select>

    <label for="short_description">Short Description</label>
    <textarea id="short_description" name="short_description" rows="5"><?php echo htmlspecialchars($project['short_description']); ?></textarea>

    <div style="display: flex; gap: 10px; margin-top: 15px;">
        <button type="submit" style="flex: 1;">Save Changes</button>
        <a href="dashboard.php" style="padding: 8px; background: #eee; text-decoration: none; border: 1px solid #ccc; border-radius: 4px; color: black; text-align: center; flex: 1;">Cancel</a>
    </div>
</form>

<?php require_once 'includes/footer.php'; ?>
