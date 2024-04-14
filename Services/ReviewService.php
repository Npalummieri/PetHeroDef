<?php

namespace Services;

use \Exception as Exception;
use Models\Review as Review;
use DAO\ReviewDAO as ReviewDAO;
use DAO\NotificationDAO as NotificationDAO;

class ReviewService
{

    private $reviewDAO;
    private $notificationDAO;

    public function __construct()
    {
        $this->reviewDAO = new ReviewDAO();
        $this->notificationDAO = new NotificationDAO();
    }

    public function generateCode()
    {
        $uuid = uniqid('REV', true);
        return $uuid;
    }

    public function srv_canReview($ownerCode, $keeperCode)
    {
        try {
            $results = null;
            if (strpos($keeperCode, "KEP") !== false) {

                if (strpos($ownerCode, "OWN") !== false) {
                    
                    $results = $this->reviewDAO->canReview($ownerCode, $keeperCode);
                }
            }   
        } catch (Exception $ex) {
            $results = $ex->getMessage();
        }
        return $results; //null || array(results)
    }

    public function srv_add($ownerCode, $keeperCode, $comment, $score)
    {
        try{
            $checkPrev = $this->srv_canReview($ownerCode, $keeperCode);

        if ($checkPrev != null) {

            $review = new Review();

            $review->setCodeKeeper($keeperCode);
            $review->setCodeOwner($ownerCode);
            $review->setComment($comment);
            $review->setScore($score);

            $review->setCodeReview($this->generateCode());

            $resultAdd = $this->reviewDAO->Add($review);
            if($resultAdd == 1)
            {
                $this->notificationDAO->generateNoti("New review on your profile!",$keeperCode);
            }
        }
        }catch(Exception $ex)
        {
            $resultAdd = $ex->getMessage();
        }
        
        return $resultAdd;
    }


    public function srv_GetReviews($keeperCode)
    {
        try {
            $arrayReviews = null;
            if (strpos($keeperCode, "KEP") !== false) {
                $arrayReviews = $this->reviewDAO->getAllByKeeperCode($keeperCode);
            }

            
        } catch (Exception $ex) {
            $arrayReviews = $ex->getMessage();
        }
        return $arrayReviews;
    }

    public function srv_deleteReview($codeReview, $ownerCodeLog)
    {
        try {

            $result = $this->reviewDAO->delete($codeReview, $ownerCodeLog);

            
        } catch (Exception $ex) {
           $result = $ex->getMessage();
        }
        return $result;

        return $result;
    }
}
