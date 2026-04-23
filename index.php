<?php
// keeping the session going. even if it's a public page, 
// we might need to know if a user is logged in for the header links.
session_start();
require_once 'includes/db.php';

$search_term = '';
$projects = [];

// okay, search logic time. i went back and forth on whether to use POST or GET here.
// i settled on GET because if you search for "cool robot" and want to send that link 
// to a friend, GET puts the search term right in the url. POST wouldn't do that.
if (isset($_GET['search']) && trim($_GET['search']) !== '') {
    $search_term = trim($_GET['search']);
    
    // this query was a bit of a headache. i wanted people to be able to search 
    // by title OR by the exact date. 
    // workaround: i'm using LIKE for the title but an exact equals (=) for the date. 
    // trying to use LIKE on a date column in mysql is just asking for weird results.
    $stmt = $pdo->prepare("SELECT pid, title, start_date, short_description FROM projects WHERE title LIKE ? OR start_date = ? ORDER BY start_date DESC");
    
    // adding the % wildcards here so it finds the term anywhere in the title.
    $like_term = '%' . $search_term . '%';
    $stmt->execute([$like_term, $search_term]);
    $projects = $stmt->fetchAll();
} else {
    // if there's no search, just grab everything. 
    // kept the ORDER BY here so the newest stuff always sits at the top.
    $stmt = $pdo->query("SELECT pid, title, start_date, short_description FROM projects ORDER BY start_date DESC");
    $projects = $stmt->fetchAll();
}

require_once 'includes/header.php';
?>

<h2>All Projects</h2>

<form method="GET" action="index.php" style="flex-direction: row; max-width: 600px; margin-bottom: 30px; padding: 1rem;">
    
    <input type="text" name="search" placeholder="Search by title or YYYY-MM-DD..." value="<?php echo htmlspecialchars($search_term); ?>" style="flex: 1;">
    
    <button type="submit">Search</button>

    <?php if ($search_term): ?>
        <a href="index.php" style="display: flex; align-items: center; padding: 0 1rem; text-decoration: none; border: 1px solid var(--border); border-radius: 6px; color: var(--text); background: var(--bg);">Clear</a>
    <?php endif; ?>
</form>

<?php if (count($projects) > 0): ?>
    <div style="display: flex; flex-direction: column; gap: 1.5rem; max-width: 700px;">
        <?php foreach ($projects as $project): ?>
            <div style="background: var(--surface); padding: 1.5rem; border-radius: 8px; border: 1px solid var(--border); box-shadow: 0 4px 6px rgba(0,0,0,0.3);">
                
                <h3 style="margin-top: 0; margin-bottom: 10px;">
                    <a href="project.php?id=<?php echo $project['pid']; ?>" style="font-size: 1.2rem;">
                        <?php echo htmlspecialchars($project['title']); ?>
                    </a>
                </h3>

                <p style="margin-top: 0; font-size: 0.9rem; color: var(--text-light);">
                    <strong>Started:</strong> <?php echo htmlspecialchars($project['start_date']); ?>
                </p>

                <p style="margin-bottom: 0;"><?php echo htmlspecialchars($project['short_description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <p style="color: var(--text-light);">No projects found matching your search.</p>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>
