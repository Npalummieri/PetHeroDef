<?php

namespace Controllers;


use Services\MessageService as MessageService;
use Utils\Session as Session;
use Models\Keeper as Keeper;
use Models\Owner as Owner;

class MessageController
{

    private $messageService;

    public function __construct()
    {
        $this->messageService = new MessageService();
    }

    public function sendMessage($msgText, $converCode)
    {

        $logged = Session::GetLoggedUser();
        $arrayCodes = $this->messageService->srv_getInfoConver($converCode);


        if ($logged instanceof Keeper) {
            $senderCode = $logged->getKeeperCode();
        } else if ($logged instanceof Owner) {
            $senderCode = $logged->getOwnerCode();
        }

        if ($arrayCodes["keeperCode"] === $senderCode) {
            $receiverCode = $arrayCodes["ownerCode"];
        } else if ($arrayCodes["ownerCode"] === $senderCode) {
            $receiverCode = $arrayCodes["keeperCode"];
        }

        if ($arrayCodes != null) {
            $msgSended = $this->messageService->srv_sendMessage($senderCode, $msgText, $converCode);
        }


        $msgeJsonEncod = json_encode($msgSended);

        echo $msgeJsonEncod;
    }


    public function getMessages($converCode)
    {
        $loggedUser = Session::GetLoggedUser();
        $senderCode = null;
        $arrayMessages = array();
        if ($loggedUser instanceof Keeper) {
            $senderCode = $loggedUser->getKeeperCode();
        } else if ($loggedUser instanceof Owner) {
            $senderCode = $loggedUser->getOwnerCode();
        }

        //echo "SENDER CODE $senderCode LOGGED USER".$loggedUser->getKeeperCode()." CONVER CODE : $converCode";
        if ($senderCode != null) {
            $arrayMessages = $this->messageService->srv_GetMessages($senderCode, $converCode);
        }

        $arrayMessages["currentUserCode"] = $senderCode;
        $jsonArray = json_encode($arrayMessages);

        echo $jsonArray;
    }


    public function toInbox()
    {
        $loggedUser = Session::GetLoggedUser();

        if (is_a($loggedUser, "Models\Keeper")) {
            $code = $loggedUser->getKeeperCode();
        } else if (is_a($loggedUser, "Models\Owner")) {
            $code = $loggedUser->getOwnerCode();
        }

        $arrayInfoConver = $this->messageService->srv_getInfoConver($code);
        //var_dump($loggedUser);
        //$userAvaiArr = $this->messageService->srv_UsersAvaiToText($code);
        require_once(VIEWS_PATH . "messagesView.php");
    }

    public function getConverInfo($codeChat)
    {
        $arrayInfoConver = $this->messageService->srv_getInfoConver($codeChat);
        require_once(VIEWS_PATH . "messagesView.php");
    }


    public function messageView($userAvaiArr)
    {
        require_once(VIEWS_PATH . "messagesView.php");
    }

    public function getAvailTalk()
    {
        $loggedUser = Session::GetLoggedUser();
        if ($loggedUser != null) {
            if (is_a($loggedUser, "Models\Keeper")) {
                $availTalk = $this->messageService->srv_getAvailTalk($loggedUser->getKeeperCode());
                if (!empty($availTalk)) {
                    $availTalk[0]["logged"] = "keeper";
                }
            } else if (is_a($loggedUser, "Models\Owner")) {
                $availTalk =  $this->messageService->srv_getAvailTalk($loggedUser->getOwnerCode());
                if (!empty($availTalk)) {
                    $availTalk[0]["logged"] = "owner";
                }
            }
        }

        $encodedContent = json_encode($availTalk);

        echo $encodedContent;
    }

    public function checkChatCode($userToSendCode, $chatCode)
    {

        if (Session::IsLogged()) {
            $logged = Session::GetLoggedUser();
            $arrayCodes = $this->messageService->srv_getUsersConver($chatCode);


            if (is_a($logged, "Models/Keeper")) {

                $senderCode = $logged->getKeeperCode();
            } else {
                $senderCode = $logged->getOwnerCode();
            }

            if ($arrayCodes["receiverCode"] === $senderCode) {
                $receiverCode = $arrayCodes["senderCode"];
            } else if ($arrayCodes["senderCode"] === $senderCode) {
                $receiverCode = $arrayCodes["receiverCode"];
            }

            //If conver already exists or not
            if ($chatCode == null) {
                $rspChatCode = $this->messageService->srv_checkPrevConv($senderCode, $receiverCode);
            }

            $rspEncoded = json_encode($rspChatCode);
        } else {
            header("location: " . FRONT_ROOT . "Home/showLoginView");
        }

        echo $rspEncoded;
    }

    public function getUnreadMessages($converCode)
    {
        if(Session::IsLogged())
        {
            $userLogged = Session::GetLoggedUser();
            $result = $this->messageService->srv_getUnreadMsg($converCode,$userLogged);
            $encodedResult = json_encode($result);
            
            echo $encodedResult;
        }else{
            header("location: ".FRONT_ROOT."Home/showLoginView");
        }
    }
}
