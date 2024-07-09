<?php

namespace Interfaces;

interface IRepositoriesExtendUser{
    public function searchByUsername($username);
    public function checkUsername($username);
    public function updatePfp($code,$pfp);
    public function updateEmail($code,$email);
    public function updateUsername($code,$username);
}
?>