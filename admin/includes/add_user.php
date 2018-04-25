<?php

if(isset($_POST['create_user'])) {

  $user_firstname = $_POST['user_firstname'];
  $user_lastname = $_POST['user_lastname'];
  $user_name = $_POST['user_name'];
  $user_role = $_POST['user_role'];
  $user_email = $_POST['user_email'];

  $user_image = $_FILES['image']['name'];
  $user_image_temp = $_FILES['image']['tmp_name'];

  $user_password = $_POST['user_password'];

  $user_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 10) );

  move_uploaded_file($user_image_temp, "../images/$user_image" );

  $query = "INSERT INTO users(user_firstname, user_lastname, user_name, user_role, user_email, user_image, user_password) ";
  $query .= "VALUES('{$user_firstname}','{$user_lastname}','{$user_name}','{$user_role}','{$user_email}','{$user_image}','{$user_password}') ";

  $create_user_query = mysqli_query($connection, $query);

  queryCheck($create_user_query);

  echo "<p class='bg-success'>User Created: " . " ". "<a href='users.php'>View Users</a></p>";

} ?>


<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
  <label for="user_firstname">First name</label>
    <input class="form-control" type="text" name="user_firstname">
  </div>
  <div class="form-group">
  <label for="user_lastname">Last name</label>
    <input class="form-control" type="text" name="user_lastname">
  </div>
  <div class="form-group">
  <label for="user_name">User name (Login)</label>
    <input class="form-control" type="text" name="user_name">
  </div>
  <div class="form-group">
  <label for="title">User email</label>
    <input class="form-control" type="email" name="user_email">
  </div>
  <div class="form-group">
  <label for="title">Wybierz role</label>
  <select class="form-control" name="user_role" id="">
    <option value="subscriber">Select Option</option>
    <option value="admin">Admin</option>
    <option value="subscriber">Subscriber</option>
    ?>
  </select>
  </div>

  <div class="form-group">
  <label for="user_image">User image</label><br>
    <input type="file" name="image">
  </div>


  <div class="form-group">
  <label for="user_password">User password</label>
    <input class="form-control" type="password" name="user_password">
  </div>

  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="create_user" value="Create User">
  </div>
</form>
