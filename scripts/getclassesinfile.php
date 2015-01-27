<?php

function file_get_php_classes($filepath) {
  $php_code = file_get_contents($filepath);
  $classes = get_php_classes($php_code);
  return $classes;
}

function get_php_classes($php_code) {
  $classes = array();
  $tokens = token_get_all($php_code);
  $count = count($tokens);
  for ($i = 2; $i < $count; $i++) {
    if (   $tokens[$i - 2][0] == T_CLASS
        && $tokens[$i - 1][0] == T_WHITESPACE
        && $tokens[$i][0] == T_STRING) {

        $class_name = $tokens[$i][1];
        $classes[] = $class_name;
    }
  }
  return $classes;
}


require_once dirname(__DIR__).'/scripts/_filelist.php';

$classes = array();
foreach($files as $file){
	//@TODO:: should be move into phing script
	//exclude files from admin/install/extension folder
	if ( 0 === strpos( $file, 'admin/install/extensions') ) {
		continue;
	}

	$cs = file_get_php_classes(dirname(__DIR__).'/source/'.$file);
	foreach($cs as $c){
		$classes[strtolower($c)] = $file;
	}
}

ksort($classes);
$data = '<?php '.PHP_EOL
		.'/**'.PHP_EOL
		.'* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.'.PHP_EOL
		.'* @license		GNU/GPL, see LICENSE.php'.PHP_EOL
		.'* @package		Paycart '.PHP_EOL
		.'* @author 		support+paycart@readybytes.in'.PHP_EOL
		.'*/'.PHP_EOL
		.'if(defined("_JEXEC")===false) die();'.PHP_EOL
		.''.PHP_EOL
		.' return '.var_export($classes,true)
		.';';
if(file_put_contents(dirname(__DIR__).'/source/site/paycart/classes.php', $data)===FALSE){
	return dirname(__DIR__).'/source/site/paycart/classes.php';
	return 1;
}

return 0;
