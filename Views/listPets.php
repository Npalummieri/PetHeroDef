<?php 
require_once("header.php");
?>

<?php 
include("msgeDisplay.php");
?>
    <a href="<?php echo FRONT_ROOT."Home/showDashboard" ?>" class="btn text-center align-items-center text-white  rounded bg-dark"><i class="fas fa-arrow-left "></i><span> DASHBOARD</span> </a>
<div class="container">
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">PET LIST</h2>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex ms-auto" action="<?php echo FRONT_ROOT."Pet/listPetsFiltered" ?>" method=GET>
                    <input class="form-control me-2" type="text" name="code" placeholder="Insert code, dni or email" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="table-responsive rounded p-2" style="background-color: #110257;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cod. Mascota</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Raza</th>
                    <th>Tamaño</th>
                    <th>Edad</th>
                    <th>FP</th>
                    <th>Plan de vac.</th>
                    <th>Cod. Dueño</th>
                    <th>Video</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody style="vertical-align: middle;">
                <?php foreach($listPets as $pet){ ?>
                <tr >
                    <td><?php echo $pet->getId() ?></td> 
                    <td><?php echo $pet->getPetCode(); ?></td>
                    <td><?php echo $pet->getName(); ?></td>
                    <td><?php echo $pet->getTypePet(); ?></td>
                    <td><?php echo $pet->getBreed(); ?></td>
                    <td><?php echo $pet->getSize(); ?></td>
                    <td><?php echo $pet->getAge(); ?></td>
					
					<td>
					<?php 
    				$pfpPath = ROOT . "Images/" . $pet->getPfp();
					if (file_exists($pfpPath)) {
						echo '<a href="' .  FRONT_ROOT."Images/".$pet->getPfp() . '" target="_blank">FP</a>';
					} else {
							echo 'Image Not Found';
							} ?></td>
							
					<td>
							<?php 
                    $vaccPlanPath = ROOT . "Images/" . $pet->getVaccPlan();
					if (file_exists($vaccPlanPath)) {
						echo '<a href="' . FRONT_ROOT."Images/".$pet->getVaccPlan() . '" target="_blank">Plan de vac.</a>';
					} else {
							echo 'Image Not Found';
							} ?></td>
							
                    <td><?php echo $pet->getOwnerCode(); ?></td>
					
					<td>
					<?php 
                    $videoPath = ROOT . $pet->getVideo();
					if (file_exists($videoPath) && $videoPath != ROOT) 
					{
						echo '<a href="'. FRONT_ROOT.$pet->getVideo() . '" target="_blank">Video</a>';
					} else 
					{
						echo 'Video Not Found';
					} ?></td>
					
                    <td style="vertical-align: middle;">
					<div class="d-flex justify-content-between align-items-center">
							<a class="btn-dis btn btn-primary m-2" data-msg = "¿Editar registro?" href="<?php echo FRONT_ROOT."Pet/showAdminEditPet/".$pet->getPetCode(); ?>">Editar</a> 
							<a class="btn-dis btn btn-danger m-2" data-msg = "Registro será borrado permanentemente ¿Confirmar?" href="<?php echo FRONT_ROOT."Pet/deletePetAdm/".$pet->getPetCode(); ?>">Borrar</a>
							</div>
					</td>
					
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <div class="d-flex justify-content-end text-white" id="paginationDiv">
            <!-- <p>Visualizando <?php echo $actualPage ?> / <?php echo $page ?></p> -->
            <p>Registros totales : <?php echo $total ?></p>
        </div>
    </div>
</div>
<script src="<?php echo JS_PATH."formScripts.js"; ?>"></script>
<script>KeepersInteract.reConfirm();</script>
<?php 
require_once("footer.php");
?>
