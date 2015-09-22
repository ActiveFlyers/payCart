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
 * ImportFromCSV Helper
 * @since 1.0.10
 * @author Neelam soni
 */
class PaycartHelperImportFromCSV extends PaycartHelper
{
	/** 
	 * validateSaveCSV Function
	 * @desc	Function to validate the uploaded file and save the same if it has proper extension
	 * @params	string $entity
	 * 
	 * @return	$filename
	 */
	public function validateSaveCSV($entity)
	{
		$uploaded_file 	= $_FILES['fileToUpload'];		
		$type	  		= $uploaded_file['type'];
		
		$mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv','application/csv');
//		if(!in_array($type , $mimes))
//		{		
//			$app = JFactory::getApplication();
//			$app->enqueueMessage(JText::_('COM_PAYCART_INVALID_FILE_UPLOAD'), 'error');
//			$app->redirect('index.php?option=com_paycart&view=product&task=import');
//		}
		
		$csv_folder 	= PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP.$entity;

		// Create a folder to store csv imported files if it doesn't exist
		if(!JFolder::exists($csv_folder)){
			if(!JFolder::create($csv_folder)){
				throw new Exception(JText::sprintf('COM_PAYCART_ADMIN_EXCEPTION_PERMISSION_DENIED', $csv_folder));
			}
		}
		
		$date 			= & JFactory::getDate();		
		$filename 		= $entity.'_'.$date->format('Y-m-d');
		$CSVFileName 	= $csv_folder.'/'.$filename.'.csv';
		
		if(JFile::exists($CSVFileName))
		{
			JFile::delete($CSVFileName);
		}
		
		// Create a folder to store csv imported files if it doesn't exist
		if(!JFile::copy($uploaded_file['tmp_name'] , $CSVFileName)){
			throw new Exception(JText::sprintf('COM_PAYCART_ADMIN_EXCEPTION_PERMISSION_DENIED', $csv_folder));		
		}

		return JFile::getName($CSVFileName);
	}
	
	/** 
	 * getCsvMapping Function
	 * @desc	Function to provide the csv headers and entity's fields for mapping 
	 * @params	string $entity
	 * 			string $filename
	 * 
	 * @return	array (array $csv_headers, array $entity_fields)
	 */
	public function getCsvMapping($entity , $filename)
	{
		//1. Get CSV Headers
		
		$csv_folder 	= PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP.$entity;
		$CSVFileName 	= $csv_folder.'/'.$filename;
		
		$file 			= fopen($CSVFileName,"r");
		$data 			= fgetcsv($file, "\n");
		
		//first element does not have double qoutes
		$format_string	= explode(';', $data[0] , 2);
		$formatted_data	= $format_string[0].'";'.$format_string[1];		
		$csv_headers  	= explode('";"', $formatted_data);
		
		//remove '"' from the last element
		$csv_headers[count($csv_headers) - 1] = str_replace('"', '', end($csv_headers));
		
		//storing the csv_headers in session
		$_SESSION["csv_headers"] = $csv_headers;


		//2. Fetch column headings from Paycart entity
		
		//check if a language table is maintained for that product
		$prefix 		 = JFactory::getApplication()->getCfg('dbprefix');
		$langTableExists = PaycartHelperExportToCSV::langTableExists($entity , $prefix);
		
		$db				 = JFactory::getDbo();
	    $query  		 = " SHOW FIELDS FROM `#__paycart_$entity` ";
	    $db->setQuery($query);
	    
	    $entity_fields   = $db->loadColumn();
	    $count  		 = count($entity_fields);
	    
		//fetch column headings from Paycart entity's lang table if it exists
		//merge them with $fields			
	    if($langTableExists)
	    {
	    	$query 		 = " SHOW FIELDS FROM `#__paycart_{$entity}_lang` ";
	    	$db->setQuery($query);
	    	
	    	$result 	 = $db->loadColumn();
	    
	    	//remove the duplicate column here itself
	    	$key    	 = array_search($entity.'_id', $result);
	    	unset($result[$key]);
	    	
	    	$entity_fields = array_merge($entity_fields , $result);		    	
	    }
		
	    //storing the entity_fields in session
	    $_SESSION["entity_fields"] = $entity_fields;
	    
	    
	    //3. Return the csv_headers and entity_fields
	    return array('csv_headers' 	 => $csv_headers ,
	    			 'entity_fields' => $entity_fields);
	}
	
