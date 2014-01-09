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

require_once LEGROUP_TRUST_PATH . '/actions/MemberEditAction.class.php';

/**
 * Legroup_MemberEditAction
**/
class Legroup_MemberAddAction extends Legroup_MemberEditAction
{
    /**
     * _getId
     *
     * @param	void
     *
     * @return	int
     **/
    protected function _getId()
    {
        $id = parent::_getId();
        if($id>0){
            return $id;
        }
        //when staff add a new member
        $req = $this->mRoot->mContext->mRequest;
        $uid = $req->getRequest('uid');
        $groupId = $this->_getGroupId();
        if($groupId>0 && $uid>0){
            $cri = new CriteriaCompo();
            $cri->add(new Criteria('group_id', $groupId));
            $cri->add(new Criteria('uid', $uid));
            $objs = $this->_getHandler()->getObjects($cri);
            if(count($objs)>0){
                return array_shift($objs)->getShow('member_id');
            }
        }
        return 0;
    }

    protected function _setupActionForm()
    {
        $this->mActionForm =& $this->mAsset->getObject('form', 'Member',false,'add');
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
        if($this->mObjectHandler->isMember($this->mObject->get('group_id'), Legacy_Utils::getUid(), Lenum_GroupRank::STAFF)){
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
        if(! $this->mGroup = Legacy_Utils::getModuleHandler('group', $this->mAsset->mDirname)->get($this->_getGroupId())){
            $this->mRoot->mController->executeRedirect($this->_getNextUri('group'), 1, _MD_LEGROUP_ERROR_INVALID_GROUP);
        }
    
        parent::prepare();
        if($this->mObject->isNew()){
            //group_id is required
            if(! $groupId = $this->_getGroupId()){
                $this->mRoot->mController->executeRedirect($this->_getNextUri('group', 'list'), 1, _MD_LEGROUP_ERROR_NO_GROUP_ID);
            }
            $this->mObject->set('group_id', $groupId);
        
            $this->mObject->set('status', Lenum_WorkflowStatus::FINISHED);
            $this->mObject->set('rank', Lenum_GroupRank::REGULAR);
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
        $render->setTemplateName($this->mAsset->mDirname . '_member_add.html');
        $render->setAttribute('actionForm', $this->mActionForm);
        $render->setAttribute('object', $this->mObject);
        $render->setAttribute('group', $this->mGroup);
        //set user list
        $cri = new CriteriaCompo();
        $user = $this->mObjectHandler->getMemberIdList($this->mGroup->get('group_id'), Lenum_GroupRank::ASSOCIATE);
        $cri->add(new Criteria('uid', $user['uid'], 'NOT IN'));
        $handler = Legroup_Utils::getXoopsHandler('user');
        $userObjs = $handler->getObjects($cri);
        $render->setAttribute('userObjs', $userObjs);
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
        $this->mRoot->mController->executeForward(Legacy_Utils::renderUri($this->mAsset->mDirname, 'group', $this->mObject->getShow('group_id')));
    }
}
