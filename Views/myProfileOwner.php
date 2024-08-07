<?php
require_once("header.php");
include("nav.php");
use Utils\Session as Session;
?>
<div id="cururl" data-cururl="<?php echo FRONT_ROOT ?>" hidden ></div>
<?php require_once("msgeDisplay.php"); ?>
    <div class="container text-white" >
    <div>
		<?php if(Session::isLogged() && Session::GetTypeLogged() == "Models\Owner"){
			
			if(Session::GetLoggedUser()->getOwnerCode() == $infoOwner->getOwnerCode())
			{ ?> 
		<h2 class="bg-dark rounded text-white text-center p-2 mt-2">MI PERFIL</h2> 
		<?php }}else { ?>
			<h2 class="bg-dark rounded text-white text-center p-2 mt-2">PERFIL DE DUEÑO</h2>
		<?php } ?>
        </div>
        <div class="row align-items-center p-4 rounded" style="background-color: #110257;">
            <div class="col-lg-4">
                <div class="about-avatar text-center border-1 rounded-circle border-dark">
                    <img class="" src="<?php echo FRONT_ROOT . "Images/" . $infoOwner->getPfp() ?>" title="" alt="Pfp owner" >
                </div>
                <?php if(Session::GetTypeLogged() == "Models\Owner")
                    { 
                        if(Session::GetLoggedUser()->getOwnerCode() == $infoOwner->getOwnerCode())
                        { ?>
                        <div class="text-center mt-2">
                    <a href="<?php echo FRONT_ROOT . 'Owner/editProfile' ?>" class="btn btn-primary m-2 text-white">Editar perfil</a>
                    </div>
                    <?php }
                    } ?>
            </div>
			
            <div class="col-lg-8 ">
                <div class="about-list">
                <?php if(Session::GetTypeLogged() == "Models\Owner")
                    { 
                        if(Session::GetLoggedUser()->getOwnerCode() == $infoOwner->getOwnerCode())
                        { ?>
                    <div class="media">
                        <label>DNI :</label>
                        <p><?php echo $infoOwner->getDni(); ?></p>
                    </div>
                    <?php }
                    } ?>
                    <div class="media">
                        <label>Nombre de usuario :</label>
                        <p><?php echo $infoOwner->getUsername(); ?></p>
                    </div>
                    <div class="media">
                        <label>Nombre completo :</label>
                        <p><?php echo $infoOwner->getName() . " " . $infoOwner->getLastName();  ?></p>
                    </div>
                    <div class="media">
                        <label>E-mail :</label>
                        <p><?php echo $infoOwner->getEmail() ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label for="bio">Sobre mí :</label>
                    <p id="bio" data-userlogged="<?php echo $infoOwner->getOwnerCode(); ?>"><?php echo $infoOwner->getBio() == "" ? "Bio sin redactar." : $infoOwner->getBio() ; ?></p>
                    <div class="form-group" style="display: none;" id="bioEditor">
                        <textarea class="form-control" name="bio" id="bioTextarea" maxlength="200" placeholder="Escriba su bio (max 200 caracteres)"></textarea>
                    </div>
                    <?php if(Session::GetTypeLogged() == "Models\Owner")
                    { 
                        if(Session::GetLoggedUser()->getOwnerCode() == $infoOwner->getOwnerCode())
                        {  ?>
                            <button class="btn btn-primary m-2" id="editBioBtn">Editar bio</button>
                            
                            <?php if($infoOwner->getSuspensionDate() != "" && $infoOwner->getSuspensionDate() != null && $remsuspense != null)
                            { ?><div class="alert alert-danger" role="alert"><?php echo $remsuspense ?></div>

                        <?php } ?>   
                       <?php }
                    } ?>
                    
                    <button class="btn btn-success" id="saveBioBtn" style="display: none;">Guardar</button>
                    <button class="btn btn-secondary" id="cancelBioBtn" style="display: none;">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="<?php echo JS_PATH . "formScripts.js" ?>">

</script>
<script>
    infoModule.bioEdit();
</script>
<?php require_once("footer.php"); ?>