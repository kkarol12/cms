<?php
  if(isset($_POST['checkBoxArray'])) {
    foreach ($_POST['checkBoxArray'] as $commentValueId) {

    $bulk_options = $_POST['bulk_options'];

    switch ($bulk_options) {
      case 'approve':
        $query = "UPDATE comments SET comment_status = '{$bulk_options}' ";
        $query .= "WHERE comment_id = {$commentValueId} ";

        $update_approve_query = mysqli_query($connection, $query);
        queryCheck($update_approve_query);
        break;
      case 'unapprove':
        $query = "UPDATE comments SET comment_status = '{$bulk_options}' ";
        $query .= "WHERE comment_id = {$commentValueId} ";

        $update_unapprove_query = mysqli_query($connection, $query);
        queryCheck($update_unapprove_query);
        break;

      case 'delete':
        $query = "DELETE FROM comments WHERE comment_id = {$commentValueId} ";

        $delete_query = mysqli_query($connection, $query);
        break;
      default:
        # code...
        break;
    }
    }
  }

 ?>

<form class="" method="post">
<table class="table table-bordered table-hover">

  <div id="bulkOptionsContainer" class="col-xs-4">
    <select class="form-control" name="bulk_options" id="">
      <option value="">Select Options</option>
      <option value="approve">Approve</option>
      <option value="unapprove">Unapprove</option>
      <option value="delete">Delete</option>
    </select>
  </div>
  <div class="cols-xs-4">
    <input type="submit" name="submit" value="Apply" class="btn btn-success">

  </div>

  <thead>
    <tr>
      <th><input id="selectAllBoxes" type="checkbox"></th>
      <th>Id</th>
      <th>Author</th>
      <th>Comment</th>
      <th>Email</th>
      <th>Status</th>
      <th>In Response to</th>
      <th>Date</th>
      <th>Approve</th>
      <th>Unapprove</th>
      <th>Delete</th>
    </tr>
  </thead>

<tbody>
  <tr>

<?php
$query = "SELECT * FROM comments";
$select_comments = mysqli_query($connection, $query);

while($row = mysqli_fetch_assoc($select_comments)){
$comment_id = $row['comment_id'];
$comment_post_id = $row['comment_post_id'];
$comment_author = $row['comment_author'];
$comment_content = $row['comment_content'];
$comment_email = $row['comment_email'];
$comment_status = $row['comment_status'];
$comment_date = $row['comment_date'];

echo "<tr>";
?>
<td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $comment_id; ?>'></td>

<?php
echo "<td>$comment_id</td>";
echo "<td>$comment_author</td>";
echo "<td>$comment_content</td>";
echo "<td>$comment_email</td>";
echo "<td>$comment_status</td>";

$query = "SELECT * FROM posts WHERE post_id = $comment_post_id ";
$select_post_id_query = mysqli_query($connection, $query);
while($row = mysqli_fetch_assoc($select_post_id_query)){
  $post_id = $row['post_id'];
  $post_title = $row['post_title'];

    echo "<td><a href='../post.php?p_id=$post_id'>$post_title</a></td>";
}


echo "<td>$comment_date</td>";
echo "<td><a href='comments.php?approve=$comment_id'>Approve</a></td>";
echo "<td><a href='comments.php?unapprove=$comment_id'>Unapprove</a></td>";
echo "<td><a onClick=\"javascript: return confirm('Are you sure you want to delete'); \" href='comments.php?delete=$comment_id'>Delete</a></td>";
echo "</tr>";
}
?>

</tbody>
</table>
</form>
<?php

if(isset($_GET['approve'])){
  $the_comment_id = $_GET['approve'];
  $query = "UPDATE comments SET comment_status = 'approve' WHERE comment_id = $the_comment_id ";
  $unapprove_query = mysqli_query($connection, $query);
  header("Location: comments.php");
}

if(isset($_GET['unapprove'])){
  $the_comment_id = $_GET['unapprove'];
  $query = "UPDATE comments SET comment_status = 'unapprove' WHERE comment_id = $the_comment_id ";
  $unapprove_query = mysqli_query($connection, $query);
  header("Location: comments.php");
}

if(isset($_GET['delete'])){
  $the_comment_id = $_GET['delete'];
  $query = "DELETE FROM comments WHERE comment_id = {$the_comment_id} ";
  $delete_query = mysqli_query($connection, $query);
  header("Location: comments.php");
}
 ?>
