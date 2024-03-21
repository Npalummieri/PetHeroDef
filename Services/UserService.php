<?php

namespace Services;


use DAO\KeeperDAO;
use DAO\ownerDAO as OwnerDAO;
use DateTime;
use DateTimeInterface;
use \Exception as Exception;
use Models\User as User;
use Models\Keeper as Keeper;
Use Utils\Dates as Dates;

class UserService {
    private $ownerDAO;
    private $keeperDAO;


    public function __construct() {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
    }

    public function searchUsernameLogin($username) {
        $user = $this->ownerDAO->searchByUsername($username);

        if ($user === null) {
            $user = $this->keeperDAO->searchByUsername($username);
        }

        return $user;
    }

    

    public function searchEmailLogin($email) {
        $user = $this->ownerDAO->searchByEmail($email);

        if ($user === null) {
            $user = $this->keeperDAO->searchByEmail($email);
        }

        return $user;
    }

    public function checkPassword($typeUser,$email,$password)
    {
        try{
            //Revisar valor de TypeUser
            var_dump($typeUser);
            if($typeUser == "Models\Owner")
            {
                $pwdBd = $this->ownerDAO->getPassword($email);
            }else
            {
                $pwdBd = $this->keeperDAO->getPassword($email);
            }
            $rsp = password_verify($password,$pwdBd);
        }catch(Exception $ex)
        {
            throw $ex;
        }

        return $rsp;
    }

    public function updateStatusUser($codeUserLogged)
    {

        try{
            if(strpos($codeUserLogged,"OWN"))
        {
            $resp = $this->ownerDAO->updateStatus($codeUserLogged);
        }else if (strpos($codeUserLogged,"KEP")){
            $resp = $this->keeperDAO->updateStatus($codeUserLogged);
        }else{
            $resp = "Error with the logging";
        }
        
        }catch(Exception $ex)
        {
            $resp = $ex->getMessage();
        }
        return $resp;
    }

    ///Checkeo de datos del registro 
    public function validateRegisterUser($typeUser, $email, $username, $password, $name, $lastname, $dni, $pfpInfo)
    {
        try {
            $msgResult = "";
            $msgeError = 0;
            $user = new User();

            // ||||||||||||||||||||||||||||||||||||||||||||||||Filter email
            //Validacion de Email (Se puede implementar regex)
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $msgeError = "Not an Email";
            } else {
                $user = $this->searchEmailLogin($email);
                if($user == null)
                {
                    $email = trim($email);
                    $user->setEmail($email);
                }else{
                    $msgeError = "email already exists!";
                }
                
            }

            // ||||||||||||||||||||||||||||||||||||||||||||||||Filter username
            $regexUsername = "/^(?=.*[A-Za-z])(?!.*[\s!@])(?:\D*\d){0,4}[A-Za-z\d]{6,20}$/";
            if ($this->searchUsernameLogin($username) != null) //No repetido
            {

                if (preg_match($regexUsername, $username)) {
                    echo "HOLA?";
                    $username = trim($username);

                    $user->setUserName($username);
                } else {
                    $msgeError = "Not validate userName";
                }
            } else {
                $msgeError = "Username already exists!";
            }


            // ||||||||||||||||||||||||||||||||||||||||||||||||Filter password
            $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[a-zA-Z])(?!.*[!@ ])[a-zA-Z\d]{8,15}$/';
            $password = trim($password);
            if (preg_match($pattern, $password) && strlen($password) <= 15) {
                $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                $user->setPassword($hashedPass);
            } else {
                $msgeError = "Password does not match the requirements!";
            }


            $user->setStatus("inactive");

            //Saco los espacios e.g = Juan Pablo --> JuanPablo si ta ok seteo el nombre como venia,no admite ñ ni tildes
            $name_alpha_spaces = ctype_alpha(str_replace(' ', '', $name));
            if ($name_alpha_spaces) {
                $user->setName($name);
            } else {
                $msgeError = "Something does not match with our requirements check your name";
            }


