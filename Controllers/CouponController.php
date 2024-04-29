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
                    
                    $coupon = $this->couponService->srv_getInfoFullCoup($couponCode);
                   
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
	
		//|||||||||||||||||||||||| Coups edits
		public function showListCoupons()
	{
		if(Session::IsLogged())
		{
			$checkAdmin = Session::GetLoggedUser();
			
			if($checkAdmin != null)
			{
				if($checkAdmin->getEmail() == "admin@gmail.com" && $checkAdmin->getDni() == "00004321" && $checkAdmin->getPassword() == "Admin123" && $checkAdmin->getUsername() == "Admin777")
				{
					$listCoups = $this->couponService->srv_getAllCoupons();
					require_once(VIEWS_PATH."listCoupons.php");
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
		
		
	public function showAdminEditCoup($coupCode)
	{
		if((Session::GetLoggedUser()->getEmail() == "admin@gmail.com" || Session::GetLoggedUser()->getUsername() == "Admin777" ) && Session::GetLoggedUser()->getPassword() == "Admin123" )
		{
			$coupon = $this->couponService->srv_getCoupByCode($coupCode);
			require_once(VIEWS_PATH."adminEditCoup.php");
		}
	}
	
	public function adminEditCoupon($coupCode,$status = "",$price = "")
	{
		
		$edits = array(
			"status" => $status,
			"price" => $price
		);

		//var_dump($edits);

		foreach ($edits as $field => $value) {
			if (!empty($value)) {
				$methodName = "srv_edit" . ucfirst($field);
				$result = $this->couponService->$methodName($coupCode, $value);
				if($result == 1){
                    $resultOkFinal ="";
					$resultOkFinal .= " || ".ucfirst($field)." successfully modified!";
					Session::SetOkMessage($resultOkFinal);
				}
				if(is_string($result))
				{
                    $resultFinal ="";
					$resultFinal .= " || ".$result;
					Session::SetBadMessage($resultFinal);
					
				}
			}
		}
		header("location: " . FRONT_ROOT . "Home/showListCoupons");
	}
	
		public function listCouponsFiltered($code ="")
	{
		
		if($code == "")
		{
			header("location: " . FRONT_ROOT . "Home/showListCoupons");
		}
		$listCoups = $this->couponService->listCouponFiltered($code);
		if(is_array($listCoups)){
			require_once(VIEWS_PATH."listCoupons.php");
		}else if($code != ""){
			Session::SetBadMessage($listCoups);
			header("location: " . FRONT_ROOT . "Home/showListCoupons");
		}			
	}
}