	/** 
	 * importCscToTempTable Function
	 * @desc	Function to import entity's data from the CSV file in a temporary table
	 * @params	array $mapped_fields 
	 * 			string $entity
	 * 			string $filename
	 * 			int $file_position
	 * 
	 * @return	null
	 */
	public function importCsvToTempTable($mapped_fields , $entity , $filename , $file_position = null)
	{
		$ajax 	  	 = PaycartFactory::getAjaxResponse();
		$response 	 = new stdClass();
		
		$CSVFileName = PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP.$entity.'/'.$filename;
		$file 		 = fopen($CSVFileName,"r");
		
		//fetch csv_headers from Session so that we can map the csv data in array accordingly
		$csv_headers = $_SESSION['csv_headers'];
			
		//if users skip mapping and asks to import directly
		if(empty($mapped_fields))
		{
			$validate_headers = $this->validateHeaders($entity , $csv_headers);
			if(!$validate_headers){
				$response->error_message = JText::_("COM_PAYCART_CSV_HEADERS_MISMATCH");
				$ajax->addScriptCall('paycart.admin.product.importerror' , json_encode($response));
				$ajax->sendResponse($response);
			}
		}
		
		if($file_position)
		{
			fseek($file, $file_position);
		}
		
		$end_of_file = feof($file);
		$count		 = 0;
		
		while($count<20 && !$end_of_file && ($data = fgetcsv($file, "\n")))
		{
			//first element does not have double qoutes
			$format_string		  = explode(';', $data[0] , 2);
			$formatted_data		  = $format_string[0].'";'.$format_string[1];
			
			$columns			  = array();
			$columns  			  = explode('";"', $formatted_data);
						
			//remove '";' from the last element
			$length				  = count($columns);
			$columns[$length - 1] = str_replace('";', '', $columns[$length - 1]);
			
			//don't save in table if fetching the records for the first time as we get headers
			//but create a temporary table here itself
			if(empty($file_position) && $count == 0){
				$this->createTempTable($mapped_fields , $entity);
				$count++;
				continue;
			}
			
			//forming the csv data
			$csv_data			  = array();

			for($i=0; $i<count($csv_headers); $i++)
			{
				//$csv_data will have only the mapped fields' corresponding values
				if(!empty($mapped_fields) && !in_array($csv_headers[$i], $mapped_fields))
				{
					continue;
				}
				$csv_data[]	= $columns[$i];
			}
			
			//saving data in temporary table
			$db		= JFactory::getDbo();
			$num	= 1;
			$query  = "INSERT INTO `#__paycart_{$entity}_temp` VALUES (";
			foreach ($csv_data as $data)
			{
				$query .= "'{$data}'";
				if($num<count($csv_data))
					$query .= ", ";
				$num++;
			}
			$query	.= ")";
			try
			{
				$db->setQuery($query)->execute();
			}
			catch (Exception $e)
			{
				$response->error_message = $e->getMessage();
				$ajax->addScriptCall('paycart.admin.product.importerror' , json_encode($response));
				$ajax->sendResponse();
			}
			
			$end_of_file = feof($file);
			$count++;
		}		
		
		$file_position = ftell($file);
		
		if($data = fgetcsv($file, "\n") && !$end_of_file)
		{
			$response->file_position = $file_position;
			$response->next			 = true;
			$response->mapped_fields = $mapped_fields;
			$response->action		 = 'doImport';
		}
		else
		{
			$count = $db->setQuery("SELECT COUNT(*) FROM `#__paycart_{$entity}_temp`")->loadResult();
			$response->remaining	= $count;
			$response->action 		= 'startImport';
			$response->next	  		= true;
		}
		// set call back function
		$ajax->addScriptCall('paycart.admin.product.doImportSuccess', json_encode($response));
		$ajax->sendResponse($response);
	}
	
