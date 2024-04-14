<?php

namespace Controllers;

use Services\CouponService as CouponService;
use Utils\Session as Session;

class CouponController
{
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
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }

    public function myCouponView($couponCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                
                $result = $this->couponService->srv_checkCouponOwner($couponCode, Session::GetLoggedUser()->getOwnerCode());
                if ($result >= 1) {
                    var_dump($couponCode);
                    $coupon = $this->couponService->srv_getInfoFullCoup($couponCode);
                    var_dump($coupon);
                    require_once(VIEWS_PATH . "manageCoupon.php");
                } else {
                    Session::DeleteSession();
                    Session::SetBadMessage("Not your coupon");
                    header("location: " . FRONT_ROOT . "Home/showLoginView");
                }
            } else {
                Session::DeleteSession();
                Session::SetBadMessage("Back to log");
                header("location: " . FRONT_ROOT . "Home/showLoginView");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }

    public function showCouponFromBook($bookCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $couponCode = $this->couponService->srv_getCoupCodeByBook($bookCode);
                $this->myCouponView($couponCode);
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }

    public function payCouponView($couponCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $result = $this->couponService->srv_checkCouponOwner($couponCode, Session::GetLoggedUser()->getOwnerCode());
                if ($result >= 1) {
                    $fullCoup = $this->couponService->srv_getInfoFullCoup($couponCode);
                    $couponCode = $fullCoup["couponCode"];
                    setcookie('couponcode', $couponCode, time() + (600), "/"); //10min
                    require_once(VIEWS_PATH . "payCoup.php");
                } else {
                    Session::DeleteSession();
                    Session::SetBadMessage("Not your coupon");
                    header("location: " . FRONT_ROOT . "Home/showLoginView");
                }
            } else {
                Session::DeleteSession();
                header("location: " . FRONT_ROOT . "Home/showLoginView");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }

    public function payCoupon($ccnum, $cardholder, $expdate, $ccv)
    {
        $couponCode = $_COOKIE["couponcode"];
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $response = $this->couponService->srv_validateCoup($couponCode, $ccnum, $cardholder, $expdate, $ccv);
                if ($response != 1) {
                    Session::SetBadMessage($response);
                    header("location: " . FRONT_ROOT . "Coupon/payCouponView/" . $couponCode);
                } else {
                    Session::SetOkMessage("Payment aproved!");
                    header("location: " . FRONT_ROOT . "Coupon/showMyCoupons");
                }
            } else {
                header("location: " . FRONT_ROOT . "Home/showLoginView");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }

    public function declineCoupon($couponCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $result = $this->couponService->srv_checkCouponOwner($couponCode, Session::GetLoggedUser()->getOwnerCode());
                if ($result >= 1) {
                    $resp = $this->couponService->srv_declineCoupon($couponCode);
                    if ($resp == 1) {
                        Session::SetOkMessage("Coupon cancelled successfully");
                    } else {
                        Session::SetBadMessage($resp);
                    }
                } else {
                    Session::SetBadMessage($result);
                }
                header("location: " . FRONT_ROOT . "Coupon/showMyCoupons");
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }
    }
}
