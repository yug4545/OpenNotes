<?php
// require_once 'header.php';
require_once 'connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Panel</title>

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

  <!-- Your CSS file -->
  <link rel="stylesheet" href="style.css">
</head>
<body>

<?php

// Initialize variables
$numrows = 0;
$rowsperpage = defined('PAGINATION') ? PAGINATION : 10;
$result = null;

// Get total post count
$count_sql = "SELECT COUNT(*) FROM posts";
$count_result = mysqli_query($dbcon, $count_sql);
if ($count_result) {
    $r = mysqli_fetch_row($count_result);
    $numrows = $r[0];
}

// Pagination logic
$totalpages = ceil($numrows / $rowsperpage);
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$page = max(1, min($page, $totalpages));
$offset = ($page - 1) * $rowsperpage;

// Get posts for current page
$sql = "SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $rowsperpage";
$result = mysqli_query($dbcon, $sql);
?>

<div class="neon-admin-container">
    <div class="admin-glass-panel">
        <div class="admin-header">
            <div class="admin-title-wrapper">
                <h1 class="admin-title">Quest Command Center</h1>
                <div class="admin-subtitle">Manage Your Adventure Portfolio</div>
            </div>
            <div class="user-profile">
                <div class="user-avatar">
                    <i class="fa-solid fa-user-astronaut"></i>
                </div>
                <div class="user-info">
                    <div class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                    <div class="user-role">Administrator</div>
                </div>
                <a href="logout.php" class="logout-btn" title="Logout">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </a>
            </div>
        </div>

        <div class="admin-actions-panel">
            <a href="new.php" class="action-card new-quest">
                <div class="action-icon">
                    <i class="fa-solid fa-circle-plus"></i>
                </div>
                <div class="action-content">
                    <h3>Create New Quest</h3>
                    <p>Design a brand new adventure</p>
                </div>
                <div class="action-arrow">
                    <i class="fa-solid fa-chevron-right"></i>
                </div>
            </a>
            
        </div>

        <div class="dashboard-stats">
            <div class="stat-card">
                <div class="stat-icon total-quests">
                    <i class="fa-solid fa-scroll"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $numrows; ?></div>
                    <div class="stat-label">Total Quests</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon per-page">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $rowsperpage; ?></div>
                    <div class="stat-label">Per Page</div>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon pages">
                    <i class="fa-solid fa-copy"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value"><?php echo $totalpages; ?></div>
                    <div class="stat-label">Pages</div>
                </div>
            </div>
        </div>

        <div class="quests-table-container">
            <div class="table-header">
                <h3>Active Quests</h3>
                <div class="table-controls">
                    <div class="search-box">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search quests...">
                    </div>
                </div>
            </div>
            
            <?php if (!$result || mysqli_num_rows($result) < 1): ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fa-solid fa-book-open"></i>
                    </div>
                    <h4>No Quests Found</h4>
                    <p>Your adventure log is currently empty</p>
                    <a href="new.php" class="btn-create">
                        <i class="fa-solid fa-plus"></i> Create First Quest
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="quests-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Quest Title</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_assoc($result)): 
                                $id = $row['id'];
                                $title = htmlspecialchars($row['title']);
                                $slug = htmlspecialchars($row['slug']);
                                $time = htmlspecialchars($row['date']);
                                $permalink = "p/".$id."/".$slug;
                            ?>
                            <tr>
                                <td class="quest-id">#<?php echo str_pad($id, 4, '0', STR_PAD_LEFT); ?></td>
                                <td class="quest-title">
                                    <a href="<?php echo $permalink; ?>" class="quest-link">
                                        <?php echo substr($title, 0, 50); ?>
                                        <?php if (strlen($title) > 50) echo '...'; ?>
                                    </a>
                                </td>
                                <td class="quest-date"><?php echo date('M j, Y', strtotime($time)); ?></td>
                                <td class="quest-status">
                                    <span class="status-badge active">Active</span>
                                </td>
                                <td class="quest-actions">
                                    <div class="action-buttons">
                                        <a href="edit.php?id=<?php echo $id; ?>" class="action-btn edit" title="Edit">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="del.php?id=<?php echo $id; ?>" class="action-btn delete" title="Delete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                        <a href="<?php echo $permalink; ?>" class="action-btn view" title="View" target="_blank">
                                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                
                <?php if ($totalpages > 1): ?>
                <div class="pagination-wrapper">
                    <div class="pagination">
                        <?php if ($page > 1): ?>
                            <a href="?page=1" class="page-btn first" title="First Page">
                                <i class="fa-solid fa-angles-left"></i>
                            </a>
                            <a href="?page=<?php echo $page - 1; ?>" class="page-btn prev" title="Previous Page">
                                <i class="fa-solid fa-angle-left"></i>
                            </a>
                        <?php endif; ?>

                        <?php
                        $range = 2;
                        $start = max(1, $page - $range);
                        $end = min($totalpages, $page + $range);
                        
                        for ($i = $start; $i <= $end; $i++) {
                            if ($i == $page) {
                                echo '<span class="page-btn current">'.$i.'</span>';
                            } else {
                                echo '<a href="?page='.$i.'" class="page-btn">'.$i.'</a>';
                            }
                        }
                        ?>

                        <?php if ($page < $totalpages): ?>
                            <a href="?page=<?php echo $page + 1; ?>" class="page-btn next" title="Next Page">
                                <i class="fas fa-angle-right"></i>
                            </a>
                            <a href="?page=<?php echo $totalpages; ?>" class="page-btn last" title="Last Page">
                                <i class="fas fa-angle-double-right"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="delete-modal" id="deleteModal">
    <div class="modal-content">
        <div class="modal-icon">
            <div class="icon-circle">
                <i class="fa-solid fa-exclamation-triangle"></i>
            </div>
        </div>
        <div class="modal-header">
            <h3 class="modal-title">Delete Quest?</h3>
            <p class="modal-message">You are about to delete this quest:</p>
        </div>
        <div class="modal-body">
            <div class="quest-preview">
                <i class="fa-solid fa-scroll preview-icon"></i>
                <p class="modal-quest-title" id="questToDelete"></p>
            </div>
            <div class="warning-message">
                <i class="fa-solid fa-circle-info"></i>
                <p>This action cannot be undone. All data associated with this quest will be permanently deleted.</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="modal-btn cancel" id="cancelDelete">
                <i class="fa-solid fa-xmark"></i>
                Keep Quest
            </button>
            <button class="modal-btn delete" id="confirmDelete">
                <i class="fa-solid fa-trash-can"></i>
                Delete Quest
            </button>
        </div>
    </div>