	/** 
	 * createTempTable Function
	 * @desc	Function to create temporary table according to mapped fields
	 * @params	array $mapped_fields
	 * 			string $entity
	 * 
	 * @return	null
	 */
	public function createTempTable($mapped_fields , $entity)
	{
		$csv_headers	= $_SESSION['csv_headers']; 
		$db 			= JFactory::getDbo();
		$query			= "DESC `#__paycart_{$entity}`";
		$db->setQuery($query);
		$structures 	= $db->loadAssocList();
		
		//check if a language table is maintained for that product
		$prefix 		 = JFactory::getApplication()->getCfg('dbprefix');
		$langTableExists = PaycartHelperExportToCSV::langTableExists($entity , $prefix);
		if($langTableExists)
		{
			$query		 	= "DESC `#__paycart_{$entity}_lang`";
			$db->setQuery($query);
			$structuresLang = $db->loadAssocList();			
			unset($structuresLang[1]);
			$structures 	= array_merge($structures , $structuresLang);
		}
		
		//getting the data-type and other info for the mapped_fields
		$columns	 = array();
		$tbl_columns = array();
		
		$fields		 = empty($mapped_fields) ? $csv_headers : $mapped_fields;
		foreach($fields as $field)
		{
			$tbl_columns[$field]	= array();
		}
		
		foreach($structures as $structure)
		{
			if(!empty($mapped_fields) && !in_array($structure['Field'], array_keys($mapped_fields))
			 	|| (!in_array($structure['Field'], $csv_headers)))
			{
				continue;			
			}

			$null		= ($structure['Null'] == 'YES' || $structure['Field'] == $entity.'_id') ? '' : ' NOT NULL';
			$default	= empty($structure['Default']) ? '' : " DEFAULT '".$structure['Default']."'";
			$columns    = array('column_name' => $structure['Field'],
							    'data_type'	 => $structure['Type'],
							    'null'		 => $null,
							    'default'	 => $default);
			$tbl_columns[$structure['Field']] = $columns;
		}
		
		//creating the table
		$query		= "DROP TABLE IF EXISTS `#__paycart_{$entity}_temp`";
		$db->setQuery($query)->execute();
		
		$query	 	= "CREATE TABLE IF NOT EXISTS `#__paycart_{$entity}_temp` (";
		$count		= 1;
		foreach ($tbl_columns as $column)
		{
			$query .= "`{$column['column_name']}` {$column['data_type']}{$column['null']}{$column['default']}";
			if($count<count($tbl_columns))
				$query .= ", ";
			$count++;
		}
		$query .= ")";
		$db->setQuery($query)->execute();
	}
	/** 
	 * importCSV Function
	 * @desc	Function to import entity's data from the CSV file
	 * @params	int $remaining
	 * 			string $entity
	 * 
	 * @return	null
	 */
	public function importCSV($start , $entity , $unimported_data , $imported_data)
	{
		$ajax 	  	 = PaycartFactory::getAjaxResponse();
		$response 	 = new stdClass();
		
		$limit	= 20;
		$db		= JFactory::getDbo();
		$query	= "SELECT * FROM `#__paycart_{$entity}_temp` LIMIT {$limit}";
		$data	= $db->setQuery($query)->loadAssocList();
		
		foreach ($data as $row) 
		{
			try
			{
				$this->saveData($entity, $row);
				$imported_data[]		= $row;
			}
			catch(Exception $e)
			{
				$unimported_data[]	    = $row;
			}			
		}
		
		$count = $db->setQuery("SELECT COUNT(*) FROM `#__paycart_{$entity}_temp`")->loadResult();
		$start = $start + $limit;
		
		if($start<$total)
		{
			$response->start			= $start;
			$response->action 		 	= 'startImport';
			$response->imported_data 	= $imported_data;
			$response->unimported_data	= $unimported_data;
			$response->next	  			= true;
		}
		else
		{
			$response->next				= false;
						
			$query 		 = " SHOW FIELDS FROM `#__paycart_{$entity}_temp` ";
	    	$db->setQuery($query);	    	
	    	$fields 	 = $db->loadColumn();
	    	
			//prepare the summary of import
			$summary	= "<div class='span6'><table style='font-weight:bold' class='table table-striped table-bordered table-hover'>";
			$summary   .= JText::sprintf("COM_PAYCART_TOTAL_PROCESSED_RECORDS" , $count);
			$summary   .= JText::sprintf("COM_PAYCART_SUCCESSFULLY_IMPORTED_RECORDS" , count($imported_data));			  
			$summary   .= JText::sprintf("COM_PAYCART_FAILED_IMPORTED_RECORDS" , count($unimported_data));
			$summary   .= "</table></div><div class='span12'>";			
			$summary   .= "<hr/>".JText::_("COM_PAYCART_DATA_IMPORT_FAILED");
			if(empty($unimported_data))
			{
				$summary .= JText::_("COM_PAYCART_ALL_RECORDS_IMPORTED_SUCCESSFULLY");
			}
			else
			{
				$summary .= "<table class='table table-striped table-bordered table-hover'><tr>";
				foreach ($fields as $val){
					$summary .= "<td>".$val."</td>";
				}
				$summary .= "</tr>";
				foreach ($unimported_data as $data){
					$summary .= "<tr>";
					foreach ($data as $val)
					{
						$summary .= "<td>".$val."</td>";	
					}					
					$summary .= "</tr>";
				}
				$summary .= "</table>";
			}		
			$summary   .= "<hr/>".JText::_("COM_PAYCART_DATA_IMPORTED_SUCCESSFULLY");
			if(empty($imported_data))
			{
				$summary .= JText::_("COM_PAYCART_NO_RECORDS_IMPORTED_SUCCESSFULLY");
			}
			else
			{
				$summary .= "<table class='table table-striped table-bordered table-hover'><tr>";
				foreach ($fields as $val){
					$summary .= "<td>".$val."</td>";
				}
				$summary .= "</tr>";
				foreach ($imported_data as $data){
					$summary .= "<tr>";
					foreach ($data as $val)
					{
						$summary .= "<td>".$val."</td>";	
					}					
					$summary .= "</tr>";
				}
				$summary .= "</table>";
			}		
			$summary   .= "</div";	
			$response->summary	= $summary;
			PaycartFactory::saveConfig(array('product_import_summary' => $summary));
			
			//delete file
			$CSVFileName = PAYCART_ATTRIBUTE_PATH_CSV_IMPEXP.$entity.'/'.$_SESSION['filename'];
			if (file_exists($CSVFileName))
			{
				unlink($CSVFileName);
			}
		}
		
		// set call back function
		$ajax->addScriptCall('paycart.admin.product.doImportSuccess', json_encode($response));
		$ajax->sendResponse($response);
	}
	
	
	/** 
	 * saveData Function
	 * @desc	Function to save the csv data in database
	 * @params	string $entity
	 * 			array $fields
	 * 
	 * @return	null
	 */
	public function saveData($entity , $fields)
	{	
		$db 	= JFactory::getDbo();		
		$update = false;
		
		// Get the product_lang_id
		if(!empty($fields[$entity.'_id']))
		{
			$code	= PaycartFactory::getPCCurrentLanguageCode();
			$query	= "SELECT `{$entity}_lang_id` FROM `#__paycart_{$entity}_lang` WHERE `{$entity}_id` = {$fields[$entity.'_id']} AND `lang_code` LIKE '{$code}'";
			$fields[$entity.'_lang_id'] = $db->setQuery($query)->loadResult();
		}
		// Check if the data already exists
		$query = " SELECT `{$entity}_id` FROM `#__paycart_{$entity}` WHERE `{$entity}_id` = {$fields[$entity.'_id']} ";
		$db->setQuery($query);
		
		if($db->loadResult())
		{
			$update = true;
		}
		
		$entity_model = PaycartFactory::getInstance($entity , 'model');
			
		if($update === true)
		{
			$entity_model->save($fields , $fields[$entity.'_id']);
		}
		else
		{
			unset($fields[$entity.'_id']);
			$entity_model->save($fields);		
		}
		
//		// Delete that row from temporary table
//		$query	= "DELETE FROM `#__paycart_{$entity}_temp` WHERE `{$entity}_id` = '{$fields[$entity.'_id']}'";
//		$db->setQuery($query)->execute();
	}
	
	/** 
	 * validateHeaders Function
	 * @desc	Function to validate that correct csv headers are mapped with entity table
	 * @params	string $entity
	 * 			array $columns
	 * 
	 * @return	bool
	 */
	public function validateHeaders($entity , $columns)
	{
		$fields 	= $_SESSION['entity_fields'];
	    
	    foreach($columns as $column)
	    {
	    	// check if all the columns of csv_data are present in fields
	    	if(!in_array($column, $fields)){
	    		return false;
	    	}
	    }
	    
	    return true;
	}
}