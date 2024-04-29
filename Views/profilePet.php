<?php
include("header.php");
include("nav.php");
?>
<?php require_once("msgeDisplay.php"); ?>
<div class="row align-items-center">
        <div class="col-lg-12">
            <h2 class="bg-dark rounded text-center mt-2 w-50 mx-auto text-white">PET PROFILE</h2>
        </div>
    </div>
<section class="section about-section text-white" id="about">
    <div class="container  rounded" style="background-color: #110257;">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="about-avatar text-center">
                    <img class="img-fluid img-rounded rounded-circle" src="<?php echo FRONT_ROOT . "Images/" . $pet->getPfp() ?>" title="" alt="" width="312px" height="312px">
                </div>
            </div>
            <div class="col-lg-8">
                <div class="about-list">
                    <div class="media">
                        
                        <p><label>Name :</label> <?php echo $pet->getName(); ?></p>
                    </div>
                    <div class="media">
                        
                        <p><label>Size :</label><?php echo $pet->getSize(); ?></p>
                    </div>
                    <div class="media">
                        
                        <p><label>Breed :</label><?php echo $pet->getBreed(); ?></p>
                    </div>
                    <div class="media">
                        
                        <p><label>Type pet :</label><?php echo $pet->getTypePet(); ?></p>
                    </div>
                    <div class="media">
                        
                        <p><label>Age :</label><?php echo $pet->getAge(); ?></p>
                    </div>

                    <div class="media ">
              <p><label>Vaccplan: <?php if($pet->getVaccPlan() != null) { ?><a   class="text-truncate" href="<?php echo FRONT_ROOT . "Images/" . $pet->getVaccPlan() ?>" target="_blank" class="text-white">Vaccplan</a><?php }else echo "Not uploaded"; ?></label></p>
            </div>

            <div class="media ">
            <p><label>Video : <?php if($pet->getVideo() != null) { ?><a  class="text-truncate" href="<?php echo FRONT_ROOT . $pet->getVideo() ?>" target="_blank" class="text-white">Video </a><?php }else echo "Not uploaded"; ?></label></p>
            </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo JS_PATH . "formScripts.js" ?>">

</script>
<?php include("footer.php"); ?>