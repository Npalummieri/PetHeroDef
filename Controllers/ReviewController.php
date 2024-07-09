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
                        Session::SetOkMessage("Reseña añadida");
                    } else {
                        Session::SetBadMessage("Error al agregar la reseña. Unicamente puede hacerlo 3 veces por usuario");
                    }
                    //Script refresh the page
                    //header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $keeperCode);
                } else {
                    Session::SetBadMessage("Error al agregar la reseña. Debe tener al menos 1 reserva con dicho usuario");
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
                    Session::SetOkMessage("Reseña borrada");
                    header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $result["review"]->getCodeKeeper());
                } else {
                    Session::SetBadMessage("No es posible borrar en estos momentos. Intente más tarde");
                    header("location: " . FRONT_ROOT . "Keeper/showProfileKeeper/" . $result["review"]->getCodeKeeper());
                }
            }
        }
    }
}