</div>

<style>
/* Modern Admin Dashboard Styles */
:root {
    --primary: #6366f1;
    --primary-light: #818cf8;
    --primary-dark: #4f46e5;
    --secondary: #f43f5e;
    --success: #10b981;
    --warning: #f59e0b;
    --info: #3b82f6;
    --dark: #1e293b;
    --darker: #0f172a;
    --light: #f8fafc;
    --lighter: #ffffff;
    --gray: #94a3b8;
    --gray-light: #e2e8f0;
    --glass: rgba(255, 255, 255, 0.05);
    --glass-border: rgba(255, 255, 255, 0.1);
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius: 12px;
    --radius-sm: 8px;
    --radius-lg: 16px;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    background-color: #f1f5f9;
    color: var(--dark);
    line-height: 1.5;
    padding: 20px;
}

.neon-admin-container {
    max-width: 1400px;
    margin: 0 auto;
}

.admin-glass-panel {
    background: var(--lighter);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-xl);
    overflow: hidden;
}

.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 32px;
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    color: white;
}

.admin-title-wrapper {
    flex: 1;
}

.admin-title {
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 4px;
    letter-spacing: -0.5px;
}

.admin-subtitle {
    font-size: 14px;
    opacity: 0.9;
    font-weight: 400;
}

.user-profile {
    display: flex;
    align-items: center;
    gap: 12px;
}

.user-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.user-info {
    display: flex;
    flex-direction: column;
}

