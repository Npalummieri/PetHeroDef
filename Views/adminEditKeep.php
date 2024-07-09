<?php require_once("header.php"); ?>
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">Edición de cuidadores</h2>
<?php 
include("msgeDisplay.php");
?>
<a href="<?php echo FRONT_ROOT."Keeper/showListKeepers" ?>" class="btn text-center text-white m-2 p-2 rounded bg-dark"><i class="fas fa-arrow-left "></i> Lista de cuidadores</a>
<div class="container text-white mt-5" style="background-color: #110257;">
    <form action="<?php echo FRONT_ROOT . "Keeper/adminEditKeeper" ?>" method="POST">
        <input type="text" class="form-control" id="keeperCode" name="keeperCode" value="<?php echo $keeper->getKeeperCode(); ?>" hidden>
        <div class="form-group m-2">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo $keeper->getEmail(); ?>" value="<?php echo $keeper->getEmail(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="username">Nombre de usuario:</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo $keeper->getUsername(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="status">Estado :</label>
            <select class="form-control" id="status" name="status">
                <option value="active" <?php if ($keeper->getStatus() === 'active') echo 'selected'; ?>>Activo</option>
                <option value="inactive" <?php if ($keeper->getStatus() === 'inactive') echo 'selected'; ?>>Inactivo</option>
                <option value="suspended" <?php if ($keeper->getStatus() === 'suspended') echo 'selected'; ?>>Suspendido</option>
            </select>
        </div>
        <div class="form-group m-2">
            <label for="name">Nombre :</label>
            <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $keeper->getName(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="lastname">Apellido :</label>
            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="<?php echo $keeper->getLastname(); ?>">
        </div>
        <div class="form-group m-2">
            <label for="status">Tamaño :</label>
            <select class="form-control" id="typeCare" name="typeCare">
                <option value="big" <?php if ($keeper->getTypeCare() === 'big') echo 'selected'; ?>>Grande</option>
                <option value="medium" <?php if ($keeper->getTypeCare() === 'medium') echo 'selected'; ?>>Mediano</option>
                <option value="small" <?php if ($keeper->getTypeCare() === 'small') echo 'selected'; ?>>Pequeño</option>
            </select>
        </div>
        <div class="form-group m-2">
            <label for="typePet ">Tipo de mascota :</label>
            <select class="form-control" id="typePet" name="typePet">
                <option value="dog" <?php if ($keeper->getTypePet() === 'dog') echo 'selected'; ?>>Perro</option>
                <option value="cat" <?php if ($keeper->getTypePet() === 'cat') echo 'selected'; ?>>Gato</option>
            </select>
        </div>
        <div class="form-group m-2">
            <label for="score">Puntaje:</label>
            <input type="text" class="form-control" id="score" name="score" min="1" max="5" placeholder="<?php echo $keeper->getScore(); ?>">
        </div>
		<div class="form-group m-2">
            <label for="score">Precio :</label>
            <input type="text" class="form-control" id="price" name="price" placeholder="<?php echo $keeper->getPrice(); ?>">
        </div>
        <button type="submit" class="btn btn-primary m-2">Guardar cambios</button>
    </form>
</div>
<?php require_once("footer.php"); ?>
