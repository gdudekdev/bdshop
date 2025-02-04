<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/public/connect.php";
$stmt = $db->prepare('SELECT * FROM table_product');
$stmt->execute();
$recordset = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>BDShop</title>

</head>

<body>
    <h1>Liste des produits</h1>
    <table>
        <thead>
            <tr>
                <th>Nom du produit</th>
                <th>Nom de la série</th>
                <th>Auteur</th>
                <th>Prix</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($recordset as $row) { ?>
                <tr>
                    <td><?= htmlspecialchars($row['product_name']); ?></td>
                    <td><?= htmlspecialchars($row['product_serie']); ?></td>
                    <?php if (is_null($row['product_author'])) { ?>
                        <td><?= "Pas de nom d'auteur" ?></td>
                    <?php } else { ?>
                        <td><?= htmlspecialchars($row['product_author']); ?></td>
                    <?php } ?>
                    <td><?= htmlspecialchars($row['product_price']) . '€'; ?></td>
                </tr>
            <?php } ?>
        </tbody>
       </table>
</body>

</html>