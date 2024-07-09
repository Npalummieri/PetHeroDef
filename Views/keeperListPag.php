<?php require_once("header.php");
include_once("nav.php");
require_once(VIEWS_PATH . "formKeepersList.php");
?>

<h2 class=" bg-dark rounded text-center text-white p-2">CUIDADORES</h2>
<div id="cururl" data-cururl="<?php echo FRONT_ROOT ?>" hidden></div>
<div class="container mt-2" id="contMain">
  <div class="row justify-content-center rounded round-3" style="background-color : #110257;">
    
    <?php if($allKeepers != null){
      foreach ($allKeepers as $keeper) {
      ?>
      <div class="col-lg-4 col-md-6 col-sm-12 p-2">
        <div class="card border-dark" style="background-color: #d6c9ae;">
          <div class="card-body text-center">

            <div class="d-inline text-center">
              <?php if ($keeper->getTypePet() == "dog") { ?>
                <i class="fa-solid fa-dog"></i>
              <?php } else if ($keeper->getTypePet() == "cat") { ?>
                <i class="fa-solid fa-cat"></i>
              <?php } ?>
            </div>

            <div class="mt-3 mb-2">
            <a  href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $keeper->getKeeperCode() ?>">
            <img src="<?php echo ($keeper->getPfp() == 0) ? FRONT_ROOT . 'Images/SysImages/default-avatar-icon-of-social-media-user-vector.jpg' : FRONT_ROOT . 'Images/' . $keeper->getPfp(); ?>" onerror="this.onerror=null;this.src='<?php echo FRONT_ROOT . 'Images/SysImages/default-avatar-icon-of-social-media-user-vector.jpg' ?>'" class="border-1 border-dark rounded-circle" width="120" height="120" alt="pfpKeeper">
          </a>
            </div>
            <p><strong>Puntaje : </strong> <?php if ($keeper->getScore() == 0) {
                          echo "Sin puntuación";
                        }
                        for ($i = 0; $i < $keeper->getScore(); $i++) {
                          echo '<i class="fa-solid fa-star"></i>';
                        } ?></p>
            <p><strong>Nombre:</strong> <?php echo $keeper->getName(); ?></p>
            <p><strong>Apellido:</strong> <?php echo $keeper->getLastname(); ?></p>
            <p><strong>Tamaño :</strong> <?php if($keeper->getTypeCare() === "big")
              { echo "Grande";}
              else if($keeper->getTypeCare() === "medium")
              {echo "Mediano";}
              else {echo "Pequeño";}
              ;?></p>
            <p><strong>Tipo :</strong> <?php echo $keeper->getTypePet() === "cat" ?  "Gato" :  "Perro"; ?></p>
            <p><strong>Precio:</strong> <?php echo $keeper->getPrice(); ?></p>
            <p><strong>Email:</strong> <?php echo $keeper->getEmail(); ?></p>


            <div class="mt-3 d-flex justify-content-center align-items-center">
              <div>
                <a class="btn btn-danger " href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $keeper->getKeeperCode() ?>">Hacer reserva</a>
                <a class="btn btn-success btn-availability" data-codekeeper="<?php echo $keeper->getKeeperCode() ?>">Ver Disponibilidad</a>
              </div>
              <div class="additional-info bg-dark bg-gradient" style="visibility: hidden;"></div>
            </div>
            <div class="text-end">
                <a class="btn btn-light ml-5" href="<?php echo FRONT_ROOT . 'Keeper/showProfileKeeper/' . $keeper->getKeeperCode() ?>"><i class="fa-solid fa-arrow-right"></i></a>
              </div>
          </div>
        </div>
      </div>
    <?php } }else{ ?> <h3 class="text-center mt-4 text-white"><strong>No hay cuidadores para mostrar actualmente</strong></h3>;
      <?php  }?>
  </div>
</div>

<div class="container mt-3">
  <div class="text-center">
    <?php for ($page = 1; $page <= $totalPages; $page++) { ?>
      <a href="<?php echo FRONT_ROOT . 'Home/showKeeperListPag/' . $page ?>" class="btn m-1 text-white" style="background-color: #110257;"><?php echo $page ?></a>
    <?php } ?>
  </div>
</div>

<script src="<?php echo JS_PATH . "formScripts.js" ?>"></script>
<script>
  KeepersInteract.getKeeperAvail();
</script>
<script>
  console.log("HOLA?")
</script>

<?php require_once("footer.php"); ?>