<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Project Manager - Samad Salman</title>
<style>
        :root {
            --primary: #3B82F6;      
            --primary-hover: #1D4ED8; 
            --bg: #111827;          
            --surface: #1F2937;      
            --text: #F3F4F6;       
            --text-light: #D1D5DB;   
            --border: #374151;
            --accent: #10B981;
        }

        body {
            font-family: 'Segoe UI', system-ui, sans-serif;
            background-color: var(--bg);
            color: var(--text);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        main {
            max-width: 1000px;
            margin: 2rem auto;
            padding: 0 1.5rem;
            min-height: 70vh;
        }

        h1, h2, h3 {
            color: var(--text);
            margin-top: 0;
        }

        nav {
            background-color: var(--surface);
            padding: 0.75rem 2rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            border-bottom: 2px solid var(--primary);
        }

        .logo {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary);
            margin-right: auto;
            text-decoration: none;
        }

        nav a:not(.logo) {
            text-decoration: none;
            color: var(--text-light);
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }

        nav a:hover {
            color: var(--primary);
        }

        form {
            background: var(--surface);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            gap: 1rem;
            border: 1px solid var(--border);
        }

        label {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--text-light);
        }

        input, select, textarea {
            padding: 0.5rem 0.75rem;
            border: 1px solid var(--border);
            border-radius: 6px;
            font-family: inherit;
            font-size: 0.95rem;
            background-color: #111827;
            color: var(--text);
            transition: border-color 0.2s ease;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        button {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 0.5rem 1.2rem;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: background-color 0.2s ease;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        button:hover {
            background-color: var(--primary-hover);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: var(--surface);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border);
        }

        th, td {
            padding: 0.75rem 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
            font-size: 0.95rem;
        }

        th {
            background-color: #111827;
            font-weight: 600;
            color: var(--primary);
        }

        tr:last-child td {
            border-bottom: none;
        }

        a {
            color: var(--primary);
            font-weight: 600;
        }

        a:hover {
            color: var(--primary-hover);
        }

        .error {
            color: #FCA5A5;
            background-color: #7F1D1D;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            border-left: 4px solid #EF4444;
        }

        .success {
            color: #D1FAE5;
            background-color: #065F46;
            padding: 0.75rem 1rem;
            border-radius: 6px;
            border-left: 4px solid var(--accent);
        }
</style>

</head>
<body>
    <nav>
        <a href="index.php" class="logo">ProjectManager</a>
        <a href="index.php">All Projects</a>
        <?php if (isset($_SESSION['uid'])): ?>
            <a href="dashboard.php">My Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    <main>