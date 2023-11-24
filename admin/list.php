<?php
session_start();
$id = $_SESSION['id'];
echo $_SESSION["username"];
include_once '../config.php';

$query = "SELECT * FROM blogpost";
$result = $conn->query($query);

//Update post status functionality code
if (isset($_POST['submit'])) {
    $post_id = $_POST['id'];

    $verify_user_sql = "SELECT * FROM blogpost WHERE id='$post_id'";
    $res = $conn->query($verify_user_sql);
    if ($res->num_rows > 0) {

        $blogpost = $res->fetch_assoc();
        $author_id = $blogpost['author'];
        // echo ($post_id);
        if ($id == $author_id) {
            $blog_status = "SELECT status FROM blogpost WHERE id='$post_id'";
            $post_status=$conn->query($blog_status);
            $published_status=$post_status->fetch_assoc();
            $status=$published_status['status'];
            
            if($status==0){
                $status=1;
                $update_status_query = "UPDATE blogpost SET status='$status' WHERE id='$post_id'";
                if ($conn->query($update_status_query) === TRUE) {
                    header("Location: ./list.php");
                    exit();
                } 
            }else{
                $status=0;
                $update_status_query = "UPDATE blogpost SET status='$status' WHERE id='$post_id'";
                if ($conn->query($update_status_query) === TRUE) {
                    header("Location: ./list.php");
                    exit();
                } 
            }
        } else {
            $delete_error_message = "<p style='background:red;padding:10px;color:white'>Sorry you are not autorized to perform this action</p>";
        }
    }
}

//Delete post functionality code
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];
    $verify_user_sql = "SELECT * FROM blogpost WHERE id='$post_id'";
    $res = $conn->query($verify_user_sql);
    if ($res->num_rows > 0) {

        $blogpost = $res->fetch_assoc();
        $author_id = $blogpost['author'];
        if ($id == $author_id) {
            $delete_query = "DELETE FROM blogpost WHERE id='$post_id'";
            if ($conn->query($delete_query) === TRUE) {
                header("Location: ./list.php");
                $delete_success_message = "<p style='background:green;padding:10px;color:white'>Post deleted successfully</p>";
                exit();
            } else {
                $delete_error_message = "<p style='background:red;padding:10px;color:white'>Failed to delete post</p>";
            }
        } else {
            $delete_error_message = "<p style='background:red;padding:10px;color:white'>Sorry you are not autorized to delete this post</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blogs</title>
</head>
<style>
table {
    border-collapse: collapse;
}

table th {
    padding: 8px;
}

table td {
    padding: 8px;
    text-align: center;
}
</style>

<body>
    <div style="width:80%;margin:auto">
        <?php if (isset($delete_error_message)) {
            echo ($delete_error_message);
        } ?>
        <?php if (isset($delete_success_message)) {
            echo ($delete_success_message);
        } ?>
        <h3>Blog Posts</h3>

        <div style='display:flex; justify-content: space-between; width:100%;padding-top:20px'>
            <div>
                <a href="../index.php"
                    style="width:8%; text-decoration:none; padding:10px 15px;background:blue;color:white;border:none;border-radius:8px">View
                    Site</a>
            </div>

            <div style="text-align: right;">
                <a href="./create.php"
                    style="width:8%; text-decoration:none; padding:10px 15px;background:blue;color:white;border:none;border-radius:8px">
                    Create
                    Post</a>
            </div>

        </div>

        <table style="width:100%;margin-top:20px">
            <tr style="background:gray;padding:10px">
                <th style="text-align:center">Id</th>
                <th style="width:30%">Title</th>
                <th style="width:20%">Category</th>
                <th style="width:20%">Image</th>
                <th style="width:20%">Status</th>
                <th style="width:10%">Actions</th>
                <img src="" alt="" srcset="">
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while ($post = $result->fetch_assoc()) {
                    if ($post['status'] == TRUE) {
                        $status = "published";
                    } else {
                        $status = "publish";
                        $id = $post['id'];
                    }
                    echo "<tr>";
                    echo "<td>" . $post['id'] . "</td>";
                    echo "<td><a href=./update.php?id=" . $post['id'] . ">" . $post['title'] . "</a></td>";
                    echo "<td>" . $post['category'] . "</td>";
                    echo "<td><img src='" . $post['image'] . "' width=60px height=40px alt='' srcset=''></td>";
                    echo "<td><form style='margin-top:15px;display:flex' action='' method='post'><input hidden style='width:40px;background:green;color:white;padding:4px 8px;border:none;border-radius:8px;' type='text' name='id' value=" . $post['id'] . "><input style='background:green;color:white;padding:4px 8px;border:none;border-radius:8px;' id=" . $id .  " type='submit' name='submit' value=" . $status . "></form></td>";
                    echo "<td><div style='display:inline-flex;'><a href=./update.php?id=" . $post['id'] . " style=' padding:5px 7px;border:none;border-radius:8px'>edit</a>
                <a href=./list.php?action=delete&id=" . $post['id'] . " style='background:red;color:white; padding:5px 7px;border:none;border-radius:8px'>delete</a></div></td>";
                    echo "</tr>";
                };
            }
            $conn->close();
            ?>

        </table>

    </div>
</body>

</html>