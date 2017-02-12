<?php
/**
 * Autoloader file.
 *
 * PHP version 5.3
 *
 * @category Autoloader
 * @package  HtmlGenerator
 * @author   Airmanbzh <noemail@noemail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/airmanbzh/php-html-generator
 */
/**
 * Autoloader file.
 *
 * @category Autoloader
 * @package  HtmlGenerator
 * @author   Airmanbzh <noemail@noemail.com>
 * @license  http://opensource.org/licenses/mit-license.php MIT
 * @link     https://github.com/airmanbzh/php-html-generator
 */
/**
 * Function performs the loading of classes.
 *
 * @param string $class The class to load.
 *
 * @return void
 */
function loader($class)
{
    $class = explode('\\', $class);
    $class = array_pop($class);

    $file = sprintf(
        '%s%s..%s%s.php',
        __DIR__,
        DIRECTORY_SEPARATOR,
        DIRECTORY_SEPARATOR,
        $class
    );
    if (file_exists($file)) {
        include $file;
    }
}
spl_autoload_register('loader');
