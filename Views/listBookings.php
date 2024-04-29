<?php 
include("header.php");
?>

<?php 
include("msgeDisplay.php");
?>
<a href="<?php echo FRONT_ROOT."Home/showDashboard" ?>" class="text-center text-white p-2 rounded bg-dark"><i class="fas fa-arrow-left "></i> DASHBOARD</a>
<div class="container">
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">BOOKING LIST</h2>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex ms-auto" action="<?php echo FRONT_ROOT."Booking/listBookingFiltered" ?>" method=GET>
                    <input class="form-control me-2" type="text" name="code" placeholder="Insert code (BOOK,PET,OWNER,KEEP)" aria-label="Search">
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
                    <th>Book Code</th>
                    <th>Owner Code</th>
                    <th>Keeper Code</th>
                    <th>Pet Code</th>
                    <th>Init Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Total Price</th>
                    <th>Total Days</th>
                    <th>Visit Per Day</th>
                    <th>Creation Time</th>
                    <th>Action</th>
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
							<a class="btn-dis btn btn-primary m-2" data-msg = "Edit this record?" href="<?php echo FRONT_ROOT."Booking/showAdminEditBook/".$booking->getBookCode() ?>">Edit</a> 
							<a class="btn-dis btn btn-danger m-2" data-msg = "Record will be removed forever. Sure?" href="<?php echo FRONT_ROOT."Booking/cancelBooking/".$booking->getBookCode() ?>">Delete</a>
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
include("footer.php");
?>
