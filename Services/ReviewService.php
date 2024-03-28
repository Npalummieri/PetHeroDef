<?php

namespace Services;

use \Exception as Exception;
use Models\Review as Review;
use DAO\ReviewDAO as ReviewDAO;


class ReviewService
{

    private $reviewDAO;


    public function __construct()
    {
        $this->reviewDAO = new reviewDAO();
    }

    public function generateCode()
    {
        $uuid = uniqid('REV', true);
        return $uuid;
    }

    public function srv_canReview($ownerCode, $keeperCode)
    {
        try {
            $result = null;
            if (strpos($keeperCode, "KEP") !== false) {

                if (strpos($ownerCode, "OWN") !== false) {

                    $result = $this->reviewDAO->canReview($ownerCode, $keeperCode);
                }
            }


            return $result; // 0 || +1
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public function srv_add($ownerCode, $keeperCode, $comment, $score)
    {
        $checkPrev = $this->srv_canReview($ownerCode, $keeperCode);

        if ($checkPrev != null) {

            $review = new Review();
            $review->setCodeKeeper($keeperCode);
            $review->setCodeOwner($ownerCode);
            $review->setComment($comment);
            $review->setScore($score);

            $review->setCodeReview($this->generateCode());

            $resultAdd = $this->reviewDAO->Add($review);
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

            return $arrayReviews;
        } catch (Exception $ex) {
            $ex->getMessage();
        }
    }

    public function srv_deleteReview($codeReview, $ownerCodeLog)
    {
        try {

            $result = $this->reviewDAO->delete($codeReview, $ownerCodeLog);

            return $result;
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }

        return $result;
    }
}
