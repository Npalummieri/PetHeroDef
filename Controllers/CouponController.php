<?php

namespace Controllers;

use Services\CouponService as CouponService;
use Utils\Session as Session;

class CouponController{


    private $couponService;

    public function __construct()
    {
        $this->couponService = new CouponService();
    }

    public function addCoupon($bookCode, $price)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $this->couponService->srv_generateCouponToOwner($bookCode, $price);
            } else {
                Session::SetBadMessage("Not allowed to be there!");
                header("location: " . FRONT_ROOT . "Home/Index");
            }
        } else {
            Session::SetBadMessage("Not allowed to be there!");
            header("location: " . FRONT_ROOT . "Home/Index");
        }
    }

    public function showMyCoupons()
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedUser = Session::GetLoggedUser();
                $codeOwnerLogged = $loggedUser->getOwnerCode();
                $myCoupons = $this->couponService->srv_getCouponsByOwn($codeOwnerLogged);
                require_once(VIEWS_PATH . "myCoupons.php");
            }
        }
    }

    public function myCouponView($couponCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $coupon = $this->couponService->srv_getInfoFullCoup($couponCode);
                require_once(VIEWS_PATH . "manageCoupon.php");
            }
        }
    }

    public function showCouponFromBook($bookCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
        $couponCode = $this->couponService->srv_getCoupCodeByBook($bookCode);

        $this->myCouponView($couponCode);
            }
        }
    }

    public function manageCoupon($mngCoup, $couponCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                if ($mngCoup == "paidup") {
                    $this->payCouponView($couponCode);
                } else {
                    //Vista normal,se cancela el coup+book y al inicio
                }
            }
        }
    }

    public function payCouponView($couponCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $fullCoup = $this->couponService->srv_getInfoFullCoup($couponCode);
                $couponCode = $fullCoup["couponCode"];
                setcookie('couponcode', $couponCode, time() + (600), "/"); //10min
                require_once(VIEWS_PATH . "payCoup.php");
            }
        }
    }

    public function payCoupon($ccnum, $cardholder, $expdate, $ccv)
    {
        $couponCode = $_COOKIE["couponcode"];
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                if ($this->couponService->srv_validateCoup($couponCode, $ccnum, $cardholder, $expdate, $ccv) != 1) {
                    Session::SetBadMessage("SOMETHING'S FAILED WITH THE PAYMENT");
                    $this->payCouponView($couponCode);
                } else {
                    Session::SetOkMessage("Payment aproved!");
                    $this->showMyCoupons();
                }
            }
        }
    }
    
    public function declineCoupon($couponCode)
    {
        if(Session::IsLogged())
        {
            if(Session::GetTypeLogged() == "Models\Owner")
            {
                $this->couponService->srv_declineCoupon($couponCode);
            }
        }
        
    }
}
?>