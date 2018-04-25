<?php include "includes/admin_header.php"; ?>
<?php
if(isset($_SESSION['username'])){

  $username = $_SESSION['username'];

  $query = "SELECT * FROM users WHERE user_name = '$username' ";
  $select_user_profile = mysqli_query($connection, $query);

  while($row = mysqli_fetch_array($select_user_profile)) {
    $user_id = $row['user_id'];
    $user_name = $row['user_name'];
    $user_password = $row['user_password'];
    $user_firstname = $row['user_firstname'];
    $user_lastname = $row['user_lastname'];
    $user_email = $row['user_email'];
    $user_image = $row['user_image'];
    $user_role = $row['user_role'];
  }
}

?>

<?php
if(isset($_POST['edit_user'])) {

  $user_firstname = $_POST['user_firstname'];
  $user_lastname = $_POST['user_lastname'];
  $user_name = $_POST['user_name'];
  $user_email = $_POST['user_email'];
  $user_image = $_FILES['image']['name'];
  $user_image_temp = $_FILES['image']['tmp_name'];

  $user_password = $_POST['user_password'];

  move_uploaded_file($user_image_temp, "../images/$user_image" );

  if(empty($user_image)) {

    $query_image = "SELECT * FROM users WHERE user_name = '{$username}' ";
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
  $query .="user_image = '{$user_image}', ";
  $query .="user_password = '{$user_password}' ";
  $query .="WHERE user_name = '{$username}' ";

  $edit_user_query = mysqli_query($connection, $query);

  queryCheck($edit_user_query);

} ?>

    <div id="wrapper">

<?php include "includes/admin_navigation.php"; ?>

        <div id="page-wrapper">

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">

                      <h1 class="page-header">
                          Welcome to Profile
                      </h1>
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
                        <label for="user_image">User image</label><br>
                        <img width="100" src="../images/<?php echo $user_image; ?>" alt="">
                          <input type="file" name="image">
                        </div>

                        <div class="form-group">
                        <label for="user_password">User password</label>
                          <input autocomplete="off" class="form-control" type="password" name="user_password">
                        </div>

                        <div class="form-group">
                          <input class="btn btn-primary" type="submit" name="edit_user" value="Update Profile">
                        </div>
                      </form>
                    </div>
                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->

<?php include "includes/admin_footer.php"; ?>
