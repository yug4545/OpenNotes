<?php
require_once 'connect.php';
require_once 'header.php';

$id = (int)$_GET['id'];
if ($id < 1) {
    header("location: index.php");
    exit;
}

$sql = "SELECT * FROM category WHERE id = '$id'";
$result = mysqli_query($dbcon, $sql);
if (mysqli_num_rows($result) == 0) {
    header("location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cyberpunk Forum</title>
    <style>
        :root {
            --neon-pink: #ff00f0;
            --neon-blue: #00f0ff;
            --neon-green: #00ffa3;
            --neon-purple: #b300ff;
            --dark-bg: #0a0a12;
            --darker-bg: rgba(20, 20, 40, 0.9);
            --font-mono: 'Courier New', monospace;
            --font-cyber: 'Rajdhani', 'Courier New', sans-serif;
        }
        
        .cyber-header {
            background: linear-gradient(90deg, #0a0a12 0%, #1a1a3a 100%);
            color: var(--neon-blue);
            padding: 2rem;
            text-align: center;
            border-bottom: 3px solid var(--neon-pink);
            box-shadow: 0 0 20px rgba(0, 240, 255, 0.3);
            margin-bottom: 2rem;
            font-family: var(--font-cyber);
            letter-spacing: 2px;
            text-transform: uppercase;
            position: relative;
            overflow: hidden;
        }
        
        .cyber-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 240, 255, 0.1), transparent);
            animation: scan 3s linear infinite;
        }
        
        .cyber-post {
            background: var(--darker-bg);
            border-left: 4px solid var(--neon-purple);
            margin: 1.5rem auto;
            padding: 1.5rem;
            max-width: 800px;
            box-shadow: 0 0 15px rgba(179, 0, 255, 0.2);
            position: relative;
            transition: all 0.3s ease;
        }
        
        .cyber-post:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 25px rgba(179, 0, 255, 0.4);
        }
        
        .cyber-post-title {
            color: var(--neon-pink);
            font-family: var(--font-cyber);
            font-size: 1.5rem;
            margin-bottom: 1rem;
            text-shadow: 0 0 5px var(--neon-pink);
        }
        
        .cyber-post-title a {
            color: inherit;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .cyber-post-title a:hover {
            color: var(--neon-blue);
            text-shadow: 0 0 10px var(--neon-blue);
        }
        
        .cyber-post-desc {
            color: #ccc;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-family: var(--font-mono);
        }
        
        .cyber-post-meta {
            display: flex;
            justify-content: space-between;
            font-family: var(--font-mono);
            font-size: 0.9rem;
        }
        
        .cyber-post-link {
            color: var(--neon-green);
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .cyber-post-link:hover {
            text-shadow: 0 0 8px var(--neon-green);
        }
        
        .cyber-post-time {
            color: var(--neon-blue);
            opacity: 0.8;
        }
        
        .cyber-empty {
            background: var(--darker-bg);
            border: 1px dashed var(--neon-pink);
            color: var(--neon-pink);
            padding: 2rem;
            text-align: center;
            max-width: 800px;
            margin: 2rem auto;
            font-family: var(--font-mono);
            text-shadow: 0 0 5px var(--neon-pink);
        }
        
        @keyframes scan {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        
        .cyber-corner {
            position: absolute;
            width: 15px;
            height: 15px;
        }
        
        .cyber-corner-tl {
            top: 0;
            left: 0;
            border-top: 2px solid var(--neon-blue);
            border-left: 2px solid var(--neon-blue);
        }
        
        .cyber-corner-tr {
            top: 0;
            right: 0;
            border-top: 2px solid var(--neon-blue);
            border-right: 2px solid var(--neon-blue);
        }
        
        .cyber-corner-bl {
            bottom: 0;
            left: 0;
            border-bottom: 2px solid var(--neon-blue);
            border-left: 2px solid var(--neon-blue);
        }
        
        .cyber-corner-br {
            bottom: 0;
            right: 0;
            border-bottom: 2px solid var(--neon-blue);
            border-right: 2px solid var(--neon-blue);
        }
    </style>
</head>
<body>
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <?php
        $post_cat = $row['id'];
        $catname = $row['category_name']; 
        ?>
        
        <div class="cyber-header">
            <div class="cyber-corner cyber-corner-tl"></div>
            <div class="cyber-corner cyber-corner-tr"></div>
            <h3>CATEGORY: <?php echo strtoupper($catname); ?></h3>
            <div class="cyber-corner cyber-corner-bl"></div>
            <div class="cyber-corner cyber-corner-br"></div>
        </div>
    <?php endwhile; ?>

    <?php
    $sql1 = "SELECT * FROM posts WHERE category_id = '$post_cat' ORDER BY id DESC";
    $res = mysqli_query($dbcon, $sql1);
    
    if (mysqli_num_rows($res) == 0): ?>
        <div class="cyber-empty">
            >> NO POSTS FOUND IN THIS CATEGORY <<
        </div>
    <?php else: ?>
        <?php while ($r = mysqli_fetch_assoc($res)): ?>
            <?php
            $id = $r['id'];
            $title = $r['title'];
            $des = $r['description'];
            $time = $r['date'];
            ?>
            
            <div class="cyber-post">
                <h3 class="cyber-post-title">
                    <a href="view.php?id=<?php echo $id; ?>"><?php echo strtoupper($title); ?></a>
                </h3>
                
                <p class="cyber-post-desc">
                    <?php echo (strlen($des) > 100) ? substr($des, 0, 100) . "..." : $des; ?>
                </p>
                
                <div class="cyber-post-meta">
                    <a href="view.php?id=<?php echo $id; ?>" class="cyber-post-link">
                        [READ FULL DATASTREAM]
                    </a>
                    <span class="cyber-post-time">
                        <?php echo date("Y-m-d H:i:s", strtotime($time)); ?>
                    </span>
                </div>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

    <?php include("footer.php"); ?>
</body>
</html>