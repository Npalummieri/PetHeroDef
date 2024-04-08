<?php
include("header.php");
include("nav.php");
use Utils\Session as Session;
?>

<?php require_once("msgeDisplay.php"); ?>
<div class="row align-items-center">
        <div class="col-lg-12">
            <h2 class="bg-dark rounded text-center mt-2 w-50 mx-auto text-white">My Profile</h2>
        </div>
    </div>
<section class="section about-section text-white" id="about">
    <div class="container  rounded" style="background-color: #110257;">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="about-avatar text-center">
                    <img class="mt-3 mx-auto img-rounded rounded-circle p-3" src="<?php echo FRONT_ROOT . "Images/" . $infoOwner->getPfp() ?>" title="" alt="" width="384px" height="384px">
                    <?php if(Session::GetTypeLogged() == "Models\Owner")
                    { 
                        if(Session::GetLoggedUser()->getOwnerCode() == $infoOwner->getOwnerCode())
                        { ?>
                    <a href="<?php echo FRONT_ROOT . 'Owner/editProfile' ?>" class="btn btn-primary m-2 text-white">Edit Profile</a>
                    <?php }
                    } ?>
                </div>

            </div>
            <div class="col-lg-8">
                <div class="about-list">
                <?php if(Session::GetTypeLogged() == "Models\Owner")
                    { 
                        if(Session::GetLoggedUser()->getOwnerCode() == $infoOwner->getOwnerCode())
                        { ?>
                    <div class="media">
                        <label>DNI :</label>
                        <p><?php echo $infoOwner->getDni(); ?></p>
                    </div>
                    <?php }
                    } ?>
                    <div class="media">
                        <label>Username :</label>
                        <p><?php echo $infoOwner->getUsername(); ?></p>
                    </div>
                    <div class="media">
                        <label>Full name :</label>
                        <p><?php echo $infoOwner->getName() . " " . $infoOwner->getLastName();  ?></p>
                    </div>
                    <div class="media">
                        <label>E-mail :</label>
                        <p><?php echo $infoOwner->getEmail() ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bio">About Me :</label>
                    <p id="bio" data-userlogged="<?php echo $infoOwner->getOwnerCode(); ?>"><?php echo $infoOwner->getBio(); ?></p>
                    <div class="form-group" style="display: none;" id="bioEditor">
                        <textarea class="form-control" name="bio" id="bioTextarea" maxlength="200" placeholder="Enter your bio (max 200 characters)"></textarea>
                    </div>
                    <?php if(Session::GetTypeLogged() == "Models\Owner")
                    { 
                        if(Session::GetLoggedUser()->getOwnerCode() == $infoOwner->getOwnerCode())
                        { ?>
                            <button class="btn btn-primary" id="editBioBtn">Edit bio</button>
                       <?php }
                    } ?>
                    
                    <button class="btn btn-success" id="saveBioBtn" style="display: none;">Save</button>
                    <button class="btn btn-secondary" id="cancelBioBtn" style="display: none;">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo JS_PATH . "formScripts.js" ?>">

</script>
<script>
    infoModule.bioEdit();
</script>
<?php include("footer.php"); ?>