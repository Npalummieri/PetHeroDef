<?php 

namespace Controllers;

use \Exception as Exception;
use Models\Review as Review;
use DAO\ReviewDAO as ReviewDAO;
use Services\ReviewService as ReviewService;
use Utils\Session as Session;
use Services\KeeperService as KeeperService;
 
class ReviewController{

    private $reviewDAO;
    private $reviewService;
    

    public function __construct()
    {
        $this->reviewDAO = new ReviewDAO();
        $this->reviewService = new ReviewService();
        
    }

    //Te envia al form de review (vista)
    public function doReview($keeperCode,$comment,$score)
    {
        
        echo " SOY POST ";
        var_dump($_POST);
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedUser = Session::GetLoggedUser();
                $canReview = $this->reviewService->srv_canReview($loggedUser->getOwnerCode(),$keeperCode);
                if($canReview == 1)
                {
                    $this->reviewService->srv_add($loggedUser->getOwnerCode(),$keeperCode,$comment,$score);
                }
            } else {
                header("location: '../index.php'");
                Session::DeleteSession();
            }
        } else {
            header("location: '../index.php'");
            Session::DeleteSession();
        }
        require_once(VIEWS_PATH . "reviewForm.php");
    }
}

?>