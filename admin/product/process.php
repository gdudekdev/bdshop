<?php
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/function.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/protect.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/admin/include/connect.php";

// la première partie du test permet de vérifier si le formulaire a été soumis
// la deuxième partie du test permet de vérifier si il s'agit du bon formulaire
// on utilise && pour ne pas traiter le deuxième membre du test si le premier retourne false
if (isset($_POST["formCU"]) && $_POST["formCU"] == "ok") {
    // Ajout d'un champ dans la BDD
    if ($_POST["product_id"] == 0) {
      // Insert into permet d'ajouter une "ligne" dans la base de données
        $stmt = $db->prepare("INSERT INTO table_product(
      --  champs dans la BDD
            product_serie,
            product_name,
            product_date,
            product_volume,
            product_author,
            product_description,
            product_resume,
            product_stock,
            product_price,
            product_publisher,
            product_cartoonist
        ) VALUES (
      --   valeurs des champs
            :product_serie,
            :product_name,
            :product_date,
            :product_volume,
            :product_author,
            :product_description,
            :product_resume,
            :product_stock,
            :product_price,
            :product_publisher,
            :product_cartoonist
        )");
      //   bindValue permet de sécuriser les données et d'associer les valeurs aux champs
        $stmt->bindValue(":product_serie", $_POST["product_serie"]);
        $stmt->bindValue(":product_name", $_POST["product_name"]);
        $stmt->bindValue(":product_date", $_POST["product_date"]);
        $stmt->bindValue(":product_volume", $_POST["product_volume"]);
        $stmt->bindValue(":product_author", $_POST["product_author"]);
        $stmt->bindValue(":product_description", $_POST["product_description"]);
        $stmt->bindValue(":product_resume", $_POST["product_resume"]);
        $stmt->bindValue(":product_stock", $_POST["product_stock"]);
        $stmt->bindValue(":product_price", $_POST["product_price"]);
        $stmt->bindValue(":product_publisher", $_POST["product_publisher"]);
        $stmt->bindValue(":product_cartoonist", $_POST["product_cartoonist"]);
        $stmt->execute();
    } else {
        // Modification d'un champ existant dans la base de données
      //   Update permet de mettre à jour une ligne qui existe déjà
        $stmt = $db->prepare("UPDATE table_product SET
            product_serie = :product_serie,
            product_name = :product_name,
            product_date = :product_date,
            product_volume = :product_volume,
            product_author = :product_author,
            product_description = :product_description,
            product_resume = :product_resume,
            product_stock = :product_stock,
            product_price = :product_price,
            product_publisher = :product_publisher,
            product_cartoonist = :product_cartoonist
        WHERE 
            product_id = :product_id");

        $stmt->bindValue(":product_serie", $_POST["product_serie"]);
        $stmt->bindValue(":product_name", $_POST["product_name"]);
        $stmt->bindValue(":product_date", $_POST["product_date"]);
        $stmt->bindValue(":product_volume", $_POST["product_volume"]);
        $stmt->bindValue(":product_author", $_POST["product_author"]);
        $stmt->bindValue(":product_description", $_POST["product_description"]);
        $stmt->bindValue(":product_resume", $_POST["product_resume"]);
        $stmt->bindValue(":product_stock", $_POST["product_stock"]);
        $stmt->bindValue(":product_price", $_POST["product_price"]);
        $stmt->bindValue(":product_publisher", $_POST["product_publisher"]);
        $stmt->bindValue(":product_cartoonist", $_POST["product_cartoonist"]);

      //   On vient récupérer la valeur passée par l'input hidden pour pouvoir mettre à jour la bonne ligne de la BDD
        $stmt->bindValue(":product_id", $_POST["product_id"]);
        $stmt->execute();
    }
}

redirect("index.php");
