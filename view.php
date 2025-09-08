<?php
require_once 'connect.php';
require_once 'header.php';

// Get and validate post ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id < 1) {
    header("location: $url_path");
    exit;
}

// Initialize variables with default values
$title = '';
$description = '';
$author = '';
$time = date('Y-m-d H:i:s');

// Get post data using prepared statement
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = mysqli_prepare($dbcon, $sql);
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result || mysqli_num_rows($result) == 0) {
    mysqli_stmt_close($stmt);
    header("location: $url_path");
    exit;
}

$row = mysqli_fetch_assoc($result);
$title = htmlspecialchars($row['title']);
$description = $row['description'];
$author = htmlspecialchars($row['posted_by']);
$time = htmlspecialchars($row['date']);
mysqli_stmt_close($stmt);
?>

<div class="geometric-shapes">
    <div class="shape1"></div>
    <div class="shape2"></div>
    <div class="shape3"></div>
    <div class="shape4"></div>
</div>

<div class="post-container">
    <article class="post-content-wrapper">
        <div class="post-header">
            <div class="post-meta">
                <time datetime="<?php echo $time; ?>"><?php echo date("F j, Y", strtotime($time)); ?></time>
                <span class="post-author">By <?php echo $author; ?></span>
            </div>
            
            <h1 class="post-title"><?php echo $title; ?></h1>
        </div>
        
        <div class="post-content">
            <?php 
            // Properly display HTML content including tables
            echo html_entity_decode($description);
            ?>
        </div>
        
        <div class="post-footer">
            <div class="post-navigation">
                <a href="<?=$url_path?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
                <div class="post-share">
                    <span>Share:</span>
                    <a href="https://api.whatsapp.com/send?text=<?php echo urlencode($title . ' - ' . $url_path . 'view.php?id=' . $id); ?>" target="_blank" class="share-link whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($url_path . 'view.php?id=' . $id); ?>" target="_blank" class="share-link facebook">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://t.me/share/url?url=<?php echo urlencode($url_path . 'view.php?id=' . $id); ?>&text=<?php echo urlencode($title); ?>" target="_blank" class="share-link telegram">
                        <i class="fab fa-telegram"></i>
                    </a>
                </div>
            </div>
        </div>
    </article>
</div>

<style>
:root {
    --text-primary: #2d3748;
    --text-secondary: #718096;
    --background: #ffffff;
    --background-alt: #f7fafc;
    --border-color: #e2e8f0;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --radius: 8px;
    --radius-lg: 12px;
    --primary: #6366f1;
    --primary-rgb: 99, 102, 241;
}