.username {
    font-weight: 600;
    font-size: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.user-role {
    font-size: 12px;
    opacity: 0.8;
    display: flex;
    align-items: center;
    gap: 6px;
}

.me-1 {
    margin-right: 4px;
}

.me-2 {
    margin-right: 8px;
}

.logout-btn {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    transition: all 0.3s ease;
    margin-left: 10px;
}

.logout-btn:hover {
    background: rgba(255, 255, 255, 0.2);
    transform: scale(1.05);
}

.logout-btn i {
    font-size: 18px;
}

.admin-actions-panel {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    padding: 24px;
    background: var(--gray-light);
}

.action-card {
    background: white;
    border-radius: var(--radius-sm);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    text-decoration: none;
    color: var(--dark);
    transition: var(--transition);
    box-shadow: var(--shadow);
}

.action-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-lg);
}

.action-card.new-quest {
    border-left: 4px solid var(--success);
}

.action-card.seo-tools {
    border-left: 4px solid var(--info);
}

.action-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.action-card.new-quest .action-icon {
    background: var(--success);
}

.action-card.seo-tools .action-icon {
    background: var(--info);
}

.action-content {
    flex: 1;
}

.action-content h3 {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 4px;
}

.action-content p {
    font-size: 14px;
    color: var(--gray);
}

.action-arrow {
    color: var(--gray);
    font-size: 14px;
}

.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    padding: 0 24px 24px;
}

.stat-card {
    background: white;
    border-radius: var(--radius-sm);
    padding: 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: var(--shadow);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
}

.stat-icon.total-quests {
    background: var(--primary);
}

.stat-icon.per-page {
    background: var(--warning);
}

.stat-icon.pages {
    background: var(--secondary);
}

.stat-value {
    font-size: 24px;
    font-weight: 700;
    line-height: 1;
}

.stat-label {
    font-size: 14px;
    color: var(--gray);
}

.quests-table-container {
    padding: 24px;
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.table-header h3 {
    font-size: 20px;
    font-weight: 600;
    color: var(--darker);
}

.table-controls {
    display: flex;
    gap: 12px;
}

.search-box {
    position: relative;
    min-width: 250px;
}

.search-box input {
    width: 100%;
    padding: 10px 16px 10px 40px;
    border: 1px solid var(--gray-light);
    border-radius: var(--radius-sm);
    font-size: 14px;
    transition: var(--transition);
}

.search-box input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}

.search-box i {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray);
    font-size: 14px;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    background: white;
    border-radius: var(--radius-sm);
    box-shadow: var(--shadow);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 20px;
    border-radius: 50%;
    background: var(--gray-light);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: var(--gray);
}

.empty-state h4 {
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--darker);
}

.empty-state p {
    color: var(--gray);
    margin-bottom: 20px;
}

.btn-create {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background: var(--primary);
    color: white;
    border-radius: var(--radius-sm);
    text-decoration: none;
    font-weight: 500;
    transition: var(--transition);
}

.btn-create:hover {
    background: var(--primary-dark);
    transform: translateY(-2px);
}

.table-responsive {
    overflow-x: auto;
    border-radius: var(--radius-sm);
    box-shadow: var(--shadow);
}

.quests-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

.quests-table thead {
    background: var(--gray-light);
}

.quests-table th {
    padding: 16px;
    text-align: left;
    font-size: 14px;
    font-weight: 600;
    color: var(--darker);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.quests-table td {
    padding: 16px;
    border-bottom: 1px solid var(--gray-light);
    vertical-align: middle;
    font-size: 14px;
}

.quest-id {
    font-family: 'Courier New', monospace;
    font-weight: 600;
    color: var(--primary-dark);
}

.quest-title {
    font-weight: 500;
}

.quest-link {
    color: var(--darker);
    text-decoration: none;
    transition: var(--transition);
}

.quest-link:hover {
    color: var(--primary);
}

.quest-date {
    color: var(--gray);
}

.quest-status .status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 500;
}

.status-badge.active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success);
}

.action-buttons {
    display: flex;
    gap: 8px;
}

.action-btn {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-decoration: none;
    transition: var(--transition);
    font-size: 14px;
}

