<?php

namespace Controllers;


use Services\PetService as PetService;

use Utils\Session;

class PetController
{


    private $petService;


    public function __construct(){

        $this->petService = new PetService();
    }

    public function add($name, $typePet, $size, $breed, $vaccPlan, $video, $pfp, $age)
    {

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == 'Models\Owner') {
                $files = $_FILES;
                $loggedUser = Session::GetLoggedUser();
                $msge = $this->petService->validatePet($name, $typePet, $loggedUser->getOwnerCode(), $size, $breed, $files["vaccPlan"], $files["video"], $files["pfp"], $age);
                if ($msge == null) {
                    Session::SetOkMessage("Mascota agregada");
                } else {
                    Session::SetBadMessage($msge);
                }
                header("location: " . FRONT_ROOT . "Owner/showMyPets");
            }
        }
    }

    public function updateVaccPlan($petCode, $ownerCode, $vaccPlan)
    {
        $loggedUser = Session::GetLoggedUser();
        $this->petService->srv_updateVacc($petCode, $ownerCode, $vaccPlan);
    }

    public function updateVideo($petCode, $ownerCode, $video)
    {
        $loggedUser = Session::GetLoggedUser();
        $this->petService->srv_updateVideo($petCode, $ownerCode, $video);
    }

    public function updatePfp($petCode, $ownerCode, $pfp)
    {
        $loggedUser = Session::GetLoggedUser();
        $this->petService->srv_updatePfp($petCode, $ownerCode, $pfp);
    }

    public function updateAge($petCode, $ownerCode, $age)
    {
        $loggedUser = Session::GetLoggedUser();
        $this->petService->srv_updateAge($petCode, $ownerCode, $age);
    }

    public function showEditPet($petCode)
    {

        $sessionOk = 1;
        if (Session::IsLogged()) {

            if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedOwner = Session::GetLoggedUser();

                $isOwner = $this->petService->srv_checkOwnerPet($petCode, $loggedOwner->getOwnerCode());
                if ($isOwner == 1) {
                    $pet = $this->petService->srv_getPet($petCode);

                    require_once(VIEWS_PATH . "editPet.php");
                } else {
                    Session::SetBadMessage("Edición no permitida");
                    header("location: " . FRONT_ROOT . "Home/showLoginView");
                }
            } else {
                Session::SetBadMessage("No puede realizar esta accion,unicamente dueño.");
                header("location: " . FRONT_ROOT . "Home/showLoginView");
            }
        } else {
            Session::SetBadMessage("Acceda con sus credenciales. Gracias");
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }

    public function updatePet($petCode, $pfp, $vaccPlan, $video, $size, $age)
    {

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedOwner = Session::GetLoggedUser();
                $files = $_FILES;
                $result = $this->petService->srv_updatePetInfo($petCode, $loggedOwner->getOwnerCode(), $size, $files["vaccPlan"], $files["video"], $files["pfp"], $age);

                if ($result == 1) {
                    Session::SetOkMessage("Mascota actualizada");
                    header("location: " . FRONT_ROOT . "Owner/showMyPets");
                } else {
                    Session::SetBadMessage("Error en la actualización. Revise e intente nuevamente");
                    $this->showEditPet($petCode);
                }
            }
        }
    }

    public function deletePet($petCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $ownerLogged = Session::GetLoggedUser();
                $result = $this->petService->srv_deletePet($ownerLogged->getOwnerCode(), $petCode);
                if ($result == 1) {
                    Session::SetOkMessage("Mascota eliminada");
                    header("location: " . FRONT_ROOT . "Owner/showMyPets");
                } else {
                    Session::SetBadMessage($result);
                    header("location: " . FRONT_ROOT . "Owner/showMyPets");
                }
            } else {
                Session::SetBadMessage("No tiene acceso");
                header("location: " . FRONT_ROOT . "Home/showLoginView");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }
    public function showPetProfile($petCode)
    {
        if (Session::IsLogged()) {
            $pet = $this->petService->srv_getProfilePet($petCode);
            require_once(VIEWS_PATH . "profilePet.php");
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }
	
		public function showListPets()
	{
		if(Session::IsLogged())
		{
			$checkAdmin = Session::GetLoggedUser();
			
			if($checkAdmin != null)
			{
				if((is_a(Session::GetLoggedUser(),"Models\Admin")))
				{
					$listPets = $this->petService->srv_getAllPets();
                    $total = count($listPets);

					require_once(VIEWS_PATH."listPets.php");
				}else{
					Session::DeleteSession();
					header("location: ".FRONT_ROOT."Home/showLoginView");
				}
			}else{
				Session::DeleteSession();
				header("location: ".FRONT_ROOT."Home/showLoginView");
			}
			
			}else{
				header("location: ".FRONT_ROOT."Home/showLoginView");
			}
	}
	
	public function showAdminEditPet($petCode)
	{
		if((is_a(Session::GetLoggedUser(),"Models\Admin")))
		{
			$pet = $this->petService->srv_getPet($petCode);
			require_once(VIEWS_PATH."adminEditPet.php");
		}
	}
	
	public function adminEditPet($petCode,$name = "",$breed = "",$size = "",$age = "",$typePet = "")
	{
		$edits = array(
			"name" => $name,
			"breed" => $breed,
            "size" => $size,
			"age" => $age
		);
        $resultFinal = null;
        $resultOkFinal = null;
		foreach ($edits as $field => $value) {
			if (!empty($value)) {
				$methodName = "srv_edit" . ucfirst($field);
                if($field == "breed")
                {
                    $result = $this->petService->$methodName($petCode,$typePet,$value);
                }else{

                    $result = $this->petService->$methodName($petCode, $value);
                }
				if($result == 1){
					$resultOkFinal .= " || ".ucfirst($field)." modificado con exito!";
					Session::SetOkMessage($resultOkFinal);
				}else if($result == 0)
                {
                    Session::SetBadMessage("");
                }else{
                    $resultFinal .= $result." - ".ucfirst($field)." no se pudo modificar <br>";
                    Session::SetBadMessage($resultFinal);
                }
				
			}
		}
		header("location: " . FRONT_ROOT . "Pet/showListPets");
	}
	
	public function listPetsFiltered($code ="")
	{
		
		if($code == "")
		{
			header("location: " . FRONT_ROOT . "Pet/showListPets");
		}
		$listPets = $this->petService->listPetFiltered($code);
        $total = count($listPets);
		if(is_array($listPets)){
			require_once(VIEWS_PATH."listPets.php");
		}else if($code != ""){
			Session::SetBadMessage($listPets);
			header("location: " . FRONT_ROOT . "Pet/showListPets");
		}			
	}
	
	public function deletePetAdm($petCode)
	{
		$pet = $this->petService->srv_getPet($petCode);
		$result = $this->petService->srv_deletePet($pet->getOwnerCode(), $pet->getPetCode());
		if($result == 1)
		{
			Session::SetOkMessage("Eliminado exitosamente");
			header("location: " . FRONT_ROOT . "Pet/showListPets");
		}else{
			Session::SetBadMessage($result);
			header("location: " . FRONT_ROOT . "Pet/showListPets");
		}
	}
}
