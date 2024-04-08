<?php

use Utils\Session as Session;


?>
<!-- Title -->
<div style="margin-top : 0;">
     <nav class="navbar navbar-expand" style="background-color: #110257;">
          <div class="container d-flex justify-content-center">
               <a class="navbar-brand text-white" href="<?php echo FRONT_ROOT . "Home/Index"; ?>">
                    <strong>PET HERO</strong>
                    <i class="fas fa-otter"></i>
               </a>
          </div>
     </nav>
</div>
<!-- main navbar -->
<div class="d-flex justify-content-end bd-navbar " style="background-color: #0a0130; " >

     <nav class="navbar navbar-expand-lg text-white">
          <a class="navbar-brand" href="#"></a>
          <?php if (!Session::IsLogged()) { ?>
               <button class="btn btn-warning"><a class="nav-link" href="<?php echo FRONT_ROOT ?>Home/showChooseRegister">REGISTER</a></button>
          <?php    } ?>

          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon"></span>
          </button>

          <div class="collapse navbar-collapse text-white" id="navbarSupportedContent">
               <ul class="navbar-nav m-0">
                    <li class="nav-item active">
                         <!-- Pendiente -->
                         <a class="nav-link text-white" href="<?php echo FRONT_ROOT."Home/showHowWorks" ?>">How it works</a>
                    </li>

                    <?php if (!isset($_SESSION["loggedUser"])) { ?>
                         <li class="nav-item active">
                              <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Home/showLoginView">Login</a>
                         </li>
                    <?php } ?>
                    <?php if (isset($_SESSION["loggedUser"])) {
                         if (is_a($_SESSION["loggedUser"], 'Models\Owner')) { ?>
                              <li class="nav-item active">
                                   <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Owner/ShowMyProfile">My profile</a>
                              </li>
                         <?php } else if (is_a($_SESSION["loggedUser"], 'Models\Keeper')) { ?>
                              <li class="nav-item active">
                                   <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Keeper/showProfileKeeper">My profile</a>
                              </li>
                    <?php }
                    } ?>
                    <li class="nav-item active">
                         <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Home/showKeeperListPag/1">Show All keepers</a>
                    </li>
                    <?php
                    if (isset($_SESSION["loggedUser"])) {
                         if (is_a($_SESSION["loggedUser"], 'Models\Owner')) { ?>
                              <li class="nav-item active ">
                                   <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Owner/showAddPet">AddPet</a>
                              </li>

                              <li class="nav-item active">
                                   <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Owner/showMyPets">My Pets</a>
                              </li>



                              <li class="nav-item active">
                                   <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Coupon/showMyCoupons">My Coupons</a>
                              </li>
                         <?php } ?>
                    <?php
                    }
                    ?>
                    <?php if (Session::IsLogged()) {  ?>
                    <li class="nav-item active">
                         <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Booking/showMyBookings">My Bookings</a>
                    </li>
                    <?php } ?>
                    <?php if (Session::IsLogged()) {
                    ?><li class="nav-item active">
                              <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Message/ToInbox">Messages</a>
                         </li>
                    <?php } ?>

                    <?php

                    if (Session::IsLogged()) {
                    ?>
                         <li class="nav-item active">
                              <a class="nav-link text-white" href="<?php echo FRONT_ROOT ?>Home/Logout">Logout</a>
                         </li>
                    <?php } ?>
               </ul>
          </div>

     </nav>
</div>