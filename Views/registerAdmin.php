<?php 
require_once ("header.php");

?>
<div class="container justify-content-center text-white p-3 rounded " style="background-color: #110257;">
<?php include("msgeDisplay.php"); ?>
<div class="form-group">
    <form action="<?php echo FRONT_ROOT ?>Home/addAdmin" method="POST" class="form text-white bg-dark rounded p-3">
    <h2 class="text-center text-white bg-dark m-2 p-2 rounded">Registro de admin</h2>
      <div class="form-group m-2">
        <label for="email" class="label-form m-2">Email</label>
        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Email" maxlength="20" required>
      </div>
      <div class="form-group m-2">
        <label for="password" class="label-form m-2">Contraseña</label>
        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Contraseña" maxlength="20" required>
      </div>
      <div class="form-group m-2">
        <label for="dni" class="label-form m-2">Dni</label>
        <input type="text" name="dni" id="dni" class="form-control form-control-lg" placeholder="DNI" required>
      </div>

      
      <div class="d-flex justify-content-end m-2">
      <button class="btn btn-success mt-3 ">Registrar admin</button>
      </div>
      </form>
</div>
</div>
<?php 
require_once ("footer.php");
?>