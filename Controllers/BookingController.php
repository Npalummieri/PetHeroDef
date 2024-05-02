<?php

namespace Controllers;


use Models\Keeper as Keeper;
use Models\Owner as Owner;
use Services\BookingService as BookingService;
use Services\KeeperService as KeeperService;
use Services\PetService as PetService;
use Utils\Session as Session;


class BookingController
{

    private $bookingService;
    private $petService;
    private $keeperService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
        $this->petService = new PetService();
        $this->keeperService = new KeeperService();
    }

    public function addBooking($initDate, $endDate, $petCode, $keeperCode, $typePet, $typeSize, $visitPerDay)
    {
        if (Session::IsLogged() && Session::GetTypeLogged() == 'Models\Owner') {
            $userLogged = $_SESSION["loggedUser"];

            $resp = $this->bookingService->srv_validateBooking($userLogged->getOwnerCode(), $initDate, $endDate, $petCode, $keeperCode, $typePet, $typeSize, $visitPerDay);
        } else {
            Session::DeleteSession();
            header("location: " . FRONT_ROOT . "Home/Index");
        }

        if (strpos($resp, "BOOK") !== false) {
            Session::SetOkMessage("Booking added successfully");
            header("location: " . FRONT_ROOT . "Booking/showMyBookings");
        } else {

            Session::SetBadMessage($resp);
            $this->showBookCreate($keeperCode, $resp);
        }
    }
    public function showBookCreate($keeperCode, $message = " ")
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

        $petsFiltered = $this->petService->petsByOwnAndType($userLogged->getOwnerCode(), $typePet);

        echo $petsFiltered;
    }

    //Return pets from the ownerLogged (filtered by type and size)
    public function getPetsByOwnFiltered($typePet, $typeSize)
    {

        $userLogged = Session::GetLoggedUser();

        $petsFiltered = $this->petService->srv_getPetsByOwnFilters($userLogged->getOwnerCode(), $typePet, $typeSize);

        echo $petsFiltered;
    }

    public function showMyBookings()
    {
        if (Session::GetLoggedUser()) {
            $loggedUser = Session::GetLoggedUser();
        } else {
            header("location: " . FRONT_ROOT . "Home/Index");
        }

        if ($loggedUser instanceof Keeper) {
            $myBookings = $this->bookingService->srv_getAllMyBookings($loggedUser->getKeeperCode());
        } else if ($loggedUser instanceof Owner) {
            $myBookings = $this->bookingService->srv_getAllMyBookings($loggedUser->getOwnerCode());
        }

        require_once(VIEWS_PATH . "myBookings.php");
    }

    public function getBookingByStatus($status)
    {
        $loggedUser = Session::GetLoggedUser();

        if ($loggedUser instanceof Keeper) {
            $myBookings = $this->bookingService->srv_getBookingByStatus($loggedUser->getKeeperCode(), $status);
        } else if ($loggedUser instanceof Owner) {
            $myBookings = $this->bookingService->srv_getBookingByStatus($loggedUser->getOwnerCode(), $status);
        }
        require_once(VIEWS_PATH . "myBookings.php");
    }

    public function getMyBookings($initDate = "", $endDate = "", $status = "")
    {
       
        if (!Session::IsLogged()) {
            header("location: " . FRONT_ROOT . "Home/Index");
        } else {
            if (Session::GetTypeLogged() == "Models\Keeper") {
                $loggedUser = Session::GetLoggedUser();
                $keeperCode = $loggedUser->getKeeperCode();
                $myBookings = $this->bookingService->srv_GetMyBookings($initDate, $endDate, $status, $keeperCode);
                if(is_string($myBookings))
                {
                    Session::SetBadMessage($myBookings);
                    header("location: " . FRONT_ROOT . "Booking/showMyBookings");
                }else{
                    require_once(VIEWS_PATH . "myBookings.php");
                }
                
            }else{
                $loggedUser = Session::GetLoggedUser();
                $ownerCode = $loggedUser->getOwnerCode();
                $myBookings = $this->bookingService->srv_GetMyBookings($initDate, $endDate, $status, $ownerCode);
                if(is_string($myBookings))
                {
                    Session::SetBadMessage($myBookings);
                    header("location: " . FRONT_ROOT . "Booking/showMyBookings");
                }else{
                    require_once(VIEWS_PATH . "myBookings.php");
                }
            }
        }
        
    }

    public function manageBooking($codeBook)
    {

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {
                $loggedUser = Session::GetLoggedUser();
                $conf = $this->bookingService->srv_confirmBooking($codeBook);
				// echo "soy conf";
				// var_dump($conf);
                if ($conf == 1 || strpos($conf, "COU") !== false) {
                    Session::SetOkMessage("Successfuly confirmed!");
                } else {
                    Session::SetBadMessage($conf);
                }
                header("location: " . FRONT_ROOT . "Booking/showMyBookings");
            }
        }
    }

    public function fullInfoBookView($codeBook)
    {

        $loggedUser = Session::GetLoggedUser();
        if ($loggedUser == NULL) {
            header("location: " . FRONT_ROOT . "Home/Index");
        }
        if ($loggedUser instanceof Keeper) {
            $fullBook = $this->bookingService->srv_getFullBooking($loggedUser->getKeeperCode(), $codeBook);
        } else if ($loggedUser instanceof Owner) {
            $fullBook = $this->bookingService->srv_getFullBooking($loggedUser->getOwnerCode(), $codeBook);
        }

        require_once(VIEWS_PATH . "fullBooking.php");
    }


    public function cancelBooking($bookCode)
    {
        $result = $this->bookingService->srv_cancelBooking($bookCode);
        if ($result == 1) {
            Session::SetOkMessage("Booking rejected!");
        } else {
            Session::SetBadMessage($result);
        }
        header("location: " . FRONT_ROOT . "Booking/showMyBookings");
    }

    public function getIntervalBooking($bookCode)
    {
        $intervalReturned = $this->bookingService->srv_getIntervalBooking($bookCode);

        $encodedInterval = json_encode($intervalReturned);

        echo $encodedInterval;
    }
	
	public function showListBookings()
	{
		if(Session::IsLogged())
		{
			$checkAdmin = Session::GetLoggedUser();
			
			if($checkAdmin != null)
			{
				if($checkAdmin->getEmail() == "admin@gmail.com" && $checkAdmin->getDni() == "00004321" && $checkAdmin->getPassword() == "Admin123" && $checkAdmin->getUsername() == "Admin777")
				{
					$listBooks = $this->bookingService->srv_getAllBookings();
					require_once(VIEWS_PATH."listBookings.php");
				}else{
					Session::DeleteSession();
					header("location: ".FRONT_ROOT."Home/showLoginView");
				}
			}else{
				Session::DeleteSession();
				header("location: ".FRONT_ROOT."Home/showLoginView");
			}
			
			}else{
				header("location: ".FRONT_ROOT."Home/showLoginView");
			}
	}
	
	public function showAdminEditBook($bookCode)
	{
		if((Session::GetLoggedUser()->getEmail() == "admin@gmail.com" || Session::GetLoggedUser()->getUsername() == "Admin777" ) && Session::GetLoggedUser()->getPassword() == "Admin123" )
		{
			$booking = $this->bookingService->srv_getBookingByCode($bookCode);
			require_once(VIEWS_PATH."adminEditBook.php");
		}
	}
	
	public function adminEditBooking($bookCode,$status = "",$price = "")
	{
		
		$edits = array(
			"status" => $status,
			"price" => $price
		);

		var_dump($edits);

		foreach ($edits as $field => $value) {
			if (!empty($value)) {
				$methodName = "srv_edit" . ucfirst($field);
				$result = $this->bookingService->$methodName($bookCode, $value);
				if($result == 1){
                    $resultOkFinal = "";
					$resultOkFinal .= " || ".ucfirst($field)." successfully modified!";
					Session::SetOkMessage($resultOkFinal);
				}
				if(is_string($result))
				{
                    $resultFinal = "";
					$resultFinal .= " || ".$result;
					Session::SetBadMessage($resultFinal);
					
				}
			}
		}
		header("location: " . FRONT_ROOT . "Home/showListBookings");
	}
	
	public function listBookingFiltered($code = "")
	{
		if($code == "")
		{
			header("location: " . FRONT_ROOT . "Home/showListBookings");
		}
		$listBooks = $this->bookingService->listBookingFiltered($code);
		if(is_array($listBooks)){
			require_once(VIEWS_PATH."listBookings.php");
		}else if($code != ""){
			Session::SetBadMessage($listBooks);
			header("location: " . FRONT_ROOT . "Home/showListBookings");
		}			
	}
	
}
