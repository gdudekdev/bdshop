<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


if (isset($_POST["formCU"]) && $_POST["formCU"]=="ok"){
      $stmt = $db -> prepare("INSERT INTO table_product(
                                    product_serie,
                                    product_name)
                                    VALUES(
                                    :product_serie,
                                    :product_name
                                    )");
      $stmt -> bindValue(":product_serie",$_POST["product_serie"]);
      $stmt -> bindValue(":product_name",$_POST["product_name"]);
      $stmt -> execute();
}
redirect("index.php");
