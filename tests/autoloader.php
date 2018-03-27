<?php
// backward compatibility
if (!class_exists('\PHPUnit\Framework\TestCase') && class_exists('\PHPUnit_Framework_TestCase')) {
    class_alias('PHPUnit_Framework_TestCase', '\PHPUnit\Framework\TestCase');
}

function loader($class)
{
	$class = explode('\\', $class);
	$class = array_pop($class);

    $file = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . $class . '.php';
	
    if (file_exists($file)) {
        require $file;
    }
}

spl_autoload_register('loader');