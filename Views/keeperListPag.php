<?php require_once("header.php");
require_once("nav.php");
require_once(VIEWS_PATH . "formKeepersList.php"); 
?>

<div class="container mt-5">
  <h2 class="text-center">Keepers</h2>
  <div class="row justify-content-center">
    <?php foreach ($allKeepers as $keeper) { ?>
      <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card border-dark">
          <div class="card-body text-center">
            <?php if ($keeper->getTypePet() == "dog") { ?>
              <i class="bi bi-dog"></i>
            <?php } else if ($keeper->getTypePet() == "cat") { ?>
              <i class="bi bi-cat"></i>
            <?php } ?>

            <div class="mt-3">
              <img src="<?php echo ($keeper->getPfp() == 0) ? FRONT_ROOT . 'Images/SysImages/default-avatar-icon-of-social-media-user-vector.jpg' : FRONT_ROOT . 'Images/' . $keeper->getPfp(); ?>" onerror="this.onerror=null;this.src='<?php echo FRONT_ROOT . 'Images/SysImages/default-avatar-icon-of-social-media-user-vector.jpg' ?>'" class="rounded-circle" width="120" height="120" alt="pfpKeeper">
            </div>
            <p><strong>Name:</strong> <?php echo $keeper->getName(); ?></p>
            <p><strong>Lastname:</strong> <?php echo $keeper->getLastname(); ?></p>
            <p><strong>Type care:</strong> <?php echo $keeper->getTypeCare(); ?></p>
            <p><strong>Type pet:</strong> <?php echo $keeper->getTypePet(); ?></p>
            <p><strong>Price:</strong> <?php echo $keeper->getPrice(); ?></p>
            <a class="btn btn-light" href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $keeper->getKeeperCode() ?>"><?php echo $keeper->getEmail(); ?></a>

            <div class="mt-3">
              <a class="btn btn-danger" href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $keeper->getKeeperCode() ?>">Make booking</a>
              <a class="btn btn-success btn-availability" data-codekeeper="<?php echo $keeper->getKeeperCode() ?>">See availability</a>
            </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<div class="container mt-3">
  <div class="d-flex justify-content-center">
    <?php for ($page = 1; $page <= $totalPages; $page++) { ?>
      <a href="<?php echo FRONT_ROOT . 'Home/showKeeperListPag/' . $page ?>" class="btn btn-primary"><?php echo $page ?></a>
    <?php } ?>
  </div>
</div>

<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>


<?php require_once("footer.php"); ?>
