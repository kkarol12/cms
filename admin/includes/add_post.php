<?php


if(isset($_POST['create_post'])) {

  $post_title = $_POST['title'];
  $post_user = $_POST['post_user'];
  $post_author = "";
  $post_category_id = $_POST['post_category'];
  $post_status = $_POST['post_status'];

  $post_image = $_FILES['image']['name'];
  $post_image_temp = $_FILES['image']['tmp_name'];

  $post_tags = $_POST['post_tags'];
  $post_content = $_POST['post_content'];
  $post_date = date('d-m-y');

  move_uploaded_file($post_image_temp, "../images/$post_image" );

  $query = "INSERT INTO posts(post_category_id, post_title, post_user, post_date, post_image, post_content, post_tags, post_status) ";
  $query .= "VALUES('{$post_category_id}','{$post_title}','{$post_user}',now(),'{$post_image}','{$post_content}','{$post_tags}','{$post_status}') ";

  $create_post_query = mysqli_query($connection, $query);

  $the_post_id = mysqli_insert_id($connection);

  queryCheck($create_post_query);

  echo "<p class='bg-success'>Post Created. <a href='../post.php?p_id={$the_post_id}'>View Post</a> or
  <a href='posts.php'>Edit More Posts</a></p>";

} ?>


<form action="" method="post" enctype="multipart/form-data">

  <div class="form-group">
  <label for="title">Post Title</label>
    <input class="form-control" type="text" name="title">
  </div>
  <div class="form-group">
  <label for="post_status">Post Category</label><br>
  <select class="form-control" name="post_category">
    <?php

    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);

    queryCheck($select_categories);

    while($row = mysqli_fetch_assoc($select_categories)){
      $cat_id = $row['cat_id'];
      $cat_title = $row['cat_title'];

      echo "<option value='$cat_id'>{$cat_title}</option>";

}
    ?>
  </select>
  </div>
  <div class="form-group">
  <label for="author">Post User</label>
  <select class="form-control" name="post_user">
    <?php

    $query = "SELECT * FROM users";
    $select_user = mysqli_query($connection, $query);

    queryCheck($select_user);

    while($row = mysqli_fetch_assoc($select_user)){
      $user_id = $row['user_id'];
      $user_name = $row['user_name'];

      echo "<option value='$user_name'>{$user_name}</option>";

}
    ?>
  </select>
  </div>
  <div class="form-group">
  <label for="post_status">Post Status</label><br>
  <select class="form-control" name="post_status">
    <option value="draft">Select Options</option>
    <option value="published">Published</option>
    <option value="draft">Draft</option>
  </select>
  </div>
  <div class="form-group">
  <label for="post_image">Post Image</label>
    <input type="file" name="image">
  </div>
  <div class="form-group">
  <label for="post_tags">Post Tags</label>
    <input class="form-control" type="text" name="post_tags">
  </div>
  <div class="form-group">
  <label for="post_content">Post Content</label>
    <textarea class="form-control" name="post_content" id="blok" cols="30" rows="10"></textarea>
  </div>
  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="create_post" value="Publish Post">
  </div>
</form>