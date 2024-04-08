<?php 

namespace Controllers;


use Models\Keeper as Keeper;
use Models\Owner as Owner;
use Services\BookingService as BookingService;
use Services\KeeperService as KeeperService;
use Services\PetService as PetService;
use Utils\Session as Session;


class BookingController{

    private $bookingService;
    private $petService;
    private $keeperService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
        $this->petService = new PetService();
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
            header("location: ".FRONT_ROOT."Home/Index");
        }

        if(strpos($resp,"BOOK") !== false){
            Session::SetOkMessage("Booking added successfully");
           //header("location: ".FRONT_ROOT."Booking/showMyBookings");
        }else{
            $this->showBookCreate($keeperCode,$resp);
        }
        var_dump($resp);
        
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
    


    //Return pets from the ownerLogged (filtered by type)
    public function getPetsByOwnAndType($typePet)
    {
        $userLogged = $_SESSION["loggedUser"];

        $petsFiltered = $this->petService->petsByOwnAndType($userLogged->getOwnerCode(),$typePet);
        
        echo $petsFiltered;
    }

    //Return pets from the ownerLogged (filtered by type and size)
    public function getPetsByOwnFiltered($typePet,$typeSize)
    {
        
        $userLogged = Session::GetLoggedUser();
        
        $petsFiltered = $this->petService->srv_getPetsByOwnFilters($userLogged->getOwnerCode(),$typePet,$typeSize);
         
        echo $petsFiltered;
    }

    public function showMyBookings()
    {
        if(Session::GetLoggedUser())
        {
            $loggedUser = Session::GetLoggedUser();
        }else
        {
            header("location: ".FRONT_ROOT."Home/Index");
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
            header("location: ".FRONT_ROOT."Home/Index");
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
                if($conf == 1)
                {
                    Session::SetOkMessage("Successfuly confirmed!");
                    
                }else{
                    Session::SetBadMessage($conf);
                }
                header("location: ".FRONT_ROOT."Booking/showMyBookings");
            }
        }

        
    }

        
        

    

    public function fullInfoBookView($codeBook)
    {
        
        $loggedUser = Session::GetLoggedUser();
        if($loggedUser == NULL)
        {
            header("location: ".FRONT_ROOT."Home/Index");
        }
        if($loggedUser instanceof Keeper)
        {
            $fullBook = $this->bookingService->srv_getFullBooking($loggedUser->getKeeperCode(),$codeBook);
        }else if($loggedUser instanceof Owner)
        {
            $fullBook = $this->bookingService->srv_getFullBooking($loggedUser->getOwnerCode(),$codeBook);
        }
        
        require_once(VIEWS_PATH."fullBooking.php");
    }


    public function cancelBooking($bookCode)
    {
        $result = $this->bookingService->srv_cancelBooking($bookCode);
        if($result == 1)
        {
            Session::SetOkMessage("Booking rejected!");
        }else{
            Session::SetBadMessage($result);
        }
        header("location: ".FRONT_ROOT."Booking/showMyBookings");
        
    }

    public function getIntervalBooking($bookCode)
    {
        $intervalReturned = $this->bookingService->srv_getIntervalBooking($bookCode);

        $encodedInterval = json_encode($intervalReturned);

        echo $encodedInterval;
    }

}

?>