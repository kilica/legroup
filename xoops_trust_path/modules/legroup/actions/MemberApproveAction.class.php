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
class Legroup_MemberApproveAction extends Legroup_MemberEditAction
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
		$req = $this->mRoot->mContext->mRequest;
		$dataId = $req->getRequest(_REQUESTED_DATA_ID);
		return isset($dataId) ? intval($dataId) : intval($req->getRequest($this->_getHandler()->mPrimary));
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
		$this->mActionForm =& $this->mAsset->getObject('form', 'Member',false,'approve');
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
		return ($this->mObjectHandler->isMember($this->mObject->get('group_id'), Legacy_Utils::getUid(), Lenum_GroupRank::STAFF)) ? true : false;
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
		$this->_setupObject();
		$this->mObject->loadGroup();
		$this->_setupActionForm();
	
		if($this->mObject->isNew()){
			$this->mRoot->mController->executeRedirect($this->_getNextUri('member'), 1, _MD_LEGROUP_ERROR_NO_MEMBER_ID);
		}
		$rank = $this->mRoot->mContext->mRequest->getRequest('rank');
		if(! $rank){
			$rank = $this->mObject->get('rank');//var_dump($this->mObject->get('rank'));
		}
		if(! in_array($rank, array_keys($this->_getRankList()))){
			$this->mRoot->mController->executeRedirect($this->_getNextUri('member'), 1, _MD_LEGROUP_ERROR_INVALID_RANK);
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
		parent::executeViewInput($render);
		$render->setAttribute('rankList', $this->_getRankList());
	}

	protected function _getTemplateName()
	{
		return $this->mAsset->mDirname . '_member_approve.html';
	}

	/**
	 * _getRankList
	 * 
	 * @param	void
	 * 
	 * @return	string[]
	**/
	protected function _getRankList()
	{
		$list = Lenum_GroupRank::getList();
		//only "owner" can make others as "owner".
		if(($this->mObjectHandler->isMember($this->mObject->get('group_id'), Legacy_Utils::getUid(), Lenum_GroupRank::OWNER))){
			return $list;
		}
		else{
			array_pop($list);	//remove 'owner' element.
			return $list;
		}
	}
}
