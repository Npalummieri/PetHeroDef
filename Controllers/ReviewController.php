<?php 

namespace Controllers;



use DAO\ReviewDAO as ReviewDAO;
use Services\ReviewService as ReviewService;
use Utils\Session as Session;
use Controllers\KeeperController as KeeperController;

 
class ReviewController{


    private $reviewService;
    private $keeperController;

    public function __construct()
    {

        $this->reviewService = new ReviewService();
        $this->keeperController = new KeeperController();
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
                    echo "ENTRE AL GMSGE";
                    $this->reviewService->srv_add($loggedUser->getOwnerCode(),$keeperCode,$comment,$score);
                    Session::SetOkMessage("Review added successfully!");
                    //header("location: ".FRONT_ROOT."Keeper/showProfileKeeper/".$keeperCode);
                }else{
                    Session::SetBadMessage("Error making the review.You must have a FINISHED booking with this keeper <br> <strong>Note</strong> : If you have it.Can't review it more than 3 times ");
                    //header("location: ".FRONT_ROOT."Keeper/showProfileKeeper/".$keeperCode);
                }
            } else {
                header("location: ".FRONT_ROOT."Home/showLoginView");
                Session::DeleteSession();
            }
        } else {
            header("location: ".FRONT_ROOT."Home/showLoginView");
            Session::DeleteSession();
        }
        //require_once(VIEWS_PATH . "reviewForm.php");
    }

    public function delete($reviewCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedOwner = Session::GetLoggedUser();
                $result = $this->reviewService->srv_deleteReview($reviewCode, $loggedOwner->getOwnerCode());
                if($reviewCode == "")
                {

                }
                echo "SOYT RESULT :";
                var_dump($result);
                if($result["deleted"] == 1)
                {
                    Session::SetOkMessage("Review Successfully deleted!");
                    header("location: ".FRONT_ROOT."Keeper/showProfileKeeper/".$result["review"]->getCodeKeeper());
                }else{
                    Session::SetBadMessage("Something wrong happen!");
                    header("location: ".FRONT_ROOT."Keeper/showProfileKeeper/".$result["review"]->getCodeKeeper());
                }
            }
        }
    }
}

?>