<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['uid'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id']) && !isset($_POST['id'])) {
    header("Location: dashboard.php");
    exit;
}

$project_id = $_GET['id'] ?? $_POST['id'];
$user_id = $_SESSION['uid'];
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $start_date = trim($_POST['start_date']);
    $end_date = !empty($_POST['end_date']) ? trim($_POST['end_date']) : null;
    $short_description = trim($_POST['short_description']);
    $phase = $_POST['phase'];

    if (empty($title) || empty($start_date) || empty($phase)) {
        $error = 'Title, Start Date, and Phase are required.';
    } else {
        $stmt = $pdo->prepare("UPDATE projects SET title = ?, start_date = ?, end_date = ?, short_description = ?, phase = ? WHERE pid = ? AND uid = ?");
        if ($stmt->execute([$title, $start_date, $end_date, $short_description, $phase, $project_id, $user_id])) {
            $success = 'Project updated successfully!';
        } else {
            $error = 'Failed to update project.';
        }
    }
}

$stmt = $pdo->prepare("SELECT * FROM projects WHERE pid = ? AND uid = ?");
$stmt->execute([$project_id, $user_id]);
$project = $stmt->fetch();

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