<?php
include_once("header.php");
include("nav.php");

?>
<!-- ################################################################################################ -->
<?php var_dump($_SESSION["loggedUser"]); ?>
<!-- ################################################################################################ -->
<?php include("msgeDisplay.php") ?>

<div class="row ">
  <h2 class="text-center">My pets</h2>
  <div class="scrollable">
    <form action="" method="">

      <?php
      foreach ($myPets as $pet) {
      ?>
        <div class=" row border border-dark">

          <div class="col-lg-3 col-md-3 col-sm-12 p-3" style="background-color: #3258a6;">
            <img src=" <?php echo FRONT_ROOT . "Images/" . $pet->getPfp(); ?>" alt="Pfpet" class="img-thumbnail">
            <i class="bi bi-pencil"><a href="<?php echo FRONT_ROOT . "Pet/updateImage" ?>"></i></a>
          </div>

          <div class=" col-lg-9 col-md-9 col-sm-12 text-white" style="background: linear-gradient(to right, #3258a6, #3498db);">

            <div class=" col-lg-6 col-md-6 col-sm-12 mt-3 p-2 mx-2">
              <label class="text-truncate">Name : <span><?php echo $pet->getName(); ?><span></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 p-2 mx-2 ">
              <label class="text-truncate">Size : <span><?php echo $pet->getSize(); ?><span></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 p-2 mx-2 ">
              <label class="text-truncate">Breed : <span><?php echo $pet->getBreed(); ?><span></label>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 p-2 mx-2 ">
              <label class="text-truncate">Type pet : <span><?php echo $pet->getTypePet(); ?><span></label>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 p-2 mx-2 ">
              <label class="text-truncate">Vaccplan: <a href="<?php echo FRONT_ROOT . "Images/" . $pet->getVaccPlan() ?>" target="_blank" class="text-white">Vaccplan <i class="fa-solid fa-pencil"></i></a></label>
              <a href="<?php echo FRONT_ROOT . "Pet/updateVaccplan" ?>"></a>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 p-2 mx-2 ">
              <label class="text-truncate">Video : <a href="<?php echo FRONT_ROOT . $pet->getVideo() ?>" target="_blank" class="text-white">Video <i class="fa-solid fa-pencil"></i></a></label>
              <i class="bi bi-pencil"><a href="<?php echo FRONT_ROOT . "Pet/updateVideo" ?>"></i></a>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 p-2 mx-2 ">
              <label class="text-truncate">Age : <span><?php echo $pet->getAge(); ?><span></label>
              <a href="<?php echo FRONT_ROOT . "Pet/updateAge" ?>" class="text-white"> <i class="fa-solid fa-pencil"></i></a>
            </div>



            <div class="col-lg-12 col-md-12 col-sm-12 mt-auto">
              <div class="text-end">
                <a class="btn bg-light" href="<?php echo FRONT_ROOT . "Pet/showEditPet/" . $pet->getpetCode(); ?>">Update</a>
              </div>
              
            </div>
          </div>
        </div>
      <?php } ?>
    </form>
  </div>
</div>


<?php
include_once("footer.php");
?>