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
        <div class="pagination">
            <?php if ($currentPage > 1) { ?>
                <a href="<?= $baseUrl ?>?<?= $param ?>=<?= $currentPage - 1 ?>&nbPerPage=<?= $nbPerPage ?>">&laquo; Précédent</a>
            <?php } ?>

            <?php if ($currentPage > 3) { ?>
                <a href="<?= $baseUrl ?>?<?= $param ?>=1&nbPerPage=<?= $nbPerPage ?>">1</a>
                <?php if ($currentPage > 4) { ?>
                    <span>...</span>
                <?php } ?>
            <?php } ?>

            <?php for ($i = max(1, $currentPage - 2); $i <= min($total_pages, $currentPage + 2); $i++) { ?>
                <a href="<?= $baseUrl ?>?<?= $param ?>=<?= $i ?>&nbPerPage=<?= $nbPerPage ?>"
                    class="<?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
            <?php } ?>

            <?php if ($currentPage < $total_pages - 2) { ?>
                <?php if ($currentPage < $total_pages - 3) { ?>
                    <span>...</span>
                <?php } ?>
                <a href="<?= $baseUrl ?>?<?= $param ?>=<?= $total_pages ?>&nbPerPage=<?= $nbPerPage ?>"><?= $total_pages ?></a>
            <?php } ?>

            <?php if ($currentPage < $total_pages) { ?>
                <a href="<?= $baseUrl ?>?<?= $param ?>=<?= $currentPage + 1 ?>&nbPerPage=<?= $nbPerPage ?>">Suivant &raquo;</a>
            <?php } ?>

            <form action="<?= $baseUrl ?>" method="get" class="page-form">
                <input type="number" name="page" min="1" max="<?= $total_pages ?>" value="<?= $currentPage ?>">
                <input type="submit" value="Aller">
            </form>
        </div>
        <?php return ob_get_clean();
    } else {
        return 0;
    }
}
