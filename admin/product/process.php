<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

    if (isset($_FILES['product_image'])) {
        var_dump($_FILES['product_image']);

        // On va vérifier si le fichier uploadé est bien une image et que l'upload s'est bien passé 
        // Vérfication de l'upload de l'image
        if ($_FILES['product_image']['error'] != 0) {
            die("Erreur lors de l'upload de l'image");
        }
        // Vérification de l'extension de l'image
        $extension = pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION);

        // Dans le cas du jpg, l'extension que l'on récupère passe à jpeg, notre condition ne fonctionnerait donc pas: on doit donc modifier avec str replace notre extension pour pouvoir la comparer avec le type de notre fichier(image/jpeg), on vérifie auss si l'extension est bien parmis celle que l'on accepte
        if (
            ("image/" . str_replace("jpg", "jpeg", strtolower($extension)) !== $_FILES['product_image']['type'])
            && (in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
        ) {
            die("L'extension de l'image n'est pas valide");
        }

        // On crée le nom de notre image en le nettoyant
        $filename = cleanFilename("bdshop_" . $_POST["product_serie"] . "_" . $_POST["product_name"]);
        

        
        // On vérifie si il n'y a pas de doublon
        $count = 1;
        while(file_exists($_SERVER['DOCUMENT_ROOT'] . "/upload/" . $filename . ($count>1 ? "(". $count .")":""). "." . $extension)) { 
            $count++;
        }
        if($count > 1) {
            $filename .= "(" . $count . ")";
        }
        var_dump($count);
        // Si l'image est bien uploadée, on la déplace dans le dossier upload
        move_uploaded_file($_FILES['product_image']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . "/upload/" . $filename . "." . $extension);
    }

    exit();

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