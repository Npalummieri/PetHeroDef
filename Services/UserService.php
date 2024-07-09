<?php

namespace Services;


use DAO\KeeperDAO;
use DAO\OwnerDAO as OwnerDAO;
use DAO\notificationDAO as NotificationDAO;
use DAO\AdminDAO as AdminDAO;
use DateTime;
use \Exception as Exception;
use Models\User as User;
use Models\Status as Status;
use Models\Admin as Admin;
use Utils\Dates as Dates;
use Utils\PHPMailer\Mailer as Mailer;

class UserService
{
    private $ownerDAO;
    private $keeperDAO;
    private $adminDAO;
    private $notificationDAO;
    private $mailer;

    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->adminDAO = new AdminDAO();
        $this->notificationDAO = new NotificationDAO();
        $this->mailer = new Mailer();
    }



    public function searchUsernameLogin($username)
    {

        try {
            $user = $this->ownerDAO->searchByUsername($username);

            if ($user === null) {
                $user = $this->keeperDAO->searchByUsername($username);
            }
        } catch (Exception $ex) {
            $user = $ex->getMessage();
        }


        return $user;
    }



    public function searchEmailLogin($email)
    {

        $user = $this->ownerDAO->searchByEmail($email);
        if ($user === null) {
            $user = $this->keeperDAO->searchByEmail($email);
        }
        if($user === null)
        {
            $user = $this->adminDAO->searchByEmail($email);
        }
        return $user;
    }

    public function checkPassword($typeUser, $email, $password)
    {

        if ($typeUser == "Models\Owner") {
            $pwdBd = $this->ownerDAO->getPassword($email);
        } else if($typeUser == "Models\Keeper") {
            $pwdBd = $this->keeperDAO->getPassword($email);
        }else if($typeUser == "Models\Admin"){
            $pwdBd = $this->adminDAO->getPassword($email);
        }else{
            $rsp = "Contraseña erronea";
        }
        //Provisional para testear admin
        if(!($typeUser == "Models\Admin"))
        {   
            $rsp = password_verify($password, $pwdBd);
        }else{
            $rsp = $pwdBd === $password ? true : false;
        }
        
        return $rsp;
    }

    public function checkDni($dni)
    {
        
        $resp = $this->ownerDAO->checkDni($dni);
        if ($resp == 0) {
            $resp = $this->keeperDAO->checkDni($dni);
        }

        if ($resp == 1) {
            $resp = "¡Ese DNI ya esta registrado!";
        }

        return $resp;
    }

    public function srv_updateStatusUser($codeUserLogged, $status)
    {

        $errorMsge = "";
        try {
            if (strpos($codeUserLogged, "OWN") !== false) {
                $errorMsge = $this->ownerDAO->updateStatus($codeUserLogged, $status);
            } else if (strpos($codeUserLogged, "KEP") !== false) {
                $errorMsge = $this->keeperDAO->updateStatus($codeUserLogged, $status);
            } else if(strpos($codeUserLogged, "ADM")){
                $errorMsge = $this->adminDAO->updateStatus($codeUserLogged, $status);
            }else{
                $errorMsge = "Error en el logueo";
            }
        } catch (Exception $ex) {
            $errorMsge = $ex->getMessage();
        }
        return $errorMsge;
    }

    public function srv_resetPassword($email, $dni)
    {
        try {
            $user = $this->searchEmailLogin($email);

            if ($user != null) {
                $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[a-zA-Z])(?!.*[!@ ])[a-zA-Z\d]{8,15}$/';
                if ($user->getDni() == $dni) {

                    $randomString = substr(str_shuffle(str_repeat('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', 5)), 0, rand(8, 15));

                    // Verifica si cumple con las condiciones requeridas
                    if (preg_match($pattern, $randomString)) {

                        $pass = $randomString;
                        $hashedPass = password_hash($pass, PASSWORD_DEFAULT);

                        if (is_a($user, "Models\Owner")) {
                            
                            $resp = $this->ownerDAO->updatePassword($email, $hashedPass);
                        } else if (is_a($user, "Models\Keeper")) {

                            $resp = $this->keeperDAO->updatePassword($email, $hashedPass);
                        }else if(is_a($user, "Models\Admin"))
                        {
                            $resp = $this->adminDAO->updatePassword($email, $hashedPass);
                        }
                        $this->mailer->sendResetPass($email, $pass);
                    }
                } else {
                    $resp = "DNI no valido";
                }
            } else {
                $resp = "Mail inexistente";
            }
        } catch (Exception $ex) {
            $resp = $ex->getMessage();
        }
        return $resp;
    }

    public function validateLogin($userField)
    {
        try{
            if (filter_var($userField, FILTER_VALIDATE_EMAIL)) {
                $user = $this->searchEmailLogin($userField);
            } else if(filter_var($userField, FILTER_SANITIZE_SPECIAL_CHARS)) {
                $user = $this->searchUsernameLogin($userField);
            }else{
                $user = "Caracteres invalidos,verificar";
            }
        }catch(Exception $ex)
        {
            $user = $ex->getMessage();
        }
        return $user;
    }

    ///Checkeo de datos del registro 
    public function validateRegisterUser($typeUser, $email, $username, $password, $name, $lastname, $dni, $pfpInfo)
    {
        try {
            $msgResult = "";
            $msgeError = "";
            $user = new User();

            // ||||||||||||||||||||||||||||||||||||||||||||||||Filter email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email invalido");
            } else {

                $searched = $this->searchEmailLogin($email);
                if ($searched == null) {

                    $email = trim($email);
                    $user->setEmail($email);
                } else {
                    throw new Exception("Este email ya está en uso");
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
                    throw new Exception("<ul> <strong> Nombre de usuario invalido </strong> <li>Entre 6-20 caracteres</li> <li>Al menos 1 mayuscula y 1 minuscula</li> <li>Invalidados = ' ! @ { } [] ? ' </li> <li>Sin espacios</li></ul> ");
                }
            } else {
                throw new Exception("Nombre de usuario ya existe");
            }


            // ||||||||||||||||||||||||||||||||||||||||||||||||Filter password
            $pattern = "/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[a-zA-Z])(?!.*[!@ ])[a-zA-Z\d]{8,15}$/";
            $password = trim($password);
            if (preg_match($pattern, $password) && strlen($password) <= 15) {
                $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                $user->setPassword($hashedPass);
            } else {
                throw new Exception("<ul> <strong> La contraseña no coincide con los requerimientos  </strong> <li>Entre 8-15 caracteres</li> <li>Al menos 1 mayuscula y 1 minuscula</li> <li>Invalidados = ' ! @ { } [] ? ' </li> <li>Sin espacios</li> </ul> ");
            }


            $user->setStatus(Status::INACTIVE);

            //Check spaces for +2 words name
            $name_alpha_spaces = ctype_alpha(str_replace(' ', '', $name));
            if ($name_alpha_spaces) {
                $user->setName($name);
            } else {
                throw new Exception("Nombre no compatible. Verificar");
            }


            $lastname_alpha_spaces = ctype_alpha(str_replace(' ', '', $lastname));
            if ($lastname_alpha_spaces) {
                $user->setLastname($lastname);
            } else {
                throw new Exception("Apellido no compatible. Verificar");
            }

            //No spaces
            $dni = trim($dni);
            $checkDni = ctype_digit($dni);
            if ($checkDni) {
                if($this->checkDni($dni) == 0)
                {   
                    $user->setDni($dni);
                }else{
                    throw new Exception("DNI ya registrado. 1 usuario por persona");
                }
            } else {
                throw new Exception("DNI no valido,verifique que no contenga puntos ( . ) ni espacios en blanco");
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
                    throw new Exception("Error con el formato de la foto,verifiquelo");
                } else if ($imgSize > 3 * 1024 * 1024) {
                    throw new Exception("Tamaño excedido, maximo 5mb MAX");
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
                    throw new Exception("No tiene imagen de perfil. Cargue una por favor");
                }
            }


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
    public function srv_getKeepersInfoAvaiPag($pageNumber, $resultsPerPage)
    {
        //Validar que lleguen ints params
        $arrayKeeps = $this->keeperDAO->getKeepersPagination($pageNumber, $resultsPerPage);
        return $arrayKeeps;
    }


    public function srv_GetFilteredKeepers($initDate, $endDate, $size, $typePet, $visitPerDay, $pageNumber, $resultsPerPage)
    {
        try {
            $actualDate = new DateTime();

            $resultDates = Dates::validateAndCompareDates($initDate, $endDate);

            if ( ($resultDates == 1 || $resultDates) == 0 && (Dates::currentCheck($initDate) && Dates::currentCheck($endDate))) {
                $result = $this->keeperDAO->getKeepersByDates($initDate, $endDate, $size, $typePet, $visitPerDay, $pageNumber, $resultsPerPage);
            }else{
                $result = "Fechas invalidas";
            }
        } catch (Exception $ex) {
            $result  = $ex->getMessage();
        }
        return $result;
    }

    public function srv_updateBio($bio, $userCode)
    {
        $result = null;
        try {
            $bio = filter_var($bio, FILTER_SANITIZE_SPECIAL_CHARS);
            if(preg_match("/<[Aa-Zz]*?>|<\/[Aa-Zz]**?>/i",$bio)){
                $result = "Caracteres invalidos en la biografia,limitese a puntos (.) y comas (,)";
            }else{
                if (strpos($userCode, "OWN") !== false) {
                    $result = $this->ownerDAO->updateBio($userCode, $bio);
                } else if (strpos($userCode, "KEP") !== false) {
                    $result =  $this->keeperDAO->updateBio($userCode, $bio);
                }
            }
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }

        return $result;
    }

    public function srv_getNotis($codeUserLogged)
    {
        try{
            $notis = $this->notificationDAO->getAllByCode($codeUserLogged);
            if($notis == null || empty($notis))
            {
                $notis = "0 Notificaciones";
            }
        }catch(Exception $ex)
        {
            $notis = $ex->getMessage();
        }
        return $notis;
    }

    public function srv_resetNotis($codeUserLogged)
    {
        try{
            $notis = $this->notificationDAO->viewNotis($codeUserLogged);
            if($notis >= 1)
            {
                $notis = "0 Notificaciones";
            }
        }catch(Exception $ex)
        {
            $notis = $ex->getMessage();
        }
        return $notis;
    }


    //Si es 0 o 1 ok,si es 2 
    private function checkDniAdmin($dni)
    {
            $respAdm = $this->adminDAO->checkDni($dni);
            if($respAdm == 1)
            {
               $resp = "No puede registrar este dni como admin. Está en uso";
            }
            return $resp;

    }

    public function validateAdminRegister($email,$password,$dni)
    {
        try{
            $adm = new Admin();
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Email invalido");
            } else {

                $searched = $this->searchEmailLogin($email);
                if ($searched == null) {

                    $email = trim($email);
                    $adm->setEmail($email);
                } else {
                    throw new Exception("Este email ya está en uso");
                }
            }


            $dni = trim($dni);
            if (ctype_digit($dni)) {
                if ($this->checkDniAdmin($dni)) {
                    throw new Exception("Este DNI ya está en uso");
                }else{
                    $adm->setDni($dni);
                }
            } else {
                throw new Exception("DNI no valido,verifique que no contenga puntos ( . ) ni espacios en blanco ni caracteres alfabeticos (a-z)");
            }

            $password = trim($password);
            if (strlen($password) <= 15) {
                $hashedPass = password_hash($password, PASSWORD_DEFAULT);
                $adm->setPassword($hashedPass);
            } else {
                throw new Exception("Maximo 15 caracteres. Sin espacios en blanco");
            }
            


            $uuidAdm = uniqid('ADM', true);
            $adm->setAdminCode($uuidAdm);
            $adm->setStatus(Status::INACTIVE); 

            $response = $this->adminDAO->Add($adm);
        }catch(Exception $ex)
        {
            $response = $ex->getMessage();
        }

        return $response;
            
    }
}
