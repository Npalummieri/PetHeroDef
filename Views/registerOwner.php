
<?php 
 require_once("header.php");
 include('nav.php');
?>
<div class="wrapper row4">
<main class="container clear d-flex justify-content-center" > 
  <div class="content"> 
      <h2 class="bg-dark rounded text-center text-white">REGISTER OWNER</h2>
      <?php include("msgeDisplay.php") ?>
      <form action="<?php echo FRONT_ROOT . "Owner/registerOwner" ?>" method="POST" enctype="multipart/form-data" class="p-5 text-white rounded" style="background-color: #110257;">
          
        <?php require_once("register.php"); ?>
        
          
        <div class="d-flex justify-content-end">
          <button class="btn btn-warning btn-block btn-lg mt-3 " type="submit">Register</button>
        </div>
        </form>
        
  </div>
</main>
</div>
<?php require_once("footer.php"); ?>