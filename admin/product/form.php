<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

// Initialisation du formulaire (nécessaire pour que l'on puisse ajouter un produit et le modifier avec le même formulaire)
$product_serie = "";
$product_name = "";
$product_id = 0;
$product_date=date("Y-m-d"); // Dans le cas où on a un champ date

if (isset($_GET["id"]) && is_numeric($_GET["id"])){	
	$stmt= $db -> prepare ("SELECT * FROM table_product WHERE product_id = :product_id");
	$stmt -> bindValue(":product_id", $_GET["id"]);
	$stmt -> execute();

	if($row = $stmt -> fetch()){
		$product_serie = $row["product_serie"];
		$product_name = $row["product_name"];
		$product_id = $row["product_id"];
	};
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Formulaire Produit</title>
</head>

<body>
	<form action="process.php" method="post">

		<label for="product_serie">Serie</label>
		<input type="text" name="product_serie" id="product_serie" value="<?= hsc($product_serie)?>">
		<label for="product_name">Titre</label>
		<input type="text" name="product_name" id="product_name" value="<?= hsc($product_name)?>" >

		<input type="hidden" name="product_id" value="<?= hsc($product_id)?>">
		<input type="hidden" name="formCU" value="ok">
		<input type="submit" value="Enregistrer">

		<a href="index.php">Retour</a>
	</form>
</body>

</html>