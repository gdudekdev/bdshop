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
}
