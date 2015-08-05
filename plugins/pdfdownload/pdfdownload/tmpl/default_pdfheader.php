<?php 
/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license	    GNU/GPL, see LICENSE.php
* @package	    PAYCART
* @subpackage	PDFEXPORT
* @contact 	    team@readybytes.in
*/

// no direct access
if(defined('_JEXEC')===false) die();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo "pdfDownload";?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
<style type="text/css">
<?php $mpdf->WriteHTML(file_get_contents(JPATH_ROOT.DS.'media/jui/css/bootstrap.css'), 1);?>
<?php $mpdf->WriteHTML(file_get_contents(JPATH_ROOT.DS.'media/jui/css/bootstrap-responsive.css'), 1);?>

</style>
</head>
<body>
<?php 
