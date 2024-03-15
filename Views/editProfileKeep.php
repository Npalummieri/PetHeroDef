<?php
include("header.php");
include("nav.php");
?>


<section class="section edit-profile-section" id="edit-profile">
    <div class="container">
        <h2>Edit Profile</h2>
        <form action="<?php echo FRONT_ROOT."Keeper/updateKeeper" ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="profile-picture">Profile Picture:</label>
                <input type="file" class="form-control" id="profile-picture" name="pfp">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $keeperLogged->getEmail(); ?>">
            </div>
            <div class="form-group">
                <label for="bio">Bio:</label>
                <textarea class="form-control" id="bio" name="bio"><?php echo $keeperLogged->getBio(); ?></textarea>
            </div>
            <div class="form-group">
                <label for="bio">Price : </label>
                <input type="number" min="1" class="form-control" id="price" name="price" placeholder="<?php echo  $keeperLogged->getPrice(); ?>"></input>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</section>


<?php include("footer.php"); ?>