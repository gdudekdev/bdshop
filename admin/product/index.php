<?php
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
    <title>Document</title>
</head>

<body>
    <table>
        <tr>
            <th>Serie</th>
            <th>Titre</th>
            <th>Action</th>
        </tr>
        <?php foreach ($recordset as $row) { ?>
            <tr>
                <td><?= hsc($row['product_serie']); ?></td>
                <td><?= hsc($row['product_name']); ?></td>
                <td>
                    <a href="form.php?id=<?= hsc($row['product_id']); ?>">Modifier</a>
                    <a href="delete.php?id=<?= hsc($row['product_id']); ?>">Supprimer</a>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>