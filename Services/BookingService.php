<?php

namespace Services;

use \Exception as Exception;

use DAO\BookingDAO as BookingDAO;
use DAO\KeeperDAO as KeeperDAO;
use \DateTime as DateTime;
use Models\Booking as Booking;
use Services\CouponService as CouponService;
use Utils\Dates as Dates;


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
        // Genera un UUID único
        $uuid = uniqid('BOOK', true); // Utiliza 'KEP' como prefijo
    
        // Devuelve el ownerCode generado
        return $uuid;
    }



    public function srv_validateBooking($ownerCode,$initDate,$endDate,$petCode,$keeperCode,$typePet,$typeSize,$visitPerDay)
    {
        $resp = null;
        try{
            //Se revalida el tipo de mascota y se chequea el overbooking! Si puede pasar que haya un falso overbooking de varias reservas 'iguales' pero en pending
            if($this->keeperDAO->revalidateKeeperPet($keeperCode,$petCode) > 0  && $this->bookingDAO->checkDoubleBooking($ownerCode,$keeperCode,$petCode,$initDate,$endDate) == 0)
            {

                $booking = new Booking();
                $booking->setBookCode($this->generateCode());
                $booking->setOwnerCode($ownerCode);
                $booking->setKeeperCode($keeperCode);
                $keeper = $this->keeperDAO->searchByKeeperCode($keeperCode);

                //¿Validar existencia de PET? Incluso en la vista ya esta 'asegurado'
                $booking->setPetCode($petCode);

                if(Dates::validateAndCompareDates($initDate,$endDate) == 1 || Dates::validateAndCompareDates($initDate,$endDate) == 0 )
                {
                    $booking->setInitDate($initDate);
                    $booking->setEndDate($endDate);
                }else{
                    throw new Exception("Not valid dates");
                }
                
                
                
                $totalDays = Dates::calculateDays($initDate,$endDate);
                if($totalDays != null)
                {
                    $booking->setTotalDays($totalDays);
                }else{
                    throw new Exception("Something is wrong with dates");
                }
                

                if($keeper->getVisitPerDay() != $visitPerDay)
                {
                    throw new Exception("Visit per day has no coincidence");
                }else{
                    $booking->setVisitPerDay($visitPerDay);
                    $booking->setTotalPrice($this->srv_calculateBookingPrice($keeper->getPrice(),$totalDays,$visitPerDay));
                    echo "SOY BOOKING SERVICE";
                    var_dump($booking);
                    $resp = $this->bookingDAO->Add($booking);
                }   

                
            }else
            {
                $resp = false;
            }
            return $resp;
            
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
        return $resp;
    }

    //
    private function srv_calculateBookingPrice($price,$totalDays,$visitPerDay)
    {
        //Se asume que todas las variables tan saneadas del srv_validateBooking

        return $price * ($totalDays * $visitPerDay);
    }

    public function srv_getAllMyBookings($userCode)
    {
        try{
            $array =  $this->bookingDAO->getAllMyBookings($userCode);
        }catch(Exception $ex)
        {
            throw $ex;
        }
        return $array;
    }

    public function srv_getMyBookings($initDate,$endDate,$status,$keeperCode)
    {
        //Deberia validar en los service que las fechas sean coherentes por ej
        //Si alguna de las fechas esta vacia muestra unicamente por status
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
        try{

            $conf = $this->bookingDAO->confirmBooking($codeBook);
            echo "CONF" . $conf;
            if ($conf == 1) {
                $this->couponService->srv_GenerateCouponToOwner($codeBook);
            } else {
                $errorMsge = "Not posible to confirm this booking,check the ones you already confirmed";
            }
            
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
        
    }

 

    public function srv_getFullBooking($userCode,$bookCode)
    {
        try{
            $fullBook = $this->bookingDAO->getBookingByCodeLogged($userCode,$bookCode);

            return $fullBook;
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
        
    }

    public function srv_cancelBooking($bookCode)
    {
        try{
            $this->bookingDAO->cancelBooking($bookCode);
            
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
    }
}

?>