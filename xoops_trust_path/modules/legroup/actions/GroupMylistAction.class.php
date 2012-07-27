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

require_once LEGROUP_TRUST_PATH . '/actions/GroupListAction.class.php';

/**
 * Legroup_GroupMylistAction
**/
class Legroup_GroupMylistAction extends Legroup_GroupListAction
{
	/**
	 * _getBaseUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getBaseUrl()
	{
		return Legacy_Utils::renderUri($this->mAsset->mDirname, 'group');
	}

	/**
	 * getDefaultView
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function getDefaultView()
	{
		$this->mFilter =& $this->_getFilterForm();
		$this->mFilter->fetch();
	
		$mHandler = Legacy_Utils::getModuleHandler('member', $this->mAsset->mDirname);
		$ids = $mHandler->getMyGroupIdList();
	
		$handler =& $this->_getHandler();
		$cri = $this->mFilter->getCriteria();
		$cri->add(new Criteria('group_id', $ids, 'IN'));
		$this->mObjects =& $handler->getObjects($cri);
	
		return LEGROUP_FRAME_VIEW_INDEX;
	}

	/**
	 * executeViewIndex
	 * 
	 * @param	XCube_RenderTarget	&$render
	 * 
	 * @return	void
	**/
	public function executeViewIndex(/*** XCube_RenderTarget ***/ &$render)
	{
		$render->setTemplateName($this->mAsset->mDirname . '_group_list.html');
		$render->setAttribute('objects', $this->mObjects);
		$render->setAttribute('dirname', $this->mAsset->mDirname);
		$render->setAttribute('dataname', 'group');
		$render->setAttribute('pageNavi', $this->mFilter->mNavi);
	}
}

?>
