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
 * Legroup_MemberEditAction
**/
class Legroup_MemberEditAction extends Legroup_AbstractEditAction
{
	public $mGroup = null;

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
		$uid = $req->getRequest('uid') ? $req->getRequest('uid') : Legacy_Utils::getUid();
		if($this->_getGroupId() && $uid>0){
			$cri = new CriteriaCompo();
			$cri->add(new Criteria('group_id', $this->_getGroupId()));
			$cri->add(new Criteria('uid', $uid));
			$objs = $this->_getHandler()->getObjects($cri);
			if(count($objs)>0){
				return array_shift($objs)->getShow('member_id');
			}
		}
	}

	protected function _getTitle()
	{
		return _MD_LEGROUP_LANG_MEMBER;
	}

	/**
	 * get group_id from request
	 * 
	 * @param	void
	 * 
	 * @return	int
	**/
	protected function _getGroupId()
	{
		return intval($this->mRoot->mContext->mRequest->getRequest('group_id'));
	}

	/**
	 * &_getHandler
	 * 
	 * @param	void
	 * 
	 * @return	Legroup_MemberHandler
	**/
	protected function &_getHandler()
	{
		$handler =& $this->mAsset->getObject('handler', 'Member');
		return $handler;
	}

	/**
	 * _setupActionForm
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	protected function _setupActionForm()
	{
		$this->mActionForm =& $this->mAsset->getObject('form', 'Member',false,'edit');
		$this->mActionForm->prepare();
	}

	/**
	 * hasPermission
	 * 
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function hasPermission()
	{
		if(! $this->mRoot->mContext->mUser->isInRole('Site.RegisteredUser')){
			return false;
		}
	
		if($this->mObjectHandler->isMember($this->mObject->get('group_id'), Legacy_Utils::getUid(), Lenum_GroupRank::STAFF)){
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
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function prepare()
	{
		$groupId = $this->_getGroupId();
		if(! ($this->mGroup = Legacy_Utils::getModuleHandler('group', $this->mAsset->mDirname)->get($this->_getGroupId()))){
			$this->mRoot->mController->executeRedirect($this->_getNextUri('group'), 1, _MD_LEGROUP_ERROR_INVALID_GROUP);
		}
	
		parent::prepare();
		if($this->mObject->isNew()){
			//group_id is required
			if(! $groupId){
				$this->mRoot->mController->executeRedirect($this->_getNextUri('group', 'list'), 1, _MD_LEGROUP_ERROR_NO_GROUP_ID);
			}
			//get user id
			$uid = ($this->mRoot->mContext->mRequest->getRequest('uid')>0) ? $this->mRoot->mContext->mRequest->getRequest('uid') : Legacy_Utils::getUid();
		
			$this->mObject->set('uid', $uid);
			$this->mObject->set('group_id', $groupId);
		
			switch($this->mGroup->get('approval')){
			case Legroup_Approval::ASSOCIATE:
				$this->mObject->set('status', Lenum_WorkflowStatus::FINISHED);
				$this->mObject->set('rank', Lenum_GroupRank::ASSOCIATE);
				break;
			case Legroup_Approval::REGULAR:
				$this->mObject->set('status', Lenum_WorkflowStatus::FINISHED);
				$this->mObject->set('rank', Lenum_GroupRank::REGULAR);
				break;
			default:
				$this->mObject->set('status', Lenum_WorkflowStatus::PROGRESS);
				$this->mObject->set('rank', Lenum_GroupRank::ASSOCIATE);
			}
		}
		return true;
	}

	/**
	 * executeViewInput
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewInput(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->_getTemplateName());
		$render->setAttribute('actionForm', $this->mActionForm);
		$render->setAttribute('object', $this->mObject);
		$render->setAttribute('group', $this->mGroup);
	}

	protected function _getTemplateName()
	{
		return $this->mAsset->mDirname . '_member_edit.html';
	}

	/**
	 * executeViewSuccess
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewSuccess(/*** XCube_RenderTarget ***/ &$render)
	{
		$this->mRoot->mController->executeForward($this->_getNextUri('member'));
	}

	/**
	 * executeViewError
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewError(/*** XCube_RenderTarget ***/ &$render)
	{
		$this->mRoot->mController->executeRedirect($this->_getNextUri('member'), 1, _MD_LEGROUP_ERROR_DBUPDATE_FAILED);
	}

	/**
	 * executeViewCancel
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewCancel(/*** XCube_RenderTarget ***/ &$render)
	{
		$this->mRoot->mController->executeForward(Legacy_Utils::renderUri($this->mAsset->mDirname, 'group', $this->mObject->getShow('group_id')));
	}
}

?>
