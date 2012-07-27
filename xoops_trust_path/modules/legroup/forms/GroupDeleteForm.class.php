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
 * Legroup_GroupDeleteForm
**/
class Legroup_GroupDeleteForm extends XCube_ActionForm
{
    /**
     * getTokenName
     * 
     * @param   void
     * 
     * @return  string
    **/
    public function getTokenName()
    {
        return "module.legroup.GroupDeleteForm.TOKEN";
    }

    /**
     * prepare
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function prepare()
    {
        //
        // Set form properties
        //
        $this->mFormProperties['group_id'] = new XCube_IntProperty('group_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['group_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['group_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['group_id']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_GROUP_ID);
    }

    /**
     * load
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function load(/*** XoopsSimpleObject ***/ &$obj)
    {
        $this->set('group_id', $obj->get('group_id'));
    }

    /**
     * update
     * 
     * @param   XoopsSimpleObject  &$obj
     * 
     * @return  void
    **/
    public function update(/*** XoopsSimpleObject ***/ &$obj)
    {
        $obj->set('group_id', $this->get('group_id'));
    }
}

?>
