<?php

namespace Services;

use \Exception as Exception;

use DAO\BookingDAO as BookingDAO;
use DAO\KeeperDAO as KeeperDAO;
use \DateTime as DateTime;
use Models\Booking as Booking;
use Services\CouponService as CouponService;
use Utils\Dates as Dates;
use \DateInterval as DateInterval;
use \DatePeriod as DatePeriod;


class BookingService{
    
    private $bookingDAO;
    private $keeperDAO;
    private $couponService;

    public function __construct()
    {
        $this->bookingDAO = new BookingDAO();
        $this->keeperDAO = new KeeperDAO();
        $this->couponService = new CouponService();
    }

    public function generateCode() {
        // UUID 
        $uuid = uniqid('BOOK', true); 

        return $uuid;
    }



    public function srv_validateBooking($ownerCode, $initDate, $endDate, $petCode, $keeperCode, $typePet, $typeSize, $visitPerDay)
    {
        $resp = null;
        try {
            if ($this->keeperDAO->revalidateKeeperPet($keeperCode, $petCode) > 0  && $this->bookingDAO->checkDoubleBooking($ownerCode, $keeperCode, $petCode, $initDate, $endDate) == 0) {

                $booking = new Booking();

                $booking->setBookCode($this->generateCode());
                $booking->setOwnerCode($ownerCode);
                $booking->setKeeperCode($keeperCode);
                $booking->setPetCode($petCode);

                $keeper = $this->keeperDAO->searchByKeeperCode($keeperCode);



                if (Dates::validateAndCompareDates($initDate, $endDate) == 1 || Dates::validateAndCompareDates($initDate, $endDate) == 0) {

                    $booking->setInitDate($initDate);
                    $booking->setEndDate($endDate);
                    $totalDays = Dates::calculateDays($initDate, $endDate);

                    if ($totalDays != null) {
                        $booking->setTotalDays($totalDays);
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
                        $resp = $this->bookingDAO->Add($booking);
                    }
                }
            } else {
                $resp = "Error with the dates and the pet selected!";
            }
        } catch (Exception $ex) {
            $resp = $ex->getMessage();
        }
        return $resp;
    }


    private function srv_calculateBookingPrice($price,$totalDays,$visitPerDay)
    {
        return $price * ($totalDays * $visitPerDay);
    }

    public function srv_getAllMyBookings($userCode)
    {
        try{
            $array =  $this->bookingDAO->getAllMyBookings($userCode);
        }catch(Exception $ex)
        {
            $array = $ex->getMessage();
        }
        return $array;
    }

    public function srv_getMyBookings($initDate,$endDate,$status,$keeperCode)
    {
        if(empty($initDate) || empty($endDate))
        {
            $initDate = null;
            $endDate = null;
        }
        return $this->bookingDAO->getMyBookings($initDate,$endDate,$status,$keeperCode);
    }

    public function srv_getBookingByStatus($userCode,$status)
    {
        return $this->bookingDAO->getMyBookingsByStatus($userCode,$status);
    }

    public function srv_getBookingByCode($codeBook)
    {
        return $this->bookingDAO->GetByCode($codeBook);
    }

    //Si la reserva se confirmo con exito -> se genera el cupon correspondiente
    public function srv_confirmBooking($codeBook)
    {
        try {

            $booking = $this->bookingDAO->GetByCode($codeBook);
            if ($booking != null) {
                $conf = $this->bookingDAO->checkOverBookingConfirm($booking);
                $confTwo = $this->bookingDAO->checkFirstBreed($booking);
                echo "CONF" . $conf;
                echo "CONFTWO".$confTwo;
                if ($conf <= 1 && ($confTwo != null || $confTwo != 0) ) {
                    $resp = $this->bookingDAO->modifyBookingStatus($booking->getBookCode(),"confirmed");
                    if($resp == 1)
                    {
                        $result = $this->couponService->srv_GenerateCouponToOwner($codeBook);
                    }
                } else {
                    $result = "Not posible to confirm this booking,check the ones you already confirmed";
                }
            } 
            
        } catch (Exception $ex) {
            $result = "Major problem ". $ex->getMessage();
        }//Retorna null si salio algo mal en el generateCoupon,retorna el coupCode si OK,retorna el msje de error si hay overBook o firstBreed mala
        return $result;
    }

 

    public function srv_getFullBooking($userCode,$bookCode)
    {
        try{
            $fullBook = $this->bookingDAO->getBookingByCodeLogged($userCode,$bookCode);

            
        }catch(Exception $ex)
        {
            $fullBook = "Cannot get this booking ".$ex->getMessage();
        }
        return $fullBook;
        
    }

    public function srv_cancelBooking($bookCode)
    {
        try{
            $result = $this->bookingDAO->cancelBooking($bookCode);
        }catch(Exception $ex)
        {
            $result .= "Not possible to cancel this booking ". $ex->getMessage();
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
            $intervalDates = "Problem at getting the interval ".$ex->getMessage();
        }

        return $intervalDates;
    }
}

?>