.action-btn.edit {
    background: var(--success);
}

.action-btn.delete {
    background: var(--secondary);
}

.action-btn.view {
    background: var(--info);
}

.action-btn:hover {
    transform: scale(1.1);
}

.pagination-wrapper {
    margin-top: 32px;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.page-btn {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-sm);
    display: flex;
    align-items: center;
    justify-content: center;
    background: white;
    color: var(--dark);
    text-decoration: none;
    transition: var(--transition);
    font-size: 14px;
    font-weight: 500;
    box-shadow: var(--shadow);
}

.page-btn:hover {
    background: var(--primary);
    color: white;
}

.page-btn.current {
    background: var(--primary);
    color: white;
    font-weight: 600;
}

.page-btn i {
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .admin-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
    
    .user-profile {
        align-self: flex-end;
    }
}

@media (max-width: 768px) {
    .admin-title {
        font-size: 24px;
    }
    
    .dashboard-stats {
        grid-template-columns: 1fr;
    }
    
    .quests-table th, 
    .quests-table td {
        padding: 12px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}

@media (max-width: 480px) {
    .admin-glass-panel {
        border-radius: 0;
    }
    
    .admin-header,
    .admin-actions-panel,
    .dashboard-stats,
    .quests-table-container {
        padding: 16px;
    }
    
    .table-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .search-box {
        width: 100%;
    }
}

/* Delete Confirmation Modal */
.delete-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(15, 23, 42, 0.8);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.delete-modal.active {
    display: flex;
    animation: modalBackdropIn 0.3s ease;
}

@keyframes modalBackdropIn {
    from {
        background: rgba(15, 23, 42, 0);
        backdrop-filter: blur(0);
    }
    to {
        background: rgba(15, 23, 42, 0.8);
        backdrop-filter: blur(4px);
    }
}

.modal-content {
    background: white;
    border-radius: var(--radius-lg);
    width: 90%;
    max-width: 460px;
    position: relative;
    animation: modalSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    overflow: hidden;
}

@keyframes modalSlideIn {
    from {
        transform: translateY(30px) scale(0.95);
        opacity: 0;
    }
    to {
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

.modal-icon {
    display: flex;
    justify-content: center;
    padding: 2rem 2rem 1rem;
}

.icon-circle {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    background: #FEE2E2;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--secondary);
    font-size: 28px;
    margin-bottom: 1rem;
    animation: iconBounce 0.6s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes iconBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.modal-header {
    text-align: center;
    padding: 0 2rem 1rem;
}

.modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--darker);
    margin-bottom: 0.5rem;
}

.modal-message {
    color: var(--gray);
    font-size: 1rem;
}

.modal-body {
    padding: 0 2rem 1.5rem;
}

.quest-preview {
    background: var(--light);
    border-radius: var(--radius);
    padding: 1rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.preview-icon {
    font-size: 1.25rem;
    color: var(--primary);
}

.modal-quest-title {
    color: var(--darker);
    font-weight: 500;
    margin: 0;
    flex: 1;
}

.warning-message {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.75rem;
    background: #FEF3C7;
    border-radius: var(--radius);
    color: #92400E;
    font-size: 0.875rem;
}

.warning-message i {
    margin-top: 0.125rem;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    padding: 1.5rem 2rem;
    background: var(--light);
    border-top: 1px solid var(--gray-light);
}

.modal-btn {
    flex: 1;
    padding: 0.875rem;
    border-radius: var(--radius);
    font-weight: 600;
    font-size: 0.875rem;
    transition: all 0.2s;
    cursor: pointer;
    border: none;
    outline: none;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.modal-btn i {
    font-size: 1rem;
}

.modal-btn.cancel {
    background: white;
    color: var(--dark);
    border: 1px solid var(--gray-light);
}

.modal-btn.cancel:hover {
    background: var(--light);
    border-color: var(--gray);
}

.modal-btn.delete {
    background: var(--secondary);
    color: white;
}

.modal-btn.delete:hover {
    background: #E11D48;
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(228, 29, 72, 0.2);
}
</style>
