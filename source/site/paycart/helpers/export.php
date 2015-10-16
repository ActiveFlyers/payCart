<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/** 
 * ExportToCSV Helper
 * @since 1.0.10
 * @author Neelam soni
 */
class PaycartHelperExportToCSV extends PaycartHelper
{
	/** 
	 * exportToCSV Function
	 * @desc	Function to export supplied entity's data to a CSV file
	 * @params	$entity , $start, $model , $export_fields
	 * 
	 * @return	null
	 */
	public function exportToCSV($entity , $start , $model , $export_fields=null , $filename)
	{		
		//If user wants to export limited products only, then set the limit and total of records accordingly
		$cid		 = JRequest::getVar('cid' , null);
		if(!empty($cid)){
			$limit	 = $total = count($cid);			
		}else{
			$limit	 = 20;		
			$model   = PaycartFactory::getInstance($entity, 'model');
			$total	 = $model->getTotal();
		}

		//Fetching the csv fields
		$csv_fields  = $this->getCsvFields($start , $limit, $entity, $model , $export_fields);
		
		//Creating a CSV File
 		$filename 	 = $this->createCSV($start, $csv_fields , $entity , $export_fields , $filename);
		
		$start 		 = $start + $limit;
		
		if($start<$total)
		{ 			
			//Warning the user for not refreshing the page as it may restart the export process again
			JFactory::getApplication()->enqueueMessage(JText::_('COM_PAYCART_PLEASE_DO_NOT_REFRESH') , 'warning');
						
			//Redirecting to get the next records
			$url = PaycartRoute::_('index.php?option=com_paycart&view='.$entity.'&task=export&start='.$start.'&filename='.$filename);
			?>
			<script>
				window.onload = function()
				{ setTimeout("redirect()", 1000); }
				function redirect()
				{ window.location = "<?php echo $url ?>" }
			</script>
			<?php
		}
		else
		{
			$url = PaycartRoute::_('index.php?option=com_paycart&view='.$entity.'&task=download');
			JFactory::getApplication()->enqueueMessage(JText::_('COM_PAYCART_CSV_EXPORT_SUCCESSFUL')."<a href='{$url}'> Click to download CSV Files.</a>");
			JFactory::getApplication()->redirect(PaycartRoute::_('index.php?option=com_paycart&view='.$entity.'&task=display'));
		}
	}
	
	/** 
	 * createCSV Function
	 * @desc	Function to create CSV File
	 * @params	int $start
	 * 			array $csv_fields
	 * 			string $entity
	 * 			array $export_fields
	 * 
	 * @return	string $CSVFileName
	 */
	public function createCSV($start, $csv_fields , $entity , $export_fields , $filename)
	{
		$csv_folder = PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP.$entity;
		
		// Create a folder to store csv exported files if it doesn't exists
		if(!JFolder::exists($csv_folder)){
			if(!JFolder::create($csv_folder)){
				throw new Exception(JText::sprintf('COM_PAYCART_ADMIN_EXCEPTION_PERMISSION_DENIED', $csv_folder));
			}
		}
		
		// delete the oldest file if count is greater than 15
		$file_names	=  array_diff(scandir(PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP.'product'), array('..', '.'));
		if(count($file_names) >= 15){
			foreach ($file_names as $file_name)
			{
			  $time = filemtime(PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP.'product/'.$file_name);
			  $files[$time] = $file_name;
			}
			krsort($files);
			unlink(PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP.'product/'.end($files));
		}
		
		// Get the date & time as per timezone
		$date 	= new DateTime();
		$config = JFactory::getConfig();
		$date->setTimezone(new DateTimeZone($config->get('offset')));
		
		$filename = $filename ? $filename : $entity.'_'.$date->format('Y-m-d_H:i:s');
		$CSVFileName = $csv_folder.'/'.$filename.'.csv';
		
		$fp = fopen($CSVFileName, 'a') or die("can't open file");		
		
		// Fetch CSV Headers if writing for the first time in the file
		if($start==0)
		{
			$csv_headers	= $this->getCsvHeader($entity , $export_fields);
			fwrite($fp,$csv_headers);
		}
		foreach ($csv_fields as $field)
		{
			fwrite($fp,$field);
		}
		fclose($fp);
		
		return $filename;
	}
	
