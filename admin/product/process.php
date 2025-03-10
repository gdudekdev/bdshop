<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/config.php";

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
        $filename = cleanFilename("bdshop_" . $_POST["product_serie"] . "_" . $_POST["product_name"]);

        // On vérifie si il n'y a pas de doublon
        $is_file_search = true;
        $count = 1;
        while ($is_file_search) {
            $is_file_search = false;
            foreach (IMG_CONFIG as $key => $value) {
                if (file_exists($path . $key . "_" . $filename . ($count > 1 ? "(" . $count . ")" : "") . ".webp")) {
                    $is_file_search = true;
                    break;
                }
            }
            if ($is_file_search)
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
        foreach (IMG_CONFIG as $prefix => $info) {
            $filePath = $path . $filename . "." . $extension;
            $srcSize = getimagesize($filePath);
            $srcWidth = $srcSize[0];
            $srcHeight = $srcSize[1];

            $destWidth = $info['width'];
            $destHeight = $info['height'];

            $ratioSrc = $srcWidth / $srcHeight;
            $ratioDest = $destWidth / $destHeight;

            if ($ratioSrc > $ratioDest) {
                // L'image source est plus large que nécessaire, on ajuste la largeur
                $newHeight = $srcHeight;
                $newWidth = round($srcHeight * $ratioDest);
                $srcX = round(($srcWidth - $newWidth) / 2);
                $srcY = 0;
            } else {
                // L'image source est plus haute que nécessaire, on ajuste la hauteur
                $newWidth = $srcWidth;
                $newHeight = round($srcWidth / $ratioDest);
                $srcX = 0;
                $srcY = round(($srcHeight - $newHeight) / 2);
            }

            // Création de l'image de destination
            $dest = imagecreatetruecolor($destWidth, $destHeight);

            // Chargement de l'image source
            $imagecreatefrom = "imagecreatefrom" . str_replace("jpg", "jpeg", $extension);
            $src = $imagecreatefrom($filePath);

            // Redimensionnement et crop
            imagecopyresampled($dest, $src, 0, 0, $srcX, $srcY, $destWidth, $destHeight, $newWidth, $newHeight);

            // Enregistrement en WebP
            imagewebp($dest, $path . $prefix . "_" . $filename . ".webp", 100);

            // Nettoyage
            imagedestroy($dest);
            imagedestroy($src);
        }

        // Suppression de l'image originale
        if (file_exists($filePath)) {
            unlink($filePath);
        }

    }
    ;

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