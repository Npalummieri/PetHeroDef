<?php

namespace Services;

use \Exception as Exception;
use DAO\KeeperDAO as KeeperDAO;
use \DateInterval as DateInterval;
use \DatePeriod as DatePeriod;
use \DateTime as DateTime;
use Models\Keeper as Keeper;
use Models\User as User;
use Utils\Dates as Dates;

class KeeperService
{

    private $keeperDAO;

    private $userService;

    public function __construct()
    {
        $this->keeperDAO = new KeeperDAO();

        $this->userService = new UserService(null, $this->keeperDAO);
    }


    public function generateCode()
    {
        // Genera un UUID único
        $uuid = uniqid('KEP', true); // Utiliza 'KEP' como prefijo

        // Devuelve el ownerCode generado
        return $uuid;
    }


    public function validateKeeperFields($userInfo, $typePet, $typeCare, $initDate, $endDate, $price, $visitPerDay) //valida los campos y retorna ya no el user sino un Keeper
    {
        try {
            // echo "USER validatekeeperfield :";
            //var_dump($user);
            if (isset($typeCare)) {
                if ($typeCare != "big") {
                    if ($typeCare != "medium") {
                        if ($typeCare != "small") {
                            $msgeError = "Not size allowed";
                        }
                    }
                }
            } else {
                $msgeError = "Null typeCare";
            }

            if (isset($typePet)) {
                if ($typePet != "dog" && $typePet != "cat") {
                    $msgeError = "And that pet is a?";
                }
            }

            if (isset($price)) {
                if ($price < 1) {
                    $msgeError = "Nothing for free";
                }
            }

            if (!($visitPerDay == 1 || $visitPerDay == 2)) {
                $msgeError = "Not valid amount of day to visit";
            }


            if (!(Dates::validateDate($initDate) && Dates::validateDate($endDate))) {
                $msgeError = "Something wrong with the dates";
            } else {
                $validateDates = Dates::validateAndCompareDates($initDate, $endDate);
            }

            $keeper = new Keeper();

            if ($userInfo["user"] instanceof User) {
                echo "VALOR TYPEPET PRE FROMSUER";
                var_dump($typePet);
                if ($validateDates >= 0) {
                    $keeper = $keeper->fromUserToKeeper($userInfo["user"], $typePet, $typeCare, $initDate, $endDate, $price, $visitPerDay);
                    $keeper->setKeeperCode($this->generateCode());
                    $keeperCode = $this->keeperDAO->Add($keeper);
                }

                if ($keeperCode != null && $keeperCode != " ") {
                    move_uploaded_file($userInfo["pfp"], $userInfo["pathToSave"]);
                    $this->keeperDAO->updatePfp($keeperCode, $userInfo["pathToDB"]);
                }
            }
        } catch (Exception $ex) {
            //keeper takes the error msge
            $keeper = $msgeError . ' ' . $ex->getMessage();
        }
        return $keeper;
    }

    public function srv_updateKeeper($keeperLogged, $email, $pfpInfo, $bio, $price, $visitPerDay)
    {
        try {
            $error = 1;
            echo "SOY PFPINFO service";
            var_dump($pfpInfo);
            $keeperSearched = $this->keeperDAO->searchByKeeperCode($keeperLogged->getKeeperCode());
            $pfpToDelete = $keeperSearched->getPfp();
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
                    $pathToSave =  PFP_KEEPERS . $hashedNameFile . '.' . $extension[1];
                    //Ruta guardada en BD de la ruta 
                    $pathToBD = "PFPKeepers/" . $hashedNameFile . '.' . $extension[1];
                    echo "PATH TO SAVE PATH TO BD PFP";
                    var_dump($pathToSave);
                    var_dump($pathToBD);
                    var_dump($pfp);
                    $result = $this->keeperDAO->updatePfp($keeperLogged->getKeeperCode(), $pathToBD);
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
                $this->keeperDAO->updateEmail($keeperLogged->getKeeperCode(), $email);
            }

            if (isset($bio) && !empty($bio) && $bio != null) {
                if (preg_match('/[^a-z0-9!.,?=$]/i', $bio)) {
                    // Si la expresión regular encuentra algún caracter que no sea letra, dígito o signo de puntuación básico, la función devuelve false
                    $error = "error at bio";
                } else {
                    $this->keeperDAO->updateBio($keeperLogged->getKeeperCode(), $bio);
                }
            }

            if ($price != "") {
                if ($price > 0) {
                    $this->keeperDAO->updatePrice($keeperLogged->getKeeperCode(), $price);
                } else {
                    $error  = "Something's wrong with price";
                }
            }

            if ($visitPerDay == 1 || $visitPerDay == 2) {
                $this->keeperDAO->updateVisitDay($keeperLogged->getKeeperCode(),$visitPerDay);
            } else {
                $error = "Not valid amount of day to visit";
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $error;
    }

    public function srv_getKeeperByCode($keeperCode)
    {
        try {
            $keeper = $this->keeperDAO->searchByKeeperCode($keeperCode);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        return $keeper;
    }

    public function srv_getIntervalDates($keeperCode)
    {
        if (strpos($keeperCode, "KEP") !== false) {
            $dates = $this->keeperDAO->getDatesByCode($keeperCode);
        }

        //Generate Interval

        $intervalDates = array();

        // Convertir las fechas a objetos DateTime
        $initDateDT = new DateTime($dates["initDate"]);
        $endDateDT = new DateTime($dates["endDate"]);

        // Agregar un día al rango de fechas para incluir la fecha final
        $endDateDT->modify('+1 day');

        // Iterar sobre el intervalo de fechas y agregarlas al array
        $intervalObj = new DateInterval('P1D'); // Intervalo de 1 día
        $datePeriodObj = new DatePeriod($initDateDT, $intervalObj, $endDateDT);
        foreach ($datePeriodObj as $date) {
            $intervalDates[] = $date->format('Y-m-d'); // Formato YYYY-MM-DD
        }

        return $intervalDates;
    }

    public function srv_updateAvailability($keeperCode, $initDate, $endDate)
    {
        try {
            $result = Dates::validateAndCompareDates($initDate, $endDate);
            if ($result == 1 || $result == 0) {
                if(Dates::currentCheck($initDate) != null &&  Dates::currentCheck($endDate) != null)
                {
                    $result = $this->keeperDAO->updateAvailability($keeperCode, $initDate, $endDate);
                }else{
                    $result ="Not possible this date at this time";
                }
                
            } else {
                $result = "Not valid dates";
            }
        } catch (Exception $ex) {
            $result .=  $ex->getMessage();
        }
        return $result;
    }

    public function srv_getDates($keeperCode)
    {
        $dates = $this->keeperDAO->getDatesByCode($keeperCode);

        return $dates;
    }
}
