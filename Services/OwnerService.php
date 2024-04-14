<?php

namespace Services;


use \Exception as Exception;
use Models\Owner as Owner;
use DAO\OwnerDAO as OwnerDAO;
use DAO\KeeperDAO as KeeperDAO;


class OwnerService
{

    private $ownerDAO;
    private $keeperDAO;


    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
    }

    public function generateCode()
    {
        $uuid = uniqid('OWN', true);

        return $uuid;
    }

    public function srv_add(Owner $owner, $userInfo)
    {
        $errorMsge = 1;

        try {
            $ownerCode = $this->generateCode();
            $owner->setOwnerCode($ownerCode);

            $resultCode = $this->ownerDAO->Add($owner);


            if ($resultCode  != null) {
                $updatepfp = $this->ownerDAO->updatePfp($resultCode, $userInfo["pathToDB"]);

                if ($updatepfp == 1) {
                    move_uploaded_file($userInfo["pfp"], $userInfo["pathToSave"]);
                } else {
                    $errorMsge = "Something's wrong with the pfp,already register";
                }
            } else {
                $errorMsge = "Failed register error in DB.Contact support";
            }
        } catch (Exception $ex) {
            $errorMsge .=  $ex->getMessage();
        }
        return $errorMsge;
    }

    public function getByCode($code)
    {
        $ownerSearched = $this->ownerDAO->searchByCode($code);

        return $ownerSearched;
    }

    public function getAllKeepers()
    {
        try {
            $arrayKeepers = $this->keeperDAO->GetAll();
        } catch (Exception $ex) {
            $arrayKeepers = $ex->getMessage();
        }
        return $arrayKeepers;
    }

    public function getAllInfoKeepers()
    {
        try {
            $arrayKeepers = $this->keeperDAO->getKeeperFullInfo();
        } catch (Exception $ex) {
            $arrayKeepers = $ex->getMessage();
        }
        return $arrayKeepers;
    }

    public function srv_updateOwner($ownerLogged, $pfpInfo, $bio)
    {
        try {
            $error = 1;

            $ownerSearched = $this->ownerDAO->searchByCode($ownerLogged);
            $pfpToDelete = $ownerSearched->getPfp();
            if (isset($pfpInfo["pfp"]["tmp_name"]) && !empty($pfpInfo["pfp"]["tmp_name"])) {

                $nameFile = $pfpInfo["pfp"]["name"];
                $imgSize = $pfpInfo["pfp"]["size"];
                $typeImg = $pfpInfo["pfp"]["type"];
                $dim = getimagesize($pfpInfo["pfp"]["tmp_name"]);
                $width = $dim[0];
                $height = $dim[1];
                $extension = explode(".", $nameFile);


                $admittedTypes = ["image/jpg", "image/png", "image/jpeg", "image/bmp", "image/gif"];

                
                $mime = mime_content_type($pfpInfo["pfp"]["tmp_name"]);

                if (!in_array($mime, $admittedTypes)) {
                    $error = "Error with your photo,check the selected file";
                } else if ($imgSize > 3 * 1024 * 1024) {
                    $error = "Not supported size";
                } else {

                    $name_pfp = $pfpInfo["pfp"]["name"];
                   
                    $pfp = $pfpInfo["pfp"]["tmp_name"];


                    //hasheo el archivo
                    $hashedNameFile = hash_file('sha1', $pfp);
                    $pathToSave =  PFP_OWNERS . $hashedNameFile . '.' . $extension[1];
                    //Ruta guardada en BD de la ruta 
                    $pathToBD = "PFPOwners/" . $hashedNameFile . '.' . $extension[1];

                    $result = $this->ownerDAO->updatePfp($ownerLogged, $pathToBD);
                    if ($result == 1) {
                        move_uploaded_file($pfp, $pathToSave);
                        unlink(IMG_PATH . $pfpToDelete);
                    }
                }
            }

            if (isset($bio) && !empty($bio) && $bio != null) {
                if (preg_match('/[^a-z0-9!.,?=$]/i', $bio)) {
                    // Si la expresión regular encuentra algún caracter que no sea letra, dígito o signo de puntuación básico, la función devuelve false
                    $error = "error at bio";
                } else {
                    $error = $this->ownerDAO->updateBio($ownerLogged, $bio);
                }
            }
        } catch (Exception $ex) {
            $error =  $ex->getMessage();
        }
        return $error;
    }
}
