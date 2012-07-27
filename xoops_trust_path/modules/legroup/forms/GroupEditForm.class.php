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
 * Legroup_GroupEditForm
**/
class Legroup_GroupEditForm extends XCube_ActionForm
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
		return "module.legroup.GroupEditForm.TOKEN";
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
		$this->mFormProperties['group_id'] = new XCube_IntProperty('group_id');
		$this->mFormProperties['title'] = new XCube_StringProperty('title');
		$this->mFormProperties['approval'] = new XCube_IntProperty('approval');
		$this->mFormProperties['publicity'] = new XCube_IntProperty('publicity');
		$this->mFormProperties['description'] = new XCube_TextProperty('description');
		$this->mFormProperties['posttime'] = new XCube_IntProperty('posttime');

	
		//
		// Set field properties
		//
		$this->mFieldProperties['group_id'] = new XCube_FieldProperty($this);
$this->mFieldProperties['group_id']->setDependsByArray(array('required'));
$this->mFieldProperties['group_id']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_GROUP_ID);
		$this->mFieldProperties['title'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['title']->setDependsByArray(array('required','maxlength'));
		$this->mFieldProperties['title']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_TITLE);
		$this->mFieldProperties['title']->addMessage('maxlength', _MD_LEGROUP_ERROR_MAXLENGTH, _MD_LEGROUP_LANG_TITLE, '255');
		$this->mFieldProperties['title']->addVar('maxlength', '255');
		$this->mFieldProperties['publicity'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['publicity']->setDependsByArray(array('required'));
		$this->mFieldProperties['publicity']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_PUBLICITY);
		$this->mFieldProperties['approval'] = new XCube_FieldProperty($this);
		$this->mFieldProperties['approval']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_APPROVAL);
		$this->mFieldProperties['approval']->setDependsByArray(array('required'));
		$this->mFieldProperties['description'] = new XCube_FieldProperty($this);
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
		$this->set('group_id', $obj->get('group_id'));
		$this->set('title', $obj->get('title'));
		$this->set('publicity', $obj->get('publicity'));
		$this->set('approval', $obj->get('approval'));
		$this->set('description', $obj->get('description'));
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
		$obj->set('title', $this->get('title'));
		$obj->set('publicity', $this->get('publicity'));
		$obj->set('approval', $this->get('approval'));
		$obj->set('description', $this->get('description'));
	}
}

?>
