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

if(!defined('LEGROUP_TRUST_PATH'))
{
	define('LEGROUP_TRUST_PATH',XOOPS_TRUST_PATH . '/modules/legroup');
}

require_once LEGROUP_TRUST_PATH . '/class/LegroupUtils.class.php';

//
// Define a basic manifesto.
//
$modversion['name'] = _MI_LEGROUP_LANG_LEGROUP;
$modversion['version'] = 0.20;
$modversion['description'] = _MI_LEGROUP_DESC_LEGROUP;
$modversion['author'] = _MI_LEGROUP_LANG_AUTHOR;
$modversion['credits'] = _MI_LEGROUP_LANG_CREDITS;
$modversion['help'] = 'help.html';
$modversion['license'] = 'GPL';
$modversion['official'] = 0;
$modversion['image'] = 'images/module_icon.png';
$modversion['dirname'] = $myDirName;
$modversion['trust_dirname'] = 'legroup';
$modversion['role'] = 'group';

$modversion['cube_style'] = true;
$modversion['legacy_installer'] = array(
	'installer'   => array(
		'class' 	=> 'Installer',
		'namespace' => 'Legroup',
		'filepath'	=> LEGROUP_TRUST_PATH . '/admin/class/installer/LegroupInstaller.class.php'
	),
	'uninstaller' => array(
		'class' 	=> 'Uninstaller',
		'namespace' => 'Legroup',
		'filepath'	=> LEGROUP_TRUST_PATH . '/admin/class/installer/LegroupUninstaller.class.php'
	),
	'updater' => array(
		'class' 	=> 'Updater',
		'namespace' => 'Legroup',
		'filepath'	=> LEGROUP_TRUST_PATH . '/admin/class/installer/LegroupUpdater.class.php'
	)
);
$modversion['disable_legacy_2nd_installer'] = false;

$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';
$modversion['tables'] = array(
//	  '{prefix}_{dirname}_xxxx',
##[cubson:tables]
	'{prefix}_{dirname}_group',
	'{prefix}_{dirname}_member',
	'{prefix}_{dirname}_policy',

##[/cubson:tables]
);

//
// Templates. You must never change [cubson] chunk to get the help of cubson.
//
$modversion['templates'] = array(
/*
	array(
		'file'		  => '{dirname}_xxx.html',
		'description' => _MI_LEGROUP_TPL_XXX
	),
*/
##[cubson:templates]
		array('file' => '{dirname}_group_delete.html','description' => _MI_LEGROUP_TPL_GROUP_DELETE),
		array('file' => '{dirname}_group_edit.html','description' => _MI_LEGROUP_TPL_GROUP_EDIT),
		array('file' => '{dirname}_group_list.html','description' => _MI_LEGROUP_TPL_GROUP_LIST),
		array('file' => '{dirname}_group_view.html','description' => _MI_LEGROUP_TPL_GROUP_VIEW),
		array('file' => '{dirname}_member_delete.html','description' => _MI_LEGROUP_TPL_MEMBER_DELETE),
		array('file' => '{dirname}_member_edit.html','description' => _MI_LEGROUP_TPL_MEMBER_EDIT),
		array('file' => '{dirname}_member_add.html','description' => 'add member'),
		array('file' => '{dirname}_member_list.html','description' => _MI_LEGROUP_TPL_MEMBER_LIST),
		array('file' => '{dirname}_member_approve.html','description' => _MI_LEGROUP_TPL_MEMBER_APPROVE),
		array('file' => '{dirname}_policy_edit.html','description' => _MI_LEGROUP_TPL_POLICY_EDIT),
##[/cubson:templates]
);

//
// Admin panel setting
//
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php?action=Index';
$modversion['adminmenu'] = array(
/*
	array(
		'title'    => _MI_LEGROUP_LANG_XXXX,
		'link'	   => 'admin/index.php?action=xxx',
		'keywords' => _MI_LEGROUP_KEYWORD_XXX,
		'show'	   => true,
		'absolute' => false
	),
*/
##[cubson:adminmenu]
##[/cubson:adminmenu]
);

//
// Public side control setting
//
$modversion['hasMain'] = 1;
$modversion['hasSearch'] = 0;
$modversion['sub'] = array(
/*
	array(
		'name' => _MI_LEGROUP_LANG_SUB_XXX,
		'url'  => 'index.php?action=XXX'
	),
*/
##[cubson:submenu]
##[/cubson:submenu]
);

//
// Config setting
//
$modversion['config'] = array(
/*
	array(
		'name'			=> 'xxxx',
		'title' 		=> '_MI_LEGROUP_TITLE_XXXX',
		'description'	=> '_MI_LEGROUP_DESC_XXXX',
		'formtype'		=> 'xxxx',
		'valuetype' 	=> 'xxx',
		'options'		=> array(xxx => xxx,xxx => xxx),
		'default'		=> 0
	),
*/

	array(
		'name'			=> 'css_file' ,
		'title' 		=> "_MI_LEGROUP_LANG_CSS_FILE" ,
		'description'	=> "_MI_LEGROUP_DESC_CSS_FILE" ,
		'formtype'		=> 'textbox' ,
		'valuetype' 	=> 'text' ,
		'default'		=> '/modules/'.$myDirName.'/style.css',
		'options'		=> array()
	) ,

##[cubson:config]
##[/cubson:config]
);

//
// Block setting
//
$modversion['blocks'] = array(
    1 => array(
        'func_num'			=> 1,
        'file'				=> 'MygroupBlock.class.php',
        'class' 			=> 'MygroupBlock',
        'name'				=> _MI_LEGROUP_BLOCK_NAME_MYGROUP,
        'description'		=> '',
        'options'			=> '0',
        'template'			=> '{dirname}_block_mygroup.html',
        'show_all_module'	=> true,
        'visible_any'		=> true
    ),
);

