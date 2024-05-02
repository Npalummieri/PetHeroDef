<?php
    require_once("header.php");
    include("nav.php");
?>
<h2 class="bg-dark text-lg text-white text-center rounded p-2 mt-2">BOOKING INFORMATION</h2>
<div class="container  " >
    <form action="<?php echo FRONT_ROOT.'' ?>" method="POST">
        <div class="row text-white" style="background-color: #110257;">
            <!-- colpfp -->
            <div class="col-lg-3 p-3 ">
                <img src="<?php echo FRONT_ROOT . "Images/".$fullBook["pfp"] ?>" alt="Profile Picture" class="img-fluid rounded-circle " width="354px" height="354px">
            </div>
            <!-- col infobook-->
            <div class="col-lg-9  p-4 ">
                
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
                    <table class="table table-bordered text-center">
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
                <div id="calendar" class="bg-light"> </div>
                <div class="d-flex justify-content-between">
                    <?php if($loggedUser instanceof Models\Keeper && $fullBook["status"] == "pending") { ?>
                        <a href="<?php echo FRONT_ROOT . 'Booking/manageBooking/' . $fullBook["bookCode"] ?>" class="btn btn-dis btn-success" data-msg="Confirm the booking?">Confirm</a>
                        <a href="<?php echo FRONT_ROOT . 'Booking/cancelBooking/' . $fullBook["bookCode"] ?>" class="btn  btn-dis btn-danger" data-msg="Cancel the booking?">Reject</a>
                    <?php } ?>
                    <?php if($loggedUser instanceof Models\Owner && $fullBook["status"] == "confirm") { ?>
                        <a href="<?php echo FRONT_ROOT.''.$fullBook["bookCode"]; ?>" class="btn btn-success">Check coupon</a>
                    <?php } ?>
                </div>  
            </div>
        </div>
    </form>
</div>
<script src="<?php echo JS_PATH."formScripts.js" ?>"></script>
<script>
KeepersInteract.calendarKeeper();
KeepersInteract.reConfirm();
FormAjaxModule.calendarBooking();</script>
<?php require_once("footer.php") ?>
