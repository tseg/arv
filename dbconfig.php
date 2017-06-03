<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "";
$DB_name = "arvdb";

try{
    $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}",$DB_user,$DB_pass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}catch(PDOException $e){
    echo $e->getMessage();
}

include_once 'crud/class/crud.php';
$crud = new Crud($DB_con);


//include_once 'class.user.php';
//$user = new user($DB_con);

include_once 'class.userlogin.php';
$userlogin = new userlogin($DB_con);

include_once 'class.imagecrud.php';
$imagecrud=new imagecrud($DB_con);
?>