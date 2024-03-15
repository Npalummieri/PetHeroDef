<?php

namespace Services;


use \Exception as Exception;
use Models\User as User;
use Models\Owner as Owner;
use DAO\OwnerDAO as OwnerDAO;
use DAO\KeeperDAO as KeeperDAO;


class OwnerService{

    private $ownerDAO;
    private $keeperDAO;


    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
    }

    public function generateCode() {
        // Genera un UUID único
        $uuid = uniqid('OWN', true); // Utiliza 'OWN' como prefijo
    
        // Devuelve el ownerCode generado
        return $uuid;
    }

    public function srv_add(Owner $owner,$userInfo)
    {
        $errorMsge = 1;
        
        try{
            $ownerCode = $this->generateCode();
            $owner->setOwnerCode($ownerCode);
            var_dump($owner);
            $resultCode = $this->ownerDAO->Add($owner);

            echo "RESULT CODE :";
            
        if($resultCode  != null)
        {
            $updatepfp = $this->ownerDAO->updatePfp($resultCode,$userInfo["pathToDB"]);
            echo "updatePFP :";
            var_dump($resultCode);
            if($updatepfp == 1){
                move_uploaded_file($userInfo["pfp"],$userInfo["pathToSave"]);
            }else{
                $errorMsge = "Something's wrong with the pfp,already register";
            }
            
        }else{
            $errorMsge = "Failed register.Check it again";
        }
        }catch(Exception $ex){
            echo $ex->getMessage();
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
        try{
            $arrayKeepers = $this->keeperDAO->GetAll();
        }catch(Exception $ex)
        {
            $ex->getMessage();
        }
        return $arrayKeepers;
    }

    public function getAllInfoKeepers()
    {
        try{
            $arrayKeepers = $this->keeperDAO->getKeeperFullInfo();
        }catch(Exception $ex)
        {
            $ex->getMessage();
        }
        return $arrayKeepers;
    }

    public function srv_updateOwner($ownerLogged,$pfpInfo, $email, $bio)
    {
        try {
            $error = 1;
            echo "SOY PFPINFO service";
            var_dump($pfpInfo);
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


                //Deberia verificar la integridad de la img/archivo que no contenga nada raro
                $admittedTypes = ["image/jpg", "image/png", "image/jpeg", "image/bmp", "image/gif"];

                //El MIME es un id que valida que lo que se sube es una imagen como tal y no por ejemplo un archivo.exe con extension cambiada
                $mime = mime_content_type($pfpInfo["pfp"]["tmp_name"]);

                if (!in_array($mime, $admittedTypes)) {
                    $error = "Error with your photo,check the selected file";
                } else if ($imgSize > 3 * 1024 * 1024) {
                    $error = "Not supported size";
                } else {
                    //Tomo el nombre del archivo del lado del cliente
                    $name_pfp = $pfpInfo["pfp"]["name"];

                    //Tomo el archivo como tal (Donde esta almacenado temporalmente)
                    $pfp = $pfpInfo["pfp"]["tmp_name"];


                    //hasheo el archivo
                    $hashedNameFile = hash_file('sha1', $pfp);
                    $pathToSave =  PFP_OWNERS . $hashedNameFile . '.' . $extension[1];
                    //Ruta guardada en BD de la ruta 
                    $pathToBD = "PFPOwners/" . $hashedNameFile . '.' . $extension[1];

                    $result = $this->ownerDAO->updatePfp($ownerLogged, $pathToBD);
                    if ($result == 1) {
                        move_uploaded_file($pfp, $pathToSave);
                        if (unlink(IMG_PATH . $pfpToDelete)) {
                            $error = "El archivo se borró correctamente.";
                        } // else { Pq si el archivo no se pudo borrar es pq probablemente no existe
                            //     $error = "No se pudo borrar el archivo.";
                            // }
                    }
                }
            }



            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $msgeError = "Not an Email";
            } else {
                $email = trim($email);
                $this->ownerDAO->updateEmail($ownerLogged, $email);
            }

            if (isset($bio) && !empty($bio) && $bio != null) {
                if (preg_match('/[^a-z0-9!.,?=$]/i', $bio)) {
                    // Si la expresión regular encuentra algún caracter que no sea letra, dígito o signo de puntuación básico, la función devuelve false
                    $error = "error at bio";
                } else {
                    $this->ownerDAO->updateBio($ownerLogged, $bio);
                }
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $error;
    }
}
