<?php
require_once 'connect.php';
require_once 'header.php';

if (isset($_GET['q'])) {
    $q = mysqli_real_escape_string($dbcon, $_GET['q']);
    $searchTerm = htmlentities($q);

    $sql = "SELECT * FROM posts WHERE title LIKE '%{$q}%' OR description LIKE '%{$q}%' ORDER BY id DESC";
    $result = mysqli_query($dbcon, $sql);
    ?>

<div class="container py-5">
    <div class="row">
        <main class="col-lg-12">
            <?php if (mysqli_num_rows($result) < 1): ?>
                <div class="no-results">
                    <i class="fas fa-search"></i>
                    <h3>No Quests Found</h3>
                    <p>Your search for "<strong><?= $searchTerm ?></strong>" didn't yield any results</p>
                    <a href="index.php" class="btn btn-primary">
                        <i class="fas fa-home"></i> Return to Home
                    </a>
                </div>
            <?php else: ?>
                <h3 class="search-results-title mb-4">
                    Search Results for "<span class="highlight"><?= $searchTerm ?></span>"
                </h3>
                
                <div class="row">
                    <?php while ($row = mysqli_fetch_assoc($result)): 
                        $id = htmlentities($row['id']);
                        $title = htmlentities($row['title']);
                        $des = htmlentities(strip_tags($row['description']));
                        $time = htmlentities($row['date']);
                        $category = isset($row['category']) ? htmlentities($row['category']) : 'Uncategorized';
                        
                        // FIXED: Use absolute URL path with view.php
                        $permalink =  "/Simple-PHP-Blog/view.php?id=" . $id;
                    ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <article class="blog-card" onclick="window.location.href='<?= $permalink ?>'">
                            <div class="card-header">
                                <span class="category-badge"><?= $category ?></span>
                                <span class="post-date"><?= date("M d, Y", strtotime($time)) ?></span>
                            </div>
                            
                            <div class="card-body">
                                <h2><?= $title ?></h2>
                                <p class="excerpt"><?= substr($des, 0, 150) ?>...</p>
                            </div>
                            
                            <div class="card-footer">
                                <span class="read-more-btn">
                                    Continue Reading <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </article>
                    </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
        </main>
    </div>
</div>


<style>
/* Search Results Styles */
.search-results-title {
    font-weight: 600;
    color: #111827;
    padding-bottom: 15px;
    border-bottom: 1px solid #e5e7eb;
}

.highlight {
    color: #4f46e5;
    font-weight: 600;
}

.no-results {
    text-align: center;
    padding: 60px 20px;
    background: #f9fafb;
    border-radius: 12px;
    border: 1px dashed #e5e7eb;
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.no-results .fa-search {
    font-size: 1.5rem;
    color: #9ca3af;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
}

.no-results h3 {
    color: #111827;
}

.no-results p {
    color: #6b7280;
    margin-bottom: 20px;
}

.no-results .btn {
    padding: 8px 16px;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
    gap: 8px;
}

.no-results .btn i {
    font-size: 0.9rem;
    display: inline-block;
    vertical-align: middle;
    position: relative;
    top: -1px;
}

/* Blog Card Styles (same as index page) */
.blog-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    margin-bottom: 30px;
    overflow: hidden;
    position: relative;
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.05);
    width: 100%;
    cursor: pointer;
}

.blog-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    border-color: var(--primary-color);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 25px 0;
}

.category-badge {
    background: #4f46e5;
    color: white;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    letter-spacing: 0.5px;
}

.post-date {
    color: #6b7280;
    font-size: 0.85rem;
}

.card-body {
    padding: 15px 25px;
}

.card-body h2 {
    margin: 10px 0 15px;
    font-size: 1.3rem;
    line-height: 1.4;
}

.card-body h2 a {
    color: #111827;
    text-decoration: none;
    transition: all 0.2s;
}

.card-body h2 a:hover {
    color: #4f46e5;
}

.excerpt {
    color: #4b5563;
    line-height: 1.6;
    margin-bottom: 15px;
}

.card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 25px 20px;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.read-more-btn {
    color: #4f46e5;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s;
}

.read-more-btn i {
    margin-left: 5px;
    transition: transform 0.3s;
}

.read-more-btn:hover {
    color: #4338ca;
}

.read-more-btn:hover i {
    transform: translateX(3px);
}

.post-id {
    color: #9ca3af;
    font-size: 0.85rem;
    font-family: monospace;
}

.card-hover-effect {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(79, 70, 229, 0.03) 0%, rgba(79, 70, 229, 0.01) 100%);
    opacity: 0;
    transition: opacity 0.3s;
}

/* Responsive */
@media (max-width: 768px) {
    .blog-card {
        margin-bottom: 20px;
    }
    
    .card-header, 
    .card-body,
    .card-footer {
        padding: 15px 20px;
    }
    
    .search-results-title {
        font-size: 1.5rem;
    }
}
</style>

<?php
}
include("footer.php");
?>