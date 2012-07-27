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

require_once LEGROUP_TRUST_PATH . '/forms/MemberEditForm.class.php';

/**
 * Legroup_MemberAddForm
**/
class Legroup_MemberAddForm extends Legroup_MemberEditForm
{
	/**
	 * update
	 * 
	 * @param	XoopsSimpleObject  &$obj
	 * 
	 * @return	void
	**/
	public function update(/*** XoopsSimpleObject ***/ &$obj)
	{
		$obj->set('group_id', $this->get('group_id'));
		$obj->set('uid', $this->get('uid'));
		$obj->set('rank', $this->get('rank'));
	}
}

?>
