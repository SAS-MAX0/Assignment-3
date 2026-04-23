<?php
session_start();
require_once 'includes/db.php';

$error = '';

// only doing the heavy lifting if they actually hit the submit button.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // trimming the username because one time i accidentally hit space bar 
    // after my name and couldn't log in for ten minutes. 
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // just making sure they didn't leave anything blank. 
    // simple but keeps the database from getting confused.
    if (empty($username) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // okay, so originally i tried to find the user AND the password in one query.
        // bad idea. since i'm hashing passwords, i have to fetch the user first 
        // and THEN use php's verify function to see if the hash matches.
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        // huge fix here: password_verify is the only way to check hashed passwords.
        // i spent way too long trying to manually compare them before i 
        // remembered that's not how security works lol.
        if ($user && password_verify($password, $user['password'])) {
            // we're in! saving the user id and name so the rest of 
            // the site knows who is logged in.
            $_SESSION['uid'] = $user['uid'];
            $_SESSION['username'] = $user['username'];
            
            // bye bye login page, hello dashboard.
            header("Location: dashboard.php");
            exit;
        } else {
            // security pro-tip: i'm using a generic error message here. 
            // if i said "wrong password," then a hacker would know the 
            // username actually exists. keep 'em guessing.
            $error = 'Invalid username or password.';
        }
    }
}

require_once 'includes/header.php';
?>

<h2>Login</h2>

<?php if ($error): ?>
    <p class="error"><?php echo htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <label for="username">Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <button type="submit">Login</button>
</form>

<?php require_once 'includes/footer.php'; ?>
