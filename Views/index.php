<?php require_once(VIEWS_PATH . "header.php"); 
require_once(VIEWS_PATH."nav.php");
require_once(VIEWS_PATH."formKeepersList.php");
use Utils\Session as Session; ?>

<!-- <p class="alert alert-info" role="alert"><?php echo "Check session :";
                                            var_dump($_SESSION); ?></p> -->
<?php include("msgeDisplay.php")?>



<?php 
require_once(VIEWS_PATH."keeperListPag.php")
?>

<?php require_once(VIEWS_PATH."footer.php"); ?>