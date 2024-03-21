<?php
include("header.php");
include("nav.php");

use Utils\Session as Session;

?>
<div class="container mt-5" id="contMain">
    <div class="row">
        <!-- Columna para la foto de perfil -->
        <div class="col-lg-3 text-center">
            <img src="<?php echo FRONT_ROOT . "Images/" . $infoKeeper->getPfp() ?>" alt="Profile Picture" class="img-fluid rounded-circle">
            <?php if ($loggedKeeper != null && $loggedKeeper->getKeeperCode() == Session::GetLoggedUser()->getKeeperCode()) { ?>
                <a href="<?php echo FRONT_ROOT . 'Keeper/showUpdateKeeper' ?>" class="btn btn-primary mt-3 p-2" id="btnprof" data-codekeeper="<?php echo $infoKeeper->getKeeperCode() ?>">Edit Profile</a>
            <?php } ?>
        </div>
        <!-- Columna para la información del usuario -->
        <div class="col-lg-9">
            <h2>User Information</h2>
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Name:</strong> <?php echo $infoKeeper->getName(); ?></p>
                    <p><strong>Email:</strong> <?php echo $infoKeeper->getEmail(); ?></p>
                    <p><strong>Username:</strong> <?php echo $infoKeeper->getUserName(); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Type Pet:</strong> <?php echo $infoKeeper->getTypePet(); ?></p>
                    <p><strong>Type Care:</strong> <?php echo $infoKeeper->getTypeCare(); ?></p>
                    <p><strong>Score:</strong> <?php echo $infoKeeper->getScore(); ?></p>
                </div>
            </div>
            <hr>
            <h3>Availability</h3>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 col-12  p-3 text-center">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">InitDate</th>
                                        <th class="text-center">EndDate</th>
                                        <?php if ($loggedKeeper != null && $loggedKeeper->getKeeperCode() == Session::GetLoggedUser()->getKeeperCode()) { ?>
                                            <th class="text-center"></th> <!-- Celda extra para el botón de editar -->
                                        <?php } ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center p-3"><?php echo $infoKeeper->getInitDate(); ?></td>
                                        <td class="text-center p-3"><?php echo $infoKeeper->getEndDate(); ?></td>
                                        <?php if ($loggedKeeper != null) {
                                            if ($loggedKeeper->getKeeperCode() == Session::GetLoggedUser()->getKeeperCode()) {


                                        ?>
                                                <td class="text-center ">
                                                    <button class="btn btn-primary btn-edit">Edit</button> <!-- Botón de editar -->
                                                    <button class="btn btn-success btn-save" style="display: none;">Save</button> <!-- Botón de guardar -->
                                                    <button class="btn btn-danger btn-cancel" style="display: none;">Cancel</button> <!-- Botón de cancelar -->
                                                </td>

                                        <?php }
                                        } ?>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <?php if ($loggedOwner != null) { ?>
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-success" href="<?php echo FRONT_ROOT . "Booking/showBookCreate/" . $keeperCode ?>">Hire it!</a>
                                <!-- Botón Rate -->
                                <button type="button" id="rateBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal" data-keepercode="<?php echo $keeper->getKeeperCode(); ?>">RATE!</button>
                            </div>
                        <?php  } ?>
                    </div>
                </div>
            </div>



            <div id="calendar"></div>
            <hr>
            <p><strong>Bio:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eget odio nec leo condimentum congue.</p>
            <p><strong>Reviews:</strong> <?php echo $infoKeeper->getScore(); ?></p>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Write a Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <textarea id="reviewText" class="form-control" rows="4" maxlength="200" placeholder="Write your review (max 200 characters)"></textarea>
                <div class="form-group mt-3">
                    <label for="rating">Rating:</label>

                    <select class="form-control" id="rating"> <i class="fa-solid fa-star">
                            <option value="1"><i class="bi bi-star-fill"></i> 1</option>
                            <option value="2"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i> 2</option>
                            <option value="3"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i> 3</option>
                            <option value="4"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i> 4</option>
                            <option value="5"><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i> 5</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button id="submitReview" type="button" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </div>
</div>
</div>


<h2>Reviews</h2>
<div id="reviews">
    <?php foreach ($reviews as $review) { ?>
        <div class="row mb-4">
            <div class="col">
                <div class="d-flex flex-start">
                    <img class="rounded-circle shadow-1-strong me-3" src="<?php echo FRONT_ROOT . "Images/" . $review["pfp"]; ?>" alt="avatar" width="65" height="65" />
                    <div class="flex-grow-1 flex-shrink-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-1">
                                <?php echo $review["name"] . " " . $review["lastname"] ?> <span class="small"><?php echo $review["timeStamp"] ?></span>
                            </p>
                            <!-- <a href="#!"><i class="fas fa-reply fa-xs"></i><span class="small"> reply</span></a> -->
                        </div>
                        <p class="small mb-0">
                            <?php echo $review["comment"]; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>
<script>
    KeepersInteract.calendarKeeper();
    KeepersInteract.displayEditDates();
    KeepersInteract.updateDates();
    moduleReview.displayFieldReview();
</script>

<?php include("footer.php"); ?>