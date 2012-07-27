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
 * Legroup_MemberEditForm
**/
class Legroup_MemberApproveForm extends Legroup_MemberEditForm
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
		$obj->set('rank', $this->get('rank'));
		$obj->set('status', $this->get('status'));
	}
}

?>
