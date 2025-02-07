<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";


if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

      // Ajout d'un champ dans la BDD
      if ($_POST["product_id"] == 0) {
            $stmt = $db->prepare("INSERT INTO table_product(
            product_serie,
            product_name)
            VALUES(
            :product_serie,
            :product_name
            )");
            $stmt->bindValue(":product_serie", $_POST["product_serie"]);
            $stmt->bindValue(":product_name", $_POST["product_name"]);
            $stmt->execute();
      } else {
            // Modification d'un champ existant dans la base de données
            $stmt = $db->prepare("UPDATE table_product SET
            product_serie = :product_serie,
            product_name = :product_name
            WHERE 
            product_id = :product_id");

            $stmt->bindValue(":product_serie", $_POST["product_serie"]);
            $stmt->bindValue(":product_name", $_POST["product_name"]);
            $stmt->bindValue(":product_id", $_POST["product_id"]);
            $stmt->execute();
      }
}

redirect("index.php");
