<?php
include("header.php");
include("nav.php");
?>

<div class="container">
    <h2 class="bg-dark text-lg text-white text-center rounded p-2 mt-2">Edit Profile</h2>
    <section class="section edit-profile-section text-white p-3" id="edit-profile" style="background-color: #110257;">
        <form action="<?php echo FRONT_ROOT . "Keeper/updateKeeper" ?>" method="post" enctype="multipart/form-data">
            <div class="form-group m-2">
                <label for="profile-picture">Profile Picture:</label>
                <input type="file" class="form-control" id="profile-picture" name="pfp">
            </div>
            <div class="form-group m-2">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $keeperLogged->getEmail(); ?>">
            </div>
            <div class="form-group m-2">
                <label for="bio">Bio:</label>
                <textarea class="form-control" id="bio" name="bio"><?php echo $keeperLogged->getBio(); ?></textarea>
            </div>
            <div class="form-group m-2">
                <label for="price">Price : </label>
                <input type="number" min="1" class="form-control" id="price" name="price" placeholder="<?php echo  $keeperLogged->getPrice(); ?>"></input>
            </div>
            <div class="form-group m-2">
                <label for="visitPerDay">Visit per day : </label>
                <select type="number" class="form-control" id="visitPerDay" name="visitPerDay" placeholder="<?php echo  $keeperLogged->getVisitPerDay(); ?>">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>
            </div>
            <div class="text-end">
            <button type="submit" class="btn btn-primary m-2">Save Changes</button>
            </div>
        </form>
    </section>
</div>


<?php include("footer.php"); ?>