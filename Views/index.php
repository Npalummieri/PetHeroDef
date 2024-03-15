<?php require_once(VIEWS_PATH . "header.php"); 
require_once(VIEWS_PATH."nav.php");
require_once(VIEWS_PATH."formKeepersList.php"); ?>

<p class="alert alert-info" role="alert"><?php echo "Check session :";
                                            var_dump($_SESSION); ?></p>
<p class="alert alert-success"><?php echo  $msgResult; ?></p>
hola?
<?php 
require_once(VIEWS_PATH."keeperListPag.php")
?>

<?php require_once(VIEWS_PATH."footer.php"); ?>