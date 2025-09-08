<?php
set_time_limit(0);
require_once './connect.php';
require_once './functions.php';
require_once './security.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slug Generator | Cyber Admin</title>
    <style>
    :root {
        --primary: #6a5acd;
        --secondary: #ff00ff;
        --accent: #00ffff;
        --dark: #0d0d1a;
        --light: #e6e6fa;
        --danger: #ff5f56;
        --success: #27c93f;
    }

    body {
        background-color: var(--dark);
        color: var(--light);
        font-family: 'Courier New', monospace;
        margin: 0;
        padding: 0;
        line-height: 1.6;
    }

    .cyber-container {
        max-width: 900px;
        margin: 2rem auto;
        padding: 2rem;
        background: rgba(13, 13, 26, 0.9);
        border: 1px solid var(--primary);
        border-radius: 8px;
        box-shadow: 0 0 30px rgba(106, 90, 205, 0.4);
    }

    .cyber-header {
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
        padding-bottom: 1rem;
    }

    .cyber-header::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 25%;
        width: 50%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    h1 {
        color: var(--accent);
        text-shadow: 0 0 5px var(--accent);
        letter-spacing: 2px;
        margin: 0 0 0.5rem 0;
    }

    .status-message {
        padding: 1rem;
        margin: 1rem 0;
        border-radius: 4px;
        border-left: 4px solid;
    }

    .status-success {
        background: rgba(39, 201, 63, 0.1);
        border-left-color: var(--success);
        color: var(--success);
    }

    .status-info {
        background: rgba(106, 90, 205, 0.1);
        border-left-color: var(--primary);
        color: var(--primary);
    }

    .status-error {
        background: rgba(255, 95, 86, 0.1);
        border-left-color: var(--danger);
        color: var(--danger);
    }

    .slug-item {
        padding: 1rem;
        margin: 1rem 0;
        background: rgba(106, 90, 205, 0.05);
        border: 1px dashed var(--primary);
        border-radius: 4px;
        transition: all 0.3s;
    }

    .slug-item:hover {
        background: rgba(106, 90, 205, 0.1);
        border-color: var(--accent);
    }

    .slug-link {
        color: var(--accent);
        text-decoration: none;
        border-bottom: 1px dashed var(--accent);
        transition: all 0.2s;
    }

    .slug-link:hover {
        color: var(--secondary);
        border-bottom-color: var(--secondary);
    }

    .terminal-loader {
        display: flex;
        justify-content: center;
        margin: 2rem 0;
    }

    .terminal-loader span {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: var(--accent);
        margin: 0 5px;
        animation: pulse 1.4s infinite ease-in-out;
    }

    .terminal-loader span:nth-child(2) {
        animation-delay: 0.2s;
    }

    .terminal-loader span:nth-child(3) {
        animation-delay: 0.4s;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(0.8); opacity: 0.5; }
        50% { transform: scale(1.2); opacity: 1; }
    }

    .cyber-footer {
        text-align: center;
        margin-top: 2rem;
        padding-top: 1rem;
        border-top: 1px solid rgba(106, 90, 205, 0.3);
        color: var(--primary);
        font-size: 0.9rem;
    }
    </style>
</head>
<body>
<div class="cyber-container">
    <div class="cyber-header">
        <h1>⚙️ SLUG GENERATOR 2.0</h1>
        <p>Generating SEO-friendly URLs for your quests</p>
    </div>

    <?php
    // Check if 'slug' column exists before altering the table
    $check_column_sql = "SHOW COLUMNS FROM posts LIKE 'slug'";
    $check_result = mysqli_query($dbcon, $check_column_sql);

    if (mysqli_num_rows($check_result) == 0) {
        $slug_sql = "ALTER TABLE `posts` ADD `slug` VARCHAR(255) NULL DEFAULT NULL AFTER `description`;";
        if (mysqli_query($dbcon, $slug_sql)) {
            echo '<div class="status-message status-success">[SUCCESS] Slug column added to database structure.</div>';
        } else {
            echo '<div class="status-message status-error">[ERROR] Could not add slug column - '.mysqli_error($dbcon).'</div>';
        }
    } else {
        echo '<div class="status-message status-info">[INFO] Slug column already exists in the database.</div>';
    }

    $sql = "SELECT * FROM posts WHERE slug IS NULL";
    $result = mysqli_query($dbcon, $sql);

    if (mysqli_num_rows($result) == 0) {
        echo '<div class="status-message status-info">[INFO] Database is already optimized - no slugs need generation.</div>';
        mysqli_close($dbcon);
        die();
    }

    echo '<div class="status-message status-success">[PROCESSING] Beginning slug generation sequence...</div>';
    echo '<div class="terminal-loader"><span></span><span></span><span></span></div>';

    while ($row = mysqli_fetch_assoc($result)) {
        $id = $row['id'];
        $title = $row['title'];
        $slug = $row['slug'];

        if (is_null($slug)) {
            $new_slug = slug($title);
            $sql2 = "UPDATE posts SET slug = '$new_slug' WHERE id = $id";

            if (mysqli_query($dbcon, $sql2)) {
                $permalink = "p/".$id."/".$new_slug;
                echo '<div class="slug-item">';
                echo '[SUCCESS] Generated slug for: <a href="'.$permalink.'" class="slug-link">'.htmlspecialchars($title).'</a>';
                echo '<div class="slug-preview">New URL: '.$permalink.'</div>';
                echo '</div>';
            } else {
                echo '<div class="status-message status-error">[ERROR] Failed to generate slug for post ID: '.$id.' - '.mysqli_error($dbcon).'</div>';
            }
        }
    }

    mysqli_close($dbcon);
    ?>

    <div class="cyber-footer">
        [SYSTEM] Slug generation process completed
    </div>
</div>
</body>
</html>
