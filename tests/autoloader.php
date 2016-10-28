<?php
function loader($class)
{
	$class = explode('\\', $class);
	$class = array_pop($class);


    $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $class . '.php';
    if (file_exists($file)) {
        require $file;
    }
}
spl_autoload_register('loader');