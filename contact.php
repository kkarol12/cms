<?php  include "includes/db.php"; ?>
<?php  include "includes/header.php"; ?>

<?php

  if(isset($_POST['submit'])){

    $subject = wordwrap($_POST['subject'], 70);
    $to = "karol_konkol.88@wp.pl";
    $email = $_POST['email'];
    $body = $_POST['body'];
    $headers = "From: " .$_POST['email'];

    if(!empty($subject) && !empty($email) && !empty($body)){

    mail($to,$subject,$body,$headers);
    $message = "<p class='bg-success'/>Your message has been sent.</p>";
  } else {
    $message = "<p class='bg-danger'>Fields cannot be empty.</p>";
  }
} else {
  $message = "";
}
 ?>
    <!-- Navigation -->
    <?php  include "includes/navigation.php"; ?>

    <!-- Page Content -->
    <div class="container">

<section id="login">
    <div class="container">
        <div class="row">
            <div class="col-xs-6 col-xs-offset-3">
                <div class="form-wrap">
                <h1>Contact</h1>
                    <form role="form" action="contact.php" method="post" id="login-form" autocomplete="off">

                        <h6 class="text-center"><?php echo $message; ?></h6>

                         <div class="form-group">
                            <label for="subject" class="sr-only">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" placeholder="Enter your Subject">
                        </div>
                        <div class="form-group">
                           <label for="email" class="sr-only">Email</label>
                           <input type="email" name="email" id="email" class="form-control" placeholder="Enter your Email">
                       </div>
                         <div class="form-group">

                            <textarea class="form-control" name="body" rows="10" cols="50"></textarea>
                        </div>

                        <input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Send messege">
                    </form>

                </div>
            </div> <!-- /.col-xs-12 -->
        </div> <!-- /.row -->
    </div> <!-- /.container -->
</section>

        <hr>

<?php include "includes/footer.php";?>
