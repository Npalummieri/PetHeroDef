<?php
include("header.php");
include("nav.php");
?>


<section class="section edit-profile-section text-white  mt-5 m-3"  id="edit-profile">
    <div class="container rounded" style="background-color: #110257;">
        <h2 class="text-center">Edit Profile</h2>
        <form action="<?php echo FRONT_ROOT."Owner/updateOwner" ?>" method="post" enctype="multipart/form-data">
        <div class="form-group m-3">
                <label for="profile-picture">Profile Picture:</label>
                <input type="file" class="form-control" id="profile-picture" name="pfp">
            </div>
            <div class="form-group m-3">
                <label for="bio">Bio:</label>
                <textarea class="form-control" id="bio" name="bio"><?php echo $infoOwner->getBio(); ?></textarea>
            </div>
            <div class="form-group m-3 p-2 text-end">
            <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</section>


<?php include("footer.php"); ?>