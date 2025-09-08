<?php
session_start();

if (!isset($_GET['confirm'])) {
    showLogoutConfirmation();
    exit;
}

if ($_GET['confirm'] === 'true') {
    // Clear all session variables
    $_SESSION = array();

    // Destroy the session cookie
    if (isset($_COOKIE[session_name()])) {
        setcookie(session_name(), '', time()-3600, '/');
    }

    // Destroy the session
    session_destroy();

    // Redirect to login page
    header("Location: login.php");
    exit();
}

function showLogoutConfirmation() {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Confirm Logout</title>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
        <style>
            :root {
                --primary: #4f46e5;
                --danger: #dc2626;
                --danger-hover: #b91c1c;
                --danger-light: #fee2e2;
                --success: #059669;
                --gray-50: #f9fafb;
                --gray-100: #f3f4f6;
                --gray-200: #e5e7eb;
                --gray-300: #d1d5db;
                --gray-400: #9ca3af;
                --gray-500: #6b7280;
                --gray-600: #4b5563;
                --gray-700: #374151;
                --gray-800: #1f2937;
                --gray-900: #111827;
                --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
                --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
                --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            }
            
            body {
                background-color: var(--gray-100);
                color: var(--gray-900);
                font-family: 'Inter', system-ui, sans-serif;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                line-height: 1.5;
            }
            
            .confirm-box {
                background: white;
                border-radius: 0.75rem;
                box-shadow: var(--shadow-lg);
                width: 90%;
                max-width: 448px;
                position: relative;
                overflow: hidden;
            }
            
            .confirm-header {
                padding: 1.5rem 1.5rem 1rem;
                text-align: center;
            }
            
            .warning-icon {
                background-color: var(--danger-light);
                color: var(--danger);
                width: 3rem;
                height: 3rem;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 1rem;
                font-size: 1.25rem;
            }
            
            .confirm-title {
                color: var(--gray-900);
                font-size: 1.25rem;
                font-weight: 600;
                margin: 0 0 0.5rem;
            }
            
            .confirm-message {
                color: var(--gray-600);
                font-size: 0.875rem;
                margin: 0.5rem 0;
            }
            
            .warning-text {
                margin: 1rem 1.5rem;
                padding: 0.75rem 1rem;
                background: var(--danger-light);
                border-radius: 0.5rem;
                color: var(--danger);
                font-size: 0.875rem;
                display: flex;
                align-items: flex-start;
                gap: 0.5rem;
            }
            
            .warning-text i {
                margin-top: 0.125rem;
            }
            
            .confirm-actions {
                display: flex;
                gap: 0.75rem;
                padding: 1.5rem;
                background: var(--gray-50);
                border-top: 1px solid var(--gray-200);
            }
            
            .btn {
                flex: 1;
                padding: 0.625rem 1.25rem;
                border-radius: 0.5rem;
                font-size: 0.875rem;
                font-weight: 500;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.5rem;
                cursor: pointer;
                text-decoration: none;
                transition: all 0.15s ease;
                border: none;
            }
            
            .btn-danger {
                background: var(--danger);
                color: white;
            }
            
            .btn-danger:hover {
                background: var(--danger-hover);
            }
            
            .btn-cancel {
                background: white;
                color: var(--gray-700);
                border: 1px solid var(--gray-300);
            }
            
            .btn-cancel:hover {
                background: var(--gray-50);
                border-color: var(--gray-400);
            }
        </style>
    </head>
    <body>
        <div class="confirm-box">
            <div class="confirm-header">
                <div class="warning-icon">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </div>
                <h1 class="confirm-title">Confirm Logout</h1>
                <p class="confirm-message">Are you sure you want to log out?</p>
            </div>
            
            <div class="warning-text">
                <i class="fa-solid fa-circle-info"></i>
                <span>You will need to log in again to access the admin features.</span>
            </div>
            
            <div class="confirm-actions">
                <a href="javascript:history.back()" class="btn btn-cancel">
                    <i class="fa-solid fa-xmark"></i>
                    Cancel
                </a>
                <a href="logout.php?confirm=true" class="btn btn-danger">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    Logout
                </a>
            </div>
        </div>
    </body>
    </html>
    <?php
}
?>
