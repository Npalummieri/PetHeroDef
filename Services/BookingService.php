<?php

namespace Services;

use \Exception as Exception;

use DAO\BookingDAO as BookingDAO;
use DAO\KeeperDAO as KeeperDAO;
use \DateTime as DateTime;
use Models\Booking as Booking;
use Services\CouponService as CouponService;


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

    public function checkOverBookingByDates($ownerCode,$initDate,$endDate,$initHour,$endHour,$petCode,$keeperCode,$typePet,$typeSize)
    {

        //¿¿¿¿¿¿¿Por que seteo todo en un obj y dps paso los mismos datos pero por getters,si ya me llegan por parametro... ?????
        $booking = new Booking();
        $booking->setOwnerCode($ownerCode);
        $booking->setKeeperCode($keeperCode);
        $booking->setPetCode($petCode);
        $booking->setInitDate($initDate);
        $booking->setEndDate($endDate);
        $booking->setInitHour($initHour);
        $booking->setEndHour($endHour);

        return $this->bookingDAO->checkOverBooking($booking->getKeeperCode(),$booking->getInitDate(),$booking->getEndDate(),$booking->getInitHour(),$booking->getEndHour(),$booking->getPetCode());
    }

    public function srv_validateBooking($ownerCode,$initHour,$endHour,$initDate,$endDate,$petCode,$keeperCode,$typePet,$typeSize)
    {
        //Falta hacer la real validacion
        try{
            //Se revalida el tipo de mascota y se chequea el overbooking! Si puede pasar que haya un falso overbooking de varias reservas 'iguales' pero en pending
            if($this->keeperDAO->revalidateKeeperPet($keeperCode,$petCode) > 0 && $this->bookingDAO->checkOverBooking($keeperCode,$initDate,$endDate,$initHour,$endHour,$petCode) > 0 && $this->bookingDAO->checkDoubleBooking($ownerCode,$keeperCode,$petCode,$initDate,$endDate,$initHour,$endHour) == 0)
            {

                $booking = new Booking();
                $booking->setBookCode($this->generateCode());
                $booking->setOwnerCode($ownerCode);
                $booking->setKeeperCode($keeperCode);
                $keeper = $this->keeperDAO->searchByKeeperCode($keeperCode);
                $booking->setTotalPrice($this->srv_calculateBookingPrice($initHour,$endHour,$keeper->getPrice()));
                $booking->setPetCode($petCode);
                $booking->setInitDate($initDate);
                $booking->setEndDate($endDate);
                $booking->setInitHour($initHour);
                $booking->setEndHour($endHour);
                $resp = $this->bookingDAO->Add($booking);
                
            }else
            {
                $resp = false;
            }
            
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }
        return $resp;
    }

    //
    public function srv_calculateBookingPrice($initHour, $endHour, $priceHour)
    {
        $initHourDT = DateTime::createFromFormat('H:i', $initHour);
        $endHourDT = DateTime::createFromFormat('H:i', $endHour);

        // Dif entre horarios,se genera un DateInterval por ->diff
        $difference = $initHourDT->diff($endHourDT);

        // Convierte la diferencia a minutos
        $totalMinutes = ($difference->h * 60) + $difference->i;

       return $totalMinutes/60;
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