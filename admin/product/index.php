<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

$stmt = $db->prepare("SELECT * FROM table_product ORDER BY product_id DESC");
$stmt->execute();
$recordset = $stmt->fetchAll();
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
    </style>
</head>

<body>
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
                    <a href="form.php?id=<?= hsc($row['product_id']); ?>">Modiff</a>
                    <a href="delete.php?id=<?= hsc($row['product_id']); ?>">Supp</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>

</html>