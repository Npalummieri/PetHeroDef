<?php

namespace Services;

use Models\Coupon as Coupon;
use DAO\CouponDAO as CouponDAO;
use DAO\BookingDAO as BookingDAO;
use DAO\KeeperDAO as KeeperDAO;
use DAO\OwnerDAO as OwnerDAO;
use DAO\PetDAO as PetDAO;
use DAO\conversationDAO as ConversationDAO;
use Exception;
use DateTime as DateTime;
use Utils\PHPMailer\Mailer as Mailer;

class CouponService{

    private $couponDAO;
    private $bookingDAO;
    private $keeperDAO;
    private $ownerDAO;
    private $petDAO;
    private $conversationDAO;
    private $mailer;

    public function __construct()
    {
        $this->couponDAO = new CouponDAO();
        $this->bookingDAO = new BookingDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->ownerDAO = new OwnerDAO();
        $this->petDAO = new PetDAO();
        $this->conversationDAO = new ConversationDAO();
        $this->mailer = new Mailer();
    }

    public function generateCode() {
        // Genera un UUID único
        $uuid = uniqid('COU', true); // Utiliza 'KEP' como prefijo
    
        // Devuelve el ownerCode generado
        return $uuid;
    }

    public function srv_GenerateCouponToOwner($bookCode)
    {
        //Recheck status booking recently confirmed (passed by parameter)
        $booking = $this->bookingDAO->GetByCode($bookCode);

        if($booking->getStatus() == "confirmed")
        {
            $coupon = new Coupon();
            //En keeper tengo el precio por hora y en booking el totalPrice deberia setearlo en base a horas*price
            $coupon->setPrice($booking->getTotalPrice());
            $coupon->setBookCode($bookCode);
            $coupon->setCouponCode($this->generateCode());
            //Podria setear el price aca o hacer calculo para la jornada,es medio lo mismo 
            $resultInsert = $this->couponDAO->Add($coupon);
        }   
        return $resultInsert;     
    }

    public function srv_getCouponsByOwn($ownerCode)
    {

        //¿Deberia hacer algun filtro,validar algo,no se? Chequear que el ownerCode sea valido pero ya lo hace la BD quizá el formato del propio code
        $couponsArr = array();
        $couponsArr = $this->couponDAO->getAllCouponsByOwner($ownerCode);
        return $couponsArr;
    }

    public function srv_getInfoFullCoup($coupCode)
    {
        $coup = $this->couponDAO->getCouponByCode($coupCode);
        return $this->couponDAO->getFullInfoCoupon($coup->getCouponCode(),$coup->getBookCode());
    }

    //Algo Luhn
    public function validateCardNumber($cardnumber)
    {
        // Eliminar los espacios en blanco del número de tarjeta
        $cardNumberFormatted = str_replace(" ", "", $cardnumber);
        
        // Invertir la cadena de números
        $reversedCardNumber = strrev($cardNumberFormatted);
        
        // Inicializar la variable de suma
        $sum = 0;
        
        // Iterar sobre cada dígito del número de tarjeta
        for ($i = 0; $i < strlen($reversedCardNumber); $i++) {
            // Obtener el dígito actual
            $digit = (int)$reversedCardNumber[$i];
            
            // Verificar si el índice actual es par o impar
            $isEvenIndex = ($i % 2 == 0);
            
            // Si el índice actual es impar, multiplicar el dígito por 2
            if (!$isEvenIndex) {
                $digit *= 2;
                
                // Si el resultado es mayor que 9, restar 9
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            
            // Sumar el dígito al total
            $sum += $digit;
        }
        
        // El número de tarjeta es válido si la suma es un múltiplo de 10
        return ($sum % 10 == 0);
    }
    public function validateCardHolder($cardHolder)
    {
        $pattern = "/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]{2,25}(?:\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]+){1,5}(?:\s+[-\sa-zA-ZáéíóúÁÉÍÓÚüÜñÑ]+)?$/";

        return preg_match($pattern,$cardHolder);
    }


    //Es un poco redundante pq ya lo hago con Ajax/Jquery pero bueno,tambien se valida server-side
    public function srv_validateCoup($couponCode,$ccnum,$cardholder,$expDate,$ccv)
    {
        try
        {
            $today = new DateTime();
            $today->format('Y-m-d H:i:s');

            //transformar expDate a month/year
            $monthAndYear = explode("/",$expDate);
            //Array ( [0] => 03 [1] => 24 ) si por ej ingresamos 03/24
            $month = $monthAndYear[0];
            $year = $monthAndYear[1];

            $flag = false;


            $checkCc = $this->validateCardNumber($ccnum);

            $checkCh = $this->validateCardHolder($cardholder);

            if($month < 1 && $month > 12 && $month <= $today->format('m'))
            {
                throw new Exception("Impossible this month");
                //Medio hardcodeada lo de agregar el 20.$year pero es cierto que por param llega los ultimos 2 digitos del año
            }else if("20".$year < $today->format('Y'))
            {
                throw new Exception("Impossible this year");
            }

            //Literal es algo de programacion I pero vale anotarlo 
            /*  El % indica el comienzo de una especificación de formato.
                El 0 indica que si el número tiene menos de tres dígitos, se llenará con ceros a la izquierda.
                El 3 indica la anchura mínima del campo, en este caso, tres caracteres.
                La d indica que se trata de un número entero (decimal).*/
            $ccvStr = sprintf('%03d',$ccv); //Formateas el string con 3 digitos 

            $ccvLimitLen = substr($ccvStr, 0, 3); //Limitas a los primeros 3 digitos,en teoria no deberia haber más

            echo "Probando prev if triple &&";

            echo "VALOR CHECK CC";
            var_dump($checkCc);
            echo "VALOR CHECK checkCh";
            var_dump($checkCh);
            echo "VALOR ccvLimitLen";
            var_dump($ccvLimitLen);
            if($checkCc && $checkCh && $ccvLimitLen)
            {
                //Return 1,1 row modified to paidup
                $flag = $this->couponDAO->paidUpCoupon($couponCode);
               
                $fullCoup = $this->couponDAO->getFullInfoCoupon($couponCode);
                   

                    if($flag == 1){


                        //Sending email

                        $sended = $this->mailer->sendingEmail("nicoop910@gmail.com",$fullCoup,VIEWS_PATH."couponMail.php");
                        
                        
                        //Update the booking to paidup
                        $this->bookingDAO->modifyBookingStatus($fullCoup["bookCode"],"paidup");


                        $bookingPaidup = $this->bookingDAO->GetByCode($fullCoup["bookCode"]);

                        //Devuelve el idConver que ya existia o el generado recien,si ya devuelve el existente no genera
                        $idConver = $this->conversationDAO->generateConver($bookingPaidup->getKeeperCode(),$bookingPaidup->getOwnerCode());
                    }else{
                        $errorMsge = "We couldn't validate your pay!";
                    }
               
            }
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
            
            return $flag;
    }
}



?>