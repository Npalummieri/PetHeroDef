<?php

namespace Services;

use Models\Coupon as Coupon;
use DAO\CouponDAO as CouponDAO;
use DAO\BookingDAO as BookingDAO;
use DAO\conversationDAO as ConversationDAO;
use Exception;
use DateTime as DateTime;
use Utils\PHPMailer\Mailer as Mailer;

class CouponService{

    private $couponDAO;
    private $bookingDAO;
    private $conversationDAO;
    private $mailer;

    public function __construct()
    {
        $this->couponDAO = new CouponDAO();
        $this->bookingDAO = new BookingDAO();
        $this->conversationDAO = new ConversationDAO();
        $this->mailer = new Mailer();
    }

    public function generateCode() {

        $uuid = uniqid('COU', true);

        return $uuid;
    }

    public function srv_GenerateCouponToOwner($bookCode)
    {
        try {
            //Recheck status booking recently confirmed (passed by parameter)
            $booking = $this->bookingDAO->GetByCode($bookCode);

            if ($booking != null) {

                $coupon = new Coupon();

                $coupon->setPrice($booking->getTotalPrice());
                $coupon->setBookCode($bookCode);
                $coupon->setCouponCode($this->generateCode());

                $resultInsert = $this->couponDAO->Add($coupon);
            }
        } catch (Exception $ex) {
            $resultInsert = $ex->getMessage();
        }
        return $resultInsert;
    }

    public function srv_getCouponsByOwn($ownerCode)
    {
        try {
            $couponsArr = array();
            $couponsArr = $this->couponDAO->getAllCouponsByOwner($ownerCode);
            
        } catch (Exception $ex) {
            $couponsArr = $ex->getMessage();
        }
        return $couponsArr;
    }

    public function srv_getInfoFullCoup($coupCode)
    {
        try {
            $coup = $this->couponDAO->getCouponByCode($coupCode);
            $result = $this->couponDAO->getFullInfoCoupon($coup->getCouponCode(), $coup->getBookCode());
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        return $result;
    }

    //Algo Luhn
    private function validateCardNumber($cardnumber)
    {
        // Delete blankspaces
        $cardNumberFormatted = str_replace(" ", "", $cardnumber);
        
        //Reverse cardnumber
        $reversedCardNumber = strrev($cardNumberFormatted);
        
        $sum = 0;
        
        // Iterate each digit
        for ($i = 0; $i < strlen($reversedCardNumber); $i++) {

            $digit = (int)$reversedCardNumber[$i];
            
            // odd or even
            $isEvenIndex = ($i % 2 == 0);
            
            // if is odd *2
            if (!$isEvenIndex) {
                $digit *= 2;
                
                
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            

            $sum += $digit;
        }
        
        return ($sum % 10 == 0);
    }

    private function validateCardHolder($cardHolder)
    {
        $pattern = "/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]{2,30}(?:\s+[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ]+){1,5}(?:\s+[-\sa-zA-ZáéíóúÁÉÍÓÚüÜñÑ]+)?$/";

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
            //Array ( [0] => 03 [1] => 24 ) ex: 03/24
            $month = $monthAndYear[0];
            $year = $monthAndYear[1];

            $flag = false;


            $checkCc = $this->validateCardNumber($ccnum);
            if($checkCc == false)
            {
                throw new Exception("Not validate credit number!");
            }
            
            $checkCh = $this->validateCardHolder($cardholder);
            if($checkCh == false)
            {
                throw new Exception("Not validate card holder!");
            }
            if($month < 1 && $month > 12 && $month <= $today->format('m'))
            {
                throw new Exception("Impossible this month");
                
            }else if("20".$year < $today->format('Y'))
            {
                throw new Exception("Impossible this year");
            }

            $ccvStr = sprintf('%03d',$ccv); //3 digits 

            $ccvLimitLen = substr($ccvStr, 0, 3);


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


                        //get the idConver between keeper/owner or generate a new one from both 'parts'
                        $idConver = $this->conversationDAO->generateConver($bookingPaidup->getKeeperCode(),$bookingPaidup->getOwnerCode());

                    }else{
                        throw new Exception("We couldn't validate your pay!");
                    }
               
            }
        }catch(Exception $ex)
        {
            $flag =  $ex->getMessage();
        }
            
            return $flag;
    }


    //Validar lo de 24hs
    public function srv_declineCoupon($couponCode)
    {
        try{
            $coupon = $this->couponDAO->getCouponByCode($couponCode);
            $datesBooking = $this->bookingDAO->getDatesByCode($coupon->getBookCode());
            $initDateFormat = DateTime::createFromFormat("Y-m-d",$datesBooking["initDate"]);
            $currentDateTime = new DateTime();
            if($initDateFormat > $currentDateTime )
            {
                //este decline coupon deberia medio en cascada cancelar el booking a cancelled too
                $result = $this->couponDAO->declineCoupon($couponCode);
            }else{
                $result = "Not possible cancel.Too late (minimum 24hs)";
            }
        }catch(Exception $ex)
        {
            $result = $ex->getMessage();
        }
        return $result;
    }

    public function srv_getCoupCodeByBook($bookCode)
    {
        try {

            $couponCode = $this->couponDAO->getCoupCodeByBook($bookCode);
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        return $couponCode;
    }

    public function srv_checkCouponOwner($couponCode,$ownerCodeLogged)
    {
        try{
            $result = $this->couponDAO->checkCouponOwner($couponCode,$ownerCodeLogged);
            if($result != 1)
            {
                $result = "The owner doesn't has coincidence with the owner of coupon!";
            }
        }catch(Exception $ex)
        {
            $result = $ex->getMessage();
        }
        return $result;
    }
}



?>