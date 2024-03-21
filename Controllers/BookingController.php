<?php 

namespace Controllers;

use Models\Booking as Booking;
use Models\Keeper as Keeper;
use Models\Owner as Owner;
use Services\BookingService as BookingService;
use Services\KeeperService as KeeperService;
use Services\AvailabilityService as AvailabilityService;
use Services\PetService as PetService;
use Services\CouponService as CouponService;
use Utils\Session as Session;


class BookingController{

    private $bookingService;
    private $petService;
    private $couponService;
    private $keeperService;
    public function __construct()
    {
        $this->bookingService = new BookingService();
        $this->petService = new PetService();
        $this->couponService = new CouponService();
        $this->keeperService = new KeeperService();
    }

    public function addBooking($initDate,$endDate,$petCode,$keeperCode,$typePet,$typeSize,$visitPerDay)
    {
        var_dump($_POST);
        if (Session::IsLogged() && Session::GetTypeLogged() == 'Models\Owner') {
            $userLogged = $_SESSION["loggedUser"];

            $resp = $this->bookingService->srv_validateBooking($userLogged->getOwnerCode(),$initDate,$endDate,$petCode,$keeperCode,$typePet,$typeSize,$visitPerDay);
        } else {
            Session::DeleteSession();
            header("location: ../index.php");
        }

        if($resp != null && $resp != false){

            $this->showMyBookings($resp);
        }else{
            $resp = "ERROR AT CREATING BOOK.CHECK THE DATA";
            $this->showBookCreate($keeperCode,$resp);
        }
        
    }
    public function showBookCreate($keeperCode,$message=" ")
    {

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $keeper = $this->keeperService->srv_getKeeperByCode($keeperCode);
            }
        }
        if ($keeper != null) {
            $keeperToCheck = $keeperCode;
            $typePet = $keeper->getTypePet();
            $typeSize = $keeper->getTypeCare();
        }

        require_once(VIEWS_PATH . "registerBooking.php");
    }
    


    //Esta retorna todas las mascotas del dueño
    public function getPetsByOwnAndType($typePet)
    {
        
        $userLogged = $_SESSION["loggedUser"];

        $petsFiltered = $this->petService->petsByOwnAndType($userLogged->getOwnerCode(),$typePet);
        
        //header('Content-Type: application/json');
        
        echo $petsFiltered;
    }

    //Esta retorna las mascotas del dueño segun tipo y tamaño (para que la reserva sea precisa ya del lado del cliente tambn)
    public function getPetsByOwnFiltered($typePet,$typeSize)
    {
        
        $userLogged = Session::GetLoggedUser();
        
        $petsFiltered = $this->petService->srv_getPetsByOwnFilters($userLogged->getOwnerCode(),$typePet,$typeSize);
        
        //header('Content-Type: application/json');
        
        echo $petsFiltered;
    }

    public function showMyBookings($resp = " ")
    {
        if(Session::GetLoggedUser())
        {
            $loggedUser = Session::GetLoggedUser();
        }else
        {
            //Patear al inicio
            header("location: ../index.php");
        }
        
        if($loggedUser instanceof Keeper)
        {
            $myBookings = $this->bookingService->srv_getAllMyBookings($loggedUser->getKeeperCode());
        }else if($loggedUser instanceof Owner)
        {
            $myBookings = $this->bookingService->srv_getAllMyBookings($loggedUser->getOwnerCode());
        }
        
        require_once(VIEWS_PATH."myBookings.php");
    }

    public function getBookingByStatus($status)
    {
        $loggedUser = Session::GetLoggedUser();
        
        if($loggedUser instanceof Keeper)
        {
            $myBookings = $this->bookingService->srv_getBookingByStatus($loggedUser->getKeeperCode(),$status);
        }else if($loggedUser instanceof Owner)
        {
            $myBookings = $this->bookingService->srv_getBookingByStatus($loggedUser->getOwnerCode(),$status);
        }
        require_once(VIEWS_PATH."myBookings.php");
    }







    public function getMyBookings($initDate, $endDate, $status)
    {
        //levantar keeperCode del session actual si no hay session,pateamos al index

        if (!Session::IsLogged()) {
            header("location: ../index.php");
        } else {
            if (Session::GetTypeLogged() == "Models\Keeper") {
                $loggedUser = Session::GetLoggedUser();
                $keeperCode = $loggedUser->getKeeperCode();
                $myBookings = $this->bookingService->srv_GetMyBookings($initDate, $endDate, $status, $keeperCode);
                require_once(VIEWS_PATH . "myBookings.php");
            }
        }
    }


    public function manageBooking($codeBook)
    {

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {
                $loggedUser = Session::GetLoggedUser();
                $conf = $this->bookingService->srv_confirmBooking($codeBook);
            }
        }
        echo "SOY CONF del controller,llego dele service";
        var_dump($conf);
        $myBookings = $this->bookingService->srv_getAllMyBookings($loggedUser->getKeeperCode());
        require_once(VIEWS_PATH . "myBookings.php");
    }

        
        

    

    public function fullInfoBookView($codeBook)
    {
        
        $loggedUser = Session::GetLoggedUser();
        if($loggedUser == NULL)
        {
            header("Location: ../index.php");
        }
        if($loggedUser instanceof Keeper)
        {
            $fullBook = $this->bookingService->srv_getFullBooking($loggedUser->getKeeperCode(),$codeBook);
        }else if($loggedUser instanceof Owner)
        {
            $fullBook = $this->bookingService->srv_getFullBooking($loggedUser->getOwnerCode(),$codeBook);
        }
        
        var_dump($fullBook);
        

        require_once(VIEWS_PATH."fullBooking.php");
    }


    public function cancelBooking($bookCode)
    {
        $this->bookingService->srv_cancelBooking($bookCode);
    }

    public function getIntervalBooking($bookCode)
    {
        $intervalReturned = $this->bookingService->srv_getIntervalBooking($bookCode);

        $encodedInterval = json_encode($intervalReturned);

        echo $encodedInterval;
    }

}

?>