<?php require_once("header.php");
?>
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">Edición de dueños</h2>
<?php 
include("msgeDisplay.php");
?>
<a href="<?php echo FRONT_ROOT."Owner/showListOwners" ?>" class="btn text-center text-white m-2 p-2 rounded bg-dark"><i class="fas fa-arrow-left "></i> Lista de dueños</a>
  <div class="container text-white mt-5" style="background-color: #110257;">
    <form action="<?php echo FRONT_ROOT."Owner/adminEditOwner" ?>" method="POST">
	<input type="text" class="form-control" id="ownerCode" name="ownerCode" value="<?php echo $owner->getOwnerCode(); ?>" hidden>
      <div class="form-group m-2">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="<?php echo $owner->getEmail(); ?>">
      </div>
      <div class="form-group  m-2">
        <label for="username">Usuario</label>
        <input type="text" class="form-control" id="username" name="username" placeholder="<?php echo $owner->getUsername(); ?>">
      </div>
      <div class="form-group m-2">
        <label for="status">Estado</label>
        <select class="form-control" id="status" name="status">
          <option value="active" <?php if ($owner->getStatus() === 'active') echo 'selected'; ?>>Activo</option>
          <option value="inactive" <?php if ($owner->getStatus() === 'inactive') echo 'selected'; ?>>Inactivo</option>
          <option value="suspended" <?php if ($owner->getStatus() === 'suspended') echo 'selected'; ?>>Suspendido</option>
        </select>
      </div>
      <div class="form-group m-2">
        <label for="name">Nombre</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="<?php echo $owner->getName(); ?>">
      </div>
      <div class="form-group m-2">
        <label for="lastname">Apellido</label>
        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="<?php echo $owner->getLastname(); ?>">
      </div>
      <div class="form-group m-2">
        <label for="suspDate">Suspension</label>
        <input type="date" class="form-control" id="suspensionDate" name="suspensionDate" placeholder="<?php echo $owner->getSuspensionDate(); ?>">
      </div>
      <button type="submit" class="btn btn-primary  m-2">Guardar cambios</button>
    </form>
  </div>
<?php require_once("footer.php");
?>