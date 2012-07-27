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

define('LEGROUP_POLICY_SORT_KEY_POLICY_ID', 1);
define('LEGROUP_POLICY_SORT_KEY_GROUP_ID', 2);
define('LEGROUP_POLICY_SORT_KEY_DIRNAME', 3);
define('LEGROUP_POLICY_SORT_KEY_DATANAME', 4);
define('LEGROUP_POLICY_SORT_KEY_ACTION', 5);
define('LEGROUP_POLICY_SORT_KEY_RANK', 6);

define('LEGROUP_POLICY_SORT_KEY_DEFAULT', LEGROUP_POLICY_SORT_KEY_POLICY_ID);

/**
 * Legroup_PolicyFilterForm
**/
class Legroup_PolicyFilterForm extends Legroup_AbstractFilterForm
{
    public /*** string[] ***/ $mSortKeys = array(
 	   LEGROUP_POLICY_SORT_KEY_POLICY_ID => 'policy_id',
 	   LEGROUP_POLICY_SORT_KEY_GROUP_ID => 'group_id',
 	   LEGROUP_POLICY_SORT_KEY_DIRNAME => 'dirname',
 	   LEGROUP_POLICY_SORT_KEY_DATANAME => 'dataname',
 	   LEGROUP_POLICY_SORT_KEY_ACTION => 'action',
 	   LEGROUP_POLICY_SORT_KEY_RANK => 'rank',

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
        return LEGROUP_POLICY_SORT_KEY_DEFAULT;
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
    
		if (($value = $root->mContext->mRequest->getRequest('policy_id')) !== null) {
			$this->mNavi->addExtra('policy_id', $value);
			$this->_mCriteria->add(new Criteria('policy_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('group_id')) !== null) {
			$this->mNavi->addExtra('group_id', $value);
			$this->_mCriteria->add(new Criteria('group_id', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('dirname')) !== null) {
			$this->mNavi->addExtra('dirname', $value);
			$this->_mCriteria->add(new Criteria('dirname', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('dataname')) !== null) {
			$this->mNavi->addExtra('dataname', $value);
			$this->_mCriteria->add(new Criteria('dataname', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('action')) !== null) {
			$this->mNavi->addExtra('action', $value);
			$this->_mCriteria->add(new Criteria('action', $value));
		}
		if (($value = $root->mContext->mRequest->getRequest('rank')) !== null) {
			$this->mNavi->addExtra('rank', $value);
			$this->_mCriteria->add(new Criteria('rank', $value));
		}
    
        $this->_mCriteria->addSort($this->getSort(), $this->getOrder());
    }
}

?>
