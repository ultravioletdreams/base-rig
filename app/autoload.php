<?php
/********************************************************************************************************************************/
// Autoload
/********************************************************************************************************************************/

// PHP Class autoloader
function class_autoload($class_name)
{
	include './app/' . $class_name . '.php';
}

// Register autoloader function
spl_autoload_register('class_autoload');

/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>