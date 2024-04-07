<?php require_once("header.php");
require_once("nav.php");
require_once(VIEWS_PATH . "formKeepersList.php");
?>

<div class="container mt-5" id="contMain" data-baseurl="<?php echo FRONT_ROOT; ?>" style="background-color : #110257;">
  <h2 class="text-center text-white">Keepers</h2>
  <div class="row justify-content-center">
    <?php foreach ($allKeepers as $keeper) { ?>
      <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
        <div class="card border-dark" style="background-color: #d6c9ae;">
          <div class="card-body text-center">

            <div class="d-inline text-center">
              <?php if ($keeper->getTypePet() == "dog") { ?>
                <i class="fa-solid fa-dog"></i>
              <?php } else if ($keeper->getTypePet() == "cat") { ?>
                <i class="fa-solid fa-cat"></i>
              <?php } ?>
            </div>

            <div class="mt-3 mb-2">
            <a  href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $keeper->getKeeperCode() ?>"><img src="<?php echo ($keeper->getPfp() == 0) ? FRONT_ROOT . 'Images/SysImages/default-avatar-icon-of-social-media-user-vector.jpg' : FRONT_ROOT . 'Images/' . $keeper->getPfp(); ?>" onerror="this.onerror=null;this.src='<?php echo FRONT_ROOT . 'Images/SysImages/default-avatar-icon-of-social-media-user-vector.jpg' ?>'" class="rounded-circle" width="120" height="120" alt="pfpKeeper"></a>
            </div>
            <p>Score : <?php if ($keeper->getScore() == 0) {
                          echo "Not reviewed";
                        }
                        for ($i = 0; $i < $keeper->getScore(); $i++) {
                          echo '<i class="fa-solid fa-star"></i>';
                        } ?></p>
            <p><strong>Name:</strong> <?php echo $keeper->getName(); ?></p>
            <p><strong>Lastname:</strong> <?php echo $keeper->getLastname(); ?></p>
            <p><strong>Type care:</strong> <?php echo $keeper->getTypeCare(); ?></p>
            <p><strong>Type pet:</strong> <?php echo $keeper->getTypePet(); ?></p>
            <p><strong>Price:</strong> <?php echo $keeper->getPrice(); ?></p>
            <p><strong>Email:</strong> <?php echo $keeper->getEmail(); ?></p>


            <div class="mt-3 d-flex justify-content-center align-items-center">
              <div>
                <a class="btn btn-danger " href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $keeper->getKeeperCode() ?>">Make booking</a>
                <a class="btn btn-success btn-availability" data-codekeeper="<?php echo $keeper->getKeeperCode() ?>">See availability</a>
              </div>
              <div class="additional-info bg-dark bg-gradient" style="visibility: hidden;"></div>
            </div>
            <div class="text-end">
                <a class="btn btn-light ml-5" href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $keeper->getKeeperCode() ?>"><i class="fa-solid fa-arrow-right"></i></a>
              </div>
          </div>
        </div>
      </div>
    <?php } ?>
  </div>
</div>

<div class="container mt-3">
  <div class="text-center">
    <?php for ($page = 1; $page <= $totalPages; $page++) { ?>
      <a href="<?php echo FRONT_ROOT . 'Home/showKeeperListPag/' . $page ?>" class="btn m-1 text-white" style="background-color: #110257;"><?php echo $page ?></a>
    <?php } ?>
  </div>
</div>

<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>
<script>
  KeepersInteract.getKeeperAvail();
</script>

<?php require_once("footer.php"); ?>