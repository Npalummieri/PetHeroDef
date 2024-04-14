<?php

namespace Controllers;


use Services\PetService as PetService;
use Utils\Session;

class PetController
{


    private $petService;
    public function __construct()
    {

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
                    Session::SetOkMessage("Pet successfully added");
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
                    Session::SetBadMessage("Not permitted editing");
                    header("location: " . FRONT_ROOT . "Home/showLoginView");
                }
            } else {
                Session::SetBadMessage("You are not an owner");
                header("location: " . FRONT_ROOT . "Home/showLoginView");
            }
        } else {
            Session::SetBadMessage("Log in please :)");
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
                    Session::SetOkMessage("Updated pet!");
                    header("location: " . FRONT_ROOT . "Owner/showMyPets");
                } else {
                    Session::SetBadMessage("Cannot update your pet correctly! Try again");
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
                    Session::SetOkMessage("Pet deleted!");
                    header("location: " . FRONT_ROOT . "Owner/showMyPets");
                } else {
                    Session::SetBadMessage($result);
                    header("location: " . FRONT_ROOT . "Owner/showMyPets");
                }
            } else {
                Session::SetBadMessage("U shouldn't be here!");
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
}
