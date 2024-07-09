<?php
require_once("header.php");
include("nav.php");
?>
<div class="container-fluid mt-5">
    <form action="">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 bg-c-lite-green user-profile">
                                <div class="card-block text-center text-white">
                                    <div class="">
                                        <img src="<?php echo FRONT_ROOT . "Images/" . $coupon["pfpk"]; ?>" class="img-fluid rounded-circle" alt="User-Profile-Image">
                                    </div>
                                    <h6 class="f-w-600"><?php echo $coupon["kname"] . ' ' . $coupon["klastname"]; ?></h6>
                                    <p><?php echo $coupon["typePet"] . "s Keeper"; ?></p>
                                    <a href="<?php echo FRONT_ROOT . "Keeper/showProfileKeeper/" . $coupon["keeperCode"]; ?>" class="btn btn-sm btn-secondary"><i class="feather icon-edit"></i>Keeper profile</a>
                                </div>
                            </div>
                            <div class="col-sm-8 ">
                                <div class="card-block ">
                                    <h2 class="m-b-20 p-b-5 b-b-default f-w-600 text-center">Información del cupón</h2>
                                    <div class="row mb-4 ">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Email</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["emailKeeper"]; ?></h6>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Estado</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php switch ($coupon["statusCoup"]) {
                                                                                                                case "pending":
                                                                                                                    echo "PENDIENTE";
                                                                                                                    break;
                                                                                                                case "paidup":
                                                                                                                    echo "PAGADO";
                                                                                                                    break;
                                                                                                                case "cancelled":
                                                                                                                    echo "CANCELADO";
                                                                                                                    break;
                                                                                                                case "finished":
                                                                                                                    echo "FINALIZADO";
                                                                                                                    break;
                                                                                                            } ?></h6>
                                        </div>
                                    </div>
                                    <div class="row mb-4 ">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Inicio</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["initDate"]; ?></h6>
                                        </div>
                                        <div class="col-sm-6 ">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Final</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["endDate"]; ?></h6>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-4 ">
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Mascota a cuidar</p>
                                        <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["namePet"]; ?></h6>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Visitas por dia</p>
                                        <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["visitPerDay"]; ?></h6>

                                    </div>
                                </div>
                                <div class="row mb-4 text-center">
                                    <div class="col-sm-12">
                                        <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Precio</p>
                                        <h6 class="text-muted f-w-400 p-2 border border-dark rounded "><?php echo '$'.$coupon["totalPrice"]; ?></h6>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-around">
                                    <?php if ($coupon["statusCoup"] == "pending") { ?>

                                        <a href="<?php echo FRONT_ROOT . 'Coupon/payCouponView/' . $coupon["couponCode"] ?>" class="btn  btn-success">Pagar</a>

                                    <?php } ?>

                                    <?php if ($coupon["statusCoup"] != "finished" && $coupon["statusCoup"] != "cancelled") { ?>
                                        <a href="<?php echo FRONT_ROOT . "Coupon/declineCoupon/" . $coupon["couponCode"] ?>" class="btn btn-dis btn-danger" data-msg="¿Cancelar cupón? Esta acción cancelará la reserva correspondiente. Se suspenderá la cuenta durante 48hs por incumplimiento del reglamento.">Cancelar cupón</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
</form>
</div>
<?php
require_once("footer.php");
?>
<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>
<script>
    KeepersInteract.reConfirm();
</script>