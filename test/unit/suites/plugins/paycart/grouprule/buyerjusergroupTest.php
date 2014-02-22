<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in 
*/

require_once JPATH_SITE.'/plugins/paycart/grouprulebuyerjusergroup/rules/buyerjusergroup/buyerjusergroup.php';
/**
 * 
 * Group Rule Buyer JUsergroup Test 
 * @author Gaurav Jain
 */
class PaycartPluginsGroupruleBuyerJusergroupTest extends PayCartTestCase
{
	public static function providerTestIsApplicable()
    {
    	// ANY
    	$case[0] = array(
    				1,
    				array(1),
    				array('jusergroup_assignment' => 'any',
    					  'jusergroups' => array()),
    				true    					  
    			);

    	// SELECTED , Applicable
    	$case[1] = array(
    				1,
    				array(1),
    				array('jusergroup_assignment' => 'selected',
    					  'jusergroups' => array(1,2,3)),
    				true    					  
    			);
    			
    	// SELECTED , Not-Applicable
    	$case[2] = array(
    				1,
    				array(1),
    				array('jusergroup_assignment' => 'selected',
    					  'jusergroups' => array(2,3)),
    				false 
    			);
    			
		// EXCEPT , Not-Applicable
    	$case[3] = array(
    				1,
    				array(1),
    				array('jusergroup_assignment' => 'except',
    					  'jusergroups' => array(1,2,3)),
    				false
    			);
    			
    	// EXCEPT , Applicable
    	$case[4] = array(
    				1,
    				array(1),
    				array('jusergroup_assignment' => 'except',
    					  'jusergroups' => array(2,3)),
    				true 
    			);    

    			
    	// ANYHING , Not-Applicable
    	$case[5] = array(
    				1,
    				array(1),
    				array('jusergroup_assignment' => 'xyz',
    					  'jusergroups' => array(2,3)),
    				false
    			);

    	// NO Buyer Id, ANY , Applicable
    	$case[6] = array(
    				0,
    				array(1),
    				array('jusergroup_assignment' => 'any',
    					  'jusergroups' => array(2,3)),
    				true
    			);    
    			
    	// NO Buyer Id, SELECTED,  guest , Not-Applicable
    	$case[7] = array(
    				0,
    				array(1),
    				array('jusergroup_assignment' => 'selected',
    					  'jusergroups' => array(2,3)),
    				false
    			);

    	// NO Buyer Id, SELECTED,  guest , Applicable
    	$case[8] = array(
    				0,
    				array(1),
    				array('jusergroup_assignment' => 'selected',
    					  'jusergroups' => array(1,2,3,9)), // guest usergroupe id is 9
    				true
    			);
    			
    	// NO Buyer Id, EXCEPT,  guest , Applicable
    	$case[9] = array(
    				0,
    				array(1),
    				array('jusergroup_assignment' => 'selected',
    					  'jusergroups' => array(1,2,3)),
    				false
    			);
    			
    	// NO Buyer Id, EXCEPT,  guest , Not-Applicable
    	$case[10] = array(
    				0,
    				array(1),
    				array('jusergroup_assignment' => 'selected',
    					  'jusergroups' => array(1,2,3,9)), // guest usergroupe id is 9
    				true
    			);
    	return $case;
    }
    
	 /**
     * @dataProvider providerTestIsApplicable
     */	
	public function testIsApplicable($buyer_id, $groups, $params, $result)
	{	 
		$options = Array(
			'get.user.id' => 111,
			'get.user.name' => 'ABC',
			'get.user.username' => 'abc',
			'get.user.guest' => 0
			);
			
		// MockSession and set user in session, other wise some error in 
		PaycartFactory::$session = $this->getMockSession($options);

		// set groups on mock object of user
		$mockUser = $this->getMock('JUser');
		$mockUser->groups = $groups;		
		
		$testObject = new PaycartGroupruleBuyerjusergroup($params);
		TestReflection::setValue('JUser', 'instances', array(1 => $mockUser));

		$component = new stdClass();		
		$component->params = new JRegistry();
		$component->params->set('guest_usergroup', 9);		
		TestReflection::setValue('JComponentHelper', 'components', array('com_users' => $component));
				
		$this->assertEquals($result, $testObject->isApplicable($buyer_id));

		
		//clean up
		TestReflection::setValue('JUser', 'instances', array());
		TestReflection::setValue('JComponentHelper', 'components', array());
	}
}