<?php

use Utils\Session as Session;


?>
<!-- Title -->
<div style="margin-top : 0;">
     <nav class="navbar navbar-expand " style="background-color: #110257;">
          <div class="container d-flex justify-content-center">
               <a class="navbar-brand text-white" href="<?php echo FRONT_ROOT . "Home/Index"; ?>">
                    <strong>PET HERO</strong>
                    <i class="fas fa-otter"></i>
               </a>
          </div>
     </nav>
</div>
<!-- main navbar -->
<div class="d-flex justify-content-end " style="background-color: #0a0130;">

     <nav class="navbar navbar-expand-lg text-white">
          <a class="navbar-brand" href="#"></a>
          <?php if (!Session::IsLogged()) { ?>
               <button class="btn btn-warning"><a class="nav-link" href="<?php echo FRONT_ROOT ?>Home/showChooseRegister">REGISTRO</a></button>
          <?php    } ?>

          <button class="navbar-toggler bg-info-subtle" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
               <span class="navbar-toggler-icon "></span>
          </button>

          <div class="collapse navbar-collapse text-white" id="navbarSupportedContent">
               <ul class="navbar-nav">
                    <li class="nav-item m-2 active border border-2 rounded-2 ">
                         <a class="nav-link text-center" href="<?php echo FRONT_ROOT . "Home/showHowWorks" ?>">Sobre nosotros</a>
                    </li>

                    <?php if (!isset($_SESSION["loggedUser"])) { ?>
                         <li class="nav-item active m-2 border border-2 rounded-2">
                              <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Home/showLoginView">Acceso</a>
                         </li>
                    <?php } ?>
                    <?php if (Session::isLogged()) {
                    ?>
                         <li class="nav-item active m-2 border border-2 rounded-2" id="notis">
                              <div id="baseurl" data-urlcur = "<?php echo FRONT_ROOT ?>" hidden></div>
                              <div class="notification-area" id="areanoti">
                                   <a class="nav-link text-center" id="notificationDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa-solid fa-bell" id="bell"></i>
                                        <span class="badge badge-danger" id="notificationCount" ></span>
                                   </a>
                                   <div class="dropdown-menu dropdown-menu-right" id="notificationMenu" aria-labelledby="notificationDropdown"></div>
                              </div>
                         </li>
                         <?php if (is_a($_SESSION["loggedUser"], 'Models\Owner')) { ?>
                              <li class="nav-item active m-2 border border-2 rounded-2">
                                   <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Owner/ShowMyProfile">Mi perfil</a>
                              </li>
                         <?php } else if (is_a($_SESSION["loggedUser"], 'Models\Keeper')) { ?>
                              <li class="nav-item active m-2 border border-2 rounded-2">
                                   <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Keeper/showProfileKeeper">Mi perfil</a>
                              </li>
                    <?php }
                    } ?>
                    <li class="nav-item active m-2 border border-2 rounded-2">
                         <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Home/showKeeperListPag/1">Cuidadores</a>
                    </li>
                    <?php
                    if (isset($_SESSION["loggedUser"])) {
                         if (is_a($_SESSION["loggedUser"], 'Models\Owner')) { ?>
                              <li class="nav-item active m-2 border border-2 rounded-2">
                                   <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Owner/showAddPet">Agregar mascota +</a>
                              </li>

                              <li class="nav-item active m-2 border border-2 rounded-2">
                                   <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Owner/showMyPets">Mis mascotas</a>
                              </li>



                              <li class="nav-item active m-2 border border-2 rounded-2">
                                   <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Coupon/showMyCoupons">Cupones</a>
                              </li>
                         <?php } ?>
                    <?php
                    }
                    ?>
                    <?php if (Session::IsLogged()) {  ?>
                         <li class="nav-item active m-2 border border-2 rounded-2">
                              <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Booking/showMyBookings">Reservas</a>
                         </li>
                    <?php } ?>
                    <?php if (Session::IsLogged()) {
                    ?><li class="nav-item active m-2 border border-2 rounded-2">
                              <a class="nav-link text-center" href="<?php echo FRONT_ROOT ?>Message/ToInbox">Mensajes</a>
                         </li>
                    <?php } ?>

                    <?php

                    if (Session::IsLogged()) {
                    ?>
                         <li class="nav-item active m-2 border border-2 rounded-2"  id="deleteSt">
                              <a class="nav-link text-center"  href="<?php echo FRONT_ROOT ?>Home/Logout">Cerrar sesión</a>
                         </li>
                    <?php } ?>
               </ul>
          </div>

     </nav>
</div>
<script src="<?php echo JS_PATH . 'formScripts.js'; ?>"></script>
<script>
     infoModule.getNotis();
     infoModule.resetNotis();
</script>