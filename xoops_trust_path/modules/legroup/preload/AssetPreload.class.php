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

if(!defined('LEGROUP_TRUST_PATH'))
{
	define('LEGROUP_TRUST_PATH',XOOPS_TRUST_PATH . '/modules/legroup');
}

require_once LEGROUP_TRUST_PATH . '/class/LegroupUtils.class.php';
require_once LEGROUP_TRUST_PATH . '/class/Enum.class.php';

/**
 * Legroup_AssetPreloadBase
**/
class Legroup_AssetPreloadBase extends XCube_ActionFilter
{
	public $mDirname = null;

    /**
     * prepare
     * 
     * @param   string	$dirname
     * 
     * @return  void
    **/
    public static function prepare(/*** string ***/ $dirname)
    {
        $root =& XCube_Root::getSingleton();
        $instance = new Legroup_AssetPreloadBase($root->mController);
        $instance->mDirname = $dirname;
        $root->mController->addActionFilter($instance);
    }

	/**
	 * preBlockFilter
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function preBlockFilter()
	{
		$file = LEGROUP_TRUST_PATH . '/class/DelegateFunctions.class.php';
		$this->mRoot->mDelegateManager->add('Module.legroup.Global.Event.GetAssetManager','Legroup_AssetPreloadBase::getManager');
		$this->mRoot->mDelegateManager->add('Legacy_Utils.CreateModule','Legroup_AssetPreloadBase::getModule');
		$this->mRoot->mDelegateManager->add('Legacy_Utils.CreateBlockProcedure','Legroup_AssetPreloadBase::getBlock');
		$this->mRoot->mDelegateManager->add('Module.'.$this->mDirname.'.Global.Event.GetNormalUri','Legroup_CoolUriDelegate::getNormalUri', $file);
		$this->mRoot->mDelegateManager->add('Legacy_ImageClient.GetClientList','Legroup_ImageClientDelegate::getClientList', $file);
	
		//Legacy Group Delegate
		$gfile = LEGROUP_TRUST_PATH . '/class/DelegateFunctions_Group.class.php';
		$prefix = 'Legacy_Group.' . $this->mDirname . '.';
	
		$this->mRoot->mDelegateManager->add($prefix .'GetTitle','Legroup_GroupDelegate::getTitle', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'GetTitleList','Legroup_GroupDelegate::getTitleList', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'HasPermission','Legroup_GroupDelegate::hasPermission', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'GetGroupIdList','Legroup_GroupDelegate::getGroupIdList', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'GetGroupList','Legroup_GroupDelegate::getGroupList', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'GetGroupIdListByAction','Legroup_GroupDelegate::getGroupIdListByAction', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'GetGroupListByAction','Legroup_GroupDelegate::getGroupListByAction', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'GetGroupList','Legroup_GroupDelegate::getGroupList', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'GetMemberList','Legroup_GroupDelegate::getMemberList', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'IsMember','Legroup_GroupDelegate::isMember', $gfile);
		$this->mRoot->mDelegateManager->add($prefix .'GetGroupsActivitiesList','Legroup_GroupDelegate::getGroupsActivitiesList', $gfile);
	}

	/**
	 * getManager
	 * 
	 * @param	Legroup_AssetManager  &$obj
	 * @param	string	$dirname
	 * 
	 * @return	void
	**/
	public static function getManager(/*** Legroup_AssetManager ***/ &$obj,/*** string ***/ $dirname)
	{
		require_once LEGROUP_TRUST_PATH . '/class/AssetManager.class.php';
		$obj = Legroup_AssetManager::getInstance($dirname);
	}

	/**
	 * getModule
	 * 
	 * @param	Legacy_AbstractModule  &$obj
	 * @param	XoopsModule  $module
	 * 
	 * @return	void
	**/
	public static function getModule(/*** Legacy_AbstractModule ***/ &$obj,/*** XoopsModule ***/ $module)
	{
		if($module->getInfo('trust_dirname') == 'legroup')
		{
			require_once LEGROUP_TRUST_PATH . '/class/Module.class.php';
			$obj = new Legroup_Module($module);
		}
	}

	/**
	 * getBlock
	 * 
	 * @param	Legacy_AbstractBlockProcedure  &$obj
	 * @param	XoopsBlock	$block
	 * 
	 * @return	void
	**/
	public static function getBlock(/*** Legacy_AbstractBlockProcedure ***/ &$obj,/*** XoopsBlock ***/ $block)
	{
		$moduleHandler =& Legroup_Utils::getXoopsHandler('module');
		$module =& $moduleHandler->get($block->get('mid'));
		if(is_object($module) && $module->getInfo('trust_dirname') == 'legroup')
		{
			require_once LEGROUP_TRUST_PATH . '/blocks/' . $block->get('func_file');
			$className = 'Legroup_' . substr($block->get('show_func'), 4);
			$obj = new $className($block);
		}
	}
}

?>
