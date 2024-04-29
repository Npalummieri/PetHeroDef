<?php 
include("header.php");

?>

<?php 
include("msgeDisplay.php");
?>
<a href="<?php echo FRONT_ROOT."Home/showDashboard" ?>" class="text-center text-white p-2 rounded bg-dark"><i class="fas fa-arrow-left "></i> DASHBOARD</a>
	<div class="container">
	<h2 class="text-center text-white bg-dark m-2 p-2 rounded">OWNER LIST</h2>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex ms-auto" action="<?php echo FRONT_ROOT."Owner/listOwnersFiltered" ?>" method=GET>
                    <input class="form-control me-2"  type="text" name="code" placeholder="Insert code, dni or email" aria-label="Search">
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
                    <th>Owner Code</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Name</th>
                    <th>Lastname</th>
                    <th>DNI</th>
                    <th>PFP</th>
                    <th>Bio</th>
                    <th>Suspension Date</th>
					<th>Action</th>
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
						echo '<a href="'. FRONT_ROOT."Images/".$owner->getPfp() . '" target="_blank">PFP</a>';
					} else 
					{
						echo 'PFP Not Found';
					} ?></td>
                    <td class="truncate-text"><?php  echo $owner->getBio(); ?></td>
                    <td><?php echo $owner->getSuspensionDate(); ?></td>
					<td style="vertical-align: middle;" class="d-flex justify-content-around align-items-center">
							<a class="btn-dis btn btn-primary m-2" data-msg = "Edit this record?" href="<?php echo FRONT_ROOT."Owner/showEditOwner/".$owner->getOwnerCode(); ?>">Edit</a> 
							<a class="btn-dis btn btn-danger m-2" data-msg = "Owner will be removed forever. Sure? Also all the pets related will be removed!" href="<?php echo FRONT_ROOT."Owner/deleteOwner/".$owner->getOwnerCode(); ?>">Delete</a>
							
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

