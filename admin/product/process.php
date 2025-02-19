<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {
    $fields = [
        "product_serie",
        "product_name",
        "product_date",
        "product_volume",
        "product_author",
        "product_description",
        "product_resume",
        "product_stock",
        "product_price",
        "product_publisher",
        "product_cartoonist",
        "product_slug"
    ];

    $queryValues = array_map(fn($field) => ":$field", $fields);
    $querySet = implode(", ", array_map(fn($field) => "$field = :$field", $fields));

    if ($_POST["product_id"] == 0) {
        $stmt = $db->prepare("INSERT INTO table_product (" . implode(", ", $fields) . ") VALUES (" . implode(", ", $queryValues) . ")");
    } else {
        $stmt = $db->prepare("UPDATE table_product SET $querySet WHERE product_id = :product_id");
        $stmt->bindValue(":product_id", $_POST["product_id"]);
    }

    foreach ($fields as $field) {
        $stmt->bindValue(":$field", $_POST[$field]);
    }

    $stmt->execute();
}

redirect("index.php");