<?php
/**
 * Redirects the user to the specified path.
 *
 * This function sends an HTTP header to the client to redirect them to the given path
 * and then terminates the script execution.
 *
 * @param string $path The path to redirect to. This can be a relative or absolute URL.
 *
 * @return void
 */
function redirect($path)
{
    header('Location:' . $path);
    exit();
}

/**
 * Escapes special characters in a string for use in HTML.
 *
 * This function checks if the provided string is null. If it is, it returns an empty string.
 * Otherwise, it uses the `htmlspecialchars` function to convert special characters to HTML entities.
 *
 * @param string|null $string The input string to be escaped. If null, an empty string is returned.
 * @return string The escaped string, or an empty string if the input was null.
 */
function hsc($string)
{
    return (is_null($string) ? "" : htmlspecialchars($string));
}

/**
 * Generates pagination links.
 *
 * @param mixed $currentPage : à lire directement via method GET sur l'attribut param
 * @param mixed $total_pages : valeur calculée au préalable dans le modèle
 * @param mixed $nbPerPage : à définir par l'utilisateur;
 * @param mixed $baseUrl : l'url auquel va renvoyer les liens
 * @param mixed $param : attribut name que l'on va utiliser via GET (le même que pour currentPage)
 * @return bool|string
 */
// A PLACER DANS LE MODELE DE LA PAGE
////////////////////////////////////////////////////////////////////////////////////////////////////////////
// $defaultPerPage = 20;
// // Nombre de page via le dropdown
// $nbPerPage = filter_input(INPUT_GET, 'nbPerPage', FILTER_VALIDATE_INT) ?: $defaultPerPage;
// // Page actuellement sélectionnée
// $currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
// $offset = ($currentPage - 1) * $nbPerPage;

// // définition des paramètres nécessaires pour la pagination
// $total_products_stmt = $db->prepare("SELECT COUNT(*) FROM table_product");
// $total_products_stmt->execute();
// $total_products = $total_products_stmt->fetch()[0];
// $total_pages = max(1, ceil($total_products / $nbPerPage));

// // Requête correspondant au numéro de la page
// $stmt = $db->prepare("SELECT * FROM table_product ORDER BY product_id DESC LIMIT :offset, :nbPerPage");
// $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
// $stmt->bindValue(':nbPerPage', $nbPerPage, PDO::PARAM_INT);
// $stmt->execute();
// $recordset = $stmt->fetchAll();
////////////////////////////////////////////////////////////////////////////////////////////////////////////
function generatePagination($currentPage, $total_pages, $nbPerPage, $baseUrl = 'index.php', $param = "page")
{
    if ($currentPage < 1) {
        $currentPage = 1;
    }
    if ($currentPage > $total_pages) {
        $currentPage = $total_pages;
    }
    if ($total_pages > 1) {
        ob_start(); ?>

        <?php if ($currentPage > 1) { ?>
            <a href="<?= $baseUrl ?>?<?= $param ?>=<?= $currentPage - 1 ?>&nbPerPage=<?= $nbPerPage ?>">&laquo; Précédent</a>
        <?php } ?>

        <?php if ($currentPage > 3) { ?>
            <a href="<?= $baseUrl ?>?<?= $param ?>=1&nbPerPage=<?= $nbPerPage ?>">1</a>
            <?php if ($currentPage > 4) { ?>
                <span class='inactive'>...</span>
            <?php } ?>
        <?php } ?>

        <?php for ($i = max(1, $currentPage - 2); $i <= min($total_pages, $currentPage + 2); $i++) { ?>
            <a href="<?= $baseUrl ?>?<?= $param ?>=<?= $i ?>&nbPerPage=<?= $nbPerPage ?>"
                class="<?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
        <?php } ?>

        <?php if ($currentPage < $total_pages - 2) { ?>
            <?php if ($currentPage < $total_pages - 3) { ?>
                <span class='inactive'>...</span>
            <?php } ?>
            <a href="<?= $baseUrl ?>?<?= $param ?>=<?= $total_pages ?>&nbPerPage=<?= $nbPerPage ?>"><?= $total_pages ?></a>
        <?php } ?>

        <?php if ($currentPage < $total_pages) { ?>
            <a href="<?= $baseUrl ?>?<?= $param ?>=<?= $currentPage + 1 ?>&nbPerPage=<?= $nbPerPage ?>">Suivant &raquo;</a>
        <?php } ?>
        <?php return ob_get_clean();
    } else {
        return 0;
    }
}


/**
 *  Cleans a string to make it a valid filename.
 * 
 * @param mixed $str
 */
