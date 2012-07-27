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
 * Legroup_MemberDeleteForm
**/
class Legroup_MemberDeleteForm extends XCube_ActionForm
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
        return "module.legroup.MemberDeleteForm.TOKEN";
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
        $this->mFormProperties['member_id'] = new XCube_IntProperty('member_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['member_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['member_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['member_id']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_MEMBER_ID);
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
        $this->set('member_id', $obj->get('member_id'));
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
        $obj->set('member_id', $this->get('member_id'));
    }
}

?>
