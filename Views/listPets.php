<?php 
require_once("header.php");
?>

<?php 
include("msgeDisplay.php");
?>
<a href="<?php echo FRONT_ROOT."Home/showDashboard" ?>" class="text-center text-white p-2 rounded bg-dark"><i class="fas fa-arrow-left "></i> DASHBOARD</a>
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
                    <th>Pet Code</th>
                    <th>Name</th>
                    <th>Type of Pet</th>
                    <th>Breed</th>
                    <th>Size</th>
                    <th>Age</th>
                    <th>PFP</th>
                    <th>Vaccination Plan</th>
                    <th>Owner Code</th>
                    <th>Video</th>
                    <th>Action</th>
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
						echo '<a href="' .  FRONT_ROOT."Images/".$pet->getPfp() . '" target="_blank">PFP</a>';
					} else {
							echo 'Image Not Found';
							} ?></td>
							
					<td>
							<?php 
                    $vaccPlanPath = ROOT . "Images/" . $pet->getVaccPlan();
					if (file_exists($vaccPlanPath)) {
						echo '<a href="' . FRONT_ROOT."Images/".$pet->getVaccPlan() . '" target="_blank">Vaccplan</a>';
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
							<a class="btn-dis btn btn-primary m-2" data-msg = "Edit this record?" href="<?php echo FRONT_ROOT."Pet/showAdminEditPet/".$pet->getPetCode(); ?>">Edit</a> 
							<a class="btn-dis btn btn-danger m-2" data-msg = "Record will be removed forever. Sure?" href="<?php echo FRONT_ROOT."Pet/deletePetAdm/".$pet->getPetCode(); ?>">Delete</a>
							</div>
					</td>
					
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo JS_PATH."formScripts.js"; ?>"></script>
<script>KeepersInteract.reConfirm();</script>
<?php 
require_once("footer.php");
?>
