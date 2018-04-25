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
              if(isset($_GET['p_id'])){
                $the_post_id = $_GET['p_id'];
                $all_post_author = $_GET['author'];
              } ?>

              <h1 class="page-header">
                All posts written by <?php echo $all_post_author ?>
                </h1>
                          <?php
                $query = "SELECT * FROM posts WHERE post_user = '{$all_post_author}' ";
                $select_all_author_posts_query = mysqli_query($connection, $query);

                while($row = mysqli_fetch_assoc($select_all_author_posts_query)){
                  $post_id = $row['post_id'];
                  $post_title = $row['post_title'];
                  $post_author = $row['post_user'];
                  $post_date = $row['post_date'];
                  $post_image = $row['post_image'];
                  $post_content = $row['post_content'];
                ?>

                <h2>
                    <a href="post.php?p_id=<?php echo $post_id ?>"><?php echo $post_title ?></a>
                </h2>
                <p><span class="glyphicon glyphicon-time"></span> Posted on <?php echo $post_date ?></p>
                <hr>
                <img class="img-responsive" src="images/<?php echo $post_image;?>" alt="">
                <hr>
                <p><?php echo $post_content ?></p>
                <hr>

                <?php
                }
                 ?>
            </div>

            <!-- Blog Sidebar Widgets Column -->
            <?php include "includes/sidebar.php"; ?>
        </div>
        <!-- /.row -->

        <hr>
<?php include "includes/footer.php"; ?>
