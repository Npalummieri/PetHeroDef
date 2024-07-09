<?php
require_once("header.php");
 include("nav.php");
 use Utils\Session as Session;
?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-sm-10 col-md-8 col-lg-6">
      <h2 class="text-center text-white bg-dark rounded mt-2">ACCESO</h2>
      <?php  include_once("msgeDisplay.php") ?>
      
      <form action="<?php echo FRONT_ROOT . "Auth/login" ?>" method="POST" class="p-5 text-white rounded" style="background-color: #110257;">
        <div class="form-group m-2">
          <label for="user">Usuario</label>
          <input type="text" name="userField" id="user" class="form-control form-control-lg input-group" placeholder="Usuario o Email" required>
        </div>
        <div class="form-group m-2">
          <label for="password">Contraseña</label>
          <input type="password" id="password" name="password" class="form-control form-control-lg input-group" placeholder="Contraseña" required>
        </div>

        <div class="form-group text-center">
          <p><a href="<?php echo FRONT_ROOT."Auth/recoverPasswordView" ?>" class="text-white">¿Ha olvidado su contraseña? <strong> Click aqui</strong></a></p>
        </div>

        <div class="form-group text-end">
          <button class="btn btn-warning btn-lg " type="submit">Acceder</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php 
require_once("footer.php");
?>