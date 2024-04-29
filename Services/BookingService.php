<?php

namespace Services;

use \Exception as Exception;

use DAO\BookingDAO as BookingDAO;
use DAO\KeeperDAO as KeeperDAO;
use DAO\OwnerDAO as OwnerDAO;
use \DateTime as DateTime;
use Models\Booking as Booking;
use Services\CouponService as CouponService;
use Utils\Dates as Dates;
use \DateInterval as DateInterval;
use \DatePeriod as DatePeriod;
use DAO\NotificationDAO as NotificationDAO;
use Models\Status as Status;

class BookingService
{

    private $bookingDAO;
    private $keeperDAO;
    private $notificationDAO;
    private $ownerDAO;
    private $couponService;

    public function __construct()
    {
        $this->bookingDAO = new BookingDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->ownerDAO = new OwnerDAO();
        $this->notificationDAO = new NotificationDAO();
        $this->couponService = new CouponService();
    }

    public function generateCode()
    {
        // UUID 
        $uuid = uniqid('BOOK', true);

        return $uuid;
    }



    public function srv_validateBooking($ownerCode, $initDate, $endDate, $petCode, $keeperCode, $typePet, $typeSize, $visitPerDay)
    {
        $resp = null;
        try {
            if($this->ownerDAO->searchByCode($ownerCode)->getStatus() != "suspended")
            {
               
            if ($this->keeperDAO->revalidateKeeperPet($keeperCode, $petCode) > 0  && $this->bookingDAO->checkDoubleBooking($ownerCode, $keeperCode, $petCode, $initDate, $endDate) == 0) {
                
                $booking = new Booking();

                $booking->setBookCode($this->generateCode());
                $booking->setOwnerCode($ownerCode);
                $booking->setKeeperCode($keeperCode);
                $booking->setPetCode($petCode);

                $keeper = $this->keeperDAO->searchByKeeperCode($keeperCode);


                if (Dates::validateAndCompareDates($initDate, $endDate) >= 0) {

                    if (Dates::currentCheck($initDate) && Dates::currentCheck($endDate)) {
                        $booking->setInitDate($initDate);
                        $booking->setEndDate($endDate);
                        $totalDays = Dates::calculateDays($initDate, $endDate);
                        if ($totalDays != null) {
                            $booking->setTotalDays($totalDays);
                        }
                    }
                } else {
                    $resp = "Not valid dates";
                }

                if ($keeper->getVisitPerDay() != $visitPerDay) {
                    $resp = "Visit per day has no coincidence";
                } else {

                    $booking->setVisitPerDay($visitPerDay);
                    $booking->setTotalPrice($this->srv_calculateBookingPrice($keeper->getPrice(), $totalDays, $visitPerDay));
					
                    if ($this->bookingDAO->checkOverBooking($booking) == 1) {
                        if ($initDate >= $keeper->getInitDate() && $endDate <= $keeper->getEndDate()) {
                            $resp = $this->bookingDAO->Add($booking);
                            
                        } else {
                            $resp = "Your dates doesn't match with the ones specified by the Keeper!";
                        }
                    }else{
                        $resp = "Overbooking problem!";
                    }
                }
            } else {
                $resp = "Error with the dates and the pet selected! Check that you already doesn't have another booking!";
            }
           
        }else{
            $resp = "Your account is suspended!";
        }
        } catch (Exception $ex) {
            $resp = $ex->getMessage();
        }
        return $resp;
    }


    private function srv_calculateBookingPrice($price, $totalDays, $visitPerDay)
    {
        return $price * ($totalDays * $visitPerDay);
    }

    public function srv_getAllMyBookings($userCode)
    {
        try {
            $array =  $this->bookingDAO->getAllMyBookings($userCode);
        } catch (Exception $ex) {
            $array = $ex->getMessage();
        }
        return $array;
    }

    public function srv_getMyBookings($initDate, $endDate, $status, $loggedCode)
    {

        try{
            if (empty($initDate) || empty($endDate)) {
                $initDate = null;
                $endDate = null;
            }
            if(empty($status))
            {
                $status = null;
            }

            $bookings = $this->bookingDAO->getMyBookings($initDate, $endDate, $status, $loggedCode);
        }catch(Exception $ex)
        {
            $bookings = $ex->getMessage();
        }

        return $bookings;
    }

    public function srv_getBookingByStatus($userCode, $status)
    {
        return $this->bookingDAO->getMyBookingsByStatus($userCode, $status);
    }

    public function srv_getBookingByCode($codeBook)
    {
        return $this->bookingDAO->GetByCode($codeBook);
    }

