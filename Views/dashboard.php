<?php
require_once("header.php");
?>

<h1 class="text-center text-white bg-dark m-2 p-2 rounded">BIENVENIDO ADMIN</h1>
<div class="d-flex justify-content-end"><a href="<?php echo FRONT_ROOT.'Home/Logout' ?>" class="btn btn-danger">Cerrar sesión</a></div>
<?php include("msgeDisplay.php"); ?>
<div class="container">
    <div class="bg-secondary">
        <h2 class="text-center text-white bg-dark p-2 rounded">Opciones</h2>
        <ul class="list-group ">
            <li class="list-group-item d-flex align-items-center justify-content-end"><a href="<?php echo FRONT_ROOT?>Home/showAdminRegister" class="btn btn-success text-white m-2">Agregar admin +</a></li>
            <li class="list-group-item text-decoration-none"><a href="<?php echo FRONT_ROOT . "Owner/showListOwners" ?>" class="text-decoration-none" id="owners">Mostrar dueños</a></li>
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT . "Keeper/showListKeepers" ?>" class="text-decoration-none" id="keepers"> Mostrar cuidadores</a></li>
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT . "Pet/showListPets" ?>" class="text-decoration-none" id="pets">Mostrar mascotas</a></li>
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT . "Booking/showListBookings"  ?>" class="text-decoration-none" id="bookings">Mostrar reservas</a></li>
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT . "Coupon/showListCoupons" ?>" class="text-decoration-none" id="coupons">Mostrar cupones</a></li>
        </ul>
    </div>
</div>
<?php
require_once("footer.php");
?>