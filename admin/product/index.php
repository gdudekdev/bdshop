<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

// Nombre de produits par page par défaut
$defaultPerPage = 20;

// Récupérer le nombre de produits par page choisi par l'utilisateur
$nbPerPage = isset($_GET['nbPerPage']) ? (int)$_GET['nbPerPage'] : $defaultPerPage;

// Page actuelle
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($currentPage - 1) * $nbPerPage;

// Récupérer le nombre total de produits
$total_products_stmt = $db->prepare("SELECT COUNT(*) FROM table_product");
$total_products_stmt->execute();
$total_products = $total_products_stmt->fetchColumn();

// Calculer le nombre total de pages
$total_pages = ceil($total_products / $nbPerPage);

// Récupérer les produits pour la page actuelle
$stmt = $db->prepare("SELECT * FROM table_product ORDER BY product_id DESC LIMIT :offset, :nbPerPage");
$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
$stmt->bindValue(':nbPerPage', $nbPerPage, PDO::PARAM_INT);
$stmt->execute();
$recordset = $stmt->fetchAll();

function generatePagination($currentPage, $total_pages, $nbPerPage) {
    ob_start();
    ?>
    <div class="pagination">
        <?php if ($currentPage > 1): ?>
            <a href="?page=<?= $currentPage - 1 ?>&nbPerPage=<?= $nbPerPage ?>">&laquo; Précédent</a>
        <?php endif; ?>

        <?php if ($currentPage > 3): ?>
            <a href="?page=1&nbPerPage=<?= $nbPerPage ?>">1</a>
            <?php if ($currentPage > 4): ?>
                <span>...</span>
            <?php endif; ?>
        <?php endif; ?>

        <?php for ($i = max(1, $currentPage - 2); $i <= min($total_pages, $currentPage + 2); $i++): ?>
            <a href="?page=<?= $i ?>&nbPerPage=<?= $nbPerPage ?>" class="<?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($currentPage < $total_pages - 2): ?>
            <?php if ($currentPage < $total_pages - 3): ?>
                <span>...</span>
            <?php endif; ?>
            <a href="?page=<?= $total_pages ?>&nbPerPage=<?= $nbPerPage ?>"><?= $total_pages ?></a>
        <?php endif; ?>

        <?php if ($currentPage < $total_pages): ?>
            <a href="?page=<?= $currentPage + 1 ?>&nbPerPage=<?= $nbPerPage ?>">Suivant &raquo;</a>
        <?php endif; ?>

        <form action="index.php" method="get" class="page-form">
            <input type="number" name="page" min="1" max="<?= $total_pages ?>" value="<?= $currentPage ?>">
            <input type="hidden" name="nbPerPage" value="<?= $nbPerPage ?>">
            <input type="submit" value="Go">
        </form>
    </div>
    <?php
    return ob_get_clean();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produits</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
            margin-top: 60px; 
        }
        th {
            background-color: rgb(206, 206, 206);
            position: sticky;
            top: 0; 
            z-index: 1;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        tr {
            border: 1px solid black;
        }
        td, th {
            padding: 8px;
            text-align: left;
        }
        .action-links {
            display: flex;
            gap: 10px;
        }
        .action-links a {
            text-decoration: none;
            color: black;
            padding: 5px 10px;
            border: 1px solid black;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }
        .action-links a:hover {
            background-color: black;
            color: #fff;
        }
        .add-button {
            background-color:rgb(134, 134, 134);
            color: white;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 4px;
            border: 1px solid black;
            cursor: pointer; 
            z-index: 2;
        }
        .add-button:hover {
            background-color:rgb(123, 124, 125);
        }
        .pagination {
            margin: 20px 0;
            text-align: center;
        }
        .pagination a {
            text-decoration: none;
            color: black;
            padding: 8px 16px;
            border: 1px solid black;
            border-radius: 4px;
            margin: 0 2px;
            transition: background-color 0.3s, color 0.3s;
        }
        .pagination a:hover {
            background-color: black;
            color: #fff;
        }
        .pagination .active {
            background-color: black;
            color: #fff;
            border: 1px solid black;
        }
        .per-page-form {
            margin-bottom: 20px;
            text-align: center;
        }
        .page-form {
            display: inline-block;
            margin-left: 10px;
        }
        .page-form input[type="number"] {
            width: 50px;
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .page-form input[type="submit"] {
            padding: 5px 10px;
            border: 1px solid black;
            border-radius: 4px;
            background-color: rgb(134, 134, 134);
            color: white;
            cursor: pointer;
        }
        .page-form input[type="submit"]:hover {
            background-color: rgb(123, 124, 125);
        }
    </style>
</head>

<body>
<a href="../index.php" class="add-button">Retour</a>    

<div class="per-page-form">
    <form action="index.php" method="get">
        <label for="nbPerPage">Nombre d'éléments par page :</label>
        <select name="nbPerPage" id="nbPerPage" onchange="this.form.submit()">
            <option value="10" <?= $nbPerPage == 10 ? 'selected' : '' ?>>10</option>
            <option value="20" <?= $nbPerPage == 20 ? 'selected' : '' ?>>20</option>
            <option value="50" <?= $nbPerPage == 50 ? 'selected' : '' ?>>50</option>
            <option value="100" <?= $nbPerPage == 100 ? 'selected' : '' ?>>100</option>
        </select>
    </form>
</div>

<?= generatePagination($currentPage, $total_pages, $nbPerPage) ?>

<table>
    <tr>
        <th>References</th>
        <th>Date</th>
        <th>Titre</th>
        <th>Serie</th>
        <th>Volume</th>
        <th>Auteur</th>
        <th>Description</th>
        <th>Resume</th>
        <th>Stock</th>
        <th>Prix</th>
        <th>Publication</th>
        <th>Dessinateur</th>
        <th><a href="form.php" class="add-button">Ajouter</a></th>
    </tr>
    <?php foreach ($recordset as $row) { ?>
        <tr>
            <td><?= hsc($row['product_slug']); ?></td>
            <td><?= hsc($row['product_date']); ?></td>
            <td><?= hsc($row['product_name']); ?></td>
            <td><?= hsc($row['product_serie']); ?></td>
            <td><?= hsc($row['product_volume']); ?></td>
            <td><?= hsc($row['product_author']); ?></td>
            <td><?= hsc($row['product_description']); ?></td>
            <td><?= hsc($row['product_resume']); ?></td>
            <td><?= hsc($row['product_stock']); ?></td>
            <td><?= hsc($row['product_price']); ?></td>
            <td><?= hsc($row['product_publisher']); ?></td>
            <td><?= hsc($row['product_cartoonist']); ?></td>
            <td class="action-links">
                <a href="form.php?id=<?= hsc($row['product_id']); ?>">Modif.</a>
                <a href="delete.php?id=<?= hsc($row['product_id']); ?>">Supp.</a>
            </td>
        </tr>
    <?php } ?>
</table>

<?= generatePagination($currentPage, $total_pages, $nbPerPage) ?>

</body>

</html>