            $lastname_alpha_spaces = ctype_alpha(str_replace(' ', '', $lastname));
            if ($lastname_alpha_spaces) {
                $user->setLastname($lastname);
            } else {
                $msgeError = "Something does not match with our requirements check your Lastname";
            }

            //Le sacamos los espacios por las dudas
            $dni = trim($dni);
            $checkDni = ctype_digit($dni);
            if ($checkDni) {
                $user->setDni($dni);
                //unset($dni);
            } else {
                $msgeError = "Something does not match with our requirements. Only numbers not spaces or dots allowed";
            }


            //Variable que almacena la ruta relativa que figura en BD
            $pathToBD = "";
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
                    throw new Exception("Error with your photo,check the selected file");
                } else if ($imgSize > 3 * 1024 * 1024) {
                    throw new Exception("Not supported size");
                } else {
                    //Tomo el nombre del archivo del lado del cliente
                    $name_pfp = $pfpInfo["pfp"]["name"];

                    //Tomo el archivo como tal (Donde esta almacenado temporalmente)
                    $pfp = $pfpInfo["pfp"]["tmp_name"];


                    //hasheo el archivo
                    $hashedNameFile = hash_file('sha1', $pfp);
                    if ($typeUser == "owner") {
                        if(!file_exists(PFP_OWNERS))
                            {
                                mkdir(PFP_OWNERS,0777,true);
                            }
                        $pathToSave =  PFP_OWNERS . $hashedNameFile . '.' . $extension[1];
                        //Ruta guardada en BD de la ruta 
                        $pathToBD = "PFPOwners/" . $hashedNameFile . '.' . $extension[1];
                    } else if ($typeUser == "keeper") {
                        if(!file_exists(PFP_KEEPERS))
                            {
                                mkdir(PFP_KEEPERS,0777,true);
                            }
                        //en el caso de keeper solo hasheo el archivo para posterior subida de la imagen
                        $pathToSave = PFP_KEEPERS . $hashedNameFile . '.' . $extension[1];
                        //Ruta guardada en BD de la ruta 
                        $pathToBD = "PFPKeepers/" . $hashedNameFile . '.' . $extension[1];
                    }
                    //Seteo la ruta donde despues moveré la PFP
                    $user->setPfp($pathToBD);
                }
            } else {

                //Contemplo que pudo no haber subido o hubo un error con la PFP pero el registro continuó
                if ($user->getPfp() == null) {
                    $errorMsge = "Registered user,upload your pfp later!";
                }
            }
        } catch (Exception $ex) {
            $ex->getMessage();
        }

        //Retorno un arreglo con el usuario validado,y la información para el seteo de PFP's
        $response = [
            "user" => $user,
            "pfp" => $pfp,
            "pathToDB" => $pathToBD,
            "pathToSave" => $pathToSave
        ];

        return $response;
    }

    

    

   

    public function getKeepersInfoAvai()
    {
       $arrayKeepAvai = $this->keeperDAO->getKeeperFullInfo();
       //Estas lineas de codigo me ahorro #4555

       return $arrayKeepAvai;
    }

    //Ahora
    public function srv_getKeepersInfoAvaiPag($pageNumber,$resultsPerPage)
    {
        //Validar que lleguen ints params
        $arrayKeeps = $this->keeperDAO->getKeepersPagination($pageNumber,$resultsPerPage);
        return $arrayKeeps;
    }


    public function srv_GetFilteredKeepers($initDate,$endDate,$size,$typePet,$visitPerDay,$pageNumber,$resultsPerPage)
    {
        //Filtrar form
        var_dump($_POST);
        try{
            $actualDate = new DateTime();

            $resultDates = Dates::validateAndCompareDates($initDate,$endDate);

            if($resultDates == 1 || $resultDates == 0)
            {
                $result = $this->keeperDAO->getKeepersByDates($initDate,$endDate,$size,$typePet,$visitPerDay,$pageNumber,$resultsPerPage);
            } 
        }catch(Exception $ex)
        {
            throw $ex;
        }
        return $result;
    }

}

?>