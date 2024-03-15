<?php
include("header.php");
include("nav.php");
?>


<section class="section edit-profile-section" id="edit-profile">
    <div class="container">
        <h2 class="mb-4">Edit Profile</h2>
        <?php var_dump($pet); ?>
        <form action="<?php echo FRONT_ROOT."Pet/updatePet" ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="petCode" value="<?php echo $pet->getPetCode() ?>">
            <div class="form-group row">
                <label for="profile-picture" class="col-sm-2 col-form-label">Profile Picture:</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="profile-picture" name="pfp" accept="image/*">
                </div>
            </div>
            <div class="form-group row">
                <label for="vaccPlan" class="col-sm-2 col-form-label">VaccPlan:</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="vaccPlan" name="vaccPlan">
                </div>
            </div>
            <div class="form-group row">
                <label for="video" class="col-sm-2 col-form-label">Video:</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="video" name="video">
                </div>
            </div>
            <div class="form-group row">
                <label for="size" class="col-sm-2 col-form-label">Size:</label>
                <div class="col-sm-10">
                    <select class="form-control" id="size" name="size">
                        <option value="big">Big</option>
                        <option value="medium">Medium</option>
                        <option value="small">Small</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="age" class="col-sm-2 col-form-label">Age:</label>
                <div class="col-sm-10">
                    <input type="number" min="1" class="form-control" id="age" name="age" placeholder="<?php echo $pet->getAge(); ?>">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-10 offset-sm-2 ">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</section>


<?php include("footer.php"); ?>
