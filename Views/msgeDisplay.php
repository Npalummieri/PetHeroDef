<?php 
use Utils\Session as Session;
?>

<?php if(isset($_SESSION["bmsg"]) && $_SESSION["bmsg"] != " " && $_SESSION["bmsg"] != ""){ ?>
      <div class="alert alert-danger" ><?php  echo $_SESSION["bmsg"]; unset($_SESSION["bmsg"]); ?></div>
      <?php } ?>
      <?php if(isset($_SESSION["gmsg"]) && $_SESSION["gmsg"] != " " && $_SESSION["gmsg"] != ""){ ?>
      <div class="alert alert-success" ><?php  echo $_SESSION["gmsg"]; unset($_SESSION["gmsg"]); ?></div>
      <?php } ?>