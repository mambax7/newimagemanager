<?php
// _LANGCODE: en
// _CHARSET : UTF-8

// Module Info

// The name of this module
define('_NIM_MI_NAME','NewImageManager');

// A brief description of this module
define('_NIM_MI_DESC','For administration of images of the site.');

// Names of blocks for this module (Not all module has blocks)
//define("_NIM_MI_BNAME1","Recent Downloads");
//define("_NIM_MI_BNAMEDSC1","Display Recent Downloads");
//define("_NIM_MI_BNAME2","Top Download");
//define("_NIM_MI_BNAMEDSC2","Display Top Downloads");
//define("_NIM_MI_BNAME3","Top Rated Download");
//define("_NIM_MI_BNAMEDSC3","Display Top Rated Downloads");
//define("_NIM_MI_BNAME4","Random Downloads");
//define("_NIM_MI_BNAMEDSC4","Display downloaded files randomly");

// Sub menu
define("_NIM_MI_SMNAME1","Suggest");
define("_NIM_MI_SMNAME2","Files List");

// Names of admin menu items
define('_NIM_MI_ADMENU_INDEX','Index');
define("_NIM_MI_ADMENU_IMAGES","Images/Categories");
    define("_NIM_MI_ADMENU3","Downloads Management");
    define("_NIM_MI_ADMENU4","Broken Downloads");
    define("_NIM_MI_ADMENU5","Modiefied Downloads");
define("_NIM_MI_ADMENU_EXTENTION","Extention");
define("_NIM_MI_ADMENU_ABOUT","About/Help");
define("_NIM_MI_ADMENU_PERMISSIONS", "Permissions");
define("_NIM_MI_ADMENU_UPDATE", "Update");
define("_NIM_MI_ADMENU_IMPORT", "Import");
define("_MD_AM_PREF", "Preferences");

// Config
define('_NIM_MI_AS_STANDARD_XOOPS_IMAGEMANAGER','[xoops integration] Set NewImageManager as standard Xoops image manager');
define('_NIM_MI_AS_STANDARD_XOOPS_IMAGEMANAGERDESC','DESCRIPTION // IN PROGRESS');

define('_NIM_MI_POPUP_IMAGEMANAGER','[filemanager] Popup Image Manager');
define('_NIM_MI_POPUP_IMAGEMANAGERDESC','DESCRIPTION // IN PROGRESS');
define('_NIM_MI_POPUP_IMAGEMANAGER_STANDARD','standard');
define('_NIM_MI_POPUP_IMAGEMANAGER_ENHANCED','enhanced');

define('_NIM_MI_UPLOAD_BASE_PATH','[categories] Uploads base path');
define('_NIM_MI_UPLOAD_BASE_PATHDESC','DESCRIPTION // IN PROGRESS');

define('_NIM_MI_UPLOAD_MAXSIZE','[files] Max file size');
define('_NIM_MI_UPLOAD_MAXSIZEDESC','Bytes<br />(1kB = 1024 Bytes, 1MB = 1048576 Bytes)');
define('_NIM_MI_UPLOAD_MAXWIDTH','[images] Max image width');
define('_NIM_MI_UPLOAD_MAXWIDTHDESC','Pixel');
define('_NIM_MI_UPLOAD_MAXHEIGHT','[images] Max image height');
define('_NIM_MI_UPLOAD_MAXHEIGHTDESC','Pixel');
?>
