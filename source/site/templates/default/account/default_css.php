<?php
/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<style>
.pc-account-header-avatar{
	margin-bottom: 10px;
    margin-right: 10px;
}
.pc-account-header-name{
    margin-top: 0;
}

.pc-account-order-details{
	border : 1px solid #E5E5E5;
	padding: 10px;
	margin-bottom: 30px;
}

.pc-account .heading{
	font-size: 16px;
    font-weight: bold;
}

.pc-account-order-product .table .progress{
	height: 8px;
	margin-bottom: 10px;
}

.pc-track-tbl-border{
	 padding-top: 5px;
}

.shipment-row .table th, .shipment-row .table td {
    border-top: medium none;
}

.pc-track-shipment .table td {
    width: 50%;
}

.pc-track-note {
    padding: 8px;
}

.pc-track-arrow::after {
    border-color: #e3e3e3 transparent;
    border-style: solid;
    border-width: 0 15px 15px;
    content: "";
    display: block;
    left: 50%;
    margin-left: -15px;
    position: absolute;
    top: -15px;
    width: 0;
    z-index: 1;
}

.pc-track-shipment .well {
    padding: 10px;
}

.pc-track-arrow {
    position: relative;
}

.pc-account-order-productdetails-header .heading {
    line-height: 2;
    font-weight: 600;
    color: #777;
}

.pc-account-order-productdetails {
	border-color: -moz-use-text-color #eee #eee;
    border-style: none solid solid;
    border-width: 0 1px 1px;
}
.shipment-row .dwn-icon{font-size: 16px;color: #333;}

.all-orders {
    font-weight: 600;
    font-size: 18px;
}
.accordion-group {
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.05);
    box-shadow: 0 1px 1px rgba(0,0,0,.05);
}
.accordion-header.well-small {
    color: #333;
    background-color: #f5f5f5;
    border-color: #ddd;
}
.font500 {
    font-weight: 500;
}
.order-item-title > span {
    margin-right: 6px;
}
.order-items-content .well-small {
    border-top: 1px solid #eee;
}
.view-order-details {
	color: #333;
}
a.thumbnail:hover{
	text-decoration:none;
	border-color: #ddd;
}
</style>
<?php 