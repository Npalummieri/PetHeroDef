<?php include("header.php"); ?>
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">Pet Editing</h2>
<?php include("msgeDisplay.php"); ?>
<div class="container text-white mt-5" style="background-color: #110257;">
    <form action="<?php echo FRONT_ROOT."Pet/adminEditPet" ?>" method="POST">
        <input type="text" class="form-control" id="petCode" name="petCode" value="<?php echo $pet->getPetCode(); ?>" hidden>
		<input type="text" class="form-control" id="typePet" name="typePet" value="<?php echo $pet->getTypePet(); ?>" hidden>
        <div  id="baseUrl" data-baseurl="<?php echo FRONT_ROOT ?>" hidden> </div>
		<div class="form-group m-2">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $pet->getName(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="breed">Breed:</label>
            <select class="form-control" id="breed" name="breed"> </select>
        </div>
        <div class="form-group m-2">
        <label for="size">Size</label>
        <select class="form-control" id="size" name="size">
          <option value="big" <?php if ($pet->getSize() === 'big') echo 'selected'; ?>>Big</option>
          <option value="medium" <?php if ($pet->getSize() === 'medium') echo 'selected'; ?>>Medium</option>
          <option value="small" <?php if ($pet->getSize() === 'small') echo 'selected'; ?>>Small</option>
        </select>
		</div>
        <div class="form-group m-2">
            <label for="age">Age:</label>
            <input type="number" class="form-control" id="age" name="age" placeholder="<?php echo $pet->getAge(); ?>">
        </div>
        <button type="submit" class="btn btn-primary m-2">Save changes</button>
    </form>
</div>
<script src="<?php echo JS_PATH."formScripts.js"; ?>"></script>
<script>
$(document).ready(function() {
        var typePet = $("#typePet").val();
    console.log(typePet);
    breedManage.loadBreed(typePet);
});

</script>
<?php include("footer.php"); ?>

