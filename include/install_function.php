<?php
/**
 * Create a new directory that contains the file 'index.html'
 *
 */
function makeDir($dir) {
    if (!is_dir($dir)){
        if (!mkdir($dir)){
            return false;
        } else {
            if ($fh = @fopen($dir.'/index.html', 'w'))
                fwrite($fh, '<script>history.go(-1);</script>');
            @fclose($fh);
            return true;
        }
    }
}

function xoops_module_pre_install_newimagemanager(&$xoopsModule) {
    // Check if this XOOPS version is supported
    $minSupportedVersion = explode('.', '2.4.2');
    $currentVersion = explode('.', substr(XOOPS_VERSION,6));
    if($currentVersion[0] > $minSupportedVersion[0]) {
        return true;
    } elseif($currentVersion[0] == $minSupportedVersion[0]) {
        if($currentVersion[1] > $minSupportedVersion[1]) {
            return true;
        } elseif($currentVersion[1] == $minSupportedVersion[1]) {
            if($currentVersion[2] > $minSupportedVersion[2]) {
                return true;
            } elseif ($currentVersion[2] == $minSupportedVersion[2]) {
                return true;
            }
        }
    }
    return false;
}

function xoops_module_install_newimagemanager(&$xoopsModule) {
// IN PROGRESS what can I do if directory exists?
    // Create newimagemanager main upload directory
    makeDir(XOOPS_UPLOAD_PATH . "/newimagemanager");
    makeDir(XOOPS_UPLOAD_PATH . "/newimagemanager/uploaded");

    // Create newimagemanager image editor directory
    makeDir(XOOPS_UPLOAD_PATH . "/newimagemanager/imageeditor");
    makeDir(XOOPS_UPLOAD_PATH . "/newimagemanager/imageeditor/original");
    makeDir(XOOPS_UPLOAD_PATH . "/newimagemanager/imageeditor/active");
    makeDir(XOOPS_UPLOAD_PATH . "/newimagemanager/imageeditor/edit");
    makeDir(XOOPS_UPLOAD_PATH . "/newimagemanager/imageeditor/undo");

    // Create newimagemanager image cache directory for Smart Image Resizer tool by Joe Lencioni
    makeDir(XOOPS_UPLOAD_PATH . "/newimagemanager/imagecache");


    // Set permissions
    $module_id = $xoopsModule->getVar('mid');
    $gpermHandler =& xoops_gethandler('groupperm');
    $configHandler =& xoops_gethandler('config');
    
    /**
     * Default public category permission mask
     */
    
/*
    // Access right
    $gpermHandler->addRight('extgallery_public_mask', 1, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('extgallery_public_mask', 1, XOOPS_GROUP_USERS, $module_id);
    $gpermHandler->addRight('extgallery_public_mask', 1, XOOPS_GROUP_ANONYMOUS, $module_id);
    
    // Public rate
    $gpermHandler->addRight('extgallery_public_mask', 2, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('extgallery_public_mask', 2, XOOPS_GROUP_USERS, $module_id);
    
    // Public eCard
    $gpermHandler->addRight('extgallery_public_mask', 4, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('extgallery_public_mask', 4, XOOPS_GROUP_USERS, $module_id);
    
    // Public download
    $gpermHandler->addRight('extgallery_public_mask', 8, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('extgallery_public_mask', 8, XOOPS_GROUP_USERS, $module_id);
    
    // Public upload
    $gpermHandler->addRight('extgallery_public_mask', 16, XOOPS_GROUP_ADMIN, $module_id);
    
    // Public autoapprove
    $gpermHandler->addRight('extgallery_public_mask', 32, XOOPS_GROUP_ADMIN, $module_id);
    
    // Public display
    $gpermHandler->addRight('extgallery_public_mask', 128, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('extgallery_public_mask', 128, XOOPS_GROUP_USERS, $module_id);
    $gpermHandler->addRight('extgallery_public_mask', 128, XOOPS_GROUP_ANONYMOUS, $module_id);
*/
    /**
     * Default User's category permission
     */
    
    // Private gallery
    
    // Private rate
/*
    $gpermHandler->addRight('extgallery_private', 2, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('extgallery_private', 2, XOOPS_GROUP_USERS, $module_id);
    
    // Private eCard
    $gpermHandler->addRight('extgallery_private', 4, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('extgallery_private', 4, XOOPS_GROUP_USERS, $module_id);
    
    // Private download
    $gpermHandler->addRight('extgallery_private', 8, XOOPS_GROUP_ADMIN, $module_id);
    $gpermHandler->addRight('extgallery_private', 8, XOOPS_GROUP_USERS, $module_id);
    
    // Private autoapprove
    $gpermHandler->addRight('extgallery_private', 16, XOOPS_GROUP_ADMIN, $module_id);
*/
    return true;
}
?>
