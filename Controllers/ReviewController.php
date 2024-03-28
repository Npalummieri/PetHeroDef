<?php

namespace Controllers;




use Services\ReviewService as ReviewService;
use Utils\Session as Session;



class ReviewController
{


    private $reviewService;


    public function __construct()
    {

        $this->reviewService = new ReviewService();
    }

    public function doReview($keeperCode, $comment, $score)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedUser = Session::GetLoggedUser();
                $canReview = $this->reviewService->srv_canReview($loggedUser->getOwnerCode(), $keeperCode);
                if ($canReview == 1) {
                    echo "ENTRE AL GMSGE";
                    $this->reviewService->srv_add($loggedUser->getOwnerCode(), $keeperCode, $comment, $score);
                    Session::SetOkMessage("Review added successfully!");
                    header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $keeperCode);
                } else {
                    Session::SetBadMessage("Error making the review.You must have a FINISHED booking with this keeper <br> <strong>Note</strong> : If you have it.Can't review it more than 3 times ");
                    header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $keeperCode);
                }
            } else {
                header("location: " . FRONT_ROOT . "Home/showLoginView");
                Session::DeleteSession();
            }
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
            Session::DeleteSession();
        }
    }

    public function delete($reviewCode)
    {
        if (Session::IsLogged()) {
            if (Session::GetTypeLogged() == "Models\Owner") {
                $loggedOwner = Session::GetLoggedUser();
                $result = $this->reviewService->srv_deleteReview($reviewCode, $loggedOwner->getOwnerCode());
                if ($result["deleted"] == 1) {
                    Session::SetOkMessage("Review Successfully deleted!");
                    header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $result["review"]->getCodeKeeper());
                } else {
                    Session::SetBadMessage("We cant delete this review.Try later!");
                    header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $result["review"]->getCodeKeeper());
                }
            }
        }
    }
}
