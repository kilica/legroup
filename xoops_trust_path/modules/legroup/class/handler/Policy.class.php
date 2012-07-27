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
 * Legroup_PolicyObject
**/
class Legroup_PolicyObject extends XoopsSimpleObject
{
	const PRIMARY = 'policy_id';
	const DATANAME = 'policy';
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
		$this->initVar('policy_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('group_id', XOBJ_DTYPE_INT, '', false);
		$this->initVar('dirname', XOBJ_DTYPE_STRING, '', false, 25);
		$this->initVar('dataname', XOBJ_DTYPE_STRING, '', false, 25);
		$this->initVar('action', XOBJ_DTYPE_STRING, '', false, 25);
		$this->initVar('rank', XOBJ_DTYPE_INT, Lenum_GroupRank::STAFF, false);
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
}

/**
 * Legroup_PolicyHandler
**/
class Legroup_PolicyHandler extends XoopsObjectGenericHandler
{
	public /*** string ***/ $mTable = '{dirname}_policy';

	public /*** string ***/ $mPrimary = 'policy_id';

	public /*** string ***/ $mClass = 'Legroup_PolicyObject';

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

	public function getPolicyArr(/*** int[] ***/ $gIds, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $action)
	{
		$policyList = array();
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $gIds, 'IN'));
		$cri->add(new Criteria('dirname', $dirname));
		$cri->add(new Criteria('dataname', $dataname));
		$cri->add(new Criteria('action', $action));
		$policyArr = $this->getObjects($cri);
		
		foreach(array_keys($policyArr) as $key){
			$policyList[$policyArr[$key]->get('group_id')] = $policyArr[$key]->get('rank');
		}
	
		//set default policy
		$actionList = array('action'=>array(), 'rank'=>array(), 'title'=>array(), 'description'=>array());
		XCube_DelegateUtils::call('Legacy_GroupClient.GetActionList', new XCube_Ref($actionList), $dirname, $dataname);
		$actionId = array_search($action, $actionList['action']);
		$defaultRank = $actionList['rank'][$actionId];if($actionId===false){var_dump($gIds);var_dump($actionList['rank']);echo $dirname.'|'.$dataname.'|'.$action;die();}
		foreach($gIds as $id){
			if(! in_array($id, array_keys($policyList))){
				$policyList[$id] = $defaultRank;
			}
		}
		return $policyList;
	}

	/**
	 * getRankByPolicy
	 * 
	 * @param	int		$groupId
	 * @param	string	$dirname
	 * @param	string	$dataname
	 * @param	string	$action
	 * 
	 * @return	Enum	Lenum_GroupRank
	**/
	public function getRankByPolicy(/*** int ***/ $groupId, /*** string ***/ $dirname, /*** string ***/ $dataname, /*** string ***/ $action)
	{
		$cri = new CriteriaCompo();
		$cri->add(new Criteria('group_id', $groupId));
		$cri->add(new Criteria('dirname', $dirname));
		$cri->add(new Criteria('dataname', $dataname));
		$cri->add(new Criteria('action', $action));
		$objs = $this->getObjects($cri);
		if(count($objs)>0){
			return array_shift($objs)->get('rank');
		}
		return Lenum_GroupRank::OWNER;
	}
}

?>
