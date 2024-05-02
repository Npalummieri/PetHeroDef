<?php
require_once("header.php");
include_once("nav.php");

?>



  <h2 class="bg-dark rounded text-white text-center mt-2 p-2">MY PETS</h2>
  <div class="container">
  <?php include_once("msgeDisplay.php") ?>
  </div>
  <div class="scrollable">
    <form action="" method="">
    <?php if($myPets == null || empty($myPets))
                {?>
                   <p class=' text-center text-white' style='background-color: #110257;'>No pets registered! Go to <a class='text-white' href="<?php echo FRONT_ROOT.'Owner/showAddPet' ?>">AddPet</a></p>;
              <?php  } ?>
      <?php
      foreach ($myPets as $pet) {
      ?>
        <div class=" row border m-2" style="max-width: 100%;">

          <div class="d-flex col-lg-3 col-md-3 col-sm-12 text-center border justify-content-center " style="background:  #3258a6;">
            <img src=" <?php echo FRONT_ROOT . "Images/" . $pet->getPfp(); ?>" alt="Pfpet" class="img-thumbnail m-4" width="256px" height="256px">
		  </div>
			 
          <div class=" col-lg-9 col-md-9 col-sm-12 text-white border" style="background: linear-gradient(to right, #3258a6, #3498db);">

            <div  class="text-truncate m-2"> <!-- mt-3 p-2 mx-2 -->
              <label class="">Name : <span><?php echo $pet->getName(); ?><span></label>
            </div>
            <div class="text-truncate m-2"> <!-- col-lg-6 col-md-6 col-sm-12 p-2 mx-2 -->
              <label class="">Size : <span><?php echo $pet->getSize(); ?><span></label>
            </div>
            <div class="text-truncate m-2">
              <label class="">Breed : <span><?php echo $pet->getBreed(); ?><span></label>
            </div>

            <div class="text-truncate m-2">
              <label class="">Type pet : <span><?php echo $pet->getTypePet(); ?><span></label>
            </div>

            <div class="text-truncate m-2">
              <label class="">Vaccplan: <?php if($pet->getVaccPlan() != null) { ?><a href="<?php echo FRONT_ROOT . "Images/" . $pet->getVaccPlan() ?>" target="_blank" class="text-white">Vaccplan</a><?php }else echo "Not uploaded"; ?></label>
              <a href="<?php echo FRONT_ROOT . "Pet/updateVaccplan" ?>"></a>
            </div>

            <div class="text-truncate m-2">
              <label class="">Video : <?php if($pet->getVideo() != null) { ?><a href="<?php echo FRONT_ROOT . $pet->getVideo() ?>" target="_blank" class="text-white">Video </a><?php }else echo "Not uploaded"; ?></label>
              <i class="bi bi-pencil"><a href="<?php echo FRONT_ROOT . "Pet/updateVideo" ?>"></i></a>
            </div>

            <div class="text-truncate m-2">
              <label class="">Age : <span><?php echo $pet->getAge(); ?><span></label>
            </div>


              <div class="text-end">
                <a class="btn bg-light m-1" href="<?php echo FRONT_ROOT . "Pet/showEditPet/" . $pet->getpetCode(); ?>"><i class="fa-solid fa-pencil"> </i> Update</a>
                <a class="btn btn-dis btn-danger m-1" href="<?php echo FRONT_ROOT . "Pet/deletePet/" . $pet->getpetCode(); ?>" data-msg="Delete this pet?">Delete</a>
            </div>
          </div>
        </div>
      <?php } ?>
    </form>
  </div>

<script src="<?php echo JS_PATH."formScripts.js" ?>"></script>
<script>KeepersInteract.reConfirm();</script>

<?php
require_once("footer.php");
?>