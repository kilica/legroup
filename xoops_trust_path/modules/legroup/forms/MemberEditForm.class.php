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
 * Legroup_MemberEditForm
**/
class Legroup_MemberEditForm extends XCube_ActionForm
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
		return "module.legroup.MemberEditForm.TOKEN";
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
		$this->mFormProperties['member_id'] = new XCube_IntProperty('member_id');
		$this->mFormProperties['uid'] = new XCube_IntProperty('uid');
		$this->mFormProperties['group_id'] = new XCube_IntProperty('group_id');
		$this->mFormProperties['status'] = new XCube_IntProperty('status');
		$this->mFormProperties['rank'] = new XCube_IntProperty('rank');
		$this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');
	
		//
		// Set field properties
		//
		$this->mFieldProperties['member_id'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['member_id']->setDependsByArray(array('required'));
		$this->mFieldProperties['member_id']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_MEMBER_ID);
		$this->mFieldProperties['uid'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['group_id'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['group_id']->setDependsByArray(array('required'));
		$this->mFieldProperties['group_id']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_GROUP_ID);
		$this->mFieldProperties['status'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['rank'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['rank']->setDependsByArray(array('required'));
		$this->mFieldProperties['rank']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_RANK);
		$this->mFieldProperties['posttime'] = new XCube_FieldProperty($this);
	}

	/**
	 * load
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function load(/*** XoopsSimpleObject ***/ &$obj)
	{
		$this->set('member_id', $obj->get('member_id'));
		$this->set('uid', $obj->get('uid'));
		$this->set('group_id', $obj->get('group_id'));
		$this->set('status', $obj->get('status'));
		$this->set('rank', $obj->get('rank'));
		$this->set('posttime', $obj->get('posttime'));
	}

	/**
	 * update
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function update(/*** XoopsSimpleObject ***/ &$obj)
	{
		$obj->set('group_id', $this->get('group_id'));
		$obj->set('rank', $this->get('rank'));
	}
}

?>
