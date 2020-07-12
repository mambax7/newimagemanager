<?php
include 'admin_header.php';
xoops_cp_header();

//appel du menu admin
if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
    newimagemanager_adminmenu(10, _NIM_MI_ADMENU10);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (10, _NIM_MI_ADMENU10);
}

$myts =& MyTextSanitizer::getInstance(); 

//Action dans switch
if (isset($_REQUEST['op'])) {
	$op = $_REQUEST['op'];
} else {
	$op = 'index';
}

switch ($op) 
{
	case "index":
    default:
        echo "<br><br>";
		echo "<div class='errorMsg'>";
		echo _NIM_AM_IMPORT_WARNING;
		echo "</div>";
        echo "<br /><br />";
        // Sous-menu
        echo '<div class="head" align="left">';
        echo '<a href="import.php?op=images.import.xoops">' . _NIM_AM_IMPORT_XOOPS . '</a>';
        echo '<br />';
        echo '<a href="import.php?op=images.import.extgallery">' . _NIM_AM_IMPORT_EXTGALLERY . '</a>';
        echo '<br />';
        echo '<a href="import.php?op=images.import.batch">' . _NIM_AM_IMPORT_BATCH . '</a>';
        echo '<br />';
        echo '<a href="import.php?op=images.import.multiupload">' . _NIM_AM_IMPORT_MULTIUPLOAD . '</a>';
        echo '</div>';
	break;
    
    // import standard Xoops Image Manager
	case "images.import.xoops":
	case "images.import.extgallery":
	case "images.import.batch":
	case "images.import.multiupload":
	echo 'IN PROGRESS';
	break;
}

xoops_cp_footer();
?>
