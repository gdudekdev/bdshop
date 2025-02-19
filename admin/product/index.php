<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

$defaultPerPage = 20;
// Nombre de page via le dropdown
$nbPerPage = filter_input(INPUT_GET, 'nbPerPage', FILTER_VALIDATE_INT) ?: $defaultPerPage;
// Page actuellement sélectionnée
$currentPage = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT) ?: 1;
$offset = ($currentPage - 1) * $nbPerPage;


// définition des paramètres nécessaires pour la pagination
$total_products_stmt = $db->prepare("SELECT COUNT(*) FROM table_product");
$total_products_stmt->execute();
$total_products = $total_products_stmt->fetch()[0];
$total_pages = max(1, ceil($total_products / $nbPerPage));


$stmt = $db->prepare("SELECT * FROM table_product ORDER BY product_id DESC LIMIT :offset, :nbPerPage");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':nbPerPage', $nbPerPage, PDO::PARAM_INT);
$stmt->execute();
$recordset = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <a href="../index.php" class="add-button">Retour</a>
    <form action="index.php" method="get" class="per-page-form">
        <label for="nbPerPage">Éléments par page :</label>
        <select name="nbPerPage" id="nbPerPage" onchange="this.form.submit()">
            <?php foreach ([10, 20, 50, 100] as $value) { ?>
                <option value="<?= $value ?>" <?= $nbPerPage == $value ? 'selected' : '' ?>><?= $value ?></option>
            <?php } ?>
        </select>
    </form>
    <?= generatePagination($currentPage, $total_pages, $nbPerPage) ?>
    <table>
        <tr>
            <?php $columns = ["References" => "product_slug", "Date" => "product_date", "Titre" => "product_name", "Serie" => "product_serie", "Volume" => "product_volume", "Auteur" => "product_author", "Description" => "product_description", "Resume" => "product_resume", "Stock" => "product_stock", "Prix" => "product_price", "Publication" => "product_publisher", "Dessinateur" => "product_cartoonist"];
            foreach ($columns as $label => $key) { ?>
                <th><?= $label ?></th>
            <?php } ?>
            <th><a href="form.php" class="add-button">Ajouter</a></th>
        </tr>
        <?php foreach ($recordset as $row) { ?>
            <tr>
                <?php foreach ($columns as $key) { ?> 
                    <td><?= hsc($row[$key]) ?></td>
                <?php } ?>
                <td class="action-links">
                    <a href="form.php?id=<?= hsc($row['product_id']) ?>">Modif.</a>
                    <a href="delete.php?id=<?= hsc($row['product_id']) ?>">Supp.</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?= generatePagination($currentPage, $total_pages, $nbPerPage) ?>
</body>
</html>
