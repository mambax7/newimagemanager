<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('NWLINE')or define('NWLINE', "\n");

include_once '../../../include/cp_header.php';

defined('MODULE_REL_UPLOAD_PATH')or define('NIM_MODULE_REL_UPLOAD_PATH', 'uploads/newimagemanager');
defined('MODULE_UPLOAD_PATH')or define('NIM_MODULE_UPLOAD_PATH', XOOPS_ROOT_PATH . '/' . NIM_MODULE_REL_UPLOAD_PATH);

defined('MODULE_REL_UPLOAD_URL') or define('NIM_MODULE_REL_UPLOAD_URL', 'uploads/newimagemanager');
defined('MODULE_UPLOAD_URL') or define('NIM_MODULE_UPLOAD_URL', XOOPS_URL . '/' . NIM_MODULE_REL_UPLOAD_URL);

/**
 * *#@+
 * Newimagecategory imgcat_storetype
 */
defined('NIM_STORETYPE_AS_FILE') or define('NIM_STORETYPE_AS_FILE', 'file');
defined('NIM_STORETYPE_DATABASE') or define('NIM_STORETYPE_DATABASE', 'db');

/**
 * *#@+
 * Newimagecategory imgcat_cattype
 */
define('NIM_CATTYPE_STANDARD', 1);
define('NIM_CATTYPE_MODULE_ID', 2);
define('NIM_CATTYPE_USER_ID', 3);

// Include module functions
include_once('../include/functions.php');
?>
