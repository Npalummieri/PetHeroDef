<?php

namespace Controllers;


use Models\Keeper as Keeper;
use Services\KeeperService as KeeperService;

use Controllers\HomeController as HomeController;
use Services\UserService as UserService;
use Utils\Session as Session;
use Services\ReviewService as ReviewService;


class KeeperController
{

    private $keeperService;
    private $homeController;

    private $userService;
    private $reviewService;

    public function __construct()
    {
        $this->keeperService = new KeeperService();
        $this->homeController = new HomeController();
        $this->userService = new UserService();
        $this->reviewService = new ReviewService();
    }


    public function registerKeeper($email, $username, $password, $name, $lastname, $dni, $pfp, $typePet, $typeCare, $initDate, $endDate, $price, $visitPerDay)
    {
        $typeUser = "keeper";
        $pfpInfo = array();
        $pfpInfo = $_FILES;
        $userInfo = $this->userService->validateRegisterUser($typeUser, $email, $username, $password, $name, $lastname, $dni, $pfpInfo);

        if (is_string($userInfo)) {
            Session::SetBadMessage($userInfo);
            header("location: " . FRONT_ROOT . "Home/showKeeperRegisterView");
        } else if (is_array($userInfo)) {
            $user = $this->keeperService->validateKeeperFields($userInfo, $typePet, $typeCare, $initDate, $endDate, $price, $visitPerDay);
            if (!($user instanceof Keeper)) {
                Session::SetBadMessage($user);
                $this->homeController->Index();
            }

            if ($userInfo["user"] !== null) {
                Session::SetOkMessage("Successfully registered!");
                header("location: " . FRONT_ROOT . "Home/Index");
            } else {
                Session::SetBadMessage("Something failed at the register.Do it again!");
                $this->homeController->Index();
                header("location: " . FRONT_ROOT . "Home/Index");
            }
        }
    }




    public function getFilteredKeepers($initDate, $endDate, $size, $typePet, $visitPerDay, $pageNumber = '1')
    {

        $totalPages = ceil(count($this->userService->srv_GetFilteredKeepers($initDate, $endDate, $size, $typePet, $visitPerDay, $pageNumber, 6)) / 6);

        $allKeepers = $this->userService->srv_GetFilteredKeepers($initDate, $endDate, $size, $typePet, $visitPerDay, $pageNumber, 6);

        require_once(VIEWS_PATH . "keeperListPag.php");
    }


    public function showProfileKeeper($keeperCode = "")
    {

        // $logged = null;
        $loggedKeeper = null;
        $loggedOwner = null;

        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {
                $loggedKeeper = Session::GetLoggedUser();
                $keeperCode = $loggedKeeper->getKeeperCode();
            } else if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedOwner = Session::GetLoggedUser();
            }
        }
        $keeper = $this->keeperService->srv_getKeeperByCode($keeperCode);
        $reviews = $this->reviewService->srv_GetReviews($keeperCode);
        $infoKeeper = $this->keeperService->srv_getKeeperByCode($keeperCode);
        require_once(VIEWS_PATH . "profileKeeper.php");
    }


    public function showUpdateKeeper()
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {

                $keeperLogged = Session::GetLoggedUser();
                require_once(VIEWS_PATH . "editProfileKeep.php");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/Index");
        }
    }

    //¿Deberia poder cambiar typePet,typeCare?
    public function updateKeeper($email = " ", $pfp = " ", $bio = " ", $price = " ", $visitPerDay = " ")
    {
        var_dump($_POST);
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {

                $keeperLogged = Session::GetLoggedUser();
                $pfpInfo = $_FILES;
                $result = $this->keeperService->srv_updateKeeper($keeperLogged, $email, $pfpInfo, $bio, $price, $visitPerDay);
                echo "SOY RESULT";
                var_dump($result);
                if ($result == 1) {
                    $infoKeeper = $this->keeperService->srv_getKeeperByCode($keeperLogged->getKeeperCode());
                    $infoKeeper = $this->keeperService->srv_getKeeperByCode($keeperLogged->getkeeperCode());
                    Session::DeleteSession();
                    Session::CreateSession($infoKeeper);
                    require_once(VIEWS_PATH . "myProfileKeeper.php");
                }
            }
        } else {
            header("location: '../index.php'");
        }
    }

    public function getIntervalDates($keeperCode)
    {
        $intervalReturned = $this->keeperService->srv_getIntervalDates($keeperCode);

        $encodedInterval = json_encode($intervalReturned);

        echo $encodedInterval;
    }

    public function updateAvailability($initDate, $endDate)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Keeper") {
                $loggedKeeper = Session::GetLoggedUser();
                $keeperCode = $loggedKeeper->getKeeperCode();
                $result = $this->keeperService->srv_updateAvailability($keeperCode, $initDate, $endDate);
            }
        }
        $resEncode = json_encode($result);
        echo $resEncode;
    }

    public function getVisitPerDay($keeperCode)
    {
        $keeper = $this->keeperService->srv_getKeeperByCode($keeperCode);

        $visitPerDay = $keeper->getVisitPerDay();
        $encodedVisit = json_encode($visitPerDay);
        echo ($encodedVisit);
    }

    public function getAvailability($keeperCode)
    {
        $datesInterval = $this->keeperService->srv_getDates($keeperCode);

        $datesEncoded = json_encode($datesInterval);

        echo $datesEncoded;
    }
}
