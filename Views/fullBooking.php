<?php
    include("header.php");
    include("nav.php");
?>
<div class="container mt-5">
    <form action="<?php echo FRONT_ROOT.'' ?>" method="POST">
        <div class="row">
            <!-- Columna para la foto de perfil -->
            <div class="col-lg-3">
                <img src="<?php echo FRONT_ROOT . "Images/".$fullBook["pfp"] ?>" alt="Profile Picture" class="img-fluid rounded-circle">
            </div>
            <!-- Columna para la información de la reserva -->
            <div class="col-lg-9 bg-light p-4 rounded">
                <h2 class="mb-4">Booking Information</h2>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Name pet :</strong> <?php echo $fullBook["petName"] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Owner name :</strong> <?php echo $fullBook["ownerName"] ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Type Pet :</strong> <?php echo $fullBook["typePet"] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Owner email :</strong> <?php echo $fullBook["oemail"] ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Breed:</strong> <?php echo $fullBook["breed"] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Keeper name:</strong> <?php echo $fullBook["kname"] ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <p><strong>Size:</strong> <?php echo $fullBook["size"] ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Keeper email:</strong> <?php echo $fullBook["kemail"] ?></p>
                    </div>
                </div>
                <div class="table-responsive mt-4">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Initial Date</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $fullBook["initDate"] ?></td>
                                <td><?php echo $fullBook["endDate"] ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <button type="button" style="display: none;" id="btnprof" data-codebook="<?php echo $fullBook["bookCode"] ?>"></button>
                <div id="calendar"></div>
                <p><strong>Bio:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eget odio nec leo condimentum congue.</p>
                <div class="d-flex justify-content-between">
                    <?php if($loggedUser instanceof Models\Keeper && $fullBook["status"] == "pending") { ?>
                        <a href="<?php echo FRONT_ROOT.'Booking/manageBooking/'.$fullBook["bookCode"]; ?>" class="btn btn-success">Confirm</a>
                    <?php } ?>
                    <?php if($loggedUser instanceof Models\Owner && $fullBook["status"] == "confirm") { ?>
                        <a href="<?php echo FRONT_ROOT.''.$fullBook["bookCode"]; ?>" class="btn btn-success">Check coupon</a>
                    <?php } ?>
                    <?php if($loggedUser instanceof Models\Owner) { ?>
                        <a class="btn btn-primary" href="<?php echo FRONT_ROOT . 'Review/doReview/' . $fullBook["keeperCode"] ?>">Rate!</a>
                    <?php }?>
                </div>  
            </div>
        </div>
    </form>
</div>
<script src="<?php echo JS_PATH."formScripts.js" ?>"></script>
<script>
KeepersInteract.calendarKeeper();
FormAjaxModule.calendarBooking();</script>
<?php include("footer.php") ?>

