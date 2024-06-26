<?php

namespace Services;


use DAO\KeeperDAO;
use DAO\ownerDAO as OwnerDAO;
use DAO\notificationDAO as NotificationDAO;
use DateTime;
use \Exception as Exception;
use Models\User as User;
use Models\Status as Status;
use Utils\Dates as Dates;
use Utils\PHPMailer\Mailer as Mailer;

class UserService
{
    private $ownerDAO;
    private $keeperDAO;
    private $notificationDAO;
    private $mailer;

    public function __construct()
    {
        $this->ownerDAO = new OwnerDAO();
        $this->keeperDAO = new KeeperDAO();
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

    public function checkDni($dni)
    {
        $resp = $this->ownerDAO->checkDni($dni);
        if ($resp == 0) {
            $resp = $this->keeperDAO->checkDni($dni);
        }
        if ($resp == 1) {
            $resp = "Error already exists the DNI";
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
            } else {
                $errorMsge = "Error with the logging";
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
                        }
                        $this->mailer->sendResetPass($email, $pass);
                    }
                } else {
                    $resp = "Not valid DNI ";
                }
            } else {
                $resp = "Not existing email";
            }
        } catch (Exception $ex) {
            $resp = $ex->getMessage();
        }
        return $resp;
    }

    public function validateLogin($userField,$password)
    {
        try{
            if (filter_var($userField, FILTER_VALIDATE_EMAIL)) {
                $user = $this->searchEmailLogin($userField);
            } else if(filter_var($userField, FILTER_SANITIZE_SPECIAL_CHARS)) {
                $user = $this->searchUsernameLogin($userField);
            }else{
                $user = "Not validate characters on fields.Check it!";
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
                throw new Exception("Not an Email");
            } else {

                $searched = $this->searchEmailLogin($email);
                if ($searched == null) {

                    $email = trim($email);
                    $user->setEmail($email);
                } else {
                    throw new Exception("Email already exists!");
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
                    throw new Exception("<ul> <strong> Not validate userName </strong> <li>Between 6-20 characters</li> <li>At least 1 mayus,1 minus</li> <li>Not ! @ { } [] ? </li> <li>Not ! @ { } [] ? </li> <li>No spaces</li></ul> ");
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
                throw new Exception("<ul> <strong> Password does not match the requirements! </strong> <li>Between 8-15 characters</li> <li>At least 1 mayus,1 minus</li> <li>Not ! @ { } [] ? <li>No spaces</li> </ul> ");
            }


            $user->setStatus(Status::INACTIVE);

            //Check spaces for +2 words name
            $name_alpha_spaces = ctype_alpha(str_replace(' ', '', $name));
            if ($name_alpha_spaces) {
                $user->setName($name);
            } else {
                throw new Exception("Something does not match with our requirements.Check your name");
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
                if($this->checkDni($dni) == 0)
                {   
                    $user->setDni($dni);
                }else{
                    throw new Exception("Already exists this dni! Not allowed multiusers");
                }
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
                    throw new Exception("Upload your pfp !");
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
                $result = "Not valid dates";
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
                $result = "Not valid characters on bio";
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
                $notis = "No notifications at the moment";
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
                $notis = "No notifications to see";
            }
        }catch(Exception $ex)
        {
            $notis = $ex->getMessage();
        }
        return $notis;
    }
}
