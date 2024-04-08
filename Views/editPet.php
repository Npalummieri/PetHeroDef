<?php
include("header.php");
include("nav.php");
?>


<h2 class="bg-dark rounded text-white text-center mt-2 ">EDIT PET INFO</h2>
<section class="section edit-profile-section text-white mt-2"  id="edit-profile" >
    <div class="container p-2" style="background-color: #364a6e;">
        <form action="<?php echo FRONT_ROOT."Pet/updatePet" ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="petCode" value="<?php echo $pet->getPetCode() ?>">
            <div class="form-group row m-2">
                <label for="profile-picture" class="col-sm-2 col-form-label">Profile Picture:</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="profile-picture" name="pfp" accept="image/*">
                </div>
            </div>
            <div class="form-group row m-2">
                <label for="vaccPlan" class="col-sm-2 col-form-label">VaccPlan:</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="vaccPlan" name="vaccPlan">
                </div>
            </div>
            <div class="form-group row m-2">
                <label for="video" class="col-sm-2 col-form-label">Video:</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="video" name="video">
                </div>
            </div>
            <div class="form-group row m-2">
                <label for="size" class="col-sm-2 col-form-label">Size:</label>
                <div class="col-sm-10">
                    <select class="form-control" id="size" name="size">
                        <option value="big">Big</option>
                        <option value="medium">Medium</option>
                        <option value="small">Small</option>
                    </select>
                </div>
            </div>
            <div class="form-group row m-2">
                <label for="age" class="col-sm-2 col-form-label">Age:</label>
                <div class="col-sm-10">
                    <input type="number" min="1" class="form-control" id="age" name="age" placeholder="<?php echo $pet->getAge(); ?>">
                </div>
            </div>
            <div class="form-group row p-2 text-end">
                <div class="col-sm-10 offset-sm-2 ">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>
</section>


<?php include("footer.php"); ?>
