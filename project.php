<?php
// starting the session so the header knows if we're logged in, 
// even though this is a public viewing page.
session_start();
require_once 'includes/db.php';

// okay, i almost broke the site here. originally i just checked if the id existed.
// then i realized if someone typed "index.php?id=awesome-project", the database 
// would scream because it expects a number. added is_numeric to keep it chill.
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$project_id = $_GET['id'];

// big brain moment: i didn't want to just show the user ID (like "Owner: 5").
// that looks super robotic. so i had to use a JOIN to grab the email 
// from the users table. it took me like three tries to remember which 
// column was named what, but we got there.
$stmt = $pdo->prepare("
    SELECT p.title, p.start_date, p.end_date, p.short_description, p.phase, u.email 
    FROM projects p 
    JOIN users u ON p.uid = u.uid 
    WHERE p.pid = ?
");
$stmt->execute([$project_id]);
$project = $stmt->fetch();

// side note: i always put the header require AFTER the redirects.
// if you put it at the top and try to redirect later, php throws 
// a "headers already sent" tantrum and dies. 
require_once 'includes/header.php';
?>

<?php if ($project): ?>
    <div style="max-width: 700px;">
        <h2 style="color: var(--primary);"><?php echo htmlspecialchars($project['title']); ?></h2>
        
        <div style="background: var(--surface); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
            <p style="margin-top: 0;">
                <strong style="color: var(--text-light);">Phase:</strong> 
                <span style="text-transform: capitalize; color: var(--accent); font-weight: bold;">
                    <?php echo htmlspecialchars($project['phase']); ?>
                </span>
            </p>
            <p>
                <strong style="color: var(--text-light);">Start Date:</strong> 
                <?php echo htmlspecialchars($project['start_date']); ?>
            </p>
            <p>
                <strong style="color: var(--text-light);">End Date:</strong> 
                <?php echo $project['end_date'] ? htmlspecialchars($project['end_date']) : '<em style="color: #9CA3AF;">Ongoing / Not specified</em>'; ?>
            </p>
            <p>
                <strong style="color: var(--text-light);">Project Owner:</strong> 
                <a href="mailto:<?php echo htmlspecialchars($project['email']); ?>">
                    <?php echo htmlspecialchars($project['email']); ?>
                </a>
            </p>
            
            <h3 style="margin-top: 25px; border-bottom: 1px solid var(--border); padding-bottom: 8px; font-size: 1.1rem;">Description</h3>
            
            <p style="white-space: pre-wrap; line-height: 1.6; margin-bottom: 0; color: var(--text);"><?php echo htmlspecialchars($project['short_description']); ?></p>
        </div>
        
        <div style="margin-top: 20px;">
            <a href="index.php">&larr; Back to all projects</a>
        </div>
    </div>

<?php else: ?>
    <h2>Project Not Found</h2>
    <p>The project you are looking for does not exist or has been removed.</p>
    <a href="index.php">Return to homepage</a>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
