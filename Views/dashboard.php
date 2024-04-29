<?php 
include("header.php");
?>

<h1 class="text-center text-white bg-dark m-2 p-2 rounded">WELCOME ADMIN!</h1>

<div class="container" >
    <div class="bg-secondary">
        <h2 class="text-center text-white bg-dark p-2 rounded">Options</h2>
        <ul class="list-group">
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT."Owner/showListOwners" ?>" id="owners">Show owners</a></li>
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT."Keeper/showListKeepers" ?>" id="keepers">Show keepers</a></li>
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT."Pet/showListPets" ?>" id="pets">Show pets</a></li>
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT."Booking/showListBookings" ?>" id="bookings">Show bookings</a></li>
            <li class="list-group-item"><a href="<?php echo FRONT_ROOT."Coupon/showListCoupons" ?>" id="coupons">Show coupon</a></li>
        </ul>
    </div>
</div>
<?php 
include("footer.php");
?>
