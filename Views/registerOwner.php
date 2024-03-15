<!-- 1. -->
<?php 
 include("header.php");
 include('nav.php');
?>
<div class="wrapper row4">
<main class="container clear" style="width: max-content;"> 
  <div class="content"> 
      <h2>REGISTER OWNER</h2>
      <p class="alert alert-danger"><?php echo  $msgResult; ?></p>
        <form action="<?php echo FRONT_ROOT . "Owner/registerOwner" //2 ?>" method="POST" enctype="multipart/form-data" class="login-form bg-dark-alpha p-5 text-white">
          
        <?php require_once("register.php"); ?>
        
          
        <div class="d-flex justify-content-end">
          <button class="btn btn-dark btn-block btn-lg mt-3" type="submit">Register</button>
        </div>
        </form>
        
  </div>
</main>
</div>