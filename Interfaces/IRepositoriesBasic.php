<?php

namespace Interfaces;

interface IRepositoriesBasic{
    public function Add($object);
    public function GetAll();
    public function delete($code);
    public function searchByEmail($email);
    public function updateStatus($code,$status);
    public function searchByCode($code);
    public function checkDni($dni);
    public function getPassword($code);
    public function updatePassword($email,$password);
}

?>