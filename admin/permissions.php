<?php
include 'admin_header.php';

xoops_cp_header();

if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
    newimagemanager_adminmenu(8, _NIM_MI_ADMENU_PERMISSIONS);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (8, _NIM_MI_ADMENU_PERMISSIONS);
}

// get current filename
$current_file = basename(__FILE__);

$permission = isset($_POST['permission']) ? intval($_POST['permission']) : 1;
$selected = array('','','','');
$selected[$permission - 1]= ' selected';

echo "<br /><br />\n";
echo "<form method='post' name='fselperm' action='" . $current_file . "'>";
echo "<table border='0'>";
echo "<tr><td>";
echo "<select name='permission' onChange='javascript: document.fselperm.submit()'>";
echo "<option value='1'" . $selected[0] . ">" . _NIM_AM_PERM_VIEW . "</option>";
echo "<option value='2'" . $selected[1] . ">" . _NIM_AM_PERM_SUBMIT . "</option>";
echo "<option value='3'" . $selected[2] . ">" . _NIM_AM_PERM_EDIT . "</option>";
echo "<option value='4'" . $selected[3] . ">" . _NIM_AM_PERM_OTHERS . "</option>";
echo "</select>";
echo "</td></tr>";
echo "<tr><td>";
echo "<input type='submit' name='go'>";
echo "</td></tr>";
echo "</table>";
echo "</form>";

$moduleId = $xoopsModule->getVar('mid');

switch($permission) {
    case 1:	// View permission
        $formTitle = _NIM_AM_PERM_VIEW;
        $permissionName = 'newimagemanager_cat_read';
        $permissionDescription = _NIM_AM_PERM_VIEW_DSC;
        break;
    case 2:	// Submit Permission
        $formTitle = _NIM_AM_PERM_SUBMIT;
        $permissionName = 'newimagemanager_cat_write';
        $permissionDescription = _NIM_AM_PERM_SUBMIT_DSC;
        break;
    case 3:	// Submit Permission
        $formTitle = _NIM_AM_PERM_EDIT;
        $permissionName = 'newimagemanager_cat_edit';
        $permissionDescription = _NIM_AM_PERM_EDIT_DSC;
        break;
    case 4:
        $formTitle = _NIM_AM_PERM_OTHERS;
        $permissionName = "newimagemanager_ac";
        $permissionDescription = _NIM_AM_PERM_OTHERS_DSC;
        $global_perms_array = array(
        '4' => _NIM_AM_PERMISSIONS_4 ,
        '8' => _NIM_AM_PERMISSIONS_8 ,
        '16' => _NIM_AM_PERMISSIONS_16 ,
        '32' => _NIM_AM_PERMISSIONS_32
         );
        break;
}

$permissionsForm = new XoopsGroupPermForm($formTitle, $moduleId, $permissionName, $permissionDescription, 'admin/' . $current_file);
if ($permission == 4) {
    foreach( $global_perms_array as $perm_id => $permissionName ) {
        $permissionsForm->addItem($perm_id , $permissionName) ;
    }
} else {
    $sql = 'SELECT imgcat_id, imgcat_name FROM ' . $xoopsDB->prefix('newimagecategory') . ' ORDER BY imgcat_name';
    $result = $xoopsDB->query($sql);
    if($result) {
        while ($row = $xoopsDB->fetchArray($result)) {
            $permissionsForm->addItem($row['imgcat_id'], $row['imgcat_name']);
        }
    }
}
echo $permissionsForm->render();
echo "<br /><br />\n";
unset ($permissionsForm);

xoops_cp_footer();
?>
