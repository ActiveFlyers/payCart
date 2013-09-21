<?php
/**
 * @package    Paycart.Test
 *
 * @copyright  Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

/**
 * Reflection helper class.
 *
 * @package  Paycart.Test
 */

class PayCartTestReflection extends TestReflection
{
	/** 
	 * 
	 * Return all class attribute name. Included Public, protected, Private and static.
	 * It will filter base class attribute
	 * 
	 * @param $className
	 * 
	 * @return Attribute array
	 */
	public static function getClassAttribute($className)
	{
		$reflection = new ReflectionClass($className);
		
		// use closure  
		$classProperty = array_filter($reflection->getProperties(), function($prop) use($reflection){ 
    										return $prop->getDeclaringClass()->getName() == $reflection->getName();
										});
		$attributes = Array();
		foreach($classProperty as $property) {
			$attributes[] = $property->getName(); 
		}
		
		return $attributes;
	} 
}
