<?php
require_once 'connect.php';
require_once 'header.php';

if (!defined('PAGINATION')) {
    define('PAGINATION', 5);
}
?>

<div class="geometric-shapes">
    <div class="shape1"></div>
    <div class="shape2"></div>
    <div class="shape3"></div>
    <div class="shape4"></div>
</div>

<div class="blog-container">
    <div class="blog-header">
        <h1>The Blog</h1>
        <p class="subtitle">Discover the latest articles and insights</p>
    </div>

    <div class="blog-layout">
        <main class="blog-main">
            <?php
            // COUNT total posts
            $sql = "SELECT COUNT(*) FROM posts";
            $result = mysqli_query($dbcon, $sql);
            $r = mysqli_fetch_row($result);
            $numrows = $r[0];

            $rowsperpage = PAGINATION;
            $totalpages = ceil($numrows / $rowsperpage);

            $page = 1;
            if (isset($_GET['page']) && is_numeric($_GET['page'])) {
                $page = (int) $_GET['page'];
            }

            if ($page > $totalpages)
                $page = $totalpages;
            if ($page < 1)
                $page = 1;

            $offset = ($page - 1) * $rowsperpage;

            $sql = "SELECT * FROM posts ORDER BY id DESC LIMIT $offset, $rowsperpage";
            $result = mysqli_query($dbcon, $sql);

            if (mysqli_num_rows($result) < 1) {
                echo '<div class="empty-state">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                        </div>
                        <h3>No posts yet</h3>
                        <p>Check back later for new content</p>
                        <a href="admin/addpost.php" class="btn-primary">Create First Post</a>
                      </div>';
            } else {
                echo '<div class="article-grid">';

                while ($row = mysqli_fetch_assoc($result)) {
                    $id = htmlentities($row['id']);
                    $title = htmlentities($row['title']);
                    $des = htmlentities(strip_tags($row['description']));
                    $slug = htmlentities($row['slug']);
                    $time = htmlentities($row['date']);
                    $permalink = "view.php?id=" . $id;
                    $category = isset($row['category']) ? htmlentities($row['category']) : 'General';
                    $image = isset($row['image']) ? htmlentities($row['image']) : 'default-post.jpg';

                    echo '<article class="article-card">';
                    // echo '<div class="article-image">';
                    // echo '<img src="uploads/' . $image . '" alt="' . $title . '">';
                    // echo '<span class="article-category">' . $category . '</span>';
                    // echo '</div>';
            
                    echo '<div class="article-content">';
                    echo '<div class="article-meta">';
                    echo '<time datetime="' . $time . '">' . date("F j, Y", strtotime($time)) . '</time>';
                    echo '<span class="read-time">5 min read</span>';
                    echo '</div>';

                    echo '<h2><a href="' . $permalink . '">' . $title . '</a></h2>';
                    echo '<p class="article-excerpt">' . substr($des, 0, 150) . '...</p>';

                    echo '<div class="article-footer">';
                    echo '<a href="' . $permalink . '" class="read-more">Read More</a>';
                    echo '<div class="article-actions">';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</article>';
                }

                echo '</div>';

                // Pagination
                echo '<div class="pagination-wrapper">';
                echo '<nav class="pagination">';

                if ($page > 1) {
                    echo '<a href="?page=1" class="pagination-link first" title="First Page">';
                    echo '<svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M18.41 16.59L13.82 12l4.59-4.59L17 6l-6 6 6 6zM6 6h2v12H6z"/></svg>';
                    echo '</a>';

                    $prevpage = $page - 1;
                    echo '<a href="?page=' . $prevpage . '" class="pagination-link prev" title="Previous Page">';
                    echo '<svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6 1.41-1.41z"/></svg>';
                    echo '</a>';
                }

                $range = 2;
                $showEllipsisStart = false;
                $showEllipsisEnd = false;

                for ($x = 1; $x <= $totalpages; $x++) {
                    if ($x == 1 || $x == $totalpages || ($x >= $page - $range && $x <= $page + $range)) {
                        if ($x == $page) {
                            echo '<span class="pagination-link current">' . $x . '</span>';
                        } else {
                            echo '<a href="?page=' . $x . '" class="pagination-link">' . $x . '</a>';
                        }
                    } elseif ($x < $page - $range && !$showEllipsisStart) {
                        echo '<span class="pagination-ellipsis">...</span>';
                        $showEllipsisStart = true;
                    } elseif ($x > $page + $range && !$showEllipsisEnd) {
                        echo '<span class="pagination-ellipsis">...</span>';
                        $showEllipsisEnd = true;
                    }
                }

                if ($page < $totalpages) {
                    $nextpage = $page + 1;
                    echo '<a href="?page=' . $nextpage . '" class="pagination-link next" title="Next Page">';
                    echo '<svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6-1.41-1.41z"/></svg>';
                    echo '</a>';

                    echo '<a href="?page=' . $totalpages . '" class="pagination-link last" title="Last Page">';
                    echo '<svg width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M5.59 7.41L10.18 12l-4.59 4.59L7 18l6-6-6-6zM16 6h2v12h-2z"/></svg>';
                    echo '</a>';
                }

                echo '</nav>';
                echo '</div>';
            }
            ?>
        </main>

    </div>
