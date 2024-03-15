<?php
include("header.php");
include("nav.php");
?>
<div class="container-fluid mt-5">
    <form action="<?php echo FRONT_ROOT . "Coupon/manageCoupon" ?>">
        <div class="row justify-content-center">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4 bg-c-lite-green user-profile">
                                <div class="card-block text-center text-white">
                                    <div class="m-b-25">
                                        <img src="<?php echo FRONT_ROOT . "Images/".$coupon["pfpk"]; ?>" class="img-fluid rounded-circle" alt="User-Profile-Image">
                                    </div>
                                    <h6 class="f-w-600"><?php echo $coupon["kname"] . ' ' . $coupon["klastname"]; ?></h6>
                                    <p><?php echo $coupon["typePet"] . "s Keeper"; ?></p>
                                    <a href="#" class="btn btn-sm btn-secondary"><i class="feather icon-edit"></i>Keeper profile</a>
                                </div>
                            </div>
                            <div class="col-sm-8 ">
                                <div class="card-block ">
                                    <h2 class="m-b-20 p-b-5 b-b-default f-w-600 text-center">Coupon Information</h2>
                                    <div class="row mb-4 ">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Email</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["emailKeeper"]; ?></h6>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Coupon status</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["statusCoup"]; ?></h6>
                                        </div>
                                    </div>
                                    <div class="row mb-4 ">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Initial Date</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["initDate"]; ?></h6>
                                        </div>
                                        <div class="col-sm-6 ">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">End Date</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["endDate"]; ?></h6>
                                        </div>
                                    </div>
                                    <div class="row mb-4 ">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Initial Hour</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["initHour"]; ?></h6>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">End Hour</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["endHour"]; ?></h6>
                                        </div>
                                    </div>
                                    <div class="row mb-4 ">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Pet to Keep</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded"><?php echo $coupon["namePet"]; ?></h6>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600 bg-dark text-white p-2  rounded">Price</p>
                                            <h6 class="text-muted f-w-400 p-2 border border-dark rounded "><?php echo $coupon["totalPrice"]; ?></h6>
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-around">
                                    <?php if($coupon["statusCoup"] == "pending"){ ?>
                                        
                                        <a href="<?php echo FRONT_ROOT.'Coupon/payCouponView/'.$coupon["couponCode"] ?>" class="btn btn-success">Pay Coupon</a>

                                        <?php } ?>

                                    <a href="#" class="btn btn-danger">Cancel Coupon</a>
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
include("footer.php");
?>
