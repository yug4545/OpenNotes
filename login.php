<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
<?php
require_once 'connect.php';

$error_message = '';

if (isset($_POST['log'])) {
    $username = mysqli_real_escape_string($dbcon, $_POST['username']);
    $password = mysqli_real_escape_string($dbcon, $_POST['password']);

    // Try to find user by either username or email
    $sql = "SELECT * FROM admin WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($dbcon, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $username, $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    $row_count = mysqli_num_rows($result);

    if ($row_count == 1) {
        if (!isset($row['password'])) {
            $error_message = '<div class="error-message animate__animated animate__shakeX">
                            <i class="fa-solid fa-circle-exclamation"></i>Database error: Password field not found
                            </div>';
        } 
        // Compare the passwords
        else if ($password === $row['password']) {
            // Set session variables
            $_SESSION['username'] = $row['username'];
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_email'] = $row['email'];
            
            // Redirect to admin page
            echo "<script>window.location.href = 'admin.php';</script>";
            exit();
        } else {
            $error_message = '<div class="error-message animate__animated animate__shakeX">
                            <i class="fa-solid fa-circle-exclamation"></i>Invalid username or password
                            </div>';
        }
    } else {
        $error_message = '<div class="error-message animate__animated animate__shakeX">
                        <i class="fas fa-exclamation-circle me-2"></i>Invalid username or password
                        </div>';
    }
}
?>

<?php if ($error_message): ?>
    <div id="error-toast" class="error-toast">
        <?php echo $error_message; ?>
    </div>
<?php endif; ?>

<div class="login-wrapper">
    <div class="login-glass">
        <div class="login-header">
            <div class="logo-circle">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h2>Secure Access</h2>
            <p>Administrator Portal</p>
        </div>
        
        <form method="POST" action="" class="login-form">
            <div class="input-field">
                <i class="fa-solid fa-user input-icon"></i>
                <input type="text" name="username" placeholder="Username" 
                       value="<?= isset($_POST['username']) ? strip_tags($_POST['username']) : '' ?>">
                <div class="underline"></div>
            </div>
            
            <div class="input-field">
                <i class="fa-solid fa-lock input-icon"></i>
                <input type="password" name="password" id="password" placeholder="Password">
                <i class="fa-solid fa-eye password-toggle" onclick="togglePassword()"></i>
                <div class="underline"></div>
            </div>
            
            <button type="submit" name="log" class="login-btn">
                <span>Authenticate</span>
                <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>
        
        <div class="login-footer">
            <div class="security-indicator">
                <div class="dots">
                    <span class="active"></span>
                    <span class="active"></span>
                    <span class="active"></span>
                </div>
                <span>Encrypted Connection</span>
            </div>
        </div>
    </div>
</div>

<style>
/* Base Styles */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --text: #2d3748;
    --light: #f8fafc;
    --border: #e2e8f0;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

body {
    background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
    font-family: 'Inter', sans-serif;
    color: var(--text);
    margin:0px;
}

/* Login Wrapper */
.login-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}

/* Glass Effect */
.login-glass {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 40px;
    width: 100%;
    max-width: 400px;
    box-shadow: var(--shadow);
    border: 1px solid rgba(255, 255, 255, 0.3);
    overflow: hidden;
    position: relative;
}

.login-glass::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        to bottom right,
        rgba(99, 102, 241, 0.1) 0%,
        rgba(255, 255, 255, 0) 50%,
        rgba(79, 70, 229, 0.1) 100%
    );
    transform: rotate(30deg);
    z-index: -1;
}

/* Header */
.login-header {
    text-align: center;
    margin-bottom: 30px;
}

.logo-circle {
    width: 80px;
    height: 80px;
    margin: 0 auto 15px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
}

.login-header h2 {
    margin: 10px 0 5px;
    font-weight: 600;
    color: var(--text);
}

.login-header p {
    color: #64748b;
    font-size: 14px;
}

/* Input Fields */
.input-field {
    position: relative;
    margin-bottom: 25px;
}

.input-field .input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    transition: all 0.3s;
}

.input-field input {
    width: 100%;
    padding: 12px 45px;
    border: none;
    background: var(--light);
    border-radius: 8px;
    font-size: 15px;
    transition: all 0.3s;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
    box-sizing: border-box;
}

.input-field input:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
}

.input-field input:focus + .input-icon {
    color: var(--primary);
}

.underline {
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: var(--primary);
    transition: width 0.4s;
}

.input-field input:focus ~ .underline {
    width: 100%;
}

/* Login Button */
.login-btn {
    width: 100%;
    padding: 14px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    font-size: 15px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s;
    box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3);
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(79, 70, 229, 0.4);
}

.login-btn i {
    transition: transform 0.3s;
}

.login-btn:hover i {
    transform: translateX(3px);
}

/* Footer */
.login-footer {
    margin-top: 30px;
    text-align: center;
}

.security-indicator {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #64748b;
}

.dots {
    display: flex;
    gap: 5px;
}

.dots span {
    display: inline-block;
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: #10b981;
    opacity: 0.7;
}

.dots span.active {
    opacity: 1;
    animation: pulse 1.5s infinite;
}

.dots span:nth-child(2) {
    animation-delay: 0.2s;
}

.dots span:nth-child(3) {
    animation-delay: 0.4s;
}

@keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.2); }
}

/* Error Message */
.error-message {
    background: linear-gradient(135deg, #ff4b4b 0%, #ff416c 100%);
    color: white;
    padding: 15px 20px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    font-size: 14px;
    box-shadow: 0 4px 15px rgba(255, 75, 75, 0.35);
    backdrop-filter: blur(10px);
}

.error-message i {
    margin-right: 10px;
    font-size: 16px;
}

/* Error Toast */
.error-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 1000;
    animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Password Toggle */
.password-toggle {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    cursor: pointer;
    transition: all 0.3s;
    padding: 8px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
}

.password-toggle:hover {
    color: var(--primary);
    background: rgba(99, 102, 241, 0.1);
}

.input-field:focus-within .password-toggle {
    color: var(--primary);
}

/* Animations */
.animate__animated {
    animation-duration: 0.5s;
}

@keyframes shakeX {
    0%, 100% { transform: translateX(0); }
    20%, 60% { transform: translateX(-5px); }
    40%, 80% { transform: translateX(5px); }
}

.animate__shakeX {
    animation-name: shakeX;
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.password-toggle');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Auto-hide error toast after 5 seconds
const errorToast = document.getElementById('error-toast');
if (errorToast) {
    setTimeout(() => {
        errorToast.style.animation = 'slideOut 0.5s ease-in forwards';
        setTimeout(() => {
            errorToast.remove();
        }, 500);
    }, 5000);
}
</script>
</body>
</html>