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
function redirect($path){
    header('Location:' . $path);
    exit();
};

/**
 * Escapes special characters in a string for use in HTML.
 *
 * This function checks if the provided string is null. If it is, it returns an empty string.
 * Otherwise, it uses the `htmlspecialchars` function to convert special characters to HTML entities.
 *
 * @param string|null $string The input string to be escaped. If null, an empty string is returned.
 * @return string The escaped string, or an empty string if the input was null.
 */
function hsc($string){
    return (is_null($string)?"":htmlspecialchars($string));
};
