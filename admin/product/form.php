<?php 
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";
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
		<input type="text" name="product_serie" id="product_serie" >
		<label for="product_name">Titre</label>
		<input type="text" name="product_name" id="product_name" >

		<input type="hidden" name="formCU" value="ok">
		<input type="submit" value="Enregistrer">

		<a href="index.php">Retour</a>
	</form>
</body>

</html>