    //booking had been confirmed -> generate coupon
    public function srv_confirmBooking($codeBook)
    {
        try {

            $booking = $this->bookingDAO->GetByCode($codeBook);
            if ($booking != null) {
                $conf = $this->bookingDAO->checkOverBookingConfirm($booking);
                $confTwo = $this->bookingDAO->checkFirstBreed($booking);

                if ($conf < 1 && $confTwo == "available" && Dates::currentCheck($booking->getInitDate()) != null && Dates::currentCheck($booking->getEndDate()) != null) {
                    $resp = $this->bookingDAO->modifyBookingStatus($booking->getBookCode(), Status::CONFIRMED);
                    if ($resp == 1) {
                        $notifyTo = $this->bookingDAO->actionPostConfirm($booking->getBookCode(),$booking->getPetCode(),$booking->getInitDate(),$booking->getEndDate());
                        if($notifyTo != null)
                        {
                            //checkThis
                            foreach($notifyTo as $value)
                            {
                                $this->srv_notifyBookingChange($value["bookCode"],Status::CANCELLED,$value["ownerCode"]);
                            }
                        }
                        $result = $this->couponService->srv_GenerateCouponToOwner($codeBook);

                        $this->notificationDAO->generateNoti("Coupon generated,go to 'myCoupons' to check it!",$booking->getOwnerCode());
                    }
                } else {
                    if ($conf >= 1) {
                        $result = "Overbooking problem!";
                    } else if ($confTwo != "available") {
                        $result = "Check your first confirmed reservation of the day.You are restricted to that breed! BREED : {$confTwo}";
                    }else if(Dates::currentCheck($booking->getInitDate()) == null || Dates::currentCheck($booking->getEndDate()) == null)
                    {
                        $this->bookingDAO->modifyBookingStatus($booking->getBookCode(), Status::CANCELLED);
                        $result = "The booking expired,too late for confirmation";
                    }
                }
            }
        } catch (Exception $ex) {
            $result = "Major problem " . $ex->getMessage();
        } 
        return $result;
    }

    public function srv_getFullBooking($userCode, $bookCode)
    {
        try {
            $fullBook = $this->bookingDAO->getBookingByCodeLogged($userCode, $bookCode);
        } catch (Exception $ex) {
            $fullBook = "Cannot get this booking " . $ex->getMessage();
        }
        return $fullBook;
    }


    public function srv_cancelBooking($bookCode)
    {
        try {
            $bookingSearched = $this->bookingDAO->GetByCode($bookCode);
            $datesBooking = $this->bookingDAO->getDatesByCode($bookCode);
            if (Dates::currentCheck($datesBooking["initDate"])) {
                $result = $this->bookingDAO->cancelBooking($bookCode);
                if($result == 1 && $bookingSearched != null)
                {
                    $this->notificationDAO->generateNoti("The $bookCode has been cancelled.Check 'myBookings' for detailed info",$bookingSearched->getOwnerCode());
                    $this->notificationDAO->generateNoti("The $bookCode has been cancelled.Check 'myBookings' for detailed info",$bookingSearched->getKeeperCode());
                }
            } else {
                $result = "Not possible cancel.Too late (minimum 24hs)";
            }
        } catch (Exception $ex) {
            $result .= "Not possible to cancel this booking " . $ex->getMessage();
        }
        return $result;
    }

    public function srv_getIntervalBooking($bookingCode)
    {

        try {
            $dates = $this->bookingDAO->getDatesByCode($bookingCode);

            //Interval
            $intervalDates = array();

            // string dates to DateTime
            $initDateDT = new DateTime($dates["initDate"]);
            $endDateDT = new DateTime($dates["endDate"]);

            $endDateDT->modify('+1 day');

            // Iterates over the interval and add to array
            $intervalObj = new DateInterval('P1D'); // 1day interval
            $datePeriodObj = new DatePeriod($initDateDT, $intervalObj, $endDateDT);
            foreach ($datePeriodObj as $date) {
                $intervalDates[] = $date->format('Y-m-d'); // Format YYYY-MM-DD
            }
        } catch (Exception $ex) {
            $intervalDates = "Problem at getting the interval " . $ex->getMessage();
        }

        return $intervalDates;
    }
	
	public function srv_getAllBookings()
	{
		try{
			$bookList = $this->bookingDAO->GetAll();
		}catch(Exception $ex)
		{
			$bookList = $ex->getMessage();
		}
		return $bookList;
	}
	
	public function srv_editPrice($bookCode,$price)
	{
		try{
			
			$resp = $this->bookingDAO->modifyPrice($bookCode,$price);
			
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		return $resp;
	}
	
	public function srv_editStatus($bookCode,$status)
	{
		try{
			$booking = $this->srv_getBookingByCode($bookCode);
			if($booking != null)
			{
			
				if($status == Status::CONFIRMED)
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
								$resp = $this->bookingDAO->modifyBookingStatus($bookCode,$status);
								//agregar generatecupon?
							}else{
							$resp = "The pet doesn't match the breed of the first record!";
							}
						
						}else{
						$resp = "Not possible to update status to confirm.Overbooking problem";
						}
					}else{
						$resp = "Not possible to update status to confirm.Invalid dates";
					}
					
				}else{
				$resp = $this->bookingDAO->modifyBookingStatus($bookCode,$status);
				}
			}
		}catch(Exception $ex)
		{
			$resp = $ex->getMessage();
		}
		
		return $resp;
	}
	
	public function listBookingFiltered($code)
	{
        try {
        if (strpos($code, "BOOK") !== false || 
            strpos($code, "OWN") !== false || 
            strpos($code, "PET") !== false || 
            strpos($code, "KEP") !== false) 
			{
				$bookList = $this->bookingDAO->getFilteredBooksAdm($code);
			}else {
				$bookList = "Not matching results.Remember to use BOOK,OWN,PET or KEP";
				}
        }catch(Exception $ex)
		{
			$bookList = $ex->getMessage();
		}
		return $bookList;
	}

    public function srv_notifyBookingChange($bookCode,$status,$receiver)
    {
        try{
            $resp = $this->notificationDAO->generateNoti("Your booking {$bookCode} has been modified to {$status}.Check on  'My Bookings' ",$receiver);
        }catch(Exception $ex)
        {
            $resp = $ex->getMessage();
        }
        return $resp;
    }
}
