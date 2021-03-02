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

/**
 * Legroup_GroupObject
**/
class Legroup_GroupObject extends Legacy_AbstractGroupObject
{
	const PRIMARY = 'group_id';
	const DATANAME = 'group';

	public $mMember = array();
	 protected $_mMemberLoadedFlag = false;
	public $mPolicy = array();
	 protected $_mPolicyLoadedFlag = false;

	/**
	 * __construct
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function __construct()
	{
		$this->initVar('group_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('title', XOBJ_DTYPE_STRING, '', false, 255);
		$this->initVar('publicity', XOBJ_DTYPE_INT, '', false);
		$this->initVar('approval', XOBJ_DTYPE_INT, '', false);
		$this->initVar('description', XOBJ_DTYPE_TEXT, '', false);
		$this->initVar('posttime', XOBJ_DTYPE_INT, time(), false);
	}

	/**
	 * getMyRank
	 * 
	 * @param	string	$target
	 * @param	Enum	$authType
	 * 
	 * @return	void
	 */
	public function getMyRank()
	{
		$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $this->get('group_id')));
		$cri->add(new Criteria('uid', Legacy_Utils::getUid()));
		$cri->add(new Criteria('status', Lenum_WorkflowStatus::FINISHED));
		$objs = $handler->getObjects($cri);
		if(count($objs)>0){
			return array_shift($objs)->get('rank');
		}
	}

	/**
	 * getImage
	 * 
	 * @param	void
	 * 
	 * @return	void
	 */
	public function getImage()
	{
		$imageObjs = array();
		XCube_DelegateUtils::call('Legacy_Image.GetImageObjects', new XCube_Ref($imageObjs), $this->getDirname(), self::DATANAME, $this->get(self::PRIMARY));
		if(count($imageObjs)>0){
			return array_shift($imageObjs);
		}
	}

	public function getMyMemberId()
	{
		$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
		$objs = $handler->getObjects(new Criteria('uid', Legacy_Utils::getUid()));
		if(count($objs)>0){
			$member = array_shift($objs);
			return $member->getShow('member_id');
		}
	}

	/**
	 * isMember
	 * 
	 * @param	int		$uid
	 * @param	Enum	$rank	Lenum_GroupRank
	 * 
	 * @return	int[]
	**/
	public function isMember(/*** int ***/ $uid, /*** Enum ***/ $rank=Lenum_GroupRank::REGULAR, $status=Lenum_WorkflowStatus::FINISHED)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $this->get('group_id')));
		$cri->add(new Criteria('uid', $uid));
		$cri->add(new Criteria('rank', $rank, '>='));
		$cri->add(new Criteria('status', $status, '>='));
		return $handler->getCount($cri)>0 ? true :false;
	}

	/**
	 * getMembers
	 * 
	 * @param	Enum	$rank	Lenum_GroupRank
	 * @param	int		$limit
	 * @param	int		$start
	 * 
	 * @return	Legroup_MemberObject[]
	**/
	public function getMembers(/*** Enum ***/ $rank=Lenum_GroupRank::REGULAR, /*** int ***/ $limit=20, /*** int ***/ $start=0)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $this->get('group_id')));
		$cri->add(new Criteria('rank', $rank, '>='));
		$cri->add(new Criteria('status', Lenum_WorkflowStatus::FINISHED));
		$cri->setSort('posttime', 'DESC');
		return $handler->getObjects($cri, $limit, $start);
	}

	/**
	 * getPendingMembers
	 * 
	 * @param	int		$limit
	 * @param	int		$start
	 * 
	 * @return	Legroup_MemberObject[]
	**/
	public function getPendingMembers(/*** int ***/ $limit=20, /*** int ***/ $start=0)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $this->get('group_id')));
		$cri->add(new Criteria('status', Lenum_WorkflowStatus::FINISHED, '!='));
		$cri->setSort('posttime', 'DESC');
		return $handler->getObjects($cri, $limit, $start);
	}

	/**
	 * countMembers
	 * 
	 * @param	Enum	$rank	Lenum_GroupRank
	 * 
	 * @return	int
	**/
	public function countMembers(/*** Enum ***/ $rank=Lenum_GroupRank::ASSOCIATE, /*** Enum ***/ $status=Lenum_WorkflowStatus::FINISHED)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $this->get('group_id')));
		$cri->add(new Criteria('rank', $rank, '>='));
		$cri->add(new Criteria('status', $status));
		return $handler->getCount($cri);
	}

	/**
	 * getShowPublicity()
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getShowPublicity()
	{
		switch($this->get('publicity')){
		case Legroup_Publicity::CLOSED:
			return _MD_LEGROUP_LANG_CLOSED;
			break;
		case Legroup_Publicity::OPEN:
			return _MD_LEGROUP_LANG_PUBLIC;
			break;
		}
	}

	/**
	 * getShowNextAction()
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getShowNextAction()
	{
		$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $this->get('group_id')));
		$cri->add(new Criteria('uid', Legacy_Utils::getUid()));
		$objs = $handler->getObjects($cri);
		if(count($objs)>0){
			$member = array_shift($objs);
		}
		else{
			return _MD_LEGROUP_LANG_MEMBER_JOIN;
		}
	
		switch($member->get('status')){
		case Lenum_WorkflowStatus::BLOCKED:
			$action = _MD_LEGROUP_LANG_MEMBER_BLOCKED;
			break;
		case Lenum_WorkflowStatus::REJECTED:
			$action = _MD_LEGROUP_LANG_MEMBER_JOIN;
			break;
		case Lenum_WorkflowStatus::PROGRESS:
			$action = _MD_LEGROUP_LANG_MEMBER_CANCEL;
			break;
		case Lenum_WorkflowStatus::FINISHED:
			$action = _MD_LEGROUP_LANG_MEMBER_WITHDRAW;
			break;
		}
		return $action;
	}

	/**
	 * getClientData
	 * 
	 * @param	array	$list
	 *  $client['template_name']
	 *  $client['data']
	 *  $client['dirname']
	 * @param	array	$client
	 *  $client['dirname']
	 *  $client['dataname']
	 *  $client['fieldname']
	 * 
	 * @return	mixed[]
	 *	string	$list['template_name'][]
	 *	string	$list['data'][]
	**/
	public function getClientData(/*** mixed[] ***/ $list, /*** mixed[] ***/ $client)
	{
		XCube_DelegateUtils::call('Legacy_GroupClient.'.$client['dirname'].'.GetClientData', new XCube_Ref($list), $client['dirname'], $client['dataname'], $client['fieldname'], $this->get('group_id'));
		return $list;
	}

	/**
	 * loadMember
	 * 
	 * @param	void
	 * 
	 * @return	void
	 */
	public function loadMember()
	{
		if ($this->_mMemberLoadedFlag == false) {
			$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
			$this->mMember =& $handler->getObjects(new Criteria('group_id', $this->get('group_id')));
			$this->_mMemberLoadedFlag = true;
		}
	}

	/**
	 * loadPolicy
	 * 
	 * @param	void
	 * 
	 * @return	void
	 */
	public function loadPolicy()
	{
		if ($this->_mPolicyLoadedFlag == false) {
			$handler = Legacy_Utils::getModuleHandler('policy', $this->getDirname());
			$this->mPolicy =& $handler->getObjects(new Criteria('group_id', $this->get('group_id')));
			$this->_mPolicyLoadedFlag = true;
		}
	}
}

