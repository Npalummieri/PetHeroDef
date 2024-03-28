<?php 

namespace Services;

use \Exception as Exception;
use Models\Review as Review;
use DAO\ReviewDAO as ReviewDAO;
use Services\BookingService as BookingService;
use Services\KeeperService as KeeperService;
class ReviewService{

    private $reviewDAO;
    private $bookingService;
    private $keeperService;

    public function __construct()
    {
        $this->reviewDAO = new reviewDAO();
        $this->bookingService = new BookingService();
        $this->keeperService = new KeeperService();
    }

    public function generateCode() {
        // Genera un UUID único
        $uuid = uniqid('REV', true); // Utiliza 'REV' como prefijo
    
        // Devuelve el ownerCode generado
        return $uuid;
    }

    public function srv_canReview($ownerCode,$keeperCode)
    {
        try
        {
                $result = null;
                if(strpos($keeperCode,"KEP") !== false)
                {
                    echo "ENTRO EN EL KEP IF?";
                    if(strpos($ownerCode,"OWN") !== false)
                    {
                        echo "ENTRO EN EL OWN IF?";
                        $result = $this->reviewDAO->canReview($ownerCode,$keeperCode);
                    }
                }
            

            return $result; //Asumimos que puede tomar 0 o +1
        }catch(Exception $ex)
        {
            $ex->getMessage();
        }
    }

    public function srv_add($ownerCode,$keeperCode,$comment,$score)
    {
        $checkPrev = $this->srv_canReview($ownerCode,$keeperCode);
        echo "SOY CHECKPREV";
        var_dump($checkPrev);
        //Deberia verificar que $comment,$score este bien
        if($checkPrev != null)
        {
            //Si todos los datos ya fueron saneados
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
        try{
            $arrayReviews = null;
            if(strpos($keeperCode,"KEP") !== false)
            {
                $arrayReviews = $this->reviewDAO->getAllByKeeperCode($keeperCode);
            }
            
            return $arrayReviews;
        }catch(Exception $ex)
        {
            $ex->getMessage();
        }
    }

    public function srv_deleteReview($codeReview,$ownerCodeLog)
    {
        try{

            $result = $this->reviewDAO->delete($codeReview,$ownerCodeLog);
            
            return $result;
        }catch(Exception $ex)
        {
            echo $ex->getMessage();
        }

        return $result;
    }

}

?>