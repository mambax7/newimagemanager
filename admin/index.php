<?php
include 'admin_header.php';
//Affichage de la partie haute de l'administration de Xoops
xoops_cp_header();


//appel du menu admin
if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
    newimagemanager_adminmenu(1, _NIM_MI_ADMENU_INDEX);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (1, _NIM_MI_ADMENU_INDEX);
}

if (phpversion() >= 5) {
    include_once XOOPS_ROOT_PATH.'/modules/newimagemanager/class/menu.php';
    $Adminmenu = $GLOBALS["xoopsModule"]->getAdminMenu();
    $menu = new newimagemanagerMenu();

    foreach ($Adminmenu as $key => $item) {
        $menu->addItem($item['title'], '../' . $item['link'], '../' . $item['icon'], $item['title']);
    }
    $menu->addItem('Preference', '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule ->getVar('mid') . '&amp;&confcat_id=1', '../images/icon/pref.png', _PREFERENCES);
    echo $menu->getCSS();

    echo '<table width="100%" border="0" cellspacing="10" cellpadding="4">';
    echo '<tr><td>' . $menu->render() . '</td>';
} else {
    echo '<table width="100%" border="0" cellspacing="10" cellpadding="4">';
    echo '<tr><td><div class="errorMsg" style="text-align: left;">' . _NIM_AM_INDEX_ERRORPHP . '</div></td>';
}
echo '<td valign="top" width="60%">';
/*
echo '<fieldset><legend class="CPmediumTitle">' . _NIM_AM_INDEX1 . '</legend><br/>';
printf(_AM_TDMDOWNLOADS_INDEX_DOWNLOADS,$nb_downloads);
echo '<br /><br />';
printf(_AM_TDMDOWNLOADS_INDEX_DOWNLOADSWAITING,$nb_downloads_waiting);
echo '<br/></fieldset><br /><br />';

echo '<fieldset><legend class="CPmediumTitle">' . _NIM_AM_INDEX2 . '</legend><br/>';
printf(_AM_TDMDOWNLOADS_INDEX_BROKEN,$nb_broken);
echo '<br/></fieldset><br /><br />';

echo '<fieldset><legend class="CPmediumTitle">' . _NIM_AM_INDEX3 . '</legend><br/>';
printf(_AM_TDMDOWNLOADS_INDEX_MODIFIED,$nb_modified);
*/
echo '<br/></fieldset><br /><br />';


echo '</td></tr>';
echo '</table>';

// message d'erreur si la copie du dossier dans uploads n'a pss marché à l'installation
$url_folder = XOOPS_ROOT_PATH . '/uploads/newimagemanager/';
if (!is_dir($url_folder)){
    echo '<div class="errorMsg" style="text-align: left;">' . sprintf(_NIM_AM_INDEX_ERRORPFOLDER, XOOPS_ROOT_PATH) . '</div>';
}

xoops_cp_footer();
?>
