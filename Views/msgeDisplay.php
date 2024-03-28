<?php 
use Utils\Session as Session;
?>

<?php if(isset($_SESSION["bmsg"]) && $_SESSION["bmsg"] != " "){ ?>
      <p class="alert alert-danger" ><?php  echo $_SESSION["bmsg"]; unset($_SESSION["bmsg"]); ?></p>
      <?php } ?>
      <?php if(isset($_SESSION["gmsg"]) && $_SESSION["gmsg"] != " "){ ?>
      <p class="alert alert-success" ><?php  echo $_SESSION["gmsg"]; unset($_SESSION["gmsg"]); ?></p>
      <?php } ?>