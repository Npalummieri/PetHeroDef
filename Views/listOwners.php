<?php 
require_once("header.php");

?>

<?php 
include("msgeDisplay.php");
?>
    <a href="<?php echo FRONT_ROOT."Home/showDashboard" ?>" class="btn text-center align-items-center text-white  rounded bg-dark"><i class="fas fa-arrow-left "></i><span> MENÚ</span> </a>
	<div class="container">
	<h2 class="text-center text-white bg-dark m-2 p-2 rounded">Lista de dueños</h2>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex ms-auto" action="<?php echo FRONT_ROOT."Owner/listOwnersFiltered" ?>" method=GET>
                    <input class="form-control me-2"  type="text" name="code" placeholder="Inserte codigo, dni o email" aria-label="Search">
                    <button class="btn btn-outline-light" type="submit">Buscar</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="table-responsive rounded p-2" style="background-color: #110257;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cod. Dueño</th>
                    <th>Email</th>
                    <th>Usuario</th>
                    <th>Estado</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DNI</th>
                    <th>FP</th>
                    <th>Bio</th>
                    <th>Suspension</th>
					<th>Acciones</th>
                </tr>
            </thead>
            <tbody style="vertical-align: middle;">
                <?php foreach($listOwns as $owner){ ?>
                <tr>
                    <td><?php echo $owner->getId() ?></td> 
                    <td><?php echo $owner->getOwnerCode(); ?> </td>
                    <td><?php echo $owner->getEmail(); ?></td>
                    <td><?php echo $owner->getUsername(); ?></td>
                    <td><?php echo $owner->getStatus(); ?></td>
                    <td><?php echo $owner->getName(); ?></td>
                    <td><?php echo $owner->getLastname(); ?></td>
                    <td><?php echo $owner->getDni(); ?></td>
                    <td>
					<?php 
                    $pfpPath = ROOT ."Images/".$owner->getPfp();
					
					if (file_exists($pfpPath) && $pfpPath != ROOT) 
					{
						echo '<a href="'. FRONT_ROOT."Images/".$owner->getPfp() . '" target="_blank">FP</a>';
					} else 
					{
						echo 'PFP Not Found';
					} ?></td>
                    <td class="truncate-text"><?php  echo $owner->getBio(); ?></td>
                    <td><?php echo $owner->getSuspensionDate(); ?></td>
					<td style="vertical-align: middle;" class="d-flex justify-content-around align-items-center">
							<a class="btn-dis btn btn-primary m-2" data-msg = "¿Editar registro?" href="<?php echo FRONT_ROOT."Owner/showEditOwner/".$owner->getOwnerCode(); ?>">Editar</a> 
							<a class="btn-dis btn btn-danger m-2" data-msg = "El dueño será borrado permanentemente junto con sus mascotas ¿Confirmar?" href="<?php echo FRONT_ROOT."Owner/deleteOwner/".$owner->getOwnerCode(); ?>">Eliminar</a>
							
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

