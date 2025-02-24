<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";

// Définition des COOKIES 
// setcookie('nom','valeur','temps en seconde, ex : 30*24*60*60')

foreach ($_POST as $key=>$value) {
      setcookie($key,$value,time() + (30*24*60*60) );
}

redirect("index.php");