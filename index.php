<?php

session_start();
$id = $_SESSION['id'];
$username = $_SESSION["username"];
include_once './config.php';

$status = TRUE;
$fetch_category_query = "SELECT * FROM category";
$menus = $conn->query($fetch_category_query);

$fetch_posts_query = "SELECT blogpost.id as id, blogpost.title as title,blogpost.post as post,blogpost.image as image,blogpost.category as category,blogpost.created as created,users.name as author FROM blogpost JOIN users ON blogpost.author=users.id WHERE blogpost.status='$status' ";
$result = $conn->query($fetch_posts_query);

//filter
if (isset($_GET['category'])) {
    echo "<p>" . $_GET['category'] . "</p>";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>posts</title>
</head>
<style>
* {
    margin: 0;
    box-sizing: border-box;
}

.btn {
    cursor: pointer;
    padding: 20px 10px;
    background: gray;
    color: white;
    border: none;
}

.btn:hover {
    background: blue;
    color: white;

}

.main-content {
    cursor: pointer;
}
</style>

<body>
    <div style='width:80%;margin:auto; padding: 10px;'>
        <div class="nav" style="width:100%; padding:0 10px">
            <ul style="display: flex; list-style: none;padding:0 10px;background:gray;justify-content: end;">
                <form action="" method="get" style="display: flex;">
                    <input type="text" name="category" value="Home" hidden>
                    <input type="submit" class="btn" value="Home">
                </form>
                <?php
                if ($menus->num_rows > 0) : ?>
                <?php while ($menu = $menus->fetch_assoc()) : ?>
                <form action="" method="get" style="display: flex;">
                    <input type="text" name="category" value="<?php echo $menu['category'] ?>" hidden>
                    <input type="submit" class="btn" value="<?php echo $menu['category'] ?>">
                </form>
                <?php endwhile; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div style='display:grid;grid-gap:10px; grid-template-columns: auto auto;padding:50px 10px 10px 10px'>
            <?php
            if ($result->num_rows > 0) {
                while ($post = $result->fetch_assoc()) {
                    echo "<a href='post_details.php?title=" . $post['title'] . "'><div class='main-content' style='box-sizing: border-box; padding:10px;border:1px solid gray;'>";
                    echo "<div style='box-sizing: border-box;'>
                <img src='./admin/" . $post['image'] . "' width=100% height='250px'  alt='' srcset=''>
                </div>";
                    echo "<div style='box-sizing: border-box;'>";
                    echo "<div style='margin:20px 0'><p style=''>" . $post['category'] . "</p></div>";
                    echo "<div style='margin:20px 0'><p style='font-style:bold; '>" . $post['title'] . "</p></div>";
                    echo "<p style='font-size:12px'>" . $post['created'] . "</p>";
                    echo "</div>";
                    echo "</div></a>";
                };
            } else {
                echo "no posts yet";
            }
            ?>

        </div>

    </div>
</body>

</html>