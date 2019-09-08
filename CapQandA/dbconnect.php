<?php

//$DBhost = "localhost";
//$DBuser = "root";
//$DBpass = "root";
//$DBname = "filo_db";


$DBhost = "localhost";
$DBuser = "root";
$DBpass = "";
$DBname = "indproj";
	 
	 $DBcon = new MySQLi($DBhost,$DBuser,$DBpass,$DBname);
    
     if ($DBcon->connect_errno) {
         die("ERROR : -> ".$DBcon->connect_error);
     }

try{
    $DB_con = new PDO("mysql:host={$DBhost};dbname={$DBname}",$DBuser,$DBpass);
    $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
    echo $e->getMessage();
}
