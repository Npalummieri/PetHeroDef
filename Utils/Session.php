<?php 

namespace Utils;

  class Session{

    public static function CreateSession($user)
    {
        $_SESSION["loggedUser"] = $user;
    }

    public static function IsLogged()
    {
        return isset($_SESSION["loggedUser"]);
    }

    public static function VerifySession()
    {
        if(!Session::IsLogged())
        {
            header("location: ".VIEWS_PATH."index.php");
        }
    }

    public static function GetLoggedUser()
    {
        return Session::IsLogged() ? $_SESSION["loggedUser"] : null;
    }

    public static function DeleteSession()
    {
        session_destroy();
    }

    public static function GetTypeLogged()
    {
        return get_class($_SESSION["loggedUser"]);
    }
}

?>