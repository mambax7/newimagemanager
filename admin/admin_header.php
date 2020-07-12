<?php
include_once('../include/common.php');



// Include xoops classes
xoops_load('xoopsmodule');
xoops_load('xoopsformloader');
xoops_load('tree');
xoops_load('xoopslists');
xoops_load('pagenav');
xoops_load('xoopsmediauploader');
xoops_load('xoopscache');
include_once(XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php');



$xoopsModule =& XoopsModule::getByDirname('newimagemanager');
//  Check module is active
if ( !xoops_isActiveModule('newimagemanager') ) redirect_header( XOOPS_URL, 2, _NIM_AM_ERRORMODNOTACTIVE );
// Check users rights
$isadmin = false;
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    redirect_header( XOOPS_URL . DS, 3, _NOPERM );
    exit();
}
else {
    $isadmin = true;
}


// Include language file
xoops_loadLanguage('admin', 'system');
xoops_loadLanguage('admin', 'newimagemanager');
xoops_loadLanguage('modinfo', 'newimagemanager');
$myts =& MyTextSanitizer::getInstance();




// Get Action type
$op = 'list'; // default action
$op = system_CleanVars ( $_REQUEST, 'op', 'list', 'string' );
if (isset($_POST)) {foreach ( $_POST as $k => $v ) {${$k} = $v;}}
if (isset($_GET['op'])) {$op = trim($_GET['op']);}
if (isset($_GET['image_id'])) {$image_id = intval($_GET['image_id']);}
if (isset($_GET['imgcat_id'])) {$imgcat_id = intval($_GET['imgcat_id']);}


?>
