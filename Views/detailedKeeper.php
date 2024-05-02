<?php
require_once("header.php");
include_once("nav.php");
?>
<div class="container mt-5">

    <form action="<?php echo FRONT_ROOT . 'Booking/showBookCreate' ?>" method="POST">
        <div class="row border rounded p-4">
            <!-- colpfp-->
            <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-3 text-center my-auto">
                <img src="<?php echo FRONT_ROOT . "Images/" . $keeper->getPfp() ?>" alt="Profile Picture" class="img-fluid rounded-circle">
            </div>
            <!-- infokeep col -->
            <div class="col-12 col-sm-6 col-md-8 col-lg-9 col-xl-9">
                <h2>Keeper Information</h2>
                <p><strong>Name :</strong> <?php echo $keeper->getName(); ?></p>
                <p><strong>Email:</strong> <?php echo $keeper->getEmail(); ?></p>
                <p><strong>Type Pet:</strong> <?php echo $keeper->getTypePet(); ?></p>
                <p><strong>Type Care:</strong> <?php echo $keeper->getTypeCare(); ?></p>
                <p><strong>Score:</strong> <?php echo $keeper->getScore(); ?></p>

                <hr>
                <h5>Availability : </h5>
                <div>

                </div>

                <hr>
                <input type="hidden" name="keeperCode" value="<?php echo $keeper->getKeeperCode(); ?>">
                <input type="hidden" name="typePet" value="<?php echo $keeper->getTypePet(); ?>">
                <input type="hidden" name="typeSize" value="<?php echo $keeper->getTypeCare(); ?>">
                <p class="m-3"><strong>Bio:</strong> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed eget odio nec leo condimentum congue.</p>
                <div class="d-flex justify-content-between">
                    <button class="btn btn-success" type="submit">Hire it!</button>
                    <!-- Botón Rate -->
                    <button type="button" id="rateBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal" data-keepercode="<?php echo $keeper->getKeeperCode(); ?>">RATE!</button>
                </div>
            </div>
    </form>


    <button type="button" id="rateBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal" data-keepercode="<?php echo $keeper->getKeeperCode(); ?>">RATE!</button>
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

<script src="<?php echo JS_PATH . "formScripts.js"; ?>"></script>
<script>
    moduleReview.displayFieldReview();
</script>

<?php require_once("footer.php") ?>