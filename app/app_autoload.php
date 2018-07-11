<?php
/********************************************************************************************************************************/
// Autoload
/********************************************************************************************************************************/

// PHP Class autoloader
function class_autoload($class_name)
{
	$result = false;
	$class_path = get_class_path($class_name);
	if($class_path !== false)
	{
		include $class_path;
		$result = true;
	}

	return $result;
}

// Get path to class
function get_class_path($class_name)
{
	$tmp_base_dir = array('app','act');
	$class_path = false;
	// Build path 
	foreach($tmp_base_dir as $dir)
	{
		$tmp_path =  './' . $dir . '/' . $class_name . '.php';
		if(file_exists($tmp_path)) 
		{
			$class_path = $tmp_path;
			break;
		}
	}
	
	return $class_path;
	
}

// Register autoloader function
spl_autoload_register('class_autoload');

/********************************************************************************************************************************/
// END FILE
/********************************************************************************************************************************/
?>