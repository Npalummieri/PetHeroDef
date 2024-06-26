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
                $results = $this->reviewService->srv_canReview($loggedUser->getOwnerCode(), $keeperCode);
                if ($results["result"] >= 1) {
                    if ($results["resultCountReview"] <= 2) {
                        $this->reviewService->srv_add($loggedUser->getOwnerCode(), $keeperCode, $comment, $score);
                        Session::SetOkMessage("Review added successfully!");
                    } else {
                        Session::SetBadMessage("Error making the review.Can't review it more than 3 times ");
                    }
                    //Script refresh the page
                    //header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $keeperCode);
                } else {
                    Session::SetBadMessage("Error making the review.You must have a previous booking with this keeper");
                    //Script refresh the page
                    //header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $keeperCode);
                }
            } else {
                Session::DeleteSession();
                header("location: " . FRONT_ROOT . "Home/showLoginView");
            }
        } else {
            Session::DeleteSession();
            header("location: " . FRONT_ROOT . "Home/showLoginView");
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
