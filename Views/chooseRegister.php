<?php include("header.php");
include("nav.php");
?>
<div class="container">
  <div class="row justify-content-center">
    <div class="col-12 text-center">
      <h1 class=" bg-dark text-white rounded p-2 m-2">Which is your Role?</h1>
    </div>
  </div>
  <div class="row justify-content-around " >
    <div class="col-sm-6 col-md-4 col-lg-3 rounded p-4" style="background-color: #110257;">
      <div class="card text-center">
        <h2 class="card-title">Owner</h2>
        <img class="card-img-top img-fluid" src="<?php echo FRONT_ROOT . 'Images/SysImages/owner.jpg'; ?>" alt="Owner Image">
        <div class="card-body">
          <p class="card-text">As owner you can register your pets and find the best keeper that suits them to taking care of</p>
          <a href="<?php echo FRONT_ROOT ?>Home/showOwnerRegisterView" class="btn btn-primary">Owner Register</a>
        </div>
      </div>
    </div>
    <div class="col-sm-6 col-md-4 col-lg-3 rounded p-4" style="background-color: #110257;">
      <div class="card text-center">
        <h2 class="card-title">Keeper</h2>
        <img class="card-img-top img-fluid" src="<?php echo FRONT_ROOT . 'Images/SysImages/keeper.jpg'; ?>" alt="Keeper Image">
        <div class="card-body">
          <p class="card-text">As a keeper you can register to take care of those pets that best suit your profile.</p>
          <a href="<?php echo FRONT_ROOT ?>Home/showKeeperRegisterView" class="btn btn-primary">Keeper Register</a>
        </div>
      </div>
    </div>
  </div>
</div>