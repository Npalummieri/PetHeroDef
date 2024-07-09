<?php
require_once("header.php");
include("nav.php");

use Utils\Session as Session;

?>
<div id="cururl" data-cururl="<?php echo FRONT_ROOT ?>" hidden ></div>
<?php require_once("msgeDisplay.php"); ?>
            <h2 class="bg-dark text-white text-center rounded mt-2">PERFIL DE CUIDADOR</h2>
<div class="container  text-white rounded" id="contMain" style="background-color: #110257;">
    <div class="row">
        <!-- pfp col -->
        <div class="col-lg-4">
            <div class="about-avatar text-center border rounded-circle border-1 border-dark mt-3">
                <img src="<?php echo FRONT_ROOT . "Images/" . $infoKeeper->getPfp() ?>" alt="Profile Picture" class="img-fluid">
				</div>
                <div class="d-flex justify-content-center">
                <?php if ($loggedKeeper != null && $loggedKeeper->getKeeperCode() == Session::GetLoggedUser()->getKeeperCode()) { ?>
                    <a href="<?php echo FRONT_ROOT . 'Keeper/showUpdateKeeper' ?>" class="btn btn-primary mt-3 p-2" id="btnprof" data-codekeeper="<?php echo $infoKeeper->getKeeperCode() ?>">Editar Perfil</a>
                <?php } ?>
                </div>
				 <div class="container responsive rounded mt-3">
                <div id="calendar" class="text-white bg-white rounded"></div>
            </div>
            
        </div>
        <!-- userinfo col -->
        <div class="col-lg-8 p-3">
            <?php if (isset($_SESSION["bmsg"])) { ?>
                <p class="alert alert-danger"><?php echo $_SESSION["bmsg"];
                                                unset($_SESSION["bmsg"]); ?> </p>

            <?php
            } else if (isset($_SESSION["gmsg"])) { ?>
                <p class="alert alert-success"><?php echo $_SESSION["gmsg"];
                                                unset($_SESSION["gmsg"]); ?></p>
            <?php } ?>

            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nombre :</strong> <?php echo $infoKeeper->getName(); ?></p>
                    <p><strong>Email :</strong> <?php echo $infoKeeper->getEmail(); ?></p>
                    <p><strong>Nombre de usuario :</strong> <?php echo $infoKeeper->getUserName(); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Tipo de mascota :</strong> <?php echo $infoKeeper->getTypePet() === "cat" ?  "Gato" :  "Perro"; ?></p>
                    <p><strong>Tamaño :</strong> <?php if($infoKeeper->getTypeCare() === "big")
              { echo "Grande";}
              else if($infoKeeper->getTypeCare() === "medium")
              {echo "Mediano";}
              else {echo "Pequeño";}
              ;?></p>
                    <p><strong>Puntaje :</strong> <?php echo $infoKeeper->getScore(); ?></p>
                </div>
            </div>
            <hr>
            <div class="container">
            <h3>Disponibilidad</h3>
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-10 col-md-12 col-sm-12 text-center">
                    <p><strong>Visitas por dia : </strong> <?php echo $infoKeeper->getVisitPerDay(); ?></p>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th class="text-center">Fecha inicial</th>
                                        <th class="text-center">Fecha final</th>
                                        <?php if ($loggedKeeper != null && $loggedKeeper->getKeeperCode() == Session::GetLoggedUser()->getKeeperCode()) { ?>
                                            <th class="text-center"></th>
                                        <?php } ?>
                                    </tr>
                                    <div id="result-message" style="display: none;"></div>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"><?php echo $infoKeeper->getInitDate(); ?></td>
                                        <td class="text-center"><?php echo $infoKeeper->getEndDate(); ?></td>
                                        <?php if ($loggedKeeper != null) {
                                            if ($loggedKeeper->getKeeperCode() == Session::GetLoggedUser()->getKeeperCode()) {


                                        ?>
                                                <td class="text-center ">
                                                    <button class="btn btn-primary btn-edit">Editar</button> 
                                                    <button class="btn btn-success btn-save" style="display: none;">Guardar</button>
                                                    <button class="btn btn-danger btn-cancel" style="display: none;">Cancelar</button> 
                                                </td>

                                        <?php }
                                        } ?>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        </div>
            </div>
                        <?php if ($loggedOwner != null) { ?>
                            <div class="d-flex justify-content-between">
                                <a class="btn btn-success" href="<?php echo FRONT_ROOT . "Booking/showBookCreate/" . $keeperCode ?>">Contratar</a>
                                <button type="button" id="rateBtn" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#reviewModal" data-keepercode="<?php echo $keeper->getKeeperCode(); ?>">Puntua</button>
                            </div>
                        <?php  } ?>
                    </div>
                
            
                    <hr>

            <div class="form-group m-3">
                <label for="bio" class="h3">Sobre mi</label>
                <p id="bio" class="text-black bg-light p-2 rounded-3" data-userlogged="<?php echo $infoKeeper->getKeeperCode(); ?>"><?php echo $infoKeeper->getBio() == null ? "Bio sin redactar." : $infoKeeper->getBio() ?></p>
                <div class="form-group" style="display: none;" id="bioEditor">
                    <textarea class="form-control" name="bio" id="bioTextarea" maxlength="200" placeholder="Enter your bio (max 200 characters)"></textarea>
                </div>
                <?php if (Session::IsLogged() && Session::GetTypeLogged() == "Models\Keeper") {
                    if (Session::GetLoggedUser()->getKeeperCode() == $infoKeeper->getKeeperCode()) { ?>

                        <div class=" text-end">
                            <button class="btn btn-primary" id="editBioBtn">Editar bio</button>
                        </div>
                <?php }
                } ?>
                <button class="btn btn-success" id="saveBioBtn" style="display: none;">Guardar</button>
                <button class="btn btn-secondary" id="cancelBioBtn" style="display: none;">Cancelar</button>

            </div>

           




            

        </div>
    </div>

