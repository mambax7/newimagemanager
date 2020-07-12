<?php
include 'admin_header.php';

xoops_cp_header();

if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
    newimagemanager_adminmenu(7, _NIM_MI_ADMENU_ABOUT);
} else {
    include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
    loadModuleAdminMenu (7, _NIM_MI_ADMENU_ABOUT);
}

$versioninfo =& $module_handler->get( $xoopsModule->getVar( 'mid' ) );
echo "
	<style type=\"text/css\">
	.changelog {
        font-family: monospace;
    }
	label,text {
		display: block;
		float: left;
		margin-bottom: 2px;
	}
	label {
		text-align: right;
		width: 150px;
		padding-right: 20px;
	}
	br {
		clear: left;
	}
	</style>
";

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . $xoopsModule->getVar("name"). "</legend>";
echo "<div style='padding: 8px;'>";
echo "<img src='" . XOOPS_URL . "/modules/" . $xoopsModule->getVar("dirname") . "/" . $versioninfo->getInfo( 'image' ) . "' alt='' hspace='10' vspace='0' /></a>\n";
echo "<div style='padding: 5px;'><strong>" . $versioninfo->getInfo( 'name' ) . " version " . $versioninfo->getInfo( 'version' ) . "</strong></div>\n";
echo "<label>" . _NIM_AM_ABOUT_RELEASEDATE . ":</label><text>" . $versioninfo->getInfo( 'release' ) . "</text><br />";
echo "<label>" . _NIM_AM_ABOUT_AUTHOR . ":</label><text>" . $versioninfo->getInfo( 'author' ) . "</text><br />";
echo "<label>" . _NIM_AM_ABOUT_CREDITS . ":</label><text>" . $versioninfo->getInfo( 'credits' ) . "</text><br />";
echo "<label>" . _NIM_AM_ABOUT_LICENSE . ":</label><text><a href=\"".$versioninfo->getInfo( 'license_file' )."\" target=\"_blank\" >" . $versioninfo->getInfo( 'license' ) . "</a></text>\n";
echo "</div>";
echo "</fieldset>";
echo "<br clear=\"all\" />";

echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _NIM_AM_ABOUT_MODULEINFOS . "</legend>";
echo "<div style='padding: 8px;'>";
echo "<label>" . _NIM_AM_ABOUT_STATUS . ":</label><text>" . $versioninfo->getInfo( 'module_status' ) . "</text><br />";
echo "<label>" . _NIM_AM_ABOUT_MODULEWEBSITE . ":</label><text>" . "<a href='" . $versioninfo->getInfo( 'support_site_url' ) . "' target='_blank'>" . $versioninfo->getInfo( 'support_site_name' ) . "</a>" . "</text><br />";
echo "</div>";
echo "</fieldset>";
echo "<br clear=\"all\" />";

$file = XOOPS_ROOT_PATH. "/modules/newimagemanager/description.html";
if ( is_readable( $file ) ){
	echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _NIM_AM_ABOUT_DESCRIPTION . "</legend>";
	echo "<div style='padding: 8px;'>";
	echo "<div>". implode("", file( $file )) . "</div>";
	echo "</div>";
	echo "</fieldset>";
	echo "<br clear=\"all\" />";
}

$file = XOOPS_ROOT_PATH. "/modules/newimagemanager/help.html";
if ( is_readable( $file ) ){
	echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _NIM_AM_ABOUT_HELP . "</legend>";
	echo "<div style='padding: 8px;'>";
	echo "<div>". implode("", file( $file )) . "</div>";
	echo "</div>";
	echo "</fieldset>";
	echo "<br clear=\"all\" />";
}

$file = XOOPS_ROOT_PATH. "/modules/newimagemanager/changelog.txt";
if ( is_readable( $file ) ){
	echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _NIM_AM_ABOUT_CHANGELOG . "</legend>";
	echo "<div class='changelog' style='padding: 8px;'>";
	echo "<div>". implode("<br />", file( $file )) . "</div>";
	echo "</div>";
	echo "</fieldset>";
	echo "<br clear=\"all\" />";
}

xoops_cp_footer();
?>
