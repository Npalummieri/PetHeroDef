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
            $booking = $this->bookingDAO->GetByCode($bookCode);

            if ($booking != null) {

                if (($this->couponDAO->getCoupCodeByBook($bookCode)) == " " || ($this->couponDAO->getCoupCodeByBook($bookCode)) == null ) {
                    $coupon = new Coupon();

                    $coupon->setPrice($booking->getTotalPrice());
                    $coupon->setBookCode($bookCode);
                    $coupon->setCouponCode($this->generateCode());

                    $resultInsert = $this->couponDAO->Add($coupon);
                } else {
                    $resultInsert = "This booking already has a coupon associated";
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
                throw new Exception("Not validate credit number!");
            }

            $checkCh = $this->validateCardHolder($cardholder);
            if ($checkCh == false) {
                throw new Exception("Not validate card holder!");
            }
            if ($month < 1 && $month > 12 && $month <= $today->format('m')) {
                throw new Exception("Impossible this month");
            } else if ("20" . $year < $today->format('Y')) {
                throw new Exception("Impossible this year");
            }

            $ccvStr = sprintf('%03d', $ccv); //3 digits 

            $ccvLimitLen = substr($ccvStr, 0, 3);

            $coupon = $this->couponDAO->getCouponByCode($couponCode);
            $bookingToCheck = $this->bookingDAO->GetByCode($coupon->getBookCode());

            if(Dates::currentCheck($bookingToCheck->getInitDate()) == null)
            {
                $this->bookingDAO->cancelBooking($coupon->getBookCode());
                $this->couponDAO->updateStatusCoup($coupon->getCouponCode(),Status::CANCELLED);
                throw new Exception("Too late to pay this booking,is already cancelled");
            }
            if ($checkCc && $checkCh && $ccvLimitLen) {
                //Return 1,1 row modified to paidup
                $flag = $this->couponDAO->paidUpCoupon($couponCode);

                $fullCoup = $this->couponDAO->getFullInfoCoupon($couponCode);


                if ($flag == 1) {


                    //Sending email

                    $sended = $this->mailer->sendingEmail("nicoop910@gmail.com", $fullCoup, VIEWS_PATH . "couponMail.php");


                    //Update the booking to paidup
                    $this->bookingDAO->modifyBookingStatus($fullCoup["bookCode"], Status::PAIDUP);


                    $bookingPaidup = $this->bookingDAO->GetByCode($fullCoup["bookCode"]);


                    //get the idConver between keeper/owner or generate a new one from both 'parts'
                    $idConver = $this->conversationDAO->generateConver($bookingPaidup->getKeeperCode(), $bookingPaidup->getOwnerCode());
                } else {
                    throw new Exception("We couldn't validate your pay!");
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
            $coupon = $this->couponDAO->getCouponByCode($couponCode);
            $datesBooking = $this->bookingDAO->getDatesByCode($coupon->getBookCode());
            $booking = $this->bookingDAO->GetByCode($coupon->getBookCode());
            $initDateFormat = DateTime::createFromFormat("Y-m-d", $datesBooking["initDate"]);
            $currentDateTime = new DateTime();
            if ($initDateFormat > $currentDateTime) {

                $result = $this->couponDAO->declineCoupon($couponCode);
                $this->notificationDAO->generateNoti("Your account will be suspended for 48hs.Your active bookings/coupon will stay but cannot generate new ones",$booking->getOwnerCode());
            } else {
                $result = "Not possible cancel.Too late (minimum 24hs)";
            }
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        return $result;
    }

    public function srv_getCoupCodeByBook($bookCode)
    {
        try {

            $couponCode = $this->couponDAO->getCoupCodeByBook($bookCode);
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
                $result = "The owner doesn't has coincidence with the owner of coupon!";
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
            $couponCode = $this->couponDAO->getCouponByCode($coupCode);
        } catch (Exception $ex) {
           $couponCode = $ex->getMessage();
        }
        return $couponCode;
    }
	
		public function srv_editPrice($coupCode,$price)
	{
		try{
			
			$resp = $this->couponDAO->modifyPrice($coupCode,$price);
			
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
			$booking = $this->bookingDAO->GetByCode($coupon->getBookCode());
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
								$resp = $this->bookingDAO->modifyBookingStatus($booking->getBookCode(),Status::PAIDUP);
								$this->srv_GenerateCouponToOwner($booking->getBookCode());
								$resp = $this->couponDAO->updateStatusCoup($coupon->getCouponCode(),$status);
								
								$fullCoup = $this->couponDAO->getFullInfoCoupon($coupon->getCouponCode());
								//Sending email

								$sended = $this->mailer->sendingEmail("nicoop910@gmail.com", $fullCoup, VIEWS_PATH . "couponMail.php");


								//Update the booking to paidup
								$this->bookingDAO->modifyBookingStatus($fullCoup["bookCode"], Status::PAIDUP);


								$bookingPaidup = $this->bookingDAO->GetByCode($fullCoup["bookCode"]);


								//get the idConver between keeper/owner or generate a new one from both 'parts'
								$idConver = $this->conversationDAO->generateConver($bookingPaidup->getKeeperCode(), $bookingPaidup->getOwnerCode());
							}else
							{
								$resp = "The pet doesn't match the breed of the first record!";
							}
						}else{
						$resp = "Not possible to update status to confirm.Overbooking problem";
						}
					}else{
						$resp = "Not possible to update status to confirm.Invalid dates";
					}
					
				}else if($status == Status::PENDING) {
				$resp = $this->bookingDAO->modifyBookingStatus($booking->getBookCode(),Status::CONFIRMED);
				$this->srv_GenerateCouponToOwner($booking->getBookCode());
				$resp = $this->couponDAO->updateStatusCoup($coupon->getCouponCode(),$status);
				}else{
					$resp = $this->couponDAO->updateStatusCoup($coupon->getCouponCode(),$status);
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
