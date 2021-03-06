<?php
include("delete_modal.php");
  if(isset($_POST['checkBoxArray'])) {
    foreach ($_POST['checkBoxArray'] as $postValueId) {

    $bulk_options = $_POST['bulk_options'];

    switch ($bulk_options) {
      case 'published':
        $query = "UPDATE posts SET post_status = '{$bulk_options}' ";
        $query .= "WHERE post_id = {$postValueId} ";

        $update_published_query = mysqli_query($connection, $query);
        queryCheck($update_published_query);
        break;
      case 'draft':
        $query = "UPDATE posts SET post_status = '{$bulk_options}' ";
        $query .= "WHERE post_id = {$postValueId} ";

        $update_draft_query = mysqli_query($connection, $query);
        queryCheck($update_draft_query);
        break;
      case 'clone':
        $query = "SELECT * FROM posts WHERE post_id = '{$postValueId}' ";
        $clone_query = mysqli_query($connection, $query);

        queryCheck($clone_query);

        while($row = mysqli_fetch_array($clone_query)){
          $post_author = $row['post_author'];
          $post_user = $row['post_user'];
          $post_title = $row['post_title'];
          $post_category_id = $row['post_category_id'];
          $post_status = $row['post_status'];
          $post_image = $row['post_image'];
          $post_content = $row['post_content'];
          $post_tags = $row['post_tags'];
          $post_date = $row['post_date'];

          if(empty($post_tags)){
            $post_tags = "No tags";
          }
        }

        $query = "INSERT INTO posts(post_category_id, post_title, post_author, post_user, post_date, post_image, post_content, post_tags, post_status) ";
        $query .= "VALUES('{$post_category_id}','{$post_title}','{$post_author}','{$post_user}',now(),'{$post_image}','{$post_content}','{$post_tags}','{$post_status}') ";

        $clone_post_query = mysqli_query($connection, $query);
        queryCheck($clone_post_query);
        break;
      case 'delete':
        $query = "DELETE FROM posts WHERE post_id = {$postValueId} ";

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
      <option value="published">Publish</option>
      <option value="draft">Draft</option>
      <option value="clone">Clone</option>
      <option value="delete">Delete</option>
    </select>
  </div>
  <div class="cols-xs-4">
    <input type="submit" name="submit" value="Apply" class="btn btn-success">
    <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>

  </div>

  <thead>
    <tr>
      <th><input id="selectAllBoxes" type="checkbox"></th>
      <th>Id</th>
      <th>Users</th>
      <th>Title</th>
      <th>Category</th>
      <th>Status</th>
      <th>Image</th>
      <th>Tags</th>
      <th>Comments</th>
      <th>Date</th>
      <th>View Post</th>
      <th>Edit</th>
      <th>Delete</th>
      <th>Views</th>
      <th>Reset Views</th>
    </tr>
  </thead>

<tbody>
  <tr>

<?php
$user = currentuser();


$query = "SELECT posts.post_id, posts.post_author, posts.post_user, posts.post_title, posts.post_category_id, posts.post_status, posts.post_image, ";
$query .= "posts.post_tags, posts.post_comment_count, posts.post_date, posts.post_views_count, categories.cat_id, categories.cat_title ";
$query .= "FROM posts ";
if(is_admin($user)){
$query .= "LEFT JOIN categories ON posts.post_category_id = categories.cat_id ORDER BY posts.post_id DESC";
} else {
  $query .= "LEFT JOIN categories ON posts.post_category_id = categories.cat_id WHERE posts.post_user = '$user' ORDER BY posts.post_id DESC";
}
$select_posts = mysqli_query($connection, $query);
queryCheck($select_posts);

while($row = mysqli_fetch_assoc($select_posts)){
$post_id = $row['post_id'];
$post_author = $row['post_author'];
$post_user = $row['post_user'];
$post_title = $row['post_title'];
$post_category_id = $row['post_category_id'];
$post_status = $row['post_status'];
$post_image = $row['post_image'];
$post_tags = $row['post_tags'];
$post_comment_count = $row['post_comment_count'];
$post_date = $row['post_date'];
$post_views_count = $row['post_views_count'];
$cat_title = $row['cat_title'];
$cat_id = $row['cat_id'];

echo "<tr>";
?>

<td><input class='checkBoxes' type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'></td>

<?php
echo "<td>{$post_id}</td>";

if(!empty($post_user)){
  echo "<td>$post_user</td>";
} else if (!empty($post_author)) {
  echo "<td>$post_author</td>";
}

echo "<td>{$post_title}</td>";
echo "<td>{$cat_title}</td>";
echo "<td>{$post_status}</td>";
echo "<td><img width='100' src='../images/$post_image' alt='image'></td>";
echo "<td>{$post_tags}</td>";

$count_comments = checkStatus('comments', 'comment_post_id', $post_id);

echo "<td><a class='btn btn-info' href='post_comments.php?id=$post_id'>{$count_comments}</a></td>";
echo "<td>{$post_date}</td>";
echo "<td><a class='btn btn-primary' href='../post.php?p_id={$post_id}'>View</a></td>";
echo "<td><a class='btn btn-info' href='posts.php?source=edit_post&p_id={$post_id}'>Edit</a></td>";
?>
  <form method="post">
    <input type="hidden" name="post_id" value="<?php echo $post_id; ?>">
    <?php
      echo '<td><input class="btn btn-danger" type="submit" value="Delete" name="delete"></td>';
    ?>
  </form>

<?php

echo "<td>{$post_views_count}</td>";
echo "<td><a class='btn btn-warning' onClick=\"javascript: return confirm('Are you sure you want to reset current count of views to 0'); \" href='posts.php?reset={$post_id}'>Reset</a></td>";
echo "</tr>";
}
?>

</tbody>
</table>
</form>

<?php
if(isset($_POST['delete'])){
  $the_post_id = $_POST['post_id'];
  $query = "DELETE FROM posts WHERE post_id = {$the_post_id} ";
  echo $query;
  $delete_query = mysqli_query($connection, $query);
  header("Location: posts.php");
}

if(isset($_GET['reset'])){
  $the_post_id = $_GET['reset'];
  $query = "UPDATE posts SET ";
  $query .="post_views_count = 0 ";
  $query .="WHERE post_id = '{$the_post_id}' ";

  $reset_views_query = mysqli_query($connection, $query);
  header("Location: posts.php");
}
 ?>

<script>
  $(document).ready(function(){
    $(".delete_link").on('click', function(){
      var id = $(this).attr("rel");
      var delete_url = "posts.php?delete="+ id +"";

      $(".modal_delete_link").attr("href", delete_url);

      $("#myModal").modal("show");

    });
  });
</script>