</div>



<!-- Modal -->
<div class="modal" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reviewModalLabel">Escribir reseña</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <textarea id="reviewText" class="form-control border bordered-1 border-dark" rows="4" maxlength="200" placeholder="Escriba su reseña (max 200 caracteres)"></textarea>
                <div class="form-group mt-3">
                    <label for="rating">Puntaje:</label>

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
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button id="submitReview" type="button" class="btn btn-primary">Enviar</button>
            </div>
        </div>
    </div>
</div>
</div>



<h2 class="text-white m-3 p-2" style="background-color: #110257;">Reseñas</h2>
<div id="reviews" class="row row-cols-1 row-cols-md-2 row-cols-sm-3 col-12-lg col-6-md col-3-sm  m-2" style="max-width: 100%;" >
    <?php foreach ($reviews as $review) { ?>
        <div class="col ">
            <div class="card h-100 ">
                <div class="card-body">
                    <div class="d-flex">
                        <img class="rounded-circle shadow-1-strong me-3" src="<?php echo FRONT_ROOT . "Images/" . $review["pfp"]; ?>" alt="avatar" width="65" height="65" />
                        <div class="flex-grow-1 flex-shrink-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <p class="mb-1"><span class="font-weight-bold text-decoration-underline fs-5"><?php echo $review["name"] . " " . $review["lastname"] ?></span> <span class="small"><?php echo $review["timeStamp"] ?></span></p>
                                <div class="stars">
                                    <?php
                                    // Loop score
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $review['score']) {
                                            echo '<span class="star filled small">&#9733;</span>';
                                        } else {
                                            echo '<span class="star small">&#9733;</span>';
                                        }
                                    }
                                    ?>
                                    <span class="small"><?php echo $review['score'] . "/5" ?></span>
                                </div>
                            </div>

                            <div class="d-flex">
                                <p class="font-weight-bold mb-0 rounded p-1 flex-grow-1" style="background-color: #ebf2f7;"><?php echo $review["comment"]; ?></p>
                                <?php if (Session::GetLoggedUser() != null && Session::GetTypeLogged() == 'Models\Owner') {
                                    if (Session::GetLoggedUser()->getOwnerCode() == $review["ownerCode"]) { ?>
                                        <a class="btn btn-dis text-end rounded p-2" style="text-decoration: none;background-color: #d14d63;" href="<?php echo FRONT_ROOT . "Review/delete/" . $review["reviewCode"] ?>" data-msg="Delete the review?"><i class="fa-solid fa-trash my-2"></i></a>
                                <?php }
                                } ?>
                            </div>
                        </div>
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
    KeepersInteract.reConfirm();
    moduleReview.displayFieldReview();
    infoModule.bioEdit();
</script>

<?php require_once("footer.php"); ?>