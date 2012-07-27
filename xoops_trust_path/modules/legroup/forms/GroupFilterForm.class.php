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

require_once LEGROUP_TRUST_PATH . '/class/AbstractFilterForm.class.php';

define('LEGROUP_GROUP_SORT_KEY_GROUP_ID', 1);
define('LEGROUP_GROUP_SORT_KEY_TITLE', 2);
define('LEGROUP_GROUP_SORT_KEY_PUBLICITY', 3);
define('LEGROUP_GROUP_SORT_KEY_APPROVAL', 4);
define('LEGROUP_GROUP_SORT_KEY_DESCRIPTION', 5);
define('LEGROUP_GROUP_SORT_KEY_POSTTIME', 6);

define('LEGROUP_GROUP_SORT_KEY_DEFAULT', LEGROUP_GROUP_SORT_KEY_GROUP_ID);

/**
 * Legroup_GroupFilterForm
**/
class Legroup_GroupFilterForm extends Legroup_AbstractFilterForm
{
	public /*** string[] ***/ $mSortKeys = array(
 	   LEGROUP_GROUP_SORT_KEY_GROUP_ID => 'group_id',
 	   LEGROUP_GROUP_SORT_KEY_TITLE => 'title',
 	   LEGROUP_GROUP_SORT_KEY_PUBLICITY => 'publicity',
 	   LEGROUP_GROUP_SORT_KEY_APPROVAL => 'approval',
 	   LEGROUP_GROUP_SORT_KEY_DESCRIPTION => 'description',
 	   LEGROUP_GROUP_SORT_KEY_POSTTIME => 'posttime',

	);

	/**
	 * getDefaultSortKey
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function getDefaultSortKey()
	{
		return LEGROUP_GROUP_SORT_KEY_DEFAULT;
	}

	/**
	 * fetch
	 * 
	 * @param	void
	 * 
	 * @return	void
	**/
	public function fetch()
	{
		parent::fetch();
	
		$root =& XCube_Root::getSingleton();
	
		if (($value = $root->mContext->mRequest->getRequest('group_id')) !== null) {
			$this->mNavi->addExtra('group_id', $value);
			$this->_mCriteria->add(new Criteria('group_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('title')) !== null) {
			$this->mNavi->addExtra('title', $value);
			$this->_mCriteria->add(new Criteria('title', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('publicity')) !== null) {
			$this->mNavi->addExtra('publicity', $value);
			$this->_mCriteria->add(new Criteria('publicity', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('approval')) !== null) {
			$this->mNavi->addExtra('approval', $value);
			$this->_mCriteria->add(new Criteria('approval', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('description')) !== null) {
			$this->mNavi->addExtra('description', $value);
			$this->_mCriteria->add(new Criteria('description', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

	
		$this->_mCriteria->addSort($this->getSort(), $this->getOrder());
	}
}

?>
