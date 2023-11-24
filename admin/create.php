<?php
session_start();
$session_username = $_SESSION["username"];
$session_id = (int)$_SESSION["id"];
include_once '../config.php';

$fetch_category_query = "SELECT * FROM category";
$category_result = $conn->query($fetch_category_query);

if ($category_result->num_rows > 0) {
    $categories = [];
    while ($category_row = $category_result->fetch_assoc()) {
        $categories[] = $category_row;
    }
} else {
    $categories = array();
}

if (isset($_POST["submit"])) {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $post = $_POST['post'];
    //$photo=$_POST['photo'];

    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, TRUE);
    }

    $image_file = $target_dir . basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"], $image_file);

    if (empty($_POST['title'])) {
        $title_error = "<p style='color:red;padding:10px'>Post title is required*</p>";
    } else {
        $title = $_POST['title'];
    }

    if (empty($_POST['category']) || $_POST['category'] === "select") {
        $category_error = "<p style='color:red;padding:10px'>Post Category is required*</p>";
    } else {
        $category = $_POST['category'];
    }

    if (empty($_POST['post'])) {
        $post_error = "<p style='color:red;padding:10px'>Post Content is required*</p>";
    } else {
        $post = $_POST['post'];
    }

    if (!empty($title) && !empty($category) && !empty($post)) {
        $query = "INSERT INTO `blogpost` (`title`, `post`, `image`,`category`,`author`) VALUES('$title', '$post','$image_file','$category','$session_id')";
        echo ($query);
        if ($conn->query($query) === TRUE) {
            $success = "<p style='background:green; color:white;padding:10px'>Post created successfully</p>";
            header("Location: list.php");
            exit();
        } else {
            $insert_error = "<p style='background:red;color:white;padding:10px'>Post creation failed" . $conn->error . "</p>";
        }
    } else {
        $empty_message = "<p style='background:red;color:white;padding:10px'>Oooops! something went wrong" . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
</head>

<body>
    <div>
        <form action="" method="post" style="width:50%;margin:auto;" enctype="multipart/form-data">
            <div style="padding:10px">
                <?php if (isset($empty_message)) {
                    echo ($empty_message);
                } ?>
                <?php if (isset($success)) {
                    echo ($success);
                } ?>
                <?php if (isset($insert_error)) {
                    echo ($insert_error);
                } ?>
            </div>
            <div style="padding:10px">
                <h3>Create Post</h3>
            </div>

            <div style="padding:10px">
                <label style="padding:8px 0" for="title">Title</label><br>
                <input
                    style="width:100%;border-color:blue;border:none;outline:1px solid blue; padding:8px;font-size:15px"
                    type="text" name="title" placeholder="Enter Title">
                <?php if (isset($title_error)) {
                    echo ($title_error);
                } ?>
            </div>

            <div style="padding:10px">
                <label style="padding:8px 0" for="category">Category</label><br>
                <select name="category" id="" style="width:100%;border-color:blue; padding:8px;font-size:15px">
                    <option value="select">select category</option>
                    <?php foreach ($categories as $category) : ?>
                    <option style='padding:10px' value="<?php echo $category['category']; ?>">
                        <?php echo $category['category']; ?>
                    </option>
                    <?php endforeach; ?>

                </select>
                <?php if (isset($category_error)) {
                    echo ($category_error);
                } ?>
            </div>

            <div style=" padding:10px;width:100%">
                <label style="padding:8px 0" for="password">Post</label><br>
                <textarea style="width:100%; border:none;outline:1px solid blue" name="post" id="" cols="50"
                    rows="10"></textarea>
                <?php if (isset($post_error)) {
                    echo ($post_error);
                } ?>
            </div>

            <div style="padding:10px;">
                <label style="padding:8px 0" for="photo">Image</label><br>
                <input type="file" name="image" style="padding:10px 0" accept="image/*">
            </div>

            <div style="padding:10px;text-align:right">
                <button style="width:35%; padding:10px 15px;background:blue;color:white;border:none;border-radius:8px"
                    type="submit" name="submit">Save Post</button>
            </div>
        </form>
    </div>
</body>

</html>