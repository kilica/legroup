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

define('LEGROUP_MEMBER_SORT_KEY_MEMBER_ID', 1);
define('LEGROUP_MEMBER_SORT_KEY_UID', 2);
define('LEGROUP_MEMBER_SORT_KEY_GROUP_ID', 3);
define('LEGROUP_MEMBER_SORT_KEY_STATUS', 4);
define('LEGROUP_MEMBER_SORT_KEY_RANK', 5);
define('LEGROUP_MEMBER_SORT_KEY_POSTTIME', 6);

define('LEGROUP_MEMBER_SORT_KEY_DEFAULT', LEGROUP_MEMBER_SORT_KEY_MEMBER_ID);

/**
 * Legroup_MemberFilterForm
**/
class Legroup_MemberFilterForm extends Legroup_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   LEGROUP_MEMBER_SORT_KEY_MEMBER_ID => 'member_id',
 	   LEGROUP_MEMBER_SORT_KEY_UID => 'uid',
 	   LEGROUP_MEMBER_SORT_KEY_GROUP_ID => 'group_id',
 	   LEGROUP_MEMBER_SORT_KEY_STATUS => 'status',
 	   LEGROUP_MEMBER_SORT_KEY_RANK => 'rank',
 	   LEGROUP_MEMBER_SORT_KEY_POSTTIME => 'posttime',

    );

    /**
     * getDefaultSortKey
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function getDefaultSortKey()
    {
        return LEGROUP_MEMBER_SORT_KEY_DEFAULT;
    }

    /**
     * fetch
     * 
     * @param   void
     * 
     * @return  void
    **/
    public function fetch()
    {
        parent::fetch();
    
        $root =& XCube_Root::getSingleton();
    
		if (($value = $root->mContext->mRequest->getRequest('member_id')) !== null) {
			$this->mNavi->addExtra('member_id', $value);
			$this->_mCriteria->add(new Criteria('member_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('uid')) !== null) {
			$this->mNavi->addExtra('uid', $value);
			$this->_mCriteria->add(new Criteria('uid', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('group_id')) !== null) {
			$this->mNavi->addExtra('group_id', $value);
			$this->_mCriteria->add(new Criteria('group_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('status')) !== null) {
			$this->mNavi->addExtra('status', $value);
			$this->_mCriteria->add(new Criteria('status', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('rank')) !== null) {
			$this->mNavi->addExtra('rank', $value);
			$this->_mCriteria->add(new Criteria('rank', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('posttime')) !== null) {
			$this->mNavi->addExtra('posttime', $value);
			$this->_mCriteria->add(new Criteria('posttime', $value));
		}

    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
