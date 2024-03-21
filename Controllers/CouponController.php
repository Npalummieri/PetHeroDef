<?php

namespace Controllers;

use DAO\BookingDAO;
use Models\Coupon as Coupon;
use DAO\CouponDAO as CouponDAO;
use Services\CouponService as CouponService;
use Utils\Session as Session;

class CouponController{

    private $couponDAO;
    private $bookingDAO;
    private $couponService;

    public function __construct()
    {
        $this->couponDAO = new CouponDAO();
        $this->bookingDAO = new BookingDAO();
        $this->couponService = new CouponService();
    }

    public function addCoupon($bookCode,$price)//Me llega el precio por hora o jornada ya calculada (ver si lo hago en booking)
    {
        $this->couponService->srv_generateCouponToOwner($bookCode,$price);
    }

    public function showMyCoupons()
    {
        $loggedUser = Session::GetLoggedUser();
        $codeOwnerLogged = $loggedUser->getOwnerCode();
        $myCoupons = $this->couponService->srv_getCouponsByOwn($codeOwnerLogged);
        require_once(VIEWS_PATH."myCoupons.php");
    }

    public function myCouponView($couponCode)
    {
        $coupon = $this->couponService->srv_getInfoFullCoup($couponCode);
        require_once(VIEWS_PATH."manageCoupon.php");
    }

    public function showCouponFromBook($bookCode)
    {
        $couponCode = $this->couponService->srv_getCoupCodeByBook($bookCode);

        $this->myCouponView($couponCode);
    }

    public function manageCoupon($mngCoup,$couponCode)
    {
        if($mngCoup == "paidup")
        {
            $this->payCouponView($couponCode);
        }else
        {
            //Vista normal,se cancela el coup+book y al inicio
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
                    $errorMsge = "SOMETHING'S FAILED WITH THE PAYMENT";
                    $this->payCouponView($couponCode);
                } else {
                    $msge = "Payment aproved!";
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