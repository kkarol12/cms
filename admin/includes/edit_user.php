<?php

if(isset($_GET['edit_user'])){
  $the_user_id = $_GET['edit_user'];

  $query = "SELECT * FROM users where user_id = $the_user_id ";
  $select_users_query = mysqli_query($connection, $query);

  while($row = mysqli_fetch_assoc($select_users_query)){
  $user_id = $row['user_id'];
  $user_name = $row['user_name'];
  $user_password = $row['user_password'];
  $user_firstname = $row['user_firstname'];
  $user_lastname = $row['user_lastname'];
  $user_email = $row['user_email'];
  $user_image = $row['user_image'];
  $user_role = $row['user_role'];
}

if(isset($_POST['edit_user'])) {

  $user_firstname = $_POST['user_firstname'];
  $user_lastname = $_POST['user_lastname'];
  $user_name = $_POST['user_name'];
  $user_role = $_POST['user_role'];
  $user_email = $_POST['user_email'];

  $user_image = $_FILES['image']['name'];
  $user_image_temp = $_FILES['image']['tmp_name'];

//password crypt
  $user_password = $_POST['user_password'];

  if(!empty($user_password)){
    $query_password = "SELECT user_password FROM users WHERE user_id = $the_user_id";
    $get_user = mysqli_query($connection, $query_password);
    queryCheck($get_user);

    $row = mysqli_fetch_array($get_user);
    $db_user_password = $row['user_password'];

    if($db_user_password != $user_password){
      $hashed_password = password_hash($user_password, PASSWORD_BCRYPT, array('cost' => 10) );
    }
  }

  move_uploaded_file($user_image_temp, "../images/$user_image" );

  if(empty($user_image)) {

    $query_image = "SELECT * FROM users WHERE user_id = $the_user_id ";
    $select_image = mysqli_query($connection, $query_image);

    while($row = mysqli_fetch_array($select_image)) {
      $user_image = $row['user_image'];
    }
  }

  $query = "UPDATE users SET ";
  $query .="user_name = '{$user_name}', ";
  $query .="user_firstname = '{$user_firstname}', ";
  $query .="user_lastname = '{$user_lastname}', ";
  $query .="user_role = '{$user_role}', ";
  $query .="user_email = '{$user_email}', ";
  if(!empty($_POST['user_password'])){
  $query .="user_password = '{$hashed_password}', ";
};
  $query .="user_image = '{$user_image}' ";
  $query .="WHERE user_id = {$the_user_id} ";
  $edit_user_query = mysqli_query($connection, $query);

  queryCheck($edit_user_query);

  echo "<p class='bg-success'>User Updated. <a href='users.php'>Come back to users list.</a></p>";

}
} else {
  header("Location: index.php");
}

 ?>


<form action="" method="post" enctype="multipart/form-data">
  <div class="form-group">
  <label for="user_firstname">First name</label>
    <input class="form-control" type="text" name="user_firstname" value="<?php echo $user_firstname; ?>">
  </div>
  <div class="form-group">
  <label for="user_lastname">Last name</label>
    <input class="form-control" type="text" name="user_lastname" value="<?php echo $user_lastname; ?>">
  </div>
  <div class="form-group">
  <label for="user_name">User name (Login)</label>
    <input class="form-control" type="text" name="user_name" value="<?php echo $user_name; ?>">
  </div>
  <div class="form-group">
  <label for="title">User email</label>
    <input class="form-control" type="email" name="user_email" value="<?php echo $user_email; ?>">
  </div>
  <div class="form-group">
  <label for="title">Wybierz role</label><br>
  <select class="form-control" name="user_role" id="">
    <option value="<?php echo $user_role; ?>"><?php echo $user_role; ?></option>
    <?php
      if($user_role == 'admin') {
        echo "<option value='subscriber'>Subscriber</option>";
      } else {
        echo "<option value='admin'>Admin</option>";
      }
     ?>
  </select>
  </div>

  <div class="form-group">
  <label for="user_image">User image</label><br>
  <img width="100" src="../images/<?php echo $user_image; ?>" alt="">
    <input type="file" name="image">
  </div>

  <div class="form-group">
  <label for="user_password">User password</label>
    <input autocomplete="off" class="form-control" type="password" name="user_password">
  </div>

  <div class="form-group">
    <input class="btn btn-primary" type="submit" name="edit_user" value="Edit User">
  </div>
</form>