/* Background Animation */
@keyframes gradientBG {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

@keyframes float {
    0%, 100% { transform: translateY(0) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(5deg); }
}

body {
    min-height: 100vh;
    background: linear-gradient(-45deg, #f8faff, #eef2ff, #f0f4ff, #e8ecff);
    background-size: 400% 400%;
    animation: gradientBG 15s ease infinite;
    position: relative;
    overflow-x: hidden;
}

/* Decorative Elements */
body::before,
body::after {
    content: '';
    position: fixed;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    pointer-events: none;
    z-index: -1;
    opacity: 0.4;
}

body::before {
    background: radial-gradient(circle, rgba(99, 102, 241, 0.1), transparent 70%);
    top: -100px;
    right: -100px;
    animation: float 8s ease-in-out infinite;
}

body::after {
    background: radial-gradient(circle, rgba(79, 70, 229, 0.1), transparent 70%);
    bottom: -100px;
    left: -100px;
    animation: float 10s ease-in-out infinite reverse;
}

.geometric-shapes {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: -1;
    overflow: hidden;
    opacity: 0.6;
}

.geometric-shapes div {
    position: absolute;
    background: linear-gradient(45deg, rgba(99, 102, 241, 0.05), rgba(79, 70, 229, 0.05));
    border: 1px solid rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(5px);
    border-radius: 20px;
    transform-origin: center;
}

.shape1 { 
    width: 150px; 
    height: 150px; 
    top: 15%; 
    left: 5%; 
    animation: float 12s infinite; 
    transform: rotate(15deg);
}

.shape2 { 
    width: 200px; 
    height: 200px; 
    top: 60%; 
    right: 10%; 
    animation: float 15s infinite;
    transform: rotate(-20deg);
}

.shape3 { 
    width: 100px; 
    height: 100px; 
    bottom: 15%; 
    left: 15%; 
    animation: float 10s infinite;
    transform: rotate(45deg);
}

.shape4 { 
    width: 180px; 
    height: 180px; 
    top: 25%; 
    right: 25%; 
    animation: float 14s infinite;
    transform: rotate(-10deg);
}

.post-container {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.post-content-wrapper {
    background: rgba(255, 255, 255, 0.95);
    border-radius: var(--radius-lg);
    box-shadow: 
        0 4px 20px rgba(0, 0, 0, 0.08),
        0 0 0 1px rgba(255, 255, 255, 0.8);
    overflow: hidden;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    position: relative;
}

.post-content-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, 
        var(--primary-color, #4361ee) 0%, 
        var(--secondary-color, #3f37c9) 50%,
        var(--primary-color, #4361ee) 100%
    );
    animation: shimmer 2s infinite linear;
    background-size: 200% 100%;
}

@keyframes shimmer {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.post-header {
    padding: 2rem;
    border-bottom: 1px solid var(--border-color);
}

.post-meta {
    display: flex;
    align-items: center;
    gap: 1rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.post-meta time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.post-meta time::before {
    content: '';
    width: 4px;
    height: 4px;
    background: currentColor;
    border-radius: 50%;
}

.post-title {
    font-size: 2.5rem;
    color: var(--text-primary);
    font-weight: 700;
    line-height: 1.3;
    margin-bottom: 1.5rem;
}

.post-content {
    padding: 2rem;
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-primary);
}

.post-content p {
    margin-bottom: 1.5rem;
}

.post-content img {
    max-width: 100%;
    height: auto;
    border-radius: var(--radius);
    margin: 2rem 0;
}

/* Table Styles */
.post-content table {
    width: 100%;
    margin: 2rem 0;
    border-collapse: collapse;
    background: var(--background);
    border-radius: var(--radius);
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.post-content table thead {
    background: var(--primary);
    color: white;
}

.post-content table th {
    font-weight: 600;
    text-align: left;
    padding: 1rem;
    font-size: 0.95rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.post-content table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
    color: var(--text-primary);
}

.post-content table tbody tr:last-child td {
    border-bottom: none;
}

.post-content table tbody tr:nth-child(even) {
    background: rgba(0, 0, 0, 0.02);
}

.post-content table tbody tr:hover {
    background: rgba(var(--primary-rgb), 0.05);
}

/* Responsive Table */
@media screen and (max-width: 768px) {
    .post-content table {
        display: block;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }
    
    .post-content table thead {
        position: sticky;
        left: 0;
        z-index: 1;
    }
}

.post-content h2 {
    font-size: 1.8rem;
    color: var(--text-primary);
    margin: 2rem 0 1rem;
}

.post-content h3 {
    font-size: 1.5rem;
    color: var(--text-primary);
    margin: 1.5rem 0 1rem;
}

.post-footer {
    padding: 2rem;
    border-top: 1px solid var(--border-color);
    background: var(--background-alt);
}

.post-navigation {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.post-share {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.post-share span {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.share-link {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    color: white;
    font-size: 1rem;
    transition: transform 0.2s;
    text-decoration: none;
}

.share-link:hover {
    transform: translateY(-2px);
    color: white;
    text-decoration: none;
}

.share-link.whatsapp {
    background: #25D366;
}

.share-link.facebook {
    background: #4267B2;
}

.share-link.telegram {
    background: #0088cc;
}

@media (max-width: 768px) {
    .post-container {
        padding: 1rem;
    }
    
    .post-header,
    .post-content,
    .post-footer {
        padding: 1.5rem;
    }
    
    .post-title {
        font-size: 2rem;
    }
    
    .post-content {
        font-size: 1rem;
    }
    
    .post-navigation {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .post-share {
        width: 100%;
        justify-content: flex-start;
    }
}

@media (max-width: 480px) {
    .post-title {
        font-size: 1.75rem;
    }
    
    .post-actions {
        flex-direction: column;
    }
    
    .post-meta {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.5rem;
    }
}
</style>

<?php include("footer.php"); ?>