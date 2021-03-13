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
 * Legroup_PolicyEditAction
**/
class Legroup_PolicyEditAction extends Legroup_AbstractEditAction
{
    public $mActionList = array('action'=>array(), 'rank'=>array(), 'title'=>array(), 'description'=>array());
    public $mPolicyArr = array();
    public $mGroup = null;
    public $mTargetModule = null;

    /**
     * &_getHandler
     * 
     * @param    void
     * 
     * @return    Legroup_PolicyHandler
    **/
    protected function &_getHandler()
    {
        $handler =& $this->mAsset->getObject('handler', 'Policy');
        return $handler;
    }

    /**
     * _getPageTitle
     * 
     * @param    void
     * 
     * @return    string
    **/
    protected function _getTitle()
    {
        return null;
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
        $this->mActionForm =& $this->mAsset->getObject('form', 'Policy',false,'edit');
        $this->mActionForm->prepare();
    }

    /**
     * _setupObject
     * 
     * @param    void
     * 
     * @return    void
    **/
    protected function _setupObject()
    {
        $dirname = $this->mRoot->mContext->mRequest->getRequest('dirname');
        $dataname = $this->mRoot->mContext->mRequest->getRequest('dataname');
        $groupId = $this->mRoot->mContext->mRequest->getRequest('group_id');
    
        XCube_DelegateUtils::call('Legacy_GroupClient.GetActionList', new XCube_Ref($this->mActionList), $dirname, $dataname);
    
        $this->mObjectHandler =& $this->_getHandler();
    
        $cri = new CriteriaCompo();
        $cri->add(new Criteria('dirname', $dirname));
        $cri->add(new Criteria('dataname', $dataname));
        $cri->add(new Criteria('group_id', $groupId));
        $cri->add(new Criteria('action', $this->mActionList['action'], 'IN'));
        $this->mObject = $this->mObjectHandler->getObjects($cri);
    
        if(count($this->mObject) < count($this->mActionList['action'])){
            foreach(array_keys($this->mActionList['action']) as $key){
                if(! $this->_checkObject($this->mActionList['action'][$key])){
                    $obj = $this->mObjectHandler->create();
                    $obj->set('group_id', $groupId);
                    $obj->set('dirname', $dirname);
                    $obj->set('dataname', $dataname);
                    $obj->set('action', $this->mActionList['action'][$key]);
                    $obj->set('rank', $this->mActionList['rank'][$key]);
                    $this->mObject[] = $obj;
                    unset($obj);
                }
            }
        }
        $this->mPolicyArr = array('groupId'=>$groupId, 'dirname'=>$dirname, 'dataname'=>$dataname);
    }

    protected function _checkObject($action)
    {
        if($this->mObject===null) return false;
        foreach(array_keys($this->mObject) as $key){
            if($this->mObject[$key]->get('action')==$action){
                return true;
            }
        }
    
        return false;
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
        if($this->mGroup->isMember(Legacy_Utils::getUid(), Lenum_GroupRank::OWNER)){
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
        $this->mTargetModule = $this->mRoot->mContext->mRequest->getRequest('dirname');
        $this->mRoot->mLanguageManager->loadModuleMessageCatalog($this->mTargetModule);
        $this->mGroup = Legacy_Utils::getModuleHandler('group', $this->mAsset->mDirname)->get($this->mRoot->mContext->mRequest->getRequest('group_id'));
    
        parent::prepare();
    
        return true;
    }


    /**
     * getDefaultView
     * 
     * @param    void
     * 
     * @return    Enum
    **/
    public function getDefaultView()
    {
        if($this->mObject == null)
        {
            //return LEGROUP_FRAME_VIEW_ERROR;
        }
    
        $this->mActionForm->load($this->mObject);
    
        return LEGROUP_FRAME_VIEW_INPUT;
    }

    /**
     * execute
     * 
     * @param    void
     * 
     * @return    Enum
    **/
    public function execute()
    {
        if ($this->mObject == null)
        {
            return LEGROUP_FRAME_VIEW_ERROR;
        }
    
        if ($this->mRoot->mContext->mRequest->getRequest('_form_control_cancel') != null)
        {
            return LEGROUP_FRAME_VIEW_CANCEL;
        }
    
        $this->mActionForm->load($this->mObject);
        $this->mActionForm->fetch();
        $this->mActionForm->validate();
        if ($this->mActionForm->hasError())
        {
            return LEGROUP_FRAME_VIEW_INPUT;
        }
    
        $this->mActionForm->update($this->mObject);
        $ret = LEGROUP_FRAME_VIEW_SUCCESS;
        foreach(array_keys($this->mObject) as $key){
            if($this->_doExecute($this->mObject[$key])==LEGROUP_FRAME_VIEW_ERROR){
                $ret = LEGROUP_FRAME_VIEW_ERROR;
            }
        }
    
        return $ret;
    }

    /**
     * _doExecute
     * 
     * @param    void
     * 
     * @return    Enum
    **/
    protected function _doExecute($obj)
    {
        if($this->mObjectHandler->insert($obj)){
            return LEGROUP_FRAME_VIEW_SUCCESS;
        }
    
        return LEGROUP_FRAME_VIEW_ERROR;
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
        $render->setTemplateName($this->mAsset->mDirname . '_policy_edit.html');
        $render->setAttribute('actionForm', $this->mActionForm);
        $render->setAttribute('object', $this->mObject);
        $render->setAttribute('group', $this->mGroup);
        $render->setAttribute('targetModule', $this->mTargetModule);
        $render->setAttribute('policyArr', $this->mPolicyArr);
        $render->setAttribute('rankList', Lenum_GroupRank::getList());
        $render->setAttribute('actionList', $this->mActionList);

        //breadcrumb
        $breadcrumbs = array();
        XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.Global.Event.GetBreadcrumbs', new XCube_Ref($breadcrumbs), $this->mAsset->mDirname, $this->mGroup);
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
    {die('error');
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
     * _getNextUri
     * 
     * @param    void
     * 
     * @return    string
    **/
    protected function _getNextUri($tableName, $actionName=null)
    {
        $handler = $this->_getHandler();
        if($this->mObject){
            return Legacy_Utils::renderUri($this->mAsset->mDirname, $tableName, intval($this->mRoot->mContext->mRequest->getRequest('group_id')), $actionName);
        }
        else{
            return Legacy_Utils::renderUri($this->mAsset->mDirname, $tableName, 0, $actionName);
        }
    }
}
