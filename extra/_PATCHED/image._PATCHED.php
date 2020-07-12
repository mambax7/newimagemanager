<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 *  Xoops Image
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         core
 * @since           2.0.0
 * @author          Kazumi Ono <onokazu@xoops.org>
 * @version         $Id: image.php 4025 2009-12-12 09:48:12Z trabis $
 */
//error_reporting(0);
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    set_magic_quotes_runtime(0);
}

if (function_exists('mb_http_output')) {
    mb_http_output('pass');
}
defined('DS') or define('DS', DIRECTORY_SEPARATOR);

$xoopsOption['nocommon'] = 1;

include dirname(__FILE__) . DS . 'mainfile.php';
include XOOPS_ROOT_PATH . DS . 'include' . DS . 'functions.php';
xoops_load('load');
xoops_load('preload');
$xoopsPreload =& XoopsPreload::getInstance();
$xoopsPreload->triggerEvent('core.image.render');

xoops_load('xoopslogger');
include_once XOOPS_ROOT_PATH . DS . 'class' . DS . 'module.textsanitizer.php';
include_once XOOPS_ROOT_PATH . DS . 'class' . DS . 'database' . DS . 'databasefactory.php';
require_once XOOPS_ROOT_PATH . DS . 'kernel' . DS . 'object.php';
require_once XOOPS_ROOT_PATH . DS . 'class' . DS . 'criteria.php';

$image_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (empty($image_id)) {
    header('Content-type: image/gif');
    readfile(XOOPS_ROOT_PATH . '/uploads/blank.gif');
    exit();
}

$xoopsLogger =& XoopsLogger::getInstance();
$xoopsLogger->startTime();

define('XOOPS_DB_PROXY', 1);
$xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();

// ################# Include class manager file ##############
$imagehandler =& xoops_gethandler('image');
$criteria = new CriteriaCompo(new Criteria('i.image_display', 1));
$criteria->add(new Criteria('i.image_id', $image_id));
$image = $imagehandler->getObjects($criteria, false, true);
if (count($image) > 0) {
    header('Content-type: ' . $image[0]->getVar('image_mimetype'));
    header('Cache-control: max-age=31536000');
    header('Expires: ' . gmdate("D, d M Y H:i:s", time() + 31536000) . 'GMT');
    header('Content-disposition: filename=' . $image[0]->getVar('image_name'));
    header('Content-Length: ' . strlen($image[0]->getVar('image_body')));
    header('Last-Modified: ' . gmdate("D, d M Y H:i:s", $image[0]->getVar('image_created')) . 'GMT');
    echo $image[0]->getVar('image_body');
} else {
    header('Content-type: image/gif');
    readfile(XOOPS_ROOT_PATH . '/uploads/blank.gif');
}
?>