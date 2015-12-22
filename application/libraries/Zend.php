<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Zend
{

	function __construct()
	{

		ini_set('include_path',
		ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'libraries');

	}


	function load($class)
	{
		require_once (string) $class . EXT;

	}
}

?>