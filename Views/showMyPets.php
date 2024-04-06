<?php
include_once("header.php");
include("nav.php");

?>


<div class="row text-white ">
  <h2 class="bg-dark text-center p-2">My pets</h2>
  <div class="container">
  <?php include("msgeDisplay.php") ?>
  </div>
  <div class="scrollable">
    <form action="" method="">
    <?php if($myPets == null || empty($myPets))
                {?>
                   <p class=' text-center' style='background-color: #110257;'>No pets registered! Go to <a class='text-white' href="<?php echo FRONT_ROOT.'Owner/showAddPet' ?>">AddPet</a></p>;
              <?php  } ?>
      <?php
      foreach ($myPets as $pet) {
      ?>
        <div class=" row border border-dark">

          <div class="col-lg-3 col-md-3 col-sm-12 p-3" style="background-color: #110257;">
            <img src=" <?php echo FRONT_ROOT . "Images/" . $pet->getPfp(); ?>" alt="Pfpet" class="img-thumbnail m-3" height="128px" width="256px">
            <a href="<?php echo FRONT_ROOT . "Pet/showEditPet/" . $pet->getpetCode(); ?>" class="text-white"><i class="fa-solid fa-pencil text-white"></i>Upload</a>
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
              <label class="text-truncate">Vaccplan: <?php if($pet->getVaccPlan() != null) { ?><a href="<?php echo FRONT_ROOT . "Images/" . $pet->getVaccPlan() ?>" target="_blank" class="text-white">Vaccplan</a><?php }else echo "Not uploaded"; ?></label>
              <a href="<?php echo FRONT_ROOT . "Pet/updateVaccplan" ?>"></a>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 p-2 mx-2 ">
              <label class="text-truncate">Video : <?php if($pet->getVideo() != null) { ?><a href="<?php echo FRONT_ROOT . $pet->getVideo() ?>" target="_blank" class="text-white">Video </a><?php }else echo "Not uploaded"; ?></label>
              <i class="bi bi-pencil"><a href="<?php echo FRONT_ROOT . "Pet/updateVideo" ?>"></i></a>
            </div>

            <div class="col-lg-6 col-md-6 col-sm-12 p-2 mx-2 ">
              <label class="text-truncate">Age : <span><?php echo $pet->getAge(); ?><span></label>
              <a href="<?php echo FRONT_ROOT . "Pet/updateAge" ?>" class="text-white"> <i class="fa-solid fa-pencil"></i></a>
            </div>



            <div class="col-lg-12 col-md-12 col-sm-12 mt-auto">
              <div class="text-end">
                <a class="btn bg-light m-1" href="<?php echo FRONT_ROOT . "Pet/showEditPet/" . $pet->getpetCode(); ?>">Update</a>
                <a class="btn btn-danger m-1" href="<?php echo FRONT_ROOT . "Pet/deletePet/" . $pet->getpetCode(); ?>">Delete</a>
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