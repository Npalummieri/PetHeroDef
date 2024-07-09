<?php require_once("header.php");
include("nav.php");
?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 text-center">
      <h1 class=" bg-dark text-white rounded p-2 m-2">¿Cual será tu rol?</h1>
    </div>
  </div>
  <div class="row justify-content-around ">
    <div class="col-sm-6 col-md-4 col-lg-3 rounded p-4" style="background-color: #110257;">
      <div class="card text-center">
        <h2 class="card-title">Dueño</h2>
        <img class="card-img-top img-fluid" src="<?php echo FRONT_ROOT . 'Images/SysImages/owner.jpg'; ?>" alt="Owner Image">
        <div class="card-body">
          <p class="card-text">Como dueño podes registrar a tus mascotas y buscar al cuidador que se adapte a lo que necesites.</p>
          <a href="<?php echo FRONT_ROOT ?>Home/showOwnerRegisterView" class="btn btn-primary">¡Soy dueño!</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3 rounded p-4" style="background-color: #110257;">
      <div class="card text-center">
        <h2 class="card-title">Cuidador</h2>
        <img class="card-img-top img-fluid" src="<?php echo FRONT_ROOT . 'Images/SysImages/keeper.jpg'; ?>" alt="Keeper Image">
        <div class="card-body">
          <p class="card-text">Como cuidador podes mostrar tu mejor perfil para que te contacten y cuidar sus mascotas</p>
          <a href="<?php echo FRONT_ROOT ?>Home/showKeeperRegisterView" class="btn btn-primary">¡Quiero ser cuidador!</a>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once("footer.php"); ?>