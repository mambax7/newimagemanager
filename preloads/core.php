<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * New Image Manager core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html& ...  public license
 * @author          luciorota <lucio.rota@gmail.com>
 */
class NewimagemanagerCorePreload extends XoopsPreloadItem
{
    // imagemanager.php
    function eventCoreImagemanagerPopup($args)
    {
        header("location: " . XOOPS_URL . "/modules/newimagemanager/imagemanager.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
        exit();
    }

    // modules/system/admin/images/main.php
    function eventCoreAdminImagesMainStart($args)
    {
        header("location: " . XOOPS_URL . "/modules/newimagemanager/admin/admin.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
        exit();
    }

    // image.php
    function eventCoreImageRender($args)
    {
        header("location: " . XOOPS_URL . "/modules/newimagemanager/image.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
        exit();
    }
}
?>