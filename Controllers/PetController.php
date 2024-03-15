<?php

namespace Controllers;

use \Exception as Exception;
use Models\Pet as Pet;
use DAO\PetDAO as PetDAO;
use Services\PetService as PetService;
use Utils\Session;
use Controllers\OwnerController as OwnerController;
class PetController{

    private $petDAO;
    private $petService;
    private $ownerController;

    public function __construct()
    {
        $this->petDAO = new PetDAO();
        $this->petService = new PetService();
        $this->ownerController = new OwnerController();
    }

    public function add($name, $typePet, $size, $breed, $vaccPlan, $video, $pfp, $age)
    {

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == 'Models\Owner') {
                $loggedUser = Session::GetLoggedUser();
                $msge = $this->petService->validatePet($name, $typePet, $loggedUser->getOwnerCode(), $size, $breed, $vaccPlan, $video, $pfp, $age);
                
                $this->ownerController->showMyPets($msge);
            }
        }
            
            // var_dump($_SESSION["loggedUser"]);
            // echo "POST :";
            // var_dump($_POST);
            // echo "filesss :";
            // var_dump($_FILES);
            
    }

    //Podria hacer una variable de scope general que sea $loggedUser = Session::GetLoggedUser(); ???
    public function updateVaccPlan($petCode,$ownerCode,$vaccPlan)
    {
        $loggedUser = Session::GetLoggedUser();
        $this->petService->srv_updateVacc($petCode,$ownerCode,$vaccPlan);
    }

    public function updateVideo($petCode,$ownerCode,$video)
    {
        $loggedUser = Session::GetLoggedUser();
        $this->petService->srv_updateVideo($petCode,$ownerCode,$video);
    } 

    public function updatePfp($petCode,$ownerCode,$pfp)
    {
        $loggedUser = Session::GetLoggedUser();
        $this->petService->srv_updatePfp($petCode,$ownerCode,$pfp);
    }

    public function updateAge($petCode,$ownerCode,$age)
    {
        $loggedUser = Session::GetLoggedUser();
        $this->petService->srv_updateAge($petCode,$ownerCode,$age);
    }

    public function showEditPet($petCode)
    {
        
        $sessionOk = 1;
        if (Session::IsLogged()) {
            
            if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedOwner = Session::GetLoggedUser();
                //Chequear que el loggedOwner sea efectivamente dueño
                $isOwner = $this->petService->srv_checkOwnerPet($petCode,$loggedOwner->getOwnerCode());
                echo "ISOWNER :";
                var_dump($isOwner);
                if($isOwner == 1)
                {
                    $pet = $this->petService->srv_getPet($petCode);
                    
                    require_once(VIEWS_PATH."editPet.php");
                }else{
                    $sessionOk = 0;
                }
            }else{
                $sessionOk = 0;
            }
        }else{
            $sessionOk = 0;
        }
        // if($sessionOk == 0)
        // {
        //     Session::DeleteSession();
        //     header("location: '../index.php' ");
        // }
        
    }

    public function updatePet($petCode,$pfp,$vaccPlan,$video,$size,$age)
    {

        if(Session::IsLogged())
        {
            if(Session::GetTypeLogged() == "Models\Owner")
            {
                $loggedOwner = Session::GetLoggedUser();
                 echo "POST :";
                 var_dump($_POST);
                $files = $_FILES;
                // echo "FILES :";
                // echo "<pre>";
                // var_dump($files);
                // echo "</pre>";
                echo "SOY PET CODE CONTROLLER".$petCode;
                $this->petService->srv_updatePetInfo($petCode,$loggedOwner->getOwnerCode(),$size,$files["vaccPlan"],$files["video"],$files["pfp"],$age);
            }
        }
        
    }
}

?>