function cleanFilename($str){
    $result = strtolower($str);

    $charKo = [' ', '/', '\\', '?', '%', '*', ':', '|', '"', '<', '>', '.', 'é', 'è', 'ê', 'à', 'ç', 'ù', 'ô', 'î', 'ï', 'â', 'ä', 'ë', 'ü', 'û', 'ÿ', 'œ', '€'];
    $charOk = ['-', '-', '-', '', '', '', '', '', '', '', '', '', 'e', 'e', 'e', 'a', 'c', 'u', 'o', 'i', 'i', 'a', 'a', 'e', 'u', 'u', 'y', 'oe', 'euro'];
    
    $result = str_replace($charKo, $charOk, $result);
    
    return trim($result,"_");
}

// function resize($source ,$destination){
//     // On récupère les dimensions de l'image
//     $source_size = getimagesize($source);
//     $source_width = $source_size[0];
//     $source_height = $source_size[1];

//     // On définit les dimensions de l'image finale*
//     $ratio = $source_width / $source_height;
//     if($ratio > 1){
//         $final_width = 200;
//         $final_height = 200 / $ratio;
//     } else {
//         $final_height = 200;
//         $final_width = 200 * $ratio;
//     }

//     // On crée une image vide aux dimensions finales
//     $image_finale = imagecreatetruecolor($final_width, $final_height);

//     // On charge l'image source
//     switch($size['mime']){
//         case 'image/jpeg':
//             $image_source = imagecreatefromjpeg($path);
//             break;
//         case 'image/png':
//             $image_source = imagecreatefrompng($path);
//             break;
//         case 'image/gif':
//             $image_source = imagecreatefromgif($path);
//             break;
//         default:
//             return false;
//     }
    
//     // On redimensionne l'image source dans l'image finale
//     imagecopyresampled($image_finale, $image_source, 0, 0, 0, 0, $final_width, $final_height, $width, $height);

//     // On enregistre l'image finale
//     imagewebp($image_finale, $destination);

//     // On libère la mémoire
//     imagedestroy($image_finale);
//     imagedestroy($image_source);

//     return $destination;

// }

function uploadProductImage($file, $postData) {
    if (!isset($file['product_image'])) {
        return "Aucune image reçue";
    }

    $path = $_SERVER['DOCUMENT_ROOT'] . "/upload/";
    if ($file['product_image']['error'] != 0) {
        return "Erreur lors de l'upload de l'image";
    }

    $extension = strtolower(pathinfo($file['product_image']['name'], PATHINFO_EXTENSION));
    if (!validateImage($file['product_image'], $extension)) {
        return "L'extension de l'image n'est pas valide";
    }

    $filename = cleanFilename("bdshop_" . $postData["product_serie"] . "_" . $postData["product_name"]);
    $filename = resolveFilenameConflict($path, $filename);
    
    move_uploaded_file($file['product_image']['tmp_name'], $path . $filename . "." . $extension);
    processImage($path, $filename, $extension);
    
    return "Image uploadée et traitée avec succès";
}

function validateImage($image, $extension) {
    return ("image/" . str_replace("jpg", "jpeg", $extension) === $image['type']) && in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
}

function resolveFilenameConflict($path, $filename) {
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
        if ($is_file_search) $count++;
    }
    return $count > 1 ? $filename . "(" . $count . ")" : $filename;
}

function processImage($path, $filename, $extension) {
    foreach (IMG_CONFIG as $prefix => $info) {
        $filePath = $path . $filename . "." . $extension;
        list($srcWidth, $srcHeight) = getimagesize($filePath);
        
        $destWidth = $info['width'];
        $destHeight = $info['height'];
        $ratioSrc = $srcWidth / $srcHeight;
        $ratioDest = $destWidth / $destHeight;
        
        if ($ratioSrc > $ratioDest) {
            $newHeight = $srcHeight;
            $newWidth = round($srcHeight * $ratioDest);
            $srcX = round(($srcWidth - $newWidth) / 2);
            $srcY = 0;
        } else {
            $newWidth = $srcWidth;
            $newHeight = round($srcWidth / $ratioDest);
            $srcX = 0;
            $srcY = round(($srcHeight - $newHeight) / 2);
        }
        
        $dest = imagecreatetruecolor($destWidth, $destHeight);
        $imagecreatefrom = "imagecreatefrom" . str_replace("jpg", "jpeg", $extension);
        $src = $imagecreatefrom($filePath);
        
        imagecopyresampled($dest, $src, 0, 0, $srcX, $srcY, $destWidth, $destHeight, $newWidth, $newHeight);
        imagewebp($dest, $path . $prefix . "_" . $filename . ".webp", 100);
        
        imagedestroy($dest);
        imagedestroy($src);
    }
    
    if (file_exists($filePath)) {
        unlink($filePath);
    }
}

