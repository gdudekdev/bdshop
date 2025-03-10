<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {

    if (isset($_FILES['product_image'])) {
        $path = $_SERVER['DOCUMENT_ROOT'] . "/upload/";
        // On va vérifier si le fichier uploadé est bien une image et que l'upload s'est bien passé 
        // Vérfication de l'upload de l'image
        if ($_FILES['product_image']['error'] != 0) {
            die("Erreur lors de l'upload de l'image");
        }
        // Vérification de l'extension de l'image
        $extension = strtolower(pathinfo($_FILES['product_image']['name'], PATHINFO_EXTENSION));

        // Dans le cas du jpg, l'extension que l'on récupère passe à jpeg, notre condition ne fonctionnerait donc pas: on doit donc modifier avec str replace notre extension pour pouvoir la comparer avec le type de notre fichier(image/jpeg), on vérifie auss si l'extension est bien parmis celle que l'on accepte
        if (
            ("image/" . str_replace("jpg", "jpeg", $extension) !== $_FILES['product_image']['type'])
            && (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
        ) {
            die("L'extension de l'image n'est pas valide");
        }
        
        // On crée le nom de notre image en le nettoyant
        $filename = cleanFilename( "lg-bdshop_" . $_POST["product_serie"] . "_" . $_POST["product_name"]);

        // On vérifie si il n'y a pas de doublon
        $count = 1;
        while (file_exists($path . $filename . ($count > 1 ? "(" . $count . ")" : "") . "." . "webp")) {
            $count++;
        }
        if ($count > 1) {
            $filename .= "(" . $count . ")";
        }
        // Si l'image est bien uploadée et après changement de son nom, on la déplace dans le dossier upload
        move_uploaded_file($_FILES['product_image']['tmp_name'], $path . $filename . "." . $extension);

        // TRAITEMENT DE L'IMAGE 
        
        // On va redimensionner l'image
        // Récupération des dimensions de l'image
        $src = getimagesize($path . $filename . "." . $extension);
        $srcWidth = $src[0];
        $srcHeight = $src[1];

        // Taille maximale de notre image finale
        $destWidth = 1200;
        $destHeight = 900;

        // Gestion des différents formats de l'image
        $ratio = $srcWidth / $srcHeight;

        if ($ratio > 1) {
            $destHeight = round($destWidth / $ratio);
        } else {
            $destWidth = round($destHeight * $ratio);
        }

        // Initialisation des sources des images
        $srcX = 0;
        $srcY = 0;
        $destX = 0;
        $destY = 0;

        // Création de l'image de destination
        $dest = imagecreatetruecolor($destWidth, $destHeight);

        // On charge l'image source
        $imagecreatefromCustom = "imagecreatefrom" . str_replace("jpg", "jpeg", $extension);
        $src = $imagecreatefromCustom($path . $filename . "." . $extension);
        
        // Chargement de l'image source dans l'image de destination
        imagecopyresampled($dest, $src, $destX, $destY, $srcX, $srcY, $destWidth, $destHeight, $srcWidth, $srcHeight);
        
        // Enregistrement de l'image finale au format webp dans le dossier de destination
        imagewebp($dest, $path . $filename . ".webp", 100);
        

        // On supprime l'image temporaire originale pour libérer de l'espace
        if (file_exists($path . $filename . "." . $extension)) {
            unlink($path . $filename . "." . $extension);
        }
    };

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