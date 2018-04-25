<?php include "includes/db.php"; ?>
<?php include "includes/header.php"; ?>

    <!-- Navigation -->
<?php include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <div class="col-md-8">

              <?php
              if(isset($_GET['category'])) {
                $post_category_id = escape($_GET['category']);

              $query = "SELECT * FROM categories where cat_id = $post_category_id ";
              $select_cat = mysqli_query($connection, $query);

              while($row = mysqli_fetch_assoc($select_cat)){
                $cat_title = $row['cat_title'];
                $cat_id = $row['cat_id'];
              }
              ?>

              <h1 class="page-header">
                <p class="text-center">
                  Category: <?php echo $cat_title; ?>
                </p>
              </h1>
              <?php
              if(isset($_SESSION['username']) && is_admin($_SESSION['username'])) {

                $stmt1 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_user, post_date, post_image, post_content FROM
                  posts WHERE post_category_id = ? ");

              } else {
                $stmt2 = mysqli_prepare($connection, "SELECT post_id, post_title, post_author, post_user, post_date, post_image, post_content FROM
                  posts WHERE post_category_id = ? AND post_status = ? ");

                $published = 'published';
              }

              if(isset($stmt1)){
                mysqli_stmt_bind_param($stmt1, "i", $post_category_id);
                mysqli_stmt_execute($stmt1);
                mysqli_stmt_bind_result($stmt1, $post_id, $post_title, $post_author, $post_user, $post_date, $post_image, $post_content);
                $stmt = $stmt1;

              } else {
                mysqli_stmt_bind_param($stmt2, "is", $post_category_id, $published);
                mysqli_stmt_execute($stmt2);
                mysqli_stmt_bind_result($stmt2, $post_id, $post_title, $post_author, $post_user, $post_date, $post_image, $post_content);
                $stmt = $stmt2;
              }

                  while(mysqli_stmt_fetch($stmt)):
                  ?>
                  <!-- First Blog Post -->
                  <h2>
                      <a href="post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title ?></a>
                  </h2>
                  <p class="lead">
                      by <a href="index.php"><?php echo !empty($post_user) ? $post_user : $post_author ?></a>
                  </p>
                  <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?></p>
                  <hr>
                  <a href="post.php?p_id=<?php echo $post_id; ?>"><img class="img-responsive" src="images/<?php echo $post_image;?>" alt=""></a>
                  <hr>
                  <p><?php echo $post_content ?></p>
                  <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">Read More <span class="glyphicon glyphicon-chevron-right"></span></a>

                  <hr>

                <?php endwhile;
                if(mysqli_stmt_num_rows($stmt) === 0){
                  echo "<h2><p class='text-center'>No posts available</p></h2>";
                }
                mysqli_stmt_close($stmt);
               } else {
                header("Location: index.php");
              }
                 ?>
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>

        </div>
        <!-- /.row -->
        <hr>

<?php include "includes/footer.php"; ?>
