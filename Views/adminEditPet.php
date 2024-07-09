<?php require_once("header.php"); ?>
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">Editar mascota</h2>
<?php include("msgeDisplay.php"); ?>
<a href="<?php echo FRONT_ROOT."Pet/showListPets" ?>" class="btn text-center text-white m-2 p-2 rounded bg-dark"><i class="fas fa-arrow-left "></i> Lista de mascotas</a>
<div class="container text-white mt-5" style="background-color: #110257;">
    <form action="<?php echo FRONT_ROOT . "Pet/adminEditPet" ?>" method="POST">
        <input type="text" class="form-control" id="petCode" name="petCode" value="<?php echo $pet->getPetCode(); ?>" hidden>
        <input type="text" name="typePet" id="typePet" value="<?php echo $pet->getTypePet(); ?>" hidden>
        
        <div id="baseUrl" data-baseurl="<?php echo FRONT_ROOT ?>" hidden> </div>
        <div class="form-group m-2">
            <label for="name">Nombre :</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $pet->getName(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="breed">Raza: (Actual : <?php echo $pet->getBreed(); ?> )</label>
            <select class="form-control" id="breed" name="breed">
                
            </select>
        </div>
        <div class="form-group m-2">
            <label for="size">Tamaño:</label>
            <select class="form-control" id="size" name="size">
                <option value="big" <?php if ($pet->getSize() === 'big') echo 'selected'; ?>>Grande (+15 kg)</option>
                <option value="medium" <?php if ($pet->getSize() === 'medium') echo 'selected'; ?>>Mediano (Entre 8kg y 15 kg)</option>
                <option value="small" <?php if ($pet->getSize() === 'small') echo 'selected'; ?>>Pequeño (Hasta 8 kg)</option>
            </select>
        </div>
        <div class="form-group m-2">
            <label for="age">Edad:</label>
            <input type="number" class="form-control" id="age" name="age" placeholder="<?php echo $pet->getAge(); ?>">
        </div>
        <button type="submit" class="btn btn-primary m-2">Guardar cambios</button>
    </form>
</div>
<script src="<?php echo JS_PATH . "formScripts.js"; ?>"></script>
<script>
    $(document).ready(function() {
        var typePet = $("#typePet").val();
        console.log(typePet);
        breedManage.loadBreed(typePet);
    });
</script>
<?php require_once("footer.php"); ?>