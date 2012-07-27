<?php
/**
 * @file
 * @package legroup
 * @version $Id$
**/

if(!defined('XOOPS_ROOT_PATH'))
{
	exit;
}

require_once XOOPS_ROOT_PATH . '/core/XCube_ActionForm.class.php';
require_once XOOPS_MODULE_PATH . '/legacy/class/Legacy_Validator.class.php';

/**
 * Legroup_PolicyEditForm
**/
class Legroup_PolicyEditForm extends XCube_ActionForm
{
	/**
	 * getTokenName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getTokenName()
	{
		return "module.legroup.PolicyEditForm.TOKEN";
	}

	/**
	 * prepare
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function prepare()
	{
		//
		// Set form properties
		//
		$this->mFormProperties['policy_id'] = new XCube_IntArrayProperty('policy_id');
		//$this->mFormProperties['group_id'] = new XCube_IntProperty('group_id');
		//$this->mFormProperties['dirname'] = new XCube_StringProperty('dirname');
		//$this->mFormProperties['dataname'] = new XCube_StringProperty('dataname');
		$this->mFormProperties['action'] = new XCube_StringArrayProperty('action');
		$this->mFormProperties['rank'] = new XCube_IntArrayProperty('rank');
	}

	/**
	 * load
	 * 
	 * @param	XoopsSimpleObject[]  &$objs
	 * 
	 * @return	void
	**/
	public function load(/*** XoopsSimpleObject ***/ &$objs)
	{
		foreach(array_keys($objs) as $key){
			$this->set('policy_id', $key, $objs[$key]->get('policy_id'));
			//$this->set('group_id', $obj->get('group_id'));
			//$this->set('dirname', $obj->get('dirname'));
			//$this->set('dataname', $obj->get('dataname'));
			$this->set('action', $key, $objs[$key]->get('action'));
			$this->set('rank', $key, $objs[$key]->get('rank'));
		}
	}

	/**
	 * update
	 * 
	 * @param	XoopsSimpleObject[]  &$objs
	 * 
	 * @return	void
	**/
	public function update(/*** XoopsSimpleObject ***/ &$objs)
	{
		foreach(array_keys($objs) as $key){
			$objs[$key]->set('rank', $this->get('rank', $key));
		}
	}
}

?>
