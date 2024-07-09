<?php
require_once("header.php");
include_once("nav.php");
?>


<h2 class="bg-dark rounded text-white text-center mt-2 ">EDITAR MASCOTA</h2>
<section class="section edit-profile-section text-white mt-2"  id="edit-profile" >
    <div class="container p-2" style="background-color: #364a6e;">
        <form action="<?php echo FRONT_ROOT."Pet/updatePet" ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="petCode" value="<?php echo $pet->getPetCode() ?>">
            <div class="form-group row m-2">
                <label for="profile-picture" class="col-sm-2 col-form-label">Foto de perfil :</label>
                <div class="col-sm-10">
                    <input type="file" class="form-control-file" id="profile-picture" name="pfp" accept="image/*">
                </div>
            </div>
            <div class="form-group row m-2">
                <label for="vaccPlan" class="col-sm-2 col-form-label">Plan de vac. :</label>
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
                <label for="size" class="col-sm-2 col-form-label">Tamaño:</label>
                <div class="col-sm-10">
                    <select class="form-control" id="size" name="size">
                        <option value="big">Grande</option>
                        <option value="medium">Mediano</option>
                        <option value="small">Pequeño</option>
                    </select>
                </div>
            </div>
            <div class="form-group row m-2">
                <label for="age" class="col-sm-2 col-form-label">Edad :</label>
                <div class="col-sm-10">
                    <input type="number" min="1" class="form-control" id="age" name="age" placeholder="<?php echo $pet->getAge(); ?>">
                </div>
            </div>
            <div class="form-group row p-2 text-end">
                <div class="col-sm-10 offset-sm-2 ">
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </div>
        </form>
    </div>
</section>


<?php require_once("footer.php"); ?>
