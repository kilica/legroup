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

require_once XOOPS_ROOT_PATH . '/core/XCube_PageNavigator.class.php';

/**
 * Legroup_AbstractListAction
**/
abstract class Legroup_AbstractListAction extends Legroup_AbstractAction
{
	public /*** XoopsSimpleObject[] ***/ $mObjects = null;

	public /*** Legroup_AbstractFilterForm ***/ $mFilter = null;

	/**
	 * &_getHandler
	 * 
	 * @param	void
	 * 
	 * @return	&XoopsObjectGenericHandler
	**/
	protected function _getHandler()
	{
	}

	/**
	 * _getActionName
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getActionName()
	{
		return _LIST;
	}

	/**
	 * getPageTitle
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	public function getPagetitle()
	{
		///XCL2.2 only
		return Legacy_Utils::formatPagetitle($this->mRoot->mContext->mModule->mXoopsModule->get('name'), null, $this->_getActionName());
	}

	/**
	 * &_getFilterForm
	 * 
	 * @param	void
	 * 
	 * @return	&LegroupAbstractFilterForm
	**/
	protected function _getFilterForm()
	{
	}

	/**
	 * _getBaseUrl
	 * 
	 * @param	void
	 * 
	 * @return	string
	**/
	protected function _getBaseUrl()
	{
	}

	/**
	 * &_getPageNavi
	 * 
	 * @param	void
	 * 
	 * @return	&XCube_PageNavigator
	**/
	protected function _getPageNavi()
	{
		$navi = new XCube_PageNavigator($this->_getBaseUrl(), XCUBE_PAGENAVI_START);
		return $navi;
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
		$this->mFilter = $this->_getFilterForm();
		$this->mFilter->fetch();
	
		$handler = $this->_getHandler();
		$this->mObjects = $handler->getObjects($this->mFilter->getCriteria());
	
		return LEGROUP_FRAME_VIEW_INDEX;
	}

	/**
	 * execute
	 * 
	 * @param	void
	 * 
	 * @return	Enum
	**/
	public function execute()
	{
		return $this->getDefaultView();
	}
}
