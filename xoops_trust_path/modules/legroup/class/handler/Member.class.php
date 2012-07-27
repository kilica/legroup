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
 * Legroup_MemberObject
**/
class Legroup_MemberObject extends XoopsSimpleObject
{
	const PRIMARY = 'member_id';
	const DATANAME = 'member';

	public $mGroup = null;
	protected $_mGroupLoadedFlag = false;

	/**
	 * __construct
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function __construct()
	{
		$this->initVar('member_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('uid', XOBJ_DTYPE_INT, '', false);
		$this->initVar('group_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('status', XOBJ_DTYPE_INT, Lenum_WorkflowStatus::PROGRESS, false);
		$this->initVar('rank', XOBJ_DTYPE_INT, Lenum_GroupRank::ASSOCIATE, false);
		$this->initVar('posttime', XOBJ_DTYPE_INT, time(), false);
	}

	/**
	 * getPrimary
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getPrimary()
	{
		return self::PRIMARY;
	}

	/**
	 * getDataname
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getDataname()
	{
		return self::DATANAME;
	}

	/**
	 * loadGroup
	 * 
	 * @param	void
	 * 
	 * @return	void
	 */
	public function loadGroup()
	{
		if ($this->_mGroupLoadedFlag == false) {
			$handler = Legacy_Utils::getModuleHandler('group', $this->getDirname());
			$this->mGroup =& $handler->get($this->get('group_id'));
			$this->_mGroupLoadedFlag = true;
		}
	}

	/**
	 * getShowRank()
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getShowRank()
	{
		$list = Lenum_GroupRank::getList();
		return $list[$this->get('rank')];
	}

	/**
	 * check has enough rank ?
	 * 
	 * @param	int		$rank
	 * 
	 * @return	bool
	**/
	public function hasRank(/*** int ***/ $rank)
	{
		return $this->get('rank')>=$rank ? true : false;
	}

	/**
	 * check this user is approved ?
	 * 
	 * @param	void
	 * 
	 * @return	bool
	**/
	public function isApproved()
	{
		return $this->get('status')===Lenum_WorkflowStatus::FINISHED ? true : false;
	}
}

/**
 * Legroup_MemberHandler
**/
class Legroup_MemberHandler extends XoopsObjectGenericHandler
{
	public /*** string ***/ $mTable = '{dirname}_member';
	public /*** string ***/ $mPrimary = 'member_id';
	public /*** string ***/ $mClass = 'Legroup_MemberObject';

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
		parent::XoopsObjectGenericHandler($db);
	}

	/**
	 * getMyGroupIdList
	 * 
	 * @param	Enum	$rank	Lenum_GroupRank
	 * @param	int		$limit
	 * @param	int		$start
	 * 
	 * @return	int[]
	**/
	public function getMyGroupIdList(/*** Enum ***/ $rank=Lenum_GroupRank::ASSOCIATE, /*** int ***/ $limit=null, /*** int ***/ $start=null)
	{
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('uid', Legacy_Utils::getUid()));
		$cri->add(new Criteria('rank', $rank, '>='));
		$cri->add(new Criteria('status', Lenum_WorkflowStatus::FINISHED));
		$cri->setSort('posttime', 'DESC');
		return $this->getIdList($cri, $limit, $start);
	}

	/**
	 * getMyGroupIdListByAction
	 * 
	 * @param	string	$dirname
	 * @param	string	$dataname
	 * @param	string	$action
	 * @param	int		$limit
	 * @param	int		$start
	 * 
	 * @return	int[]
	**/
	public function getMyGroupIdListByAction(/*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $action, /*** int ***/ $limit=null, /*** int ***/ $start=null)
	{
		$handler = Legacy_Utils::getModuleHandler('policy', $this->getDirname());
		$policyList = $handler->getPolicyArr($this->getMyGroupIdList(), $dirname, $dataname, $action);
	
		$ids = array();	//group_id
		$mCri = new CriteriaCompo();
		$mCri->add(new Criteria('uid', Legacy_Utils::getUid()));
		$mCri->add(new Criteria('status', Lenum_WorkflowStatus::FINISHED));
		$mCri->setSort('posttime', 'DESC');
		$memberArr = $this->getObjects($mCri);
		foreach(array_keys($memberArr) as $key){
			$groupId = $memberArr[$key]->get('group_id');
			if($policyList[$groupId] <= $memberArr[$key]->get('rank')){
				$ids[] = $memberArr[$key]->get('group_id');
			}
		}
		return (isset($limit)) ? array_slice($ids, intval($start), intval($limit)) : $ids;
	}

	/**
	 * getMemberIdList
	 * 
	 * @param	int		$groupId
	 * @param	Enum	$rank	Lenum_GroupRank
	 * 
	 * @return	int[]
	**/
	public function getMemberIdList(/*** int ***/ $groupId, /*** Enum ***/ $rank=Lenum_GroupRank::REGULAR)
	{
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $groupId));
		$cri->add(new Criteria('rank', $rank, '>='));
		$cri->add(new Criteria('status', Lenum_WorkflowStatus::FINISHED));
		$cri->setSort('posttime', 'DESC');
		$objs = $this->getObjects($cri);
		foreach(array_keys($objs) as $key){
			$ret['uid'][] = $objs[$key]->get('uid');
			$ret['rank'][] = $objs[$key]->get('rank');
		}
		return $ret;
	}

	/**
	 * isMember
	 * 
	 * @param	int		$groupId
	 * @param	int		$uid
	 * @param	Enum	$rank	Lenum_GroupRank
	 * 
	 * @return	int[]
	**/
	public function isMember(/*** int ***/ $groupId, /*** int ***/ $uid, /*** Enum ***/ $rank=Lenum_GroupRank::REGULAR)
	{
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $groupId));
		$cri->add(new Criteria('uid', $uid));
		$cri->add(new Criteria('rank', $rank, '>='));
		$cri->add(new Criteria('status', Lenum_WorkflowStatus::FINISHED));
		$cri->setSort('posttime', 'DESC');
		$ids = $this->getIdList($cri);
		return count($ids)>0 ? true :false;
	}
}

?>