	/** 
	 * getCsvHeader Function
	 * @desc	Function to get CSV Header Fields
	 * @params	string $entity
	 * 			array $export_fields
	 * 
	 * @return	array $csv_fields
	 */
	public function getCsvHeader($entity , $export_fields=null)
	{
		
		// Check if a language table is maintained for that product
		$prefix 		 = JFactory::getApplication()->getCfg('dbprefix');
		$langTableExists = $this->langTableExists($entity , $prefix);
		
		if(isset($export_fields))
		{	    
		    $csv_headers = '"'. implode('";"' , $export_fields). '"';		    
		}
		else
		{
			// Fetch column headings from Paycart entity
			$db		= JFactory::getDbo();
		    $query  = " SHOW FIELDS FROM `#__paycart_".$entity."` ";
		    $db->setQuery($query);
		    
		    $fields = $db->loadColumn();
		    
			// Fetch column headings from Paycart entity's lang table if it exists
			// Merger them with $fields			
		    if($langTableExists){
		    	$query   = " SHOW FIELDS FROM `#__paycart_{$entity}_lang` ";
		    	$db->setQuery($query);
		    	
		    	$result  = $db->loadColumn();
		    	
		    	// Remove the duplicate column here itself
		    	$key     = array_search($entity.'_id', $result);
		    	unset($result[$key]);
		    	
		    	$fields  = array_merge($fields , $result);		    	
		    } 
		    
		    $csv_headers = '"'. implode('";"' , $fields). '"';
		}
		
		return $csv_headers;
	}
	
	/** 
	 * getCsvFields Function
	 * @desc	Function to get CSV Fileds
	 * @params	int $start
	 * 			int $limit
	 * 			string $entity
	 * 			string $model
	 * 			array $export_fields
	 * 
	 * @return	array $csv_fields
	 */
	public function getCsvFields($start , $limit , $entity , $model , $export_fields=null)
	{		
		$model->setState('limit' , $limit);
       	$model->setState('limitstart' , $start);
       	
       	$model->getQuery()->limit($limit , $start);

		// Fetching the records	from database if user has not selected any particular record
		$entities	= array();
		$cid		= JRequest::getVar('cid');
		if(!empty($cid))
		{
			// It means user want to import selected records only
			foreach ($cid as $id)
			{
				$product	= PaycartProduct::getInstance($id);
				$entities[]	= $product->toArray();
			}
		}
		else
		{
			$entities	= $model->loadRecords();
			$entities	= array_values(json_decode(json_encode($entities), true));
		}
		
		
		$count	   	= count($entities);		
		$csv_fields = array();
		
		for($i=0, $k=0 ; $i<$count ; $i++, $k++)
		{
			$csv_fields[] = "\n"; //if we give single quotes, then it won't work
						
			if(isset($export_fields))
			{
				$field_count 	= count($export_fields);
				for($j=0 ; $j<$field_count ; $j++)
			   	{
			   		$record = $entities[$k];
			   		$field	= $record[$export_fields[$j]];
			   		$csv_fields[] .= '"'.$field.'"'.';';
			   	}		
			}
			else
			{
				$field_count 	= count($entities[$k]);
				$record 		= array_values(json_decode(json_encode($entities[$k]), true));
				$csv_fields[]   = '"'. implode('";"' , $record). '"';
			}		  	
		}
		    
		return $csv_fields;
	}
	
	/** 
	 * langTableExists Function
	 * @desc	Function to determine if a corresponding language table exists for particular entity
	 * @params	string $entity
	 * 			string $prefix
	 * @return	bool
	 */
	public function langTableExists($entity , $prefix)
	{
		$db 		= JFactory::getDbo();
		$query		= "SHOW TABLES LIKE '{$prefix}paycart_{$entity}_lang'";
		$db->setQuery($query);
		
		if($db->loadResult()){
			return true;
		}
		return false;
	}
	
	/** 
	 * getLangaugeFields Function
	 * @desc	Function to determine if a corresponding language table exists for particular entity
	 * @params	string $tbl
	 * 			string $entity_id
	 * 			string $alias
	 * @return	string $lang_query
	 */
	public function getLangaugeFields($tbl , $entity_id , $alias)
	{
		$db 		= JFactory::getDbo();
		
		$query 		= " SHOW FIELDS FROM $tbl ";
		$db->setQuery($query);
		
		$fields 	= $db->loadColumn();
		$lang_query = "";
		
		$i=0;
		foreach ($fields as $field)
		{
			if($field == $entity_id){
				continue;
			}
			$lang_query.= "$alias.`{$field}`";
			if($i != count($fields)-1)
				$lang_query.=", ";
			$i++;
		}
		
		return $lang_query;
	}
}