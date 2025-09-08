<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'functions.php';
require_once 'config.php';

// Hardcoded URL for localhost project
$url_path = "http://localhost/Simple-PHP-Blog/";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - OpenNotes' : 'OpenNotes'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= $url_path ?>assets/image/note.ico">
    <link rel="shortcut icon" type="image/x-icon" href="<?= $url_path ?>assets/image/note.ico">
    <link rel="icon" type="image/jpg" href="<?= $url_path ?>assets/image/note.jpg">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= $url_path ?>assets/css/style.css">
</head>
<body>

<header class="header">
    <div class="container header-content">
        <div class="header-left">
            <a href="<?= $url_path ?>" class="logo">OpenNotes</a>
        </div>
        <nav class="nav-menu">
            <a href="<?= $url_path ?>" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Home</a>
            <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                <a href="<?= $url_path ?>new.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'new.php' ? 'active' : '' ?>">New Post</a>
                <a href="<?= $url_path ?>admin.php" class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">Admin</a>
                <a href="<?= $url_path ?>logout.php" class="nav-link">Logout</a>
            <?php else: ?>
            <?php endif; ?>
        </nav>
        <div class="header-right">
            <form action="<?= $url_path ?>search.php" method="GET" class="search-form" id="searchForm">
                <div class="search-input-wrapper">
                    <input type="text" name="q" class="form-control" placeholder="Search posts..." required>
                    <button type="submit" class="search-button">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</header>

<style>
.header {
    background-color: var(--background);
    box-shadow: var(--shadow-sm);
    position: sticky;
    top: 0;
    z-index: 100;
}

.search-input-wrapper {
    position: relative;
    width: 250px;
    display: flex;
    align-items: center;
}

.search-form .form-control {
    width: 100%;
    padding: 0.75rem 1rem;
    padding-right: 3rem;
    border: 1px solid var(--border-color);
    border-radius: 2rem;
    background-color: var(--background);
    transition: all 0.3s ease;
}

.search-form .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px var(--accent-color);
    outline: none;
}

.search-button {
    position: absolute;
    right: 0.2rem;
    top: 50%;
    transform: translateY(-50%);
    background: var(--primary-color);
    border: none;
    width: 2.3rem;
    height: 2.3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    cursor: pointer;
    transition: all 0.3s ease;
}

.search-button:hover {
    opacity: 0.9;
    box-shadow: 0 0 0 2px var(--accent-color);
    transform: translateY(-50%) scale(1.02);
}

.search-button i {
    font-size: 1rem;
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    gap: 2rem;
}

.header-left {
    flex-shrink: 0;
}

.logo {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--primary-color);
    text-decoration: none;
}

.nav-menu {
    display: flex;
    gap: 2rem;
    justify-content: center;
    align-items: center;
    flex-grow: 1;
}

.nav-link {
    color: var(--text-secondary);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
}

.nav-link:hover,
.nav-link.active {
    color: var(--primary-color);
    background-color: var(--accent-color);
}

.header-right {
    flex-shrink: 0;
}

.search-form {
    position: relative;
}

.search-input-wrapper {
    position: relative;
    width: 250px;
}

.search-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    pointer-events: none;
}

.search-form .form-control {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid var(--border-color);
    border-radius: 2rem;
    background-color: var(--background);
    transition: all 0.3s ease;
}

.search-form .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px var(--accent-color);
    outline: none;
}

@media (max-width: 968px) {
    .header-content {
        flex-direction: column;
        padding: 1rem;
    }

    .nav-menu {
        order: 2;
        width: 100%;
        justify-content: center;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .header-right {
        order: 1;
        width: 100%;
    }

    .search-input-wrapper {
        width: 100%;
    }
}

@media (max-width: 480px) {
    .nav-menu {
        flex-direction: column;
        gap: 0.5rem;
    }

    .nav-link {
        width: 100%;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    const searchButton = searchForm.querySelector('.search-button');
    const searchInput = searchForm.querySelector('input[name="q"]');

    // Handle search button click
    searchButton.addEventListener('click', function(e) {
        e.preventDefault();
        if (searchInput.value.trim()) {
            searchForm.submit();
        } else {
            searchInput.focus();
        }
    });

    // Handle enter key press
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && searchInput.value.trim()) {
            searchForm.submit();
        }
    });
});
</script>
<main class="container">
    <?php if(isset($alert_message)): ?>
        <div class="alert <?php echo isset($alert_type) ? 'alert-' . $alert_type : 'alert-info'; ?>">
            <?php echo $alert_message; ?>
        </div>
    <?php endif; ?>
