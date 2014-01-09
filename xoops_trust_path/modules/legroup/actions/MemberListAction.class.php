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

require_once LEGROUP_TRUST_PATH . '/class/AbstractListAction.class.php';

/**
 * Legroup_MemberListAction
**/
class Legroup_MemberListAction extends Legroup_AbstractListAction
{
     /**
     * _getid
     * 
     * @param    void
     * 
     * @return    int
    **/
    protected function _getGroupId()
    {
        $req = $this->mRoot->mContext->mRequest;
        $dataId = $req->getRequest(_REQUESTED_DATA_ID);
        return isset($dataId) ? intval($dataId) : intval($req->getRequest('group_id'));
    }

     /**
     * _getHandler
     * 
     * @param    void
     * 
     * @return    Legroup_MemberHandler
    **/
    protected function _getHandler()
    {
        $handler =& $this->mAsset->getObject('handler', 'Member');
        return $handler;
    }

    /**
     * _getFilterForm
     * 
     * @param    void
     * 
     * @return    Legroup_MemberFilterForm
    **/
    protected function _getFilterForm()
    {
        $filter =& $this->mAsset->getObject('filter', 'Member',false);
        $filter->prepare($this->_getPageNavi(), $this->_getHandler());
        return $filter;
    }

    /**
     * _getBaseUrl
     * 
     * @param    void
     * 
     * @return    string
    **/
    protected function _getBaseUrl()
    {
        return Legacy_Utils::renderUri($this->mAsset->mDirname, 'member');
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
        if(! $this->mGroup = Legacy_Utils::getModuleHandler('group', $this->mAsset->mDirname)->get($this->_getGroupId())){
            $this->mRoot->mController->executeRedirect($this->_getNextUri('group'), 1, _MD_LEGROUP_ERROR_INVALID_GROUP);
        }
    
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
        $this->mFilter =& $this->_getFilterForm();
        $this->mFilter->fetch();
    
        $handler =& $this->_getHandler();
        $cri = $this->mFilter->getCriteria();
        //if you are staff, show all members unauthorized.
        if(! $this->_getHandler()->isMember($this->_getGroupId(), Legacy_Utils::getUid(), Lenum_GroupRank::STAFF)){
            $cri->add(new Criteria('status', Lenum_WorkflowStatus::FINISHED));
        }
        $this->mObjects =& $handler->getObjects($cri);
    
        return LEGROUP_FRAME_VIEW_INDEX;
    }

    /**
     * executeViewIndex
     * 
     * @param    XCube_RenderTarget    &$render
     * 
     * @return    void
    **/
    public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
    {
        $render->setTemplateName($this->mAsset->mDirname . '_member_list.html');
        $render->setAttribute('objects', $this->mObjects);
        $render->setAttribute('group', $this->mGroup);
        $render->setAttribute('dirname', $this->mAsset->mDirname);
        $render->setAttribute('dataname', 'member');
        $render->setAttribute('pageNavi', $this->mFilter->mNavi);
        //set this user's rank
        $cri = new CriteriaCompo();
        $cri->add(new Criteria('uid', Legacy_Utils::getUid()));
        $cri->add(new Criteria('group_id', $this->_getGroupId()));
        $members = $this->_getHandler()->getObjects($cri);
        if(count($members)>0){
            $myrank = array_shift($members)->get('rank');
        }
        $render->setAttribute('myrank', $myrank ? $myrank : 0);
        //breadcrumb
        $breadcrumbs = array();
        XCube_DelegateUtils::call('Module.'.$this->mAsset->mDirname.'.Global.Event.GetBreadcrumbs', new XCube_Ref($breadcrumbs), $this->mAsset->mDirname, $this->mGroup);
        $render->setAttribute('xoops_breadcrumbs', $breadcrumbs);
    }
}

?>
