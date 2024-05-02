<?php require_once("header.php");
include_once("nav.php");
include_once("formKeepersList.php");
?>
<div class="container">

  <h2>Keepers</h2>
  <form action="  " method="">

    <?php
    echo "<div class='d-flex row justify-content-center' style='border: 5px solid pink;'>";
    foreach ($allKeepers as $keeperCode => $internArray) {
      
      echo "<div class='col-3 card m-1 border border-dark'>" ?>

      <?php foreach ($internArray as $value) {
        if (is_a($value, "Models\Keeper")) { ?>

          <div class="additional-info" style="visibility : hidden;">
          
          </div>
          <div class="d-flex align-self-center w-50 h-50 justify-content-center" id="Pfp" style="background-color: greenyellow;">
            <img class="w-100 h-100" src="<?php if ($value->getPfp() == 0) {
                                            echo FRONT_ROOT . 'Images/SysImages/default-avatar-icon-of-social-media-user-vector.jpg';
                                          } else {
                                            echo FRONT_ROOT ."Images/".$value->getPfp();
                                          } ?>" width="120px" height="120px" alt="pfpKeeper">
          </div>

          <?php if ($value->getTypePet() == "dog") { ?>
            <svg class="w-25 h-25" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128" style="enable-background:new 0 0 128 128" xml:space="preserve">
              <path style="fill:#434544" d="M95.929 73.029c1.37-1.24 2.09-7.02.63-8.15-4.34-3.37-14.8-4.67-14.87-6.26-.07-1.51-.15-3.35-1.37-4.24-3.12-2.26-6.63-3.47-7.66-3.37-.02-1.12-1.61-8.32-2.57-12.57-.22-.96-1.54-1.06-1.91-.14l-2.49 6.22-3-12.25c-.24-.98-1.6-1.04-1.92-.08-1.86 5.7-5.73 16.76-7.98 18.13-3.06 1.86-20.66 15.18-22.1 18.18-1.44 3 27.13 28 27.13 28s6.16-12.88 11.52-15.75c5.35-2.87 12.72 1.75 20.19-.49 3.58-1.07 3.25-2.89 1.41-4.88-1.01-1.1 3.31-.81 4.99-2.35zm-36.82-24.28s1.21-12.71 2.49-13.95c1.08-.38 2.84 12.7 2.84 12.7l-5.33 1.25zm8.87-1.44s.62-6.45 1.27-7.08c.55-.19 1.44 6.45 1.44 6.45l-2.71.63z" />
            </svg>

          <?php } else if ($value->getTypePet() == "cat") {
          ?>
            <img class="w-25 h-25" src="<?php echo FRONT_ROOT . 'Images/SysImages/icons8-cat-64 (1).png' ?>" alt="catIcon">
          <?php         } ?>

          <p>Name :<?php echo $value->getName(); ?></p>
          <p>Lastname :<?php echo $value->getLastname(); ?></p>
          <p>Type care :<?php echo $value->getTypeCare(); ?></p>
          <p>Type pet :<?php echo $value->getTypePet(); ?></p>
          <p>Price :<?php echo $value->getPrice(); ?></p>
          <a style="border: 1px solid black; padding:2px;" href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $value->getKeeperCode() ?>">
            <?php echo $value->getEmail(); ?>
          </a>






        <?php } ?>


      <?php } ?>
      <div class="d-flex-row my-2" style="background-color: red;">
        <a class="d-flex btn btn-danger flex-grow-1 mx-2 text-truncate" style="color:white;" href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $value->getKeeperCode() ?>"><span style="color:white;">Make booking</span></a>
        <button class="d-flex btn btn-success btn-availability flex-grow-1 mx-2 text-truncate" style="color:white;" href="" data-codekeeper="<?php echo $value->getKeeperCode() ?>"><span style="color:white;">See availability</span></button>
      </div>



    <?php echo "</div>";
    }
    echo "</div>"; ?>

  </form>
    </div>
</div>




<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>
<script>
  KeepersInteract.getKeeperAvail();
</script>