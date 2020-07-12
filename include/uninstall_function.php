<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
/**
 * Delete a not empty directory
 *
 */
function delDir($dir) {
    if (!file_exists($dir)) return true;
    if (!is_dir($dir)) return unlink($dir);
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') continue;
        if (!delDir($dir . DS . $item)) return false;
    }
    return rmdir($dir);
}
/**
 * Desactivate textsanitizer extention
 *
 */
function desactivateExtention() {
    $conf = include XOOPS_ROOT_PATH . DS . 'class' . DS . 'textsanitizer' . DS . 'config.php';
    $conf['extensions']['newimage'] = 0;
    file_put_contents(XOOPS_ROOT_PATH . DS . 'class' . DS . 'textsanitizer' . DS . 'config.php', "<?php\rreturn \$config = " . var_export($conf, true) . "\r?>");
}



function xoops_module_pre_uninstall_newimagemanager(&$xoopsModule) {
    return true;
}

function xoops_module_uninstall_newimagemanager(&$xoopsModule) {
	// Desactivate and delete newimagemanager textsanitizer extention
    desactivateExtention();
    delDir(XOOPS_ROOT_PATH . DS . 'class' . DS . 'textsanitizer' . DS . 'newimage');
	// Delete newimagemanager main upload directory
    delDir(XOOPS_UPLOAD_PATH . DS . 'newimagemanager');
	return true;
}
?>
