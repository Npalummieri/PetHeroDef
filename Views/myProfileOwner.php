<?php
include("header.php");
include("nav.php");
?>

<section class="section about-section text-white" id="about">
    <div class="container mt-3" style="background-color: #364a6e;">
        <h2 class="text-center">My Profile</h2>
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="about-avatar text-center">
                    <img class="mt-3 mx-auto img-rounded rounded-circle" src="<?php echo FRONT_ROOT . "Images/" . $infoOwner->getPfp() ?>" title="" alt="" width="384px" height="384px">
                    <a href="<?php echo FRONT_ROOT . 'Owner/editProfile' ?>" class="btn btn-primary mt-3 text-white">Edit Profile</a>
                </div>

            </div>
            <div class="col-lg-8">
                <div class="about-list">
                    <div class="media">
                        <label>DNI</label>
                        <p><?php echo $infoOwner->getDni(); ?></p>
                    </div>
                    <div class="media">
                        <label>Username</label>
                        <p><?php echo $infoOwner->getUsername(); ?></p>
                    </div>
                    <div class="media">
                        <label>Full name</label>
                        <p><?php echo $infoOwner->getName() . " " . $infoOwner->getLastName();  ?></p>
                    </div>
                    <div class="media">
                        <label>E-mail</label>
                        <p><?php echo $infoOwner->getEmail() ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bio">About Me</label>
                    <p id="bio" data-userlogged="<?php echo $infoOwner->getOwnerCode(); ?>"><?php echo $infoOwner->getBio(); ?></p>
                    <div class="form-group" style="display: none;" id="bioEditor">
                        <textarea class="form-control" name="bio" id="bioTextarea" maxlength="200" placeholder="Enter your bio (max 200 characters)"></textarea>
                    </div>
                    <button class="btn btn-primary" id="editBioBtn">Edit bio</button>
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