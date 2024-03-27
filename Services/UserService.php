<?php

namespace Services;


use DAO\KeeperDAO;
use DAO\ownerDAO as OwnerDAO;
use DateTime;
use \Exception as Exception;
use Models\User as User;
Use Utils\Dates as Dates;

class UserService {
    private $ownerDAO;
    private $keeperDAO;


    public function __construct() {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
    }

    public function searchUsernameLogin($username)
    {
        
        $user = $this->ownerDAO->searchByUsername($username);

        if ($user === null) {
            $user = $this->keeperDAO->searchByUsername($username);
        }

        return $user;
    }



    public function searchEmailLogin($email)
    {
        
            $user = $this->ownerDAO->searchByEmail($email);
            if ($user === null) {
                $user = $this->keeperDAO->searchByEmail($email);
            }
        return $user;
    }

    public function checkPassword($typeUser, $email, $password)
    {

        if ($typeUser == "Models\Owner") {
            $pwdBd = $this->ownerDAO->getPassword($email);
        } else {
            $pwdBd = $this->keeperDAO->getPassword($email);
        }
        $rsp = password_verify($password, $pwdBd);
        return $rsp;
    }

    public function updateStatusUser($codeUserLogged)
    {
        $errorMsge = "";
        try{
            if(strpos($codeUserLogged,"OWN"))
        {
            $errorMsge = $this->ownerDAO->updateStatus($codeUserLogged);
        }else if (strpos($codeUserLogged,"KEP")){
            $errorMsge = $this->keeperDAO->updateStatus($codeUserLogged);
        }else{
            $errorMsge = "Error with the logging";
        }
        
        }catch(Exception $ex)
        {
            $resp = $errorMsge.' '.$ex->getMessage();
        }
        return $resp;
    }

    ///Checkeo de datos del registro 
    public function validateRegisterUser($typeUser, $email, $username, $password, $name, $lastname, $dni, $pfpInfo)
    {
        try {
            $msgResult = "";
            $msgeError = "";
            $user = new User();
            echo "SOY EMAIL VAIDATE".$email;
                        // ||||||||||||||||||||||||||||||||||||||||||||||||Filter email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
               throw new Exception("Not an Email");
            } else {
                
                $searched = $this->searchEmailLogin($email);
                if ($searched == null) {
                    
                    $email = trim($email);
                    $user->setEmail($email);
                } else {
                    throw new Exception("email already exists!");
                }
            }
            // ||||||||||||||||||||||||||||||||||||||||||||||||Filter username
            $regexUsername = "/^(?=.*[A-Za-z])(?!.*[\s!@])(?:\D*\d){0,4}[A-Za-z\d]{6,20}$/";
            if ($this->searchUsernameLogin($username) == null) //No repetido
            {
                if (preg_match($regexUsername, $username)) {

                    $username = trim($username);

                    $user->setUserName($username);
                } else {
                    throw new Exception("Not validate userName");
                }
            } else {
                throw new Exception("Username already exists!");
            }


            // ||||||||||||||||||||||||||||||||||||||||||||||||Filter password
            $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[a-zA-Z])(?!.*[!@ ])[a-zA-Z\d]{8,15}$/';
            $password = trim($password);
            if (preg_match($pattern, $password) && strlen($password) <= 15) {
                $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                $user->setPassword($hashedPass);
            } else {
                throw new Exception("Password does not match the requirements!");
            }


            $user->setStatus("inactive");

            //Check spaces for +2 words name
            $name_alpha_spaces = ctype_alpha(str_replace(' ', '', $name));
            if ($name_alpha_spaces) {
                $user->setName($name);
            } else {
                throw new Exception("Something does not match with our requirements check your name");
            }


            $lastname_alpha_spaces = ctype_alpha(str_replace(' ', '', $lastname));
            if ($lastname_alpha_spaces) {
                $user->setLastname($lastname);
            } else {
                throw new Exception("Something does not match with our requirements check your Lastname");
            }

            //No spaces
            $dni = trim($dni);
            $checkDni = ctype_digit($dni);
            if ($checkDni) {
                $user->setDni($dni);
            } else {
                throw new Exception("Something does not match with our requirements. Only numbers not spaces or dots allowed");
            }



            $pathToBD = "";
            if (isset($pfpInfo["pfp"]["tmp_name"]) && !empty($pfpInfo["pfp"]["tmp_name"])) {

                $nameFile = $pfpInfo["pfp"]["name"];
                $imgSize = $pfpInfo["pfp"]["size"];
                $dim = getimagesize($pfpInfo["pfp"]["tmp_name"]);
                $extension = explode(".", $nameFile);
                $typeImg = $pfpInfo["pfp"]["type"];
                $width = $dim[0];
                $height = $dim[1];



                $admittedTypes = ["image/jpg", "image/png", "image/jpeg", "image/bmp", "image/gif"];

                //MIME check
                $mime = mime_content_type($pfpInfo["pfp"]["tmp_name"]);

                if (!in_array($mime, $admittedTypes)) {
                    throw new Exception("Error with your photo,check the selected file");
                } else if ($imgSize > 3 * 1024 * 1024) {
                    throw new Exception("Not supported size");
                } else {

                    //Name from the clientfile
                    $name_pfp = $pfpInfo["pfp"]["name"];

                    //Tmp location on $pfp
                    $pfp = $pfpInfo["pfp"]["tmp_name"];

                    //Hashing
                    $hashedNameFile = hash_file('sha1', $pfp);

                    if ($typeUser == "owner") {
                        if (!file_exists(PFP_OWNERS)) {
                            mkdir(PFP_OWNERS, 0777, true);
                        }
                        $pathToSave =  PFP_OWNERS . $hashedNameFile . '.' . $extension[1];

                        $pathToBD = "PFPOwners/" . $hashedNameFile . '.' . $extension[1];
                    } else if ($typeUser == "keeper") {
                        if (!file_exists(PFP_KEEPERS)) {
                            mkdir(PFP_KEEPERS, 0777, true);
                        }

                        $pathToSave = PFP_KEEPERS . $hashedNameFile . '.' . $extension[1];

                        $pathToBD = "PFPKeepers/" . $hashedNameFile . '.' . $extension[1];
                    }
                    $user->setPfp($pathToBD);
                }
            } else {

                if ($user->getPfp() == null) {
                    throw new Exception("Registered user,upload your pfp later!");
                }
            }

            //Retorno un arreglo con el usuario validado,y la información para el seteo de PFP's
        $response = [
            "user" => $user,
            "pfp" => $pfp,
            "pathToDB" => $pathToBD,
            "pathToSave" => $pathToSave
        ];
        } catch (Exception $ex) {
            $response =  $ex->getMessage();
        }
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