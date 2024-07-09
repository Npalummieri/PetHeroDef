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
        try {

            $keeper = $this->keeperDAO->searchByCode($keeperCode);

            $resp = null;

            if ($this->ownerDAO->searchByCode($ownerCode)->getStatus() == "suspended") //Si el usuario esta suspendido
            {
                $resp = "Tu cuenta esta suspendida";
            }

            if ($this->keeperDAO->revalidateKeeperPet($keeperCode, $petCode) <= 0) {
                $resp = "Fechas no validas";
            }

            if ($this->bookingDAO->checkDoubleBooking($ownerCode, $keeperCode, $petCode, $initDate, $endDate) != 0) {
                $resp = "Problema de reserva duplicada. Imposible registrar";
            }

            if (Dates::validateAndCompareDates($initDate, $endDate) <  0) {
                $resp = "Fechas no validas. Revisar que la fecha inicial no supere la final";
            }

            if ($keeper->getVisitPerDay() < $visitPerDay) {
                $resp = "Las visitas seleccionadas no corresponden con las del cuidador";
            }

            if (Dates::currentCheck($initDate) == null || Dates::currentCheck($endDate) == null) {
                $resp = "Fechas no validas";
            }

            //Ver si la lógica esta bien
            if ($initDate < $keeper->getInitDate() || $endDate > $keeper->getEndDate()) {
                $resp = "Fechas especificadas no corresponden con las fechas del cuidador.";
            }

            $totalDays = Dates::calculateDays($initDate, $endDate);
            $totalPriceCalc = null;
            if ($totalDays != null) {
                $totalPriceCalc = $this->srv_calculateBookingPrice($keeper->getPrice(), $totalDays, $visitPerDay);
            }else{
                $resp = "Error con los fechas. Intervalo invalido.";
            }
            
            if(ctype_digit($visitPerDay))
            {
                if($visitPerDay != "1" && $visitPerDay != "2")
                {
                    $resp = "Visitas por dia no validas";
                }
            }
            

            if ($resp == null) {
                $booking = new Booking();

                $booking->setBookCode($this->generateCode());
                $booking->setOwnerCode($ownerCode);
                $booking->setKeeperCode($keeperCode);
                $booking->setPetCode($petCode);
                $booking->setInitDate($initDate);
                $booking->setEndDate($endDate);
                $booking->setTotalDays($totalDays);
                $booking->setTotalPrice($totalPriceCalc);
                $booking->setVisitPerDay($visitPerDay);
                //Revisar dif checkOverBooking y doubleBooking no me acuerdo
                if ($this->bookingDAO->checkOverBooking($booking) != 1) {
                    $resp = "Conflicto de fechas y mascota selecccionada,verifique que no tenga otra reserva.";
                }else{
                    $resp = $this->bookingDAO->Add($booking);
                }
                
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

        try {
            if (empty($status)) {
                $status = null;
            }
            if (empty($initDate) || empty($endDate)) {
                $initDate = null;
                $endDate = null;
                $bookings = $this->bookingDAO->getMyBookings($initDate, $endDate, $status, $loggedCode);
            }
            if (Dates::validateAndCompareDates($initDate, $endDate) >= 0) {
                $bookings = $this->bookingDAO->getMyBookings($initDate, $endDate, $status, $loggedCode);
            }else{
                throw new Exception("Fechas no validas.");
            }
        } catch (Exception $ex) {
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
        return $this->bookingDAO->searchByCode($codeBook);
    }

    //booking had been confirmed -> generate coupon
    public function srv_confirmBooking($codeBook)
    {
        try {

            $booking = $this->bookingDAO->searchByCode($codeBook);
            if ($booking != null) {
                $conf = $this->bookingDAO->checkOverBookingConfirm($booking);
                $confTwo = $this->bookingDAO->checkFirstBreed($booking);

                if ($conf < 1 && $confTwo == "available" && Dates::currentCheck($booking->getInitDate()) != null && Dates::currentCheck($booking->getEndDate()) != null) {
                    $resp = $this->bookingDAO->updateStatus($booking->getBookCode(), Status::CONFIRMED);
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
                        if($result == 1)
                        {
                            $coupCodeGenerated = $this->couponService->srv_getCoupCodeByBook($codeBook);
                            $this->notificationDAO->generateNoti("Reserva confirmada. Cupon generado {$coupCodeGenerated} ve a 'Mis cupones' para verlo.",$booking->getOwnerCode(),$coupCodeGenerated);
                        }
                        
                    }
                } else {
                    if ($conf >= 1) {
                        $result = "Problema de reservas cruzadas. No es posible registrar";
                    } else if ($confTwo != "available") {
                        $result = "Revise la raza de la primera reserva confirmada! Raza : {$confTwo}";
                    }else if(Dates::currentCheck($booking->getInitDate()) == null || Dates::currentCheck($booking->getEndDate()) == null)
                    {
                        $this->bookingDAO->updateStatus($booking->getBookCode(), Status::CANCELLED);
                        $result = "Reserva expirada,tarde para confirmar.";
                    }
                }
            }
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        } 
        return $result;
    }

    public function srv_getFullBooking($userCode, $bookCode)
    {
        try {
            $fullBook = $this->bookingDAO->getBookingsByCodeLogged($userCode, $bookCode);
        } catch (Exception $ex) {
            $fullBook = " No se puede acceder. Intente más tarde. ";
        }
        return $fullBook;
    }


    public function srv_cancelBooking($bookCode)
    {
        try {
            $bookingSearched = $this->bookingDAO->searchByCode($bookCode);
            $datesBooking = $this->bookingDAO->getDatesByCode($bookCode);
            if (Dates::currentCheck($datesBooking["initDate"])) {
                $result = $this->bookingDAO->cancelBooking($bookCode);
                if($result == 1 && $bookingSearched != null)
                {
                    $this->notificationDAO->generateNoti("La reserva {$bookCode} ha sido cancelada.Revise 'Mis reservas' para detalles",$bookingSearched->getOwnerCode(),$bookCode);
                    $this->notificationDAO->generateNoti("La reserva {$bookCode} ha sido cancelada.Revise 'Mis reservas' para detalles",$bookingSearched->getKeeperCode(),$bookCode);
                }
            } else {
                $result = "Imposible cancelar con este tiempo de antelación (minimum 24hs)";
            }
        } catch (Exception $ex) {
            $result .= "No es posible cancelar esta reserva.";
            $ex->getMessage();
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
            $intervalDates = "Problema de fechas .Intente nuevamente luego de revisar";
            $ex->getMessage();
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
								$resp = $this->bookingDAO->updateStatus($bookCode,$status);
								//agregar generatecupon?
							}else{
							$resp = "Raza de la mascota no es igual a la primer reserva confirmada del dia";
							}
						
						}else{
						$resp = "No se ha podido actualizar el estado. Problema de solapamiento de reservas";
						}
					}else{
						$resp = "No es posible actualizar el estado. Problemas de fechas";
					}
					
				}else{
				$resp = $this->bookingDAO->updateStatus($bookCode,$status);
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
				$bookList = "Resultados no encontrados. Recuerde usar BOOK,OWN,PET o KEP";
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
            $resp = $this->notificationDAO->generateNoti("Tu reserva {$bookCode} cambió su estado a {$status}.Revisá 'Mis reservas' ",$receiver,$bookCode);
        }catch(Exception $ex)
        {
            $resp = $ex->getMessage();
        }
        return $resp;
    }
}
