<?php

// have to remember about change the path;
function redirect($location){
  header("Location: /PHPforBeginnersUdemy/cms/" . $location);
  exit;
}

function ifItIsMethod($method=null){
  if($_SERVER['REQUEST_METHOD'] == strtoupper($method)) {
    return true;
  }
  return false;
}

function isLoggedIn(){
  if(isset($_SESSION['user_role'])){
    return true;
  }
  return false;
}

function checkIfUserIsLoggedInAndRedirect($redirectLocation=null){
  if(isLoggedIn()){
    redirect($redirectLocation);
  }
}

function escape($string){
  global $connection;

  return mysqli_real_escape_string($connection, trim($string));

}


function usersOnline() {

  if(isset($_GET['onlineusers'])){

    global $connection;

    if(!$connection){
      session_start();

      include("../includes/db.php");

      $session = session_id();
      $time = time();
      $time_out_seconds = 05;
      $time_out = $time - $time_out_seconds;

      $query = "SELECT * FROM users_online WHERE session = '$session' ";
      $send_query = mysqli_query($connection, $query);
      $count = mysqli_num_rows($send_query);

      if($count == NULL){
        $query = "INSERT INTO users_online(session, time) VALUES('$session', '$time') ";
        mysqli_query($connection, $query);
      } else {
        $query = "UPDATE users_online SET time = '$time' WHERE session = '$session' ";
        mysqli_query($connection, $query);

      }
      $query = "SELECT * FROM users_online WHERE time > '$time_out' ";
      $users_online_query = mysqli_query($connection, $query);
      echo $count_user = mysqli_num_rows($users_online_query);
      }
  }
}

usersOnline();

// Checking the validation of query
function queryCheck($query_check) {
  global $connection;

  if(!$query_check){
    die("QUERY FAILED .". mysqli_error($connection));
  }
}

function insert_categories() {

  global $connection;

  if(isset($_POST['submit'])){

    $cat_title = $_POST['cat_title'];

    if($cat_title == "" || empty($cat_title)){
      echo "This field should not be empty";
    } else {

      $stmt = mysqli_prepare($connection, "INSERT INTO categories(cat_title) VALUES(?) ");

      mysqli_stmt_bind_param($stmt, 's', $cat_title);
      mysqli_stmt_execute($stmt);

      queryCheck($stmt);
    }
  }
}

// Showing table with all Categories and buttons to edit and delete it
function findAllCategories() {

  global $connection;

  $query = "SELECT * FROM categories";
  $select_categories = mysqli_query($connection, $query);

  while($row = mysqli_fetch_assoc($select_categories)){
    $cat_id = $row['cat_id'];
    $cat_title = $row['cat_title'];

    echo "<tr>";
    echo "<td>{$cat_id}</td>";
    echo "<td>{$cat_title}</td>";
    echo "<td><a href='categories.php?delete={$cat_id}'>Delete</a></td>";
    echo "<td><a href='categories.php?edit={$cat_id}'>Edit</a></td>";
    echo "</tr>";
  }
}

function deleteCategories() {

    global $connection;

     if(isset($_GET['delete'])){
       $the_cat_id = escape($_GET['delete']);
       $query = "DELETE FROM categories WHERE cat_id = {$the_cat_id} ";
       $delete_query = mysqli_query($connection, $query);
       header("Location: categories.php");
     }

}

// Counting all records from specific table
function recordCount($table){

  global $connection;

  $query = "SELECT * FROM " . $table;
  $select_all = mysqli_query($connection, $query);
  $result = mysqli_num_rows($select_all);

  queryCheck($result);
  return $result;
}

function imagePlaceholder($image=null){
  if(!$image){
    return 'placeholder.png';
  } else {
    return $image;
  }
}



// Counting rows in table with specific value in one column.
function checkStatus($table, $columnName, $status){
  global $connection;

  $query = "SELECT * FROM $table WHERE $columnName = '$status' ";
  $result = mysqli_query($connection, $query);
  queryCheck($result);
  return mysqli_num_rows($result);
}


function is_admin($username = ''){

  global $connection;

  $query = "SELECT user_role FROM users WHERE user_name = '$username' ";
  $result = mysqli_query($connection, $query);
  queryCheck($result);

  $row = mysqli_fetch_array($result);

  if($row['user_role'] == 'admin'){
    return true;
  } else {
    return false;
  }
}

function currentUser(){
  if(isset($_SESSION['username'])){
    return $_SESSION['username'];
  }
  return false;
}

function username_exists($username){
  global $connection;

  $query = "SELECT user_name FROM users WHERE user_name = '$username'";
  $result = mysqli_query($connection, $query);
  queryCheck($result);

  if(mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return false;
  }
}

function email_exists($email){
  global $connection;

  $query = "SELECT user_email FROM users WHERE user_email = '$email'";
  $result = mysqli_query($connection, $query);
  queryCheck($result);

  if(mysqli_num_rows($result) > 0) {
    return true;
  } else {
    return false;
  }

}

function register_user($username, $email, $password){
  global $connection;

        $username = mysqli_real_escape_string($connection, $username);
        $email = mysqli_real_escape_string($connection, $email);
        $password = mysqli_real_escape_string($connection, $password);

        $password = password_hash($password, PASSWORD_BCRYPT, array('cost' => 10) );

        $query = "INSERT INTO users(user_name, user_email, user_password, user_role) ";
        $query .= "VALUES ('{$username}', '{$email}', '{$password}', 'subscriber' ) ";
        $create_user_query = mysqli_query($connection, $query);
        queryCheck($create_user_query);

}

function login_user($username, $password) {
  global $connection;

  $username = trim($username);
  $password = trim($password);

  $username = mysqli_real_escape_string($connection, $username);
  $password = mysqli_real_escape_string($connection, $password);

  $query = "SELECT * FROM users WHERE user_name = '{$username}' ";
  $select_user_query = mysqli_query($connection, $query);

  queryCheck($select_user_query);

  while($row = mysqli_fetch_array($select_user_query)){

      $db_id = $row['user_id'];
      $db_username = $row['user_name'];
      $db_password = $row['user_password'];
      $db_firstname = $row['user_firstname'];
      $db_lastname = $row['user_lastname'];
      $db_user_role = $row['user_role'];

      if(password_verify($password, $db_password)){
        $_SESSION['username'] = $db_username;
        $_SESSION['firstname'] = $db_firstname;
        $_SESSION['lastname'] = $db_lastname;
        $_SESSION['user_role'] = $db_user_role;

        redirect("admin/");

      } else {
        return false;

      }
    }
}


?>
