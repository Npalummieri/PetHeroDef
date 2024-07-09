<?php
require_once("header.php");
include_once("nav.php");
?>




    <h2 class="bg-dark rounded text-white text-center m-2">Editar perfil</h2>
    <div class="container rounded p-2 text-white" style="background-color: #110257;">
        <form action="<?php echo FRONT_ROOT."Owner/updateOwner" ?>" method="post" enctype="multipart/form-data">
        <div class="form-group m-3">
                <label for="profile-picture">Foto de perfil :</label>
                <input type="file" class="form-control" id="profile-picture" name="pfp">
            </div>
            <div class="form-group m-3">
                <label for="bio">Bio :</label>
                <textarea class="form-control" id="bio" name="bio"><?php echo $infoOwner->getBio(); ?></textarea>
            </div>
            <div class="form-group m-3 p-2 text-end">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </div>
        </form>
    </div>



<?php require_once("footer.php"); ?>