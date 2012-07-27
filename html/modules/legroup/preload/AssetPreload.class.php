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

require_once XOOPS_TRUST_PATH . '/modules/legroup/preload/AssetPreload.class.php';
Legroup_AssetPreloadBase::prepare(basename(dirname(dirname(__FILE__))));

?>
