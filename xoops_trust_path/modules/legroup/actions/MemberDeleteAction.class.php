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

require_once LEGROUP_TRUST_PATH . '/class/AbstractDeleteAction.class.php';

/**
 * Legroup_MemberDeleteAction
**/
class Legroup_MemberDeleteAction extends Legroup_AbstractDeleteAction
{
    protected function _getTitle()
    {
        return _MD_LEGROUP_LANG_MEMBER;
    }

    /**
     * &_getHandler
     * 
     * @param    void
     * 
     * @return    Legroup_MemberHandler
    **/
    protected function &_getHandler()
    {
        $handler =& $this->mAsset->getObject('handler', 'Member');
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
        $this->mActionForm =& $this->mAsset->getObject('form', 'Member',false,'delete');
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
    
        if($this->mObject->mGroup->isMember(Legacy_Utils::getUid(), Lenum_GroupRank::STAFF)){
            return true;
        }
    
        if($this->mObject->get('uid')==Legacy_Utils::getUid()){
            return true;
        }
        return false;
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
        $this->mObject->loadGroup();
    
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
        $render->setTemplateName($this->mAsset->mDirname . '_member_delete.html');
        $render->setAttribute('actionForm', $this->mActionForm);
        $render->setAttribute('object', $this->mObject);
        //breadcrumb
        $breadcrumbs = array();
        XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.Global.Event.GetBreadcrumbs', new XCube_Ref($breadcrumbs), $this->mAsset->mDirname, $this->mObject->mGroup);
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
        $this->mRoot->mController->executeForward($this->_getNextUri('member', 'list'));
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
        $this->mRoot->mController->executeRedirect($this->_getNextUri('member'), 1, _MD_LEGROUP_ERROR_DBUPDATE_FAILED);
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
        $this->mRoot->mController->executeForward($this->_getNextUri('member'));
    }
}