</div>

<style>
    /* Base Styles */
    :root {
        --primary-color: #4361ee;
        --primary-light: #e0e7ff;
        --secondary-color: #3f37c9;
        --text-color: #2b2d42;
        --text-light: #8d99ae;
        --light-color: #f8f9fa;
        --white: #ffffff;
        --border-radius: 12px;
        --box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    /* Background Animation */
    @keyframes gradientBG {
        0% {
            background-position: 0% 50%;
        }

        50% {
            background-position: 100% 50%;
        }

        100% {
            background-position: 0% 50%;
        }
    }

    @keyframes float {

        0%,
        100% {
            transform: translateY(0) rotate(0deg);
        }

        50% {
            transform: translateY(-20px) rotate(5deg);
        }
    }

    body {
        min-height: 100vh;
        background: linear-gradient(-45deg, #f6f8ff, #e9efff, #edf2ff, #f0f4ff);
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
        background: radial-gradient(circle, var(--primary-light), transparent 70%);
        top: -100px;
        right: -100px;
        animation: float 8s ease-in-out infinite;
    }

    body::after {
        background: radial-gradient(circle, #e0e7ff, transparent 70%);
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
        opacity: 0.5;
    }

    .geometric-shapes div {
        position: absolute;
        background: linear-gradient(45deg, rgba(67, 97, 238, 0.1), rgba(63, 55, 201, 0.1));
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(5px);
        border-radius: 20px;
    }

    .shape1 {
        width: 100px;
        height: 100px;
        top: 20%;
        left: 10%;
        animation: float 10s infinite;
    }

    .shape2 {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 15%;
        animation: float 12s infinite;
    }

    .shape3 {
        width: 80px;
        height: 80px;
        bottom: 10%;
        left: 20%;
        animation: float 9s infinite;
    }

    .shape4 {
        width: 120px;
        height: 120px;
        top: 30%;
        right: 30%;
        animation: float 11s infinite;
    }

    /* Override and add new styles */
    .blog-container {
        position: relative;
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
    }

    .blog-container::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        opacity: 0.05;
        z-index: -1;
        pointer-events: none;
    }

    .blog-header {
        text-align: center;
        margin-bottom: 4rem;
        padding: 3rem 1rem;
        background: rgba(255, 255, 255, 0.95);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
    }

    .blog-header h1 {
        font-size: 3rem;
        font-weight: 800;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        margin-bottom: 1rem;
        line-height: 1.2;
    }

    .blog-header .subtitle {
        font-size: 1.2rem;
        color: var(--text-secondary);
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.6;
    }

    .article-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 400px));
        gap: 2rem;
        justify-content: center;
        margin: 0 auto;
        max-width: 1000px;
    }

    .article-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--box-shadow);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .article-card:hover {
        transform: translateY(-5px) scale(1.02);
        box-shadow: 0 20px 30px rgba(0, 0, 0, 0.1);
        border-color: var(--primary-color);
    }

    .article-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
        background: rgba(255, 255, 255, 0.9);
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.9rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }

    .article-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 1rem;
        line-height: 1.3;
    }

    .article-excerpt {
        color: var(--text-light);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .article-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        background: rgba(255, 255, 255, 0.95);
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .read-more {
        color: var(--primary-color);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.3s ease;
    }

    .read-more:hover {
        transform: translateX(5px);
    }

    @media (max-width: 768px) {
        .blog-header {
            padding: 2rem 1rem;
            margin-bottom: 2rem;
        }

        .blog-header h1 {
            font-size: 2.5rem;
        }

        .article-grid {
            grid-template-columns: minmax(280px, 400px);
        }
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        color: var(--text-color);
        line-height: 1.6;
        background-color: #f5f7ff;
    }

    a {
        text-decoration: none;
        color: inherit;
    }

    img {
        max-width: 100%;
        height: auto;
        display: block;
    }

    /* Blog Container */
    .blog-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem 1.5rem;
        position: relative;
    }

    .blog-container::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url('https://images.unsplash.com/photo-1486312338219-ce68d2c6f44d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2072&q=80');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        opacity: 0.05;
        z-index: -1;
        pointer-events: none;
    }

    .blog-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .blog-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--text-color);
        margin-bottom: 0.5rem;
        background: linear-gradient(90deg, #4361ee, #3a0ca3);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .blog-header .subtitle {
        font-size: 1.1rem;
        color: var(--text-light);
        max-width: 600px;
        margin: 0 auto;
    }

    /* Blog Layout */
    .blog-layout {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .blog-main {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        width: 100%;
    }

    /* Article Grid */
    .article-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
        margin: 0 auto;
        max-width: 800px;
    }

    .article-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    }

    .article-image {
        position: relative;
        height: 200px;
        overflow: hidden;
    }

    .article-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: var(--transition);
    }

    .article-card:hover .article-image img {
        transform: scale(1.05);
    }

    .article-category {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background: var(--primary-color);
        color: var(--white);
        padding: 0.35rem 0.8rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .article-content {
        padding: 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .article-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.85rem;
        color: var(--text-light);
        margin-bottom: 0.75rem;
    }

    .read-time {
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    .article-content h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.3;
        transition: var(--transition);
    }

    .article-content h2 a:hover {
        color: var(--primary-color);
    }

    .article-excerpt {
        color: var(--text-light);
        margin-bottom: 1.5rem;
        flex: 1;
    }

    .article-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    .read-more {
        font-weight: 600;
        color: var(--primary-color);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: var(--transition);
    }

    .read-more:hover {
        color: var(--secondary-color);
    }

    .read-more::after {
        content: '→';
        transition: var(--transition);
    }

    .read-more:hover::after {
        transform: translateX(3px);
    }

    .article-actions {
        display: flex;
        gap: 0.5rem;
    }

    .icon-button {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
        border: none;
        color: var(--text-light);
        cursor: pointer;
        transition: var(--transition);
    }

    .icon-button:hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: var(--white);
        border-radius: var(--border-radius);
        box-shadow: var(--box-shadow);
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--primary-light);
        color: var(--primary-color);
        border-radius: 50%;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: var(--text-light);
        margin-bottom: 1.5rem;
    }

    .btn-primary {
        display: inline-block;
        background: var(--primary-color);
        color: var(--white);
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }

    .btn-primary:hover {
        background: var(--secondary-color);
        transform: translateY(-2px);
    }

    /* Pagination */
    .pagination-wrapper {
        margin-top: 3rem;
    }

    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .pagination-link {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        font-weight: 600;
        color: var(--text-light);
        transition: var(--transition);
    }

    .pagination-link:hover {
        background: var(--primary-light);
        color: var(--primary-color);
    }

    .pagination-link.current {
        background: var(--primary-color);
        color: var(--white);
    }

    .pagination-ellipsis {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-light);
    }

    .pagination-link.first,
    .pagination-link.last,
    .pagination-link.prev,
    .pagination-link.next {
        width: auto;
        padding: 0 1rem;
        gap: 0.5rem;
    }



    /* Responsive */
    @media (max-width: 1024px) {
        .article-image {
            height: 180px;
        }
    }

    @media (max-width: 768px) {
        .blog-header h1 {
            font-size: 2rem;
        }

        .article-content h2 {
            font-size: 1.3rem;
        }

        .article-image {
            height: 160px;
        }

        .blog-container {
            padding: 1.5rem 1rem;
        }
    }

    @media (max-width: 480px) {
        .article-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 1rem;
        }

        .article-actions {
            width: 100%;
            justify-content: flex-end;
        }

        .pagination-link.first,
        .pagination-link.last {
            display: none;
        }
    }
</style>

<?php include("footer.php"); ?>