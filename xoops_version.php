<?php
if (!defined('XOOPS_ROOT_PATH')) {
	die('XOOPS root path not defined');
}

$modversion['name'] = _NIM_MI_NAME;
$modversion['version'] = 0.0088;
$modversion['description'] = _NIM_MI_DESC;
$modversion['author'] = "luciorota";
$modversion['credits'] = "The XOOPS Project and...";
$modversion['help'] = "help.html";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/imagemanager_slogo.png";
$modversion['dirname'] = "newimagemanager";
// Extra informations
$modversion["release"] = "03-03-2010";
$modversion["module_status"] = "In progress";
$modversion['support_site_url']	= "http://luciorota.altervista.org/xoops/";
$modversion['support_site_name'] = "http://luciorota.altervista.org/xoops/";

// Scripts to run upon installation or update
$modversion['onInstall'] = 'include/install_function.php';
$modversion['onUninstall'] = 'include/uninstall_function.php'; // IN PROGRESS
$modversion['onUpdate'] = 'include/update_function.php';

// Mysql file
$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][1] = "newimage";
$modversion['tables'][2] = "newimagebody";
$modversion['tables'][3] = "newimagecategory";
$modversion['tables'][4] = "newimgoptions";

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Templates
$modversion['templates'][] = array( 'file' => 'newimagemanager_imagemanager.standard.list.html', 'description' => '' );
$modversion['templates'][] = array( 'file' => 'newimagemanager_imagemanager.standard.upload.html', 'description' => '' );

// Admin Templates
$modversion['templates'][] = array( 'file' => 'newimagemanager_header.html', 'description' => '', 'type' => 'admin' );
$modversion['templates'][] = array( 'file' => 'newimagemanager_images.html', 'description' => '', 'type' => 'admin' );
$modversion['templates'][] = array( 'file' => 'newimagemanager_index.html', 'description' => '', 'type' => 'admin' );

// Blocks

// Menu
$modversion['hasMain'] = 0;
/* IN PROGRESS
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _NIM_MI_SMNAME1;
$modversion['sub'][1]['url'] = "submit.php";
$modversion['sub'][2]['name'] = _NIM_MI_SMNAME2;
$modversion['sub'][2]['url'] = "search.php";
*/

// Config
$i=1;
$modversion['config'][$i]['name'] = 'as_standard_xoops_imagemanager';
$modversion['config'][$i]['title'] = '_NIM_MI_AS_STANDARD_XOOPS_IMAGEMANAGER';
$modversion['config'][$i]['description'] = '_NIM_MI_AS_STANDARD_XOOPS_IMAGEMANAGERDESC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'yesno';
$modversion['config'][$i]['default'] = 1;
$i++;
// name of config option for accessing its specified value. i.e. $xoopsModuleConfig['storyhome']
$modversion['config'][$i]['name'] = 'popup_imagemanager';
// title of this config option displayed in config settings form
$modversion['config'][$i]['title'] = '_NIM_MI_POPUP_IMAGEMANAGER';
// description of this config option displayed under title
$modversion['config'][$i]['description'] = '_NIM_MI_POPUP_IMAGEMANAGERDESC';
// form element type used in config form for this option. can be one of either textbox, textarea, select, select_multi, yesno, group, group_multi
$modversion['config'][$i]['formtype'] = 'select';
// value type of this config option. can be one of either int, text, float, array, or other
// form type of 'group_multi', 'select_multi' must always be 'array'
// form type of 'yesno', 'group' must be always be 'int'
$modversion['config'][$i]['valuetype'] = 'text';
// the default value for this option
// ignore it if no default
// 'yesno' formtype must be either 0(no) or 1(yes)
$modversion['config'][$i]['default'] = 'standard';
// options to be displayed in selection preferences box
// required and valid for 'select' or 'select_multi' formtype option only
// language constants can be used for both array keys and values
$modversion['config'][$i]['options'] = array('standard' => _NIM_MI_POPUP_IMAGEMANAGER_STANDARD, 'enhanced' => _NIM_MI_POPUP_IMAGEMANAGER_ENHANCED);
$i++;
// name of config option for accessing its specified value. i.e. $xoopsModuleConfig['storyhome']
$modversion['config'][$i]['name'] = 'upload_base_path';
$modversion['config'][$i]['title'] = '_NIM_MI_UPLOAD_BASE_PATH';
$modversion['config'][$i]['description'] = '_NIM_MI_UPLOAD_BASE_PATHDESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = XOOPS_ROOT_PATH . '/uploads/newimagemanager/uploaded';



$i++;
$modversion['config'][$i]['name'] = 'upload_maxsize';
$modversion['config'][$i]['title'] = '_NIM_MI_UPLOAD_MAXSIZE';
$modversion['config'][$i]['description'] = '_NIM_MI_UPLOAD_MAXSIZEDESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '2000000'; // bytes
$i++;
$modversion['config'][$i]['name'] = 'upload_maxwidth';
$modversion['config'][$i]['title'] = '_NIM_MI_UPLOAD_MAXWIDTH';
$modversion['config'][$i]['description'] = '_NIM_MI_UPLOAD_MAXWIDTHDESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '2000'; //pixel
$i++;
$modversion['config'][$i]['name'] = 'upload_maxheight';
$modversion['config'][$i]['title'] = '_NIM_MI_UPLOAD_MAXHEIGHT';
$modversion['config'][$i]['description'] = '_NIM_MI_UPLOAD_MAXHEIGHTDESC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = '2000'; //pixel

?>
