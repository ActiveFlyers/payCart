<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) OR die( 'Restricted access' );

?>
<style>
.pc-product-attribute-list{
	min-height : 36px;
}
.ui-draggable-handle {
	-ms-touch-action: none;
	touch-action: none;
}

.ui-draggable-dragging{
	width: 400px;
}

ul.pc-attribute-list,
ul.pc-product-attribute-list{
	list-style : none;
	margin: 0px;
}

ul.pc-attribute-list li.pc-attribute,
ul.pc-product-attribute-list li.pc-product-attribute {
	background: #FFFFFF;
	margin: 0px 5px 5px 0px;
	padding: 5px;
	border: 1px dashed #CCCCCC;
	line-height:36px;
}

ul.pc-product-attribute-list li.pc-product-attribute,
ul.pc-attribute-list li.pc-attribute.pc-attribute-draggable{
	background: #F6F6F6;
	border: 1px solid #CCCCCC;
}

.pc-droppable-highlight{    
    border: 2px dashed #CCCCCC;
}
.pc-attribute-draggable:hover,
ul.pc-product-attribute-list li.pc-product-attribute{
	cursor: move; 
}

.pc-product-coverimage{
	border : 3px solid #CCCCCC;
}

.pc-product .thumbnails .thumbnail img{
	max-height : 128px;
	cursor: grab;
}

.pc-product fieldset.scheduler-border {
    border: 1px solid #CCC !important;
    padding: 0 1.4em 1.4em 1.4em !important;
    margin: 0 0 1.5em 0 !important;    
}
.pc-product .ui-state-highlight { 
	line-height: 1.2em; 
	border : 1px solid #fcefa1;
	height:36px;
	background-color: #FAFAD2;
}

.pc-product legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }

</style>
<?php 