<?php 
require_once("header.php");
?>

<?php 
include("msgeDisplay.php");
?>
    <a href="<?php echo FRONT_ROOT."Home/showDashboard" ?>" class="btn text-center align-items-center text-white  rounded bg-dark"><i class="fas fa-arrow-left "></i><span> MENÚ</span> </a>
<div class="container">
    <h2 class="text-center text-white bg-dark m-2 p-2 rounded">Lista de reservas</h2>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex ms-auto" action="<?php echo FRONT_ROOT."Booking/listBookingFiltered" ?>" method=GET>
                    <input class="form-control me-2" type="text" name="code" placeholder="Inserte codigo (BOOK,PET,OWNER,KEEP)" aria-label="Search">
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
                    <th>Cod. Reserva</th>
                    <th>Cod. Dueño</th>
                    <th>Cod. Cuidador</th>
                    <th>Cod. Mascota</th>
                    <th>Desde</th>
                    <th>Hasta</th>
                    <th>Estado</th>
                    <th>Precio</th>
                    <th>Cant. Dias</th>
                    <th>Visitas/dia</th>
                    <th>Creado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody style="vertical-align: middle;">
                <?php foreach($listBooks as $booking){ ?>
                <tr>
                    <td><?php echo $booking->getId() ?></td> 
                    <td><?php echo $booking->getBookCode(); ?></td>
                    <td><?php echo $booking->getOwnerCode(); ?></td>
                    <td><?php echo $booking->getKeeperCode(); ?></td>
                    <td><?php echo $booking->getPetCode(); ?></td>
                    <td><?php echo $booking->getInitDate(); ?></td>
                    <td><?php echo $booking->getEndDate(); ?></td>
                    <td><?php echo $booking->getStatus(); ?></td>
                    <td><?php echo $booking->getTotalPrice(); ?></td>
                    <td><?php echo $booking->getTotalDays(); ?></td>
                    <td><?php echo $booking->getVisitPerDay(); ?></td>
                    <td><?php echo $booking->getTimeStamp(); ?></td>
                    <td style="vertical-align: middle;">
					<div class="d-flex justify-content-between align-items-center">
							<a class="btn-dis btn btn-primary m-2" data-msg = "¿Modificar registro?" href="<?php echo FRONT_ROOT."Booking/showAdminEditBook/".$booking->getBookCode() ?>">Editar</a> 
							<a class="btn-dis btn btn-danger m-2" data-msg = "El registro será ELIMINADO ¿Confirmar?" href="<?php echo FRONT_ROOT."Booking/cancelBooking/".$booking->getBookCode() ?>">Eliminar</a>
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
