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
 * Legroup_PolicyDeleteForm
**/
class Legroup_PolicyDeleteForm extends XCube_ActionForm
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
        return "module.legroup.PolicyDeleteForm.TOKEN";
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
        $this->mFormProperties['policy_id'] = new XCube_IntProperty('policy_id');
    
        //
        // Set field properties
        //
        $this->mFieldProperties['policy_id'] = new XCube_FieldProperty($this);
        $this->mFieldProperties['policy_id']->setDependsByArray(array('required'));
        $this->mFieldProperties['policy_id']->addMessage('required', _MD_LEGROUP_ERROR_REQUIRED, _MD_LEGROUP_LANG_POLICY_ID);
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
        $this->set('policy_id', $obj->get('policy_id'));
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
        $obj->set('policy_id', $this->get('policy_id'));
    }
}

?>
