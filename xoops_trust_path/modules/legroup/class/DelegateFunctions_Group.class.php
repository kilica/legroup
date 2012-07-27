<?php
/**
 * @package legroup
 */

if (!defined('XOOPS_ROOT_PATH')) exit();

/**
 * group delegate
**/
class Legroup_GroupDelegate implements Legacy_iGroupDelegate
{
	/**
	 * getTitle Legacy_Group.GetTitle
	 *
	 * @param string 	&$title
	 * @param string	$gDirname	Group Module Dirname
	 * @param int		$groupId
	 *
	 * @return	void
	 */ 
	public static function getTitle(/*** string ***/ &$title, /*** string ***/ $gDirname, /*** int ***/ $groupId)
	{
		$handler = Legacy_Utils::getModuleHandler('group', $gDirname);
		$obj = $handler->get($groupId);
		if($obj){
			$title = $obj->getShow('title');
		}
	}

	/**
	 * getTitleList
	 *
	 * @param string[]		&$titleList
	 * @param string	$gDirname
	 *
	 * @return	void
	 */ 
	public static function getTitleList(/*** string[] ***/ &$titleList, /*** string ***/ $gDirname)
	{
		$objs = Legacy_Utils::getModuleHandler('group', $gDirname)->getObjects();
		foreach(array_keys($objs) as $key){
			$titleList[$objs[$key]->get('group_id')] = $objs[$key]->get('title');
		}
	}

	/**
	 * hasPermission
	 *
	 * @param bool	 &$check
	 * @param string $gDirname
	 * @param int	 $groupId
	 * @param string $dirname
	 * @param string $dataname
	 * @param string $action
	 *
	 * @return	void
	 */ 
	public static function hasPermission(/*** bool ***/ &$check, /*** string ***/ $gDirname, /*** int ***/ $groupId, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $action)
	{
		$rank = Legacy_Utils::getModuleHandler('policy', $gDirname)->getRankByPolicy($groupId, $dirname, $dataname, $action);
		$check = Legacy_Utils::getModuleHandler('member', $gDirname)->isMember($groupId, Legacy_Utils::getUid(), $rank);
	}

	/**
	 * getGroupIdList Legacy_Group.GetGroupIdList
	 *
	 * @param int[] 	&$list
	 * @param string	$gDirname	Group Module Dirname
	 * @param Enum		$rank	Legacy_GroupRank
	 * @param int		$limit
	 * @param int		$start
	 *
	 * @return	void
	 */ 
	public static function getGroupIdList(/*** int[] ***/ &$list, /*** string ***/ $gDirname, /*** Enum ***/ $rank, /*** int ***/ $limit=null, /*** int ***/ $start=null)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $gDirname);
		$list = $handler->getMyGroupIdList($rank, $limit, $start);
	}

	/**
	 * getGroupList Legacy_Group.GetGroupList
	 *
	 * @param Legacy_AbstractGroupObject[] &$list
	 * @param string	$gDirname	Group Module Dirname
	 * @param Enum		$rank	Lenum_GroupRank
	 * @param int		$limit
	 * @param int		$start
	 *
	 * @return	void
	 */ 
	public static function getGroupList(/*** mixed[] ***/ &$list, /*** string ***/ $gDirname, /*** Lenum_GroupRank ***/ $rank, /*** int ***/ $limit=null, /*** int ***/ $start=null)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $gDirname);
		$idList = $handler->getMyGroupIdList($rank, $start, $limit);
		$handler = Legacy_Utils::getModuleHandler('group', $gDirname);
		$list = $handler->getGroupListByIds($idList);
	}

	/**
	 * getGroupIdListByAction Legacy_Group.GetGroupIdListByAction
	 *
	 * @param int[] 	&$list
	 * @param string	$gDirname	Group Module Dirname
	 * @param string	$dirname
	 * @param string	$dataname
	 * @param string	$action
	 * @param int		$limit
	 * @param int		$start
	 *
	 * @return	void
	 */ 
	public static function getGroupIdListByAction(/*** int[] ***/ &$list, /*** string ***/ $gDirname, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $action, /*** int ***/ $limit=null, /*** int ***/ $start=null)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $gDirname);
		$list = $handler->getMyGroupIdListByAction($dirname, $dataname, $action, $limit, $start);
	}

	/**
	 * getGroupListByAction Legacy_Group.GetGroupListByAction
	 *
	 * @param Legacy_AbstractGroupList[] &$list
	 * @param string	$gDirname	Group Module Dirname
	 * @param string	$dirname
	 * @param string 	$dataname
	 * @param string 	$action
	 * @param int		$limit
	 * @param int		$start
	 *
	 * @return	void
	 */ 
	public static function getGroupListByAction(/*** mixed[] ***/ &$list, /*** string ***/ $gDirname, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $action, /*** int ***/ $limit=null, /*** int ***/ $start=null)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $gDirname);
		$idList = $handler->getMyGroupIdListByAction($dirname, $dataname, $action, $limit, $start);
		$handler = Legacy_Utils::getModuleHandler('group', $gDirname);
		$list = $handler->getGroupListByIds($idList);
	}

	/**
	 * getMemberList	  Legacy_Group.GetMemberList
	 * get member list in the given group
	 *
	 * @param mixed $list
	 *	$list['uid']
	 *	$list['rank']
	 * @param string	$gDirname	Group Module Dirname
	 * @param int		$groupId
	 * @param Enum		$rank	Lenum_GroupRank
	 *
	 * @return	void
	 */ 
	public static function getMemberList(/*** int[] ***/ &$list, /*** string ***/ $gDirname, /*** int ***/ $groupId, /*** Enum ***/ $rank)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $gDirname);
		$list = $handler->getMemberIdList($groupId, $rank);
	}

	/**
	 * isMember 	 Legacy_Group.IsMember
	 * check the user's belonging and rank in the given group
	 *
	 * @param string	$gDirname	Group Module Dirname
	 * @param bool	&$check
	 * @param int	$groupId
	 * @param int	$uid
	 * @param Enum	$rank	Lenum_GroupRank
	 *
	 * @return	void
	 */ 
	public static function isMember(/*** bool ***/ &$check, /*** string ***/ $gDirname, /*** int ***/ $groupId, /*** int ***/ $uid, /*** Enum ***/ $rank=Lenum_GroupRank::REGULAR)
	{
		$handler = Legacy_Utils::getModuleHandler('member', $gDirname);
		$check = $handler->isMember($groupId, $uid, $rank);
	}

	/**
	 * getGroupsActivitiesList 	Legacy_Group.GetGroupActivitiesList
	 * get friends recent action list
	 *
	 * @param Legacy_AbstractGroupActivityObject[] &$actionList
	 * @param string	$gDirname	Group Module Dirname
	 * @param int	$uid
	 * @param int	$limit
	 * @param int	$start
	 *
	 * @return	void
	 */ 
	public static function getGroupsActivitiesList(/*** Legacy_AbstractGroupActivityObject[] ***/ &$actionList, /*** string ***/ $gDirname, /*** int ***/ $uid, /*** int ***/ $limit=20, /*** int ***/ $start=0)
	{
	}
}

?>
