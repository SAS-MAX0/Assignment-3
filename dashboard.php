<?php
// alright, dashboard time. obviously need to start the session right away 
// because we need to know who we're talking to.
session_start();
require_once 'includes/db.php';

// standard bouncer logic. if you skipped the login page or your session timed out, 
// you get kicked right back to the login screen.
if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['uid'];
$error = '';
$success = '';

// so here's a fun workaround. i check for a hidden 'action' input here.
// originally i just checked if it was a POST request, but then i realized 
// if i ever add a 'delete' button or another form to this page, it would 
// try to run this create script and make a weird blank project. this ensures 
// we only run this block when they specifically hit the 'add project' button.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_project') {
    $title = trim($_POST['title']);
    $start_date = trim($_POST['start_date']);
    
    // ah, the classic mysql date trap again. if someone left the end date blank, 
    // the form sent an empty string (""), which made mysql absolutely lose its mind 
    // and throw fatal errors. so if it's empty, i intercept it and force it to a php null here.
    $end_date = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
    
    $short_description = trim($_POST['short_description']);
    $phase = $_POST['phase'];

    // basic check so we don't have nameless projects floating around
    if (empty($title) || empty($start_date) || empty($phase)) {
        $error = 'Title, Start Date, and Phase are required.';
    } else {
        // adding the project. making absolutely sure to pass the $user_id at the end
        // so it's tied to their account and doesn't just dump orphaned projects into the db.
        $stmt = $pdo->prepare("INSERT INTO projects (title, start_date, end_date, short_description, phase, uid) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $start_date, $end_date, $short_description, $phase, $user_id])) {
            $success = 'Project added successfully!';
        } else {
            $error = 'Failed to add project.';
        }
    }
}

// fetching the user's projects to display in the table on the right side. 
// added the ORDER BY start_date DESC because before this, they were 
// just displaying in whatever random order mysql felt like giving them back. 
$stmt = $pdo->prepare("SELECT * FROM projects WHERE uid = ? ORDER BY start_date DESC");
$stmt->execute([$user_id]);
$my_projects = $stmt->fetchAll();

require_once 'includes/header.php';
?>

<h2>Welcome to your Dashboard, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>

<div style="display: flex; gap: 40px; margin-top: 30px; flex-wrap: wrap;">
    
    <div style="flex: 1; min-width: 300px;">
        <h3>Add New Project</h3>
        
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>
        <?php if ($success): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php endif; ?>

        <form method="POST" action="dashboard.php" style="max-width: 100%;">
            <input type="hidden" name="action" value="add_project">
            
            <label for="title">Project Title *</label>
            <input type="text" id="title" name="title" required>

            <label for="start_date">Start Date *</label>
            <input type="date" id="start_date" name="start_date" required>

            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date">

            <label for="phase">Current Phase *</label>
            <select id="phase" name="phase" required>
                <option value="design">Design</option>
                <option value="development">Development</option>
                <option value="testing">Testing</option>
                <option value="deployment">Deployment</option>
                <option value="complete">Complete</option>
            </select>

            <label for="short_description">Short Description</label>
            <textarea id="short_description" name="short_description" rows="4"></textarea>

            <button type="submit">Create Project</button>
        </form>
    </div>

    <div style="flex: 2; min-width: 300px;">
        <h3>My Projects</h3>
        <?php if (count($my_projects) > 0): ?>
            <table style="width: 100%; border-collapse: collapse; text-align: left;">
                <thead>
                    <tr style="border-bottom: 2px solid #ccc;">
                        <th style="padding: 10px;">Title</th>
                        <th style="padding: 10px;">Phase</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($my_projects as $project): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><?php echo htmlspecialchars($project['title']); ?></td>
                            
                            <td style="padding: 10px; text-transform: capitalize;"><?php echo htmlspecialchars($project['phase']); ?></td>
                            
                            <td style="padding: 10px;">
                                <a href="edit_project.php?id=<?php echo $project['pid']; ?>" style="color: #0066cc; text-decoration: none;">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You haven't created any projects yet.</p>
        <?php endif; ?>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
