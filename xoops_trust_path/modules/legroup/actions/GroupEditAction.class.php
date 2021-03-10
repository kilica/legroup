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

require_once LEGROUP_TRUST_PATH . '/class/AbstractEditAction.class.php';

/**
 * Legroup_GroupEditAction
**/
class Legroup_GroupEditAction extends Legroup_AbstractEditAction
{

    /**
     * &_getHandler
     * 
     * @param    void
     * 
     * @return    Legroup_GroupHandler
    **/
    protected function &_getHandler()
    {
        $handler =& $this->mAsset->getObject('handler', 'Group');
        return $handler;
    }

    /**
     * _setupActionForm
     * 
     * @param    void
     * 
     * @return    void
    **/
    protected function _setupActionForm()
    {
        $this->mActionForm =& $this->mAsset->getObject('form', 'Group',false,'edit');
        $this->mActionForm->prepare();
    }

    /**
     * hasPermission
     * 
     * @param    void
     * 
     * @return    bool
    **/
    public function hasPermission()
    {
        if(! $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser')){
            return false;
        }
    
        if(! $this->mObject->isNew()){
            return ($this->mObject->isMember(Legacy_Utils::getUid(), Lenum_GroupRank::OWNER)) ? true : false;
        }
        else{
            return true;
        }
    }

    /**
     * prepare
     * 
     * @param    void
     * 
     * @return    bool
    **/
    public function prepare()
    {
        parent::prepare();
        if($this->mObject->isNew()){

        }
        return true;
    }

    /**
     * executeViewInput
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
    {
        $render->setTemplateName($this->mAsset->mDirname . '_group_edit.html');
        $render->setAttribute('actionForm', $this->mActionForm);
        $render->setAttribute('dirname', $this->mAsset->mDirname);
        $render->setAttribute('object', $this->mObject);
        $render->setAttribute('image', $this->mObject->getImage());
        //breadcrumb
        $breadcrumbs = array();
        XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.Global.Event.GetBreadcrumbs', new XCube_Ref($breadcrumbs), $this->mAsset->mDirname, $this->mObject);
        $render->setAttribute('xoops_breadcrumbs', $breadcrumbs);
    }

    /**
     * executeViewSuccess
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeForward($this->_getNextUri('group'));
    }

    /**
     * executeViewError
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeRedirect($this->_getNextUri('group'), 1, _MD_LEGROUP_ERROR_DBUPDATE_FAILED);
    }

    /**
     * executeViewCancel
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    public function executeViewCancel(/*** XCube_RenderTarget ***/ &$render)
    {
        $this->mRoot->mController->executeForward($this->_getNextUri('group'));
    }

    /**
     * _doExecute
     * 
     * @param    void
     * 
     * @return    Enum
    **/
    protected function _doExecute()
    {
        if(! parent::_doExecute()){
            return LEGROUP_FRAME_VIEW_ERROR;
        }
        if(! file_exists($_FILES['image1']['tmp_name'])){
            return LEGROUP_FRAME_VIEW_SUCCESS;
        }
        //insert group's image
        $ret = $this->_saveImage('group', $this->mObject->get('title'), $_FILES['image1']['tmp_name']);
        return ($ret==true) ? LEGROUP_FRAME_VIEW_SUCCESS : LEGROUP_FRAME_VIEW_ERROR;
    }
}
