<?php

/**
* @copyright	Copyright (C) 2013 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @package 		Paycart.Test
* @author		mManishTrivedi
*/

/** 
 * Test PayCartHelperImage 
 * @author mManishTrivedi
 */
class PaycartHelperImageTest extends PayCartTestCase
{

	/**
	 * Provider for testCreate Method 
	 */
	public function providerCreate() {
		//image info
		$provider[] = Array( 	
	  						'sourceFile' 	=>	RBTEST_BASE . '/_data/images/dummy.jpg',
	  						'targetFolder'	=>	RBTEST_BASE.'/tmp/',
	  						'targetFileName'=>	'tested_paycart' 
	  					);
	  	// result
	  	$provider[]	= 'COM_PAYCART_IMAGE_NOT_EXIST';
	  	$provider[] = null;
		// Case :: through Exception Image 	  	
	  	$providers[] = $provider;
	  			
	  	$extensions = Array('.jpg','.png','.gif'
	  					//	,'.bmp'				// Right now, Joomla does not support it
	  						);
	 
	  	// Cases ::Paycart Config have 
	  	// When paycart config have .jpg extension and image uploaded in different-2 flavour {.jpg, .gif, .png}
		// When paycart config have .png extension and image uploaded in different-2 flavour {.jpg, .gif, .png}
		// When paycart config have .gif extension and image uploaded in different-2 flavour {.jpg, .gif, .png}
	  	foreach ($extensions as $extension) {
	  		foreach ($extensions as $value) {
	  			$provider = Array();
	  			// Image Info
	  			$provider[] = Array( 	
	  						'sourceFile' 	=>	RBTEST_BASE . '/_data/images/paycart'.$value,
	  						'targetFolder'	=>	RBTEST_BASE.'/tmp/',
	  						'targetFileName'=>	'tested_paycart' 
	  					);	
	  			// result images	
	  			$provider[]	=	Array(
	  						'original_image'  => RBTEST_BASE.'/tmp/'.Paycart::IMAGE_ORIGINAL_PREFIX.'tested_paycart'.Paycart::IMAGE_ORIGINAL_SUFIX,
	  						'optimized_image' => RBTEST_BASE.'/tmp/tested_paycart'.$extension,
	  						'thumb_image' 	  => RBTEST_BASE.'/tmp/thumb_tested_paycart'.$extension			
	  					);
	  			// Config Extension
				$provider[]  = $extension;
				
	  			$providers[] = $provider;
	  		}
	  	}
	  	
	  	return $providers;
	}
	
	/**
	 * 
	 * Test image Create Method
	 * 
	 * @dataProvider providerCreate
	 */
	public function testCreate($imageInfo, $result, $configExtension) 
	{	
		// Backup Paycart Config
		$backupConfig = PayCartTestReflection::getValue('PaycartFactory', '_config');

		// before test clean all files
		JFile::delete(glob(RBTEST_BASE.'/tmp/*.*'));
		
		// Dependency :Create Mock for PaycartConfig 
		$mockConfig = $this->getMock('Rb_Registry');
		$mockConfig->expects($this->any())
					->method('get')
					->will($this->returnCallback(function($prop, $default) use ($configExtension) 
								{ return  PaycartHelperImageTest::callbackCreate($prop, $default,$configExtension); }
								));

		// Set Mockconfig
	  	PayCartTestReflection::setValue('PaycartFactory', '_config', $mockConfig);
		
	  	//Get Product Instance
	  	$object = PaycartFactory::getInstance('image', 'helper');
	  		  	
	  	try {
	  		
	  		
	  		// invoke SUT
	  		$object->loadFile($imageInfo['sourceFile'])
	  			   ->validate()
	  			   ->create($imageInfo['targetFolder'],$imageInfo['targetFileName']);
	  					  				

	  				//PayCartTestReflection::invoke($object, '_imageCreate', $imageInfo);
	  	} catch (InvalidArgumentException $e) {
	  		//@PCTODO ::Clean language string
	  		//@PCTODO:: Seperate this test case (Only for First Case) so we proper testing if exception fire
	  		$this->assertSame(Rb_Text::_($result), $e->getMessage());
	  	}
	  	
	  	if (is_array($result)) {
	  		$this->assertFileExists($result['original_image'], 'Missing Original Image');
	  		$this->assertFileExists($result['optimized_image'], 'Missing Optimized Image');
	  		$this->assertFileExists($result['thumb_image'], 'Missing Thumb Image');

	  		//check  image scale
	  		$expected_optimized_size = Array (Paycart::IMAGE_OPTIMIZE_HEIGHT, Paycart::IMAGE_OPTIMIZE_WIDTH);
	  		//height, width 
	  		list($actual_optimized_size[], $actual_optimized_size[]) = getimagesize($result['optimized_image']);
	  		
			$this->assertSame($expected_optimized_size, $actual_optimized_size);
			
	  		$expected_thumb_size = Array (Paycart::IMAGE_THUMB_HEIGHT, Paycart::IMAGE_THUMB_WIDTH);
	  		list($actual_thumb_size[], $actual_thumb_size[]) = getimagesize($result['thumb_image']);
	  		
	  		$this->assertSame($expected_thumb_size, $actual_thumb_size);
	  	}
	  	
	  	// After test clean all files
		JFile::delete(glob(RBTEST_BASE.'/tmp/*.*'));

	  	// Revert Paycart config
	  	PayCartTestReflection::setValue('PaycartFactory', '_config', $backupConfig);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $prop
	 * @param unknown_type $default
	 */
	public static function callbackCreate($prop, $default= null, $predefine = '.png') 
	{
		switch ($prop) 
		{
			case 'image_maximum_upload_limit' :
				return 5;
				
			case 'image_upload_directory' :
				return RBTEST_BASE;
			
			case 'image_extension'	:
				return $predefine;
				
			default:
				return $default;		
		}
	}
}
