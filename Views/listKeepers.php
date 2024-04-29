<?php 
include("header.php");
?>


<?php 
include("msgeDisplay.php");
?>
<a href="<?php echo FRONT_ROOT."Home/showDashboard" ?>" class="text-center text-white p-2 rounded bg-dark"><i class="fas fa-arrow-left "></i> DASHBOARD</a>
<div class="container">
<h2 class="text-center text-white bg-dark m-2 p-2 rounded">KEEPER LIST</h2>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark rounded">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <form class="d-flex ms-auto" action="<?php echo FRONT_ROOT."Keeper/listKeepersFiltered" ?>" method=GET>
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
                    <th>Keeper Code</th>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Status</th>
                    <th>Name</th>
                    <th>Lastname</th>
                    <th>DNI</th>
                    <th>PFP</th>
                    <th>Type Care</th>
                    <th>Type Pet</th>
                    <th>Price</th>
                    <th>Score</th>
                    <th>Bio</th>
                    <th>Initial Date</th>
                    <th>End Date</th>
                    <th>Visit per Day</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody style="vertical-align: middle;">
                <?php foreach($listKeeps as $keeper){ ?>
                <tr>
                    <td><?php echo $keeper->getId() ?></td> 
                    <td><?php echo $keeper->getKeeperCode(); ?></td>
                    <td><?php echo $keeper->getEmail(); ?></td>
                    <td><?php echo $keeper->getUsername(); ?></td>
                    <td><?php echo $keeper->getStatus(); ?></td>
                    <td><?php echo $keeper->getName(); ?></td>
                    <td><?php echo $keeper->getLastname(); ?></td>
                    <td><?php echo $keeper->getDni(); ?></td>
                    <td><a href="<?php echo FRONT_ROOT."Images/".$keeper->getPfp(); ?>" target="_blank" >PFP</a></td>
                    <td><?php echo $keeper->getTypeCare(); ?></td>
                    <td><?php echo $keeper->getTypePet(); ?></td>
                    <td><?php echo $keeper->getPrice(); ?></td>
                    <td><?php echo $keeper->getScore(); ?></td>
                    <td class="text-truncate"><?php echo $keeper->getBio(); ?></td>
                    <td><?php echo $keeper->getInitDate(); ?></td>
                    <td><?php echo $keeper->getEndDate(); ?></td>
                    <td><?php echo $keeper->getVisitPerDay(); ?></td>
                    <td style="vertical-align: middle;" class="d-flex justify-content-around align-items-center">
                        <a class="btn-dis btn btn-primary m-2" data-msg = "Edit this record?" href="<?php echo FRONT_ROOT."Keeper/showEditKeeper/".$keeper->getKeeperCode() ?>">Edit</a> 
                        <a class="btn-dis btn btn-danger m-2" data-msg = "Keeper will be removed forever. Sure?" href="<?php echo FRONT_ROOT."Keeper/deleteKeeper/".$keeper->getKeeperCode() ?>">Delete</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<script src="<?php echo JS_PATH."formScripts.js"; ?>"></script>
<script>KeepersInteract.reConfirm();</script>
<script>
    // Agregar evento click al enlace del botón de toggle
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.querySelector('.sidebar');
        var toggleButton = document.getElementById('toggleButton');

        toggleButton.addEventListener('click', function() {
            sidebar.classList.toggle('active');
        });
    });
</script>
<?php 
include("footer.php");
?>
