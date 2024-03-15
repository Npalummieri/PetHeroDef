<?php
include("header.php");
include("nav.php");
?>

<section class="section about-section gray-bg" id="about">
    <div class="container">
        <div class="row align-items-center flex-row-reverse">
            <div class="col-lg-6">
                <div class="about-text go-to">
                    <h3 class="dark-color">About Me</h3>
                    <h6 class="theme-color lead">lorem impsum h</h6>
                    <p><?php echo $infoOwner->getBio(); ?></p>
                    <div class="row about-list">
                        <div class="col-md-6">
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
                                <p><?php echo $infoOwner->getName()." ". $infoOwner->getLastName();  ?></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="media">
                                <label>E-mail</label>
                                <p><?php echo $infoOwner->getEmail() ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-avatar">
                    <img class="mt-3 mx-auto img-rounded rounded-circle" src="<?php echo FRONT_ROOT . "Images/" . $infoOwner->getPfp() ?>" title="" alt="" width="384px" height="384px">
                    <a href="<?php echo FRONT_ROOT.'Owner/editProfile' ?>" class="btn">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include("footer.php"); ?>
