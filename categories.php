<?php
require_once 'connect.php';
require_once 'header.php';
?>

<div class="categories-container">
    <div class="categories-header">
        <h1>Browse by Categories</h1>
        <p>Explore our collection of articles by topic</p>
    </div>

    <div class="categories-grid">
        <?php
        $sql = "SELECT c.*, COUNT(p.id) as post_count 
                FROM categories c 
                LEFT JOIN posts p ON c.id = p.category_id 
                GROUP BY c.id 
                ORDER BY c.name ASC";
        $result = mysqli_query($dbcon, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($cat = mysqli_fetch_assoc($result)) {
                $catName = htmlentities($cat['name']);
                $catId = htmlentities($cat['id']);
                $postCount = htmlentities($cat['post_count']);
                ?>
                <a href="category.php?id=<?= $catId ?>" class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-folder"></i>
                    </div>
                    <h3 class="category-name"><?= $catName ?></h3>
                    <span class="post-count"><?= $postCount ?> posts</span>
                </a>
                <?php
            }
        } else {
            ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-folder-open"></i>
                </div>
                <h3>No Categories Yet</h3>
                <p>Categories will appear here once created</p>
                <?php if(isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                    <a href="admin.php?action=categories" class="btn-primary">Create Category</a>
                <?php endif; ?>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<style>
.categories-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1rem;
}

.categories-header {
    text-align: center;
    margin-bottom: 3rem;
}

.categories-header h1 {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 1rem;
}

.categories-header p {
    font-size: 1.1rem;
    color: var(--text-secondary);
    max-width: 600px;
    margin: 0 auto;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 2rem;
}

.category-card {
    background: var(--background);
    border-radius: 1rem;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
    text-decoration: none;
    border: 1px solid var(--border-color);
    box-shadow: var(--shadow-sm);
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
    border-color: var(--primary-color);
}

.category-icon {
    width: 60px;
    height: 60px;
    background: var(--accent-color);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--primary-color);
    transition: all 0.3s ease;
}

.category-card:hover .category-icon {
    background: var(--primary-color);
    color: white;
    transform: scale(1.1);
}

.category-name {
    color: var(--text-primary);
    font-size: 1.25rem;
    font-weight: 600;
    margin: 0;
}

.post-count {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: var(--background);
    border-radius: 1rem;
    box-shadow: var(--shadow);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--accent-color);
    color: var(--primary-color);
    border-radius: 50%;
    font-size: 2rem;
}

.empty-state h3 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.empty-state p {
    color: var(--text-secondary);
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .categories-header h1 {
        font-size: 2rem;
    }
    
    .categories-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.5rem;
    }
    
    .category-card {
        padding: 1.5rem;
    }
}

@media (max-width: 480px) {
    .categories-container {
        padding: 1.5rem 1rem;
    }
    
    .category-icon {
        width: 50px;
        height: 50px;
        font-size: 1.25rem;
    }
    
    .category-name {
        font-size: 1.1rem;
    }
}
</style>

<?php include 'footer.php'; ?>