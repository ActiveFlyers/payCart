<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		rimjhim jain
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/**
 * List of populated Variables
 * $displayData : object of stdclass containing data to show applied filters
 */

$categoryTree = $displayData->core->categoryTree;
$selectedCategoryId = $displayData->core->selectedCategoryId;
$searchWord = $displayData->searchWord;

//recursive function to display tree structure of categories
$displayTree = function($tree,$displayData) use (&$displayTree) {
	$selectedCategory    = $displayData->core->selectedCategoryId;
	$categories		     = $displayData->core->categories;
	$searchWord			 = $displayData->searchWord;
	
    if (!is_array($tree)) {
    	$title   = ($selectedCategory == $tree)?'<strong>'.$categories[$tree]->title.'</strong>':$categories[$tree]->title;
    	$q       = ($searchWord)?'?q='.urlencode($searchWord):'';
    	$link    = PaycartRoute::_("index.php?option=com_paycart&view=productcategory&task=display&productcategory_id=".$tree).$q;
    	$html    = '<span class="pc-cursor-pointer" data-pc-category="click" data-pc-categorylink="'.$link.'" data-pc-categoryid="'.$tree.'">'.
    			  str_repeat('<span class="gi">&mdash;</span>', ($categories[$tree]->level - 1)<0?0:($categories[$tree]->level - 1)).
    			  $title.'</span><br/>';
						
        echo $html;
        return;
    }

    foreach($tree as $k => $value) {
       $displayTree($k,$displayData);
        if(is_array($value)){
            $displayTree($value,$displayData);
        }
    }
};
		
?>
<div>
	<h4><?php echo JText::_("COM_PAYCART_CATEGORIES")?></h4>
	<?php 
		// Display Link to All category
		$q    = ($searchWord)?'?q='.urlencode($searchWord):'';
		$link = PaycartRoute::_("index.php?option=com_paycart&view=productcategory&task=display").$q;
		echo '<span class="pc-cursor-pointer" data-pc-category="click" data-pc-categorylink="'.$link.'" data-pc-categoryid="0">'.JText::_('COM_PAYCART_ALL').'</span><br/>';
		echo $displayTree($categoryTree,$displayData);
	?>
		
	<input type="hidden" name="filters[core][category]" value="<?php echo $selectedCategoryId;?>"/>
</div>
<?php 