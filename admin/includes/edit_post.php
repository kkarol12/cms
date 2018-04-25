<?php

if(isset($_GET['p_id'])){
  $the_post_id = $_GET['p_id'];
}
$query = "SELECT * FROM posts WHERE post_id = $the_post_id";
$select_posts_by_id = mysqli_query($connection, $query);

while($row = mysqli_fetch_assoc($select_posts_by_id)){
$post_id = $row['post_id'];
$post_user = $row['post_user'];
$post_title = $row['post_title'];
$post_category_id = $row['post_category_id'];
$post_status = $row['post_status'];
$post_image = $row['post_image'];
$post_content = $row['post_content'];
$post_tags = $row['post_tags'];
$post_comment_count = $row['post_comment_count'];
$post_date = $row['post_date'];
$post_views_count = $row['post_views_count'];
}

if(isset($_POST['update_post'])) {

  $post_title = $_POST['title'];
  $post_user = $_POST['post_user'];
  $post_category_id = $_POST['post_category'];
  $post_status = $_POST['post_status'];

  $post_image = $_FILES['image']['name'];
  $post_image_temp = $_FILES['image']['tmp_name'];

  $post_tags = $_POST['post_tags'];
  $post_content = $_POST['post_content'];
  $post_views_count = $_POST['post_views_count'];

  move_uploaded_file($post_image_temp, "../images/$post_image" );

  if(empty($post_image)) {

    $query = "SELECT * FROM posts WHERE post_id = $the_post_id ";
    $select_image = mysqli_query($connection, $query);

    while($row = mysqli_fetch_array($select_image)) {
      $post_image = $row['post_image'];
    }
  }

  $post_content = mysqli_real_escape_string($connection, $post_content);

  $query = "UPDATE posts SET ";
  $query .="post_title = '{$post_title}', ";
  $query .="post_category_id = '{$post_category_id}', ";
  $query .="post_date = now(), ";
  $query .="post_user = '{$post_user}', ";
  $query .="post_status = '{$post_status}', ";
  $query .="post_tags = '{$post_tags}', ";
  $query .="post_content = '{$post_content}', ";
  $query .="post_image = '{$post_image}', ";
  $query .="post_views_count = '{$post_views_count}' ";
  $query .="WHERE post_id = {$the_post_id} ";

  $update_post = mysqli_query($connection, $query);

  queryCheck($update_post);

  echo "<p class='bg-success'>Post Updated. <a href='../post.php?p_id={$the_post_id}'>View Post</a> or
  <a href='posts.php'>Edit More Posts</a></p>";

}
 ?>


<form action="" method="post" enctype="multipart/form-data">

  <div class="form-group">
  <label for="title">Post Title</label>
    <input value="<?php echo $post_title; ?>" class="form-control" type="text" name="title">
  </div>
  <div class="form-group">
  <label for="title">Post Category</label>
  <select class="form-control" name="post_category">

    <?php

    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);
    queryCheck($select_categories);

    while($row = mysqli_fetch_assoc($select_categories)){
      $cat_id = $row['cat_id'];
      $cat_title = $row['cat_title'];

      if($cat_id == $post_category_id){
        echo "<option value='$cat_id' selected='selected'>{$cat_title}</option>";
      } else {
        echo "<option value='$cat_id'>{$cat_title}</option>";
      }
}
    ?>
  </select>
  </div>
  <div class="form-group">
  <label for="author">Post User</label>
  <select class="form-control" name="post_user">
    <option value="<?php echo $post_user; ?>"><?php echo $post_user; ?></option>
    <?php

    $query = "SELECT * FROM users";
    $select_user = mysqli_query($connection, $query);

    queryCheck($select_user);

    while($row = mysqli_fetch_assoc($select_user)){
      $user_id = $row['user_id'];
      $user_name = $row['user_name'];

      if ($user_name != $post_user){
      echo "<option value='$user_name'>{$user_name}</option>";
}
}
    ?>
  </select>
</div>

  <div class="form-group">
  <label for="post_status">Post Status</label><br>
  <select class="form-control" name="post_status">
    <option value="<?php echo $post_status; ?>"><?php echo $post_status; ?></option>
    <?php if($post_status == 'published') {
      echo "<option value='draft'>draft</option>";
    } else {
      echo "<option value='published'>published</option>";
    } ?>
  </select>
  </div>
  <div class="form-group">
  <label for="post_image">Post Image</label><br><br>
  <img width="100" src="../images/<?php echo $post_image; ?>" alt=""><br><br>
  <input type="file" name="image">
  </div>
  <div class="form-group">
  <label for="post_tags">Post Tags</label>
    <input value="<?php echo $post_tags; ?>" class="form-control" type="text" name="post_tags">
  </div>
  <div class="form-group">
  <label for="post_content">Post Content</label>
    <textarea class="form-control" name="post_content" id="blok" cols="30" rows="10"><?php echo $post_content; ?>
    </textarea>
  </div>
  <div class="form-group">
  <label for="post_tags">Post Views Count</label>
  <select class="form-control" name="post_views_count">
    <option value="<?php echo $post_views_count; ?>">Current count of displays: <?php echo $post_views_count; ?></option>
    <option value="0">Reset Views Count</option>";
  </select>
  </div>
  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="update_post" value="Update Post">
  </div>
</form>
