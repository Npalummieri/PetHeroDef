<?php

namespace Services;

use Models\Coupon as Coupon;
use DAO\CouponDAO as CouponDAO;
use DAO\BookingDAO as BookingDAO;
use DAO\conversationDAO as ConversationDAO;
use DAO\NotificationDAO as NotificationDAO;
use Exception;
use DateTime as DateTime;
use Utils\PHPMailer\Mailer as Mailer;
use Utils\Dates as Dates;
use Models\Status as Status;

class CouponService
{

    private $couponDAO;
    private $bookingDAO;
    private $conversationDAO;
    private $notificationDAO;
    private $mailer;

    public function __construct()
    {
        $this->couponDAO = new CouponDAO();
        $this->bookingDAO = new BookingDAO();
        $this->conversationDAO = new ConversationDAO();
        $this->notificationDAO = new NotificationDAO();
        $this->mailer = new Mailer();
    }

    public function generateCode()
    {

        $uuid = uniqid('COU', true);

        return $uuid;
    }

    public function srv_GenerateCouponToOwner($bookCode)
    {
        try {
            //Recheck status booking recently confirmed (passed by parameter)
            $booking = $this->bookingDAO->searchByCode($bookCode);

            if ($booking != null) {

                if (($this->couponDAO->getCoupCodeByBookCode($bookCode)) == " " || ($this->couponDAO->getCoupCodeByBookCode($bookCode)) == null ) {
                    $coupon = new Coupon();

                    $coupon->setPrice($booking->getTotalPrice());
                    $coupon->setBookCode($bookCode);
                    $coupon->setCouponCode($this->generateCode());

                    $resultInsert = $this->couponDAO->Add($coupon);
                } else {
                    $resultInsert = "Esta reserva ya tiene un cupon asociado";
                }
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
            $coup = $this->couponDAO->searchByCode($coupCode);
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

        return preg_match($pattern, $cardHolder);
    }


    //already checked with ajax/jquery also server-side
    public function srv_validateCoup($couponCode, $ccnum, $cardholder, $expDate, $ccv)
    {
        try {
            $today = new DateTime();
            $today->format('Y-m-d H:i:s');

            // expDate to month/year
            $monthAndYear = explode("/", $expDate);
            //Array ( [0] => 03 [1] => 24 ) ex: 03/24
            $month = $monthAndYear[0];
            $year = $monthAndYear[1];

            $flag = false;

            $checkCc = $this->validateCardNumber($ccnum);
            if ($checkCc == false) {
                throw new Exception("Numero de tarjeta no valido");
            }

            $checkCh = $this->validateCardHolder($cardholder);
            if ($checkCh == false) {
                throw new Exception("Titular no valido.");
            }
            if ($month < 1 && $month > 12 && $month <= $today->format('m')) {
                throw new Exception("Mes no valido");
            } else if ("20" . $year < $today->format('Y')) {
                throw new Exception("Año no valido");
            }

            $ccvStr = sprintf('%03d', $ccv); //3 digits 

            $ccvLimitLen = substr($ccvStr, 0, 3);

            $coupon = $this->couponDAO->searchByCode($couponCode);
            $bookingToCheck = $this->bookingDAO->searchByCode($coupon->getBookCode());

            if(Dates::currentCheck($bookingToCheck->getInitDate()) == null)
            {
                $this->bookingDAO->cancelBooking($coupon->getBookCode());
                $this->couponDAO->updateStatus($coupon->getCouponCode(),Status::CANCELLED);
                throw new Exception("Tarde para pagar esta reserva. Estado cancelado.");
            }
            if ($checkCc && $checkCh && $ccvLimitLen) {
                //Return 1,1 row modified to paidup
                $flag = $this->couponDAO->paidUpCoupon($couponCode);

                $fullCoup = $this->couponDAO->getFullInfoCoupon($couponCode);


                if ($flag == 1) {


                    //Sending email
                    //placeholder de mi email
                    $sended = $this->mailer->sendingEmail("nicoop910@gmail.com", $fullCoup, VIEWS_PATH . "couponMail.php");


                    //Update the booking to paidup
                    $this->bookingDAO->updateStatus($fullCoup["bookCode"], Status::PAIDUP);


                    $bookingPaidup = $this->bookingDAO->searchByCode($fullCoup["bookCode"]);


                    //get the idConver between keeper/owner or generate a new one from both 'parts'
                    $idConver = $this->conversationDAO->generateConver($bookingPaidup->getKeeperCode(), $bookingPaidup->getOwnerCode());
                } else {
                    throw new Exception("No se ha podido proceder con el pago. Verifique sus datos");
                }
            }
        } catch (Exception $ex) {
            $flag =  $ex->getMessage();
        }

        return $flag;
    }



    public function srv_declineCoupon($couponCode)
    {
        try {
            $coupon = $this->couponDAO->searchByCode($couponCode);
            $datesBooking = $this->bookingDAO->getDatesByCode($coupon->getBookCode());
            $booking = $this->bookingDAO->searchByCode($coupon->getBookCode());
            $initDateFormat = DateTime::createFromFormat("Y-m-d", $datesBooking["initDate"]);
            $currentDateTime = new DateTime();
            if ($initDateFormat > $currentDateTime) {

                $result = $this->couponDAO->declineCoupon($couponCode);
                $this->notificationDAO->generateNoti("Tu cuenta queda suspendida por 24 horas. Sus reservas y cupones seguirán activos pero no podrá hacer nuevas.",$booking->getOwnerCode(),$booking->getOwnerCode());
                $this->notificationDAO->generateNoti("La reserva {$booking->getBookCode()} ha sido cancelada por el dueño. Queda liberado para otra reserva.",$booking->getKeeperCode(),$booking->getKeeperCode());
            } else {
                $result = "No es posible cancelar con esta anticipación. (Minimo 24hs)";
            }
        } catch (Exception $ex) {
            $result = 'Error en la consulta: ' . $ex->getMessage() . ' en ' . $ex->getFile() . ':' . $ex->getLine();
        }
        // return $result;
    }

    public function srv_getCoupCodeByBook($bookCode)
    {
        try {

            $couponCode = $this->couponDAO->getCoupCodeByBookCode($bookCode);
        } catch (Exception $ex) {
           $couponCode = $ex->getMessage();
        }
        return $couponCode;
    }

    public function srv_checkCouponOwner($couponCode, $ownerCodeLogged)
    {
        try {
            $result = $this->couponDAO->checkCouponOwner($couponCode, $ownerCodeLogged);
            if ($result != 1) {
                $result = "Owner no coincide con la info del cupon.";
            }
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        return $result;
    }
	
	public function srv_getAllCoupons()
	{
		try{
			$coupList = $this->couponDAO->getAll();
		}catch(Exception $ex)
		{
			$coupList = $ex->getMessage();
		}
		return $coupList;
	}
	
	  public function srv_getCoupByCode($coupCode)
    {
        try {
            $couponCode = $this->couponDAO->searchByCode($coupCode);
        } catch (Exception $ex) {
           $couponCode = $ex->getMessage();
        }
        return $couponCode;
    }
	
		public function srv_editPrice($coupCode,$price)
	{
		try{
			
			$resp = $this->couponDAO->updatePrice($coupCode,$price);
			
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editStatus($coupCode,$status)
	{
		//echo "STATUS :".$status;
		$resp = null;
		try{
			$coupon = $this->srv_getCoupByCode($coupCode);
			$booking = $this->bookingDAO->searchByCode($coupon->getBookCode());
			if($coupon != null && $booking != null)
			{
			
				if($status == Status::PAIDUP)
				{
					$valDates = Dates::validateAndCompareDates($booking->getInitDate(),$booking->getEndDate());
					if(Dates::currentCheck($booking->getInitDate()) != null &&  ($valDates != -1 || $valDates != null))
					{
						$resultOB = $this->bookingDAO->checkOverBookingConfirm($booking);
						$resultFirstBreed = $this->bookingDAO->checkFirstBreed($booking);
						if($resultOB == 0)
						{
							if($resultFirstBreed == 1)
							{
								$resp = $this->bookingDAO->updateStatus($booking->getBookCode(),Status::PAIDUP);
								$this->srv_GenerateCouponToOwner($booking->getBookCode());
								$resp = $this->couponDAO->updateStatus($coupon->getCouponCode(),$status);
								
								$fullCoup = $this->couponDAO->getFullInfoCoupon($coupon->getCouponCode());
								//Sending email

								$sended = $this->mailer->sendingEmail("nicoop910@gmail.com", $fullCoup, VIEWS_PATH . "couponMail.php");


								//Update the booking to paidup
								$this->bookingDAO->updateStatus($fullCoup["bookCode"], Status::PAIDUP);


								$bookingPaidup = $this->bookingDAO->searchByCode($fullCoup["bookCode"]);


								//get the idConver between keeper/owner or generate a new one from both 'parts'
								$idConver = $this->conversationDAO->generateConver($bookingPaidup->getKeeperCode(), $bookingPaidup->getOwnerCode());
							}else
							{
								$resp = "La mascota no coincide con la raza de la primera reserva del dia";
							}
						}else{
						$resp = "No es posible actualizar estado. Superposicion de reservas";
						}
					}else{
						$resp = "No es posible actualizar estado. Fechas invalidas";
					}
					
				}else if($status == Status::PENDING) {
				$resp = $this->bookingDAO->updateStatus($booking->getBookCode(),Status::CONFIRMED);
				$this->srv_GenerateCouponToOwner($booking->getBookCode());
				$resp = $this->couponDAO->updateStatus($coupon->getCouponCode(),$status);
				}else{
					$resp = $this->couponDAO->updateStatus($coupon->getCouponCode(),$status);
				}
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		
		return $resp;
	}
	
	public function listCouponFiltered($code)
	{
        try {
        if (strpos($code, "BOOK") !== false || 
            strpos($code, "COU") !== false )
			{
				$coupList = $this->couponDAO->getFilteredCoupsAdm($code);
			}else {
				$coupList = "Not matching results.Remember to use BOOK,COU";
				}
        }catch(Exception $ex)
		{
			$coupList = $ex->getMessage();
		}
		return $coupList;
	}
}
