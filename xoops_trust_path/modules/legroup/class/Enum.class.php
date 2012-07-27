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
 * Legroup_Permission
**/
interface Legroup_Permission
{
	const READ = 2;
	const WRITE = 5;
	const MANAGE = 9;
}

/**
 * Legroup_Publicity
**/
interface Legroup_Publicity
{
	const CLOSED = 0;
	const OPEN = 9;
}

/**
 * Legroup_Approval
 * When new member requests to join group, what is happen ?
**/
interface Legroup_Approval
{
	const REQUIRED = 0;	//require staff's confirmation
	const ASSOCIATE = 2;	//automatically registered as associate member
	const REGULAR = 5;	//automatically registered as regular member
}

?>