/**
 * Legroup_GroupHandler
**/
class Legroup_GroupHandler extends XoopsObjectGenericHandler
{
	public /*** string ***/ $mTable = '{dirname}_group';

	public /*** string ***/ $mPrimary = 'group_id';

	public /*** string ***/ $mClass = 'Legroup_GroupObject';

	/**
	 * __construct
	 * 
	 * @param	XoopsDatabase  &$db
	 * @param	string	$dirname
	 * 
	 * @return	void
	**/
	public function __construct(/*** XoopsDatabase ***/ &$db,/*** string ***/ $dirname)
	{
		$this->mTable = strtr($this->mTable,array('{dirname}' => $dirname));
		//!Fix parent::XoopsObjectGenericHandler($db);
        parent::__construct($db);
	}

	/**
	 * insert
	 * 
	 * @param	XoopsSimpleObject	&$obj
	 * @param	bool	$force
	 * 
	 * @return	bool
	**/
	public function insert(/*** XoopsSimpleObject ***/ &$obj, $force=false)
	{
		$result = parent::insert($obj, $force);
		if($obj->isNew()){
			$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
			$member = $handler->create();
			$member->set('uid', Legacy_Utils::getUid());
			$member->set('group_id', $obj->get('group_id'));
			$member->set('status', Lenum_WorkflowStatus::FINISHED);
			$member->set('rank', Lenum_GroupRank::OWNER);
			$handler->insert($member);
		}
		return $result;
	}

	/**
	 * delete
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	
	**/
	public function delete(&$obj, $force = false)
	{
		//delete members in this group
		$handler = Legacy_Utils::getModuleHandler('member', $this->getDirname());
		$handler->deleteAll(new Criteria('group_id', $obj->get('group_id')));
		unset($handler);
	
		//delete policy for this group
		$handler = Legacy_Utils::getModuleHandler('policy', $this->getDirname());
		$handler->deleteAll(new Criteria('group_id', $obj->get('group_id')));
		unset($handler);
	
		return parent::delete($obj, $force);
	}

	/**
	 * getGroupListByIds
	 * 
	 * @param	int[]	$list
	 * 
	 * @return	Legroup_GroupObject[]
	**/
	public function getGroupListByIds(/*** int[] ***/ $list)
	{
		return $this->getObjects(new Criteria($this->mPrimary, $list, 'IN'));
	}
}

