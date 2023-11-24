<?php
session_start();
echo $_SESSION["username"];
include_once '../config.php';

if(isset($_GET['id'])){
    $id=$_GET['id'];

    $query="SELECT * FROM blogpost WHERE id='$id'";
    $result=$conn->query($query);

    if($result->num_rows > 0){
        $post=$result->fetch_assoc();
        $title=$post["title"];
        $category=$post["category"];
        $post=$post["post"];
        $image=$post["image"];
    }else{
        echo("Post not found.");
        exit();
    }
}

if(isset($_POST['update'])){
    $title=$_POST['title'];
    $category=$_POST['category'];
    $post=$_POST['post'];

    $id=$_GET['id'];

    $target_dir="uploads/";
    if(!file_exists($target_dir)){
        mkdir($target_dir,0755,TRUE);
    }

    $image_file=$target_dir .basename($_FILES["image"]["name"]);
    move_uploaded_file($_FILES["image"]["tmp_name"],$image_file);

    if(empty($_POST['title'])){
        $title_error="<p style='color:red;padding:10px'>Post title is required*</p>";
    }else{
        $title=$_POST['title'];
    }

    if(empty($_POST['category']) || $_POST['category']==="select"){
        $category_error="<p style='color:red;padding:10px'>Post Category is required*</p>";
    }else{
        $category=$_POST['category'];
    }

    if(empty($_POST['post'])){
        $post_error="<p style='color:red;padding:10px'>Post Content is required*</p>";
    }else{
        $post=$_POST['post'];
    }

    if(!empty($title) && !empty($category) && !empty($post)){
        $update_query="UPDATE blogpost SET title='$title',category='$category',post='$post',image='$image_file' WHERE id='$id'";
        echo($query);
        if($conn->query($update_query)===TRUE){
            $update_message="<p style='background:green; color:white;padding:10px'>Post updated successfully</p>";
            header("Location: ./list.php");
            exit();
        }else{
            $update_error_message="<p style='background:red;color:white;padding:10px'>Post update failed" . $conn->error ."</p>";
        }
    }else{
        $empty_message="<p style='background:red;color:white'>Ooooop something went wrong " . $conn->error ."</p>";
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
                <h3>Edit post</h3>
            </div>

            <?php if(isset($update_message)){echo($update_message);}?>
            <?php if(isset($update_error_message)){echo($update_error_message);}?>
            <div style="padding:10px">
                <label style="padding:8px 0" for="title">Title</label><br>
                <textarea style="width:100%; border:none;outline:1px solid blue;padding:10px" name="title" id=""
                    cols="10" rows="1"><?php if(isset($title)){echo($title);}?></textarea>
            </div>

            <div style="padding:10px">
                <label style="padding:8px 0" for="category">Category</label><br>
                <select name="category" value=<?php if(isset($category)){echo($category);}?> id=""
                    style="width:100%;border-color:blue; padding:8px;font-size:16px">
                    <option value=<?php if(isset($category)){echo($category);}?>>
                        <?php if(isset($category)){echo($category);}?></option>
                    <option style="padding:10px" value="Technlogy">Technlogy</option>
                    <option style="padding:10px" value="Agriculture">Agriculture</option>
                    <option style="padding:10px" value="Education">Education</option>
                    <option style="padding:10px" value="Business">Business</option>
                </select>
            </div>

            <div style="padding:10px;width:100%">
                <label style="padding:8px 0" for="password">Post</label><br>
                <textarea style="width:100%; border:none;outline:1px solid blue;padding:10px" name="post" id=""
                    cols="50" rows="10"><?php if(isset($post)){echo($post);}?></textarea>
            </div>

            <div style="padding:10px;">
                <label style="padding:8px 0" for="image">Image</label><br>
                <input type="file" name="image" accept="image/*" style="padding:10px 0"><br>
                <?php if(isset($image)){echo("<img src='".$image ."' alt='' srcset='' width=70px heigth=40px>");}?>

            </div>

            <div style="padding:10px;text-align:right">
                <button style="width:35%; padding:10px 15px;background:blue;color:white;border:none;border-radius:8px"
                    type="submit" name="update">Update Post</button>
                <a href="./list.php"
                    style="width:35%; text-decoration:none; padding:10px 15px;background:red;color:white;border:none;border-radius:8px">Cancel</a>
            </div>
        </form>
    </div>
</body>

</html>