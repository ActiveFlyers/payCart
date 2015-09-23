<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license	    GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	back-end
 * @contact	    support+paycart@readybytes.in
 * @author      Rimjhim Jain
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
?>
<table style="max-width: 600px; border-left: solid 1px #e6e6e6; border-right: solid 1px #e6e6e6; border-bottom: 1px solid rgb(230, 230, 230);" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td style="width: 10px; background-color: #ededed; border-top: solid 1px #e6e6e6;" bgcolor="#ededed" width="10"></td>
<td style="background-color: #ededed;  border-top: solid 1px #e6e6e6;" align="left" bgcolor="#00436d"><img height="30" border="0" style="border:none" alt="[[store_name]]" src="[[company_logo]]"></td>
<td style="background-color: #ededed; padding: 0; margin: 0; border-top: solid 1px #e6e6e6;" align="right" bgcolor="#ededed" height="50" valign="middle">[[buyer_orders]] | [[buyer_account]] | [[store_url]]</td>
<td style="width: 10px; background-color: #ededed; border-top: solid 1px #e6e6e6;" bgcolor="#ededed" width="10">&nbsp;</td>
</tr>
</tbody>
</table>
<table style="max-width: 600px; border-left: solid 1px #e6e6e6; border-right: solid 1px #e6e6e6;" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td height="65" align="center" style="font-size:21px;color:#000000;font-weight:normal">YOUR ORDER IS COMPLETED</td>
</tr>
<tr>
<td style="color: #2c2c2c; display: block; line-height: 20px; font-weight: 300; margin: 0 auto; clear: both; border-top: 1px solid #e6e6e6; border-bottom: 1px solid #e6e6e6; background-color: #ededed; padding: 20px;" align="left" bgcolor="#ededed" valign="top">
<p style="padding: 0; margin: 0; font-size: 13px; font-weight: bold;">Hi [[buyer_name]],</p>
<br>
<p style="padding: 0; margin: 0; color: #565656; font-size: 13px;">All of the items have been delivered successfully. Thank you for purchasing!</p>
<p style="padding: 0; margin: 0; color: #565656; line-height: 22px; font-size: 13px;">Your Order ID :&nbsp;<span style="color: #2c2c2c; font-size: 11px; line-height: 20px;">[[order_id]]</span></p>
</td>
</tr>
</tbody>
</table>
<table style="border-left: 1px solid rgb(230, 230, 230); border-right: 1px solid rgb(230, 230, 230); max-width: 600px;" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td style="font-size: 14px; color: rgb(93, 93, 93); font-weight: normal; padding:15px;" height="40"><span style="  font-size: 16px; color: #000000; font-weight: normal;">ORDER DETAILS</span></td>
</tr>
<tr>
<td style="  font-size: 14px; color: #5d5d5d; font-weight: normal;">
[[products_detail]]
</td>
</tr>
</tbody>
</table>
<table style="max-width: 600px; border: solid 1px #e6e6e6;" cellpadding="0" cellspacing="0" width="100%">
<tbody>
<tr>
<td style="background-color: #ffffff; color: #565656; display: block; font-weight: 300; margin: 0; padding: 0; clear: both;" align="left" bgcolor="#ffffff" valign="top">
<table style="height: 168px;" cellpadding="0" cellspacing="0" width="598">
<tbody>
<tr>
<td style="padding: 20px 20px 0 20px; margin: 0;" align="left" valign="top">
<p style="margin: 0; padding: 0; color: #565656; font-size: 13px;">ADDRESS</p>
<p style="padding: 0; margin: 15px 0 10px 0; font-size: 18px; color: #333333;">[[buyer_name]] &nbsp; &nbsp; &nbsp;[[shipping_phone]]</p>
<p style="line-height: 18px; padding: 0; margin: 0; color: #565656; font-size: 13px;">[[shipping_address]]<br>[[shipping_city]]</p>
<p style="line-height: 18px; padding: 0; margin: 0; color: #565656; font-size: 13px;">[[shipping_state]] -&nbsp;[[shipping_zip_code]]</p>
<p style="line-height: 18px; padding: 0; margin: 0; color: #565656; font-size: 13px;">[[shipping_country]]</p>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<?php 