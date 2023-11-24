<?php

session_start();
include_once './config.php';

if (isset($_GET['title'])) {
    $title = $_GET['title'];

    $fetch_posts_query = "SELECT blogpost.title as title,blogpost.post as post,blogpost.image as image,blogpost.category as category,blogpost.created as created,users.name as author FROM blogpost JOIN users ON blogpost.author=users.id WHERE blogpost.title='$title' ";
    $result = $conn->query($fetch_posts_query);
}

$status = TRUE;
$fetch_category_query = "SELECT * FROM category";
$menus = $conn->query($fetch_category_query);

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
                    <input type="text" name="home" hidden>
                    <input type="submit" class="btn" id="home" ; value="Home">
                </form>
                <?php
                if ($menus->num_rows > 0) : ?>
                <?php while ($menu = $menus->fetch_assoc()) : ?>
                <form action="" method="get" style="display: flex;">
                    <input type="text" name="<?php echo $menu['category'] ?>" hidden>
                    <input type="submit" class="btn" value="<?php echo $menu['category'] ?>">
                </form>
                <?php endwhile; ?>
                <?php endif; ?>
            </ul>
        </div>
        <div style='display:grid;grid-gap:10px; grid-template-columns: auto auto;padding:50px 10px 10px 10px'>
            <?php
            // if (isset($title)) {
            //     echo $title;
            // }
            // if (isset($author)) {
            //     echo $author;
            // }
            // if (isset($category)) {
            //     echo $category;
            // }
            ?>

        </div>

    </div>
</body>

</html>