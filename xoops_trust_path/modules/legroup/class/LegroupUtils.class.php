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
 * Legroup_Utils
**/
class Legroup_Utils
{
    /**
     * &getXoopsHandler
     * 
     * @param   string  $name
     * @param   bool  $optional
     * 
     * @return  XoopsObjectHandler
    **/
    public static function getXoopsHandler(/*** string ***/ $name,/*** bool ***/ $optional = false)
    {
        // TODO will be emulated xoops_gethandler
        return xoops_gethandler($name,$optional);
    }

    /**
     * getClientList
     * 
     * @param   string  $dirname
     * 
     * @return  array
    **/
	public static function getClientList(/*** string ***/ $dirname)
	{
		$clients = array();
		$list = array();
		XCube_DelegateUtils::call('Legacy_GroupClient.GetClientList', new XCube_Ref($clients), $dirname);
	
		foreach($clients as $module){
			$list[] = array('dirname'=>trim($module['dirname']), 'dataname'=>trim($module['dataname']), 'fieldname'=>trim($module['fieldname']));
		}
		return $list;
	}
}

?>
