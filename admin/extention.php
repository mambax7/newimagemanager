<?php
include 'admin_header.php';

// get current filename
$current_file = basename(__FILE__);
$localModuleDir = 'newimagemanager';

if(isset($_POST['step'])) {
    $step = $_POST['step'];
} else {
    $step = 'default';
}

// init
// get current filename
$current_file = basename(__FILE__);
// load classes
xoops_load('xoopsformloader');
// get handlers
$option_handler = xoops_getModuleHandler('newimgoption', 'newimagemanager');
$errors = array();
$output = '';

// Get user groups array
$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
// $admin is true if user is an admin
$admin = (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) ? true : false;

switch( $step ) {
    case 'activate':
        activateExtention();
        redirect_header("extention.php", 3, _NIM_AM_EXTENTION_ACTIVATED);
        break;
    case 'desactivate':
        desactivateExtention();
        redirect_header("extention.php", 3, _NIM_AM_EXTENTION_DESACTIVATED);
        break;
    case 'install':
        $source = XOOPS_ROOT_PATH . '/modules/' . $localModuleDir . '/extra/textsanitizer.extension';
        $destination = XOOPS_ROOT_PATH . '/class/textsanitizer';
        if(!file_exists($source . '/newimage')) {
            redirect_header("extention.php", 3, _NIM_AM_EXT_FILE_DONT_EXIST_SHORT);
            break;
        }

        // Copy extention
        if (!copyDir($source, $destination)) {
            redirect_header("extention.php", 3, _NIM_AM_EXT_FILE_NOT_INSTALLABLE);
        }

        // Activate extention
        activateExtention();
        redirect_header("extention.php", 3, _NIM_AM_EXTENTION_INSTALLED . "<br />" . _NIM_AM_EXTENTION_ACTIVATED);
        break;
    default:
    case 'default':
        xoops_cp_header();
        //appel du menu admin
        if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
            newimagemanager_adminmenu(6, _NIM_MI_ADMENU_EXTENTION);
        } else {
            include_once XOOPS_ROOT_PATH . '/Frameworks/art/functions.admin.php';
            loadModuleAdminMenu (6, _NIM_MI_ADMENU_EXTENTION);
        }
        echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _NIM_AM_EXTENTION_INFO . '</legend>';
        if(!extentionInstalled()) {
            echo "<h3 style=\"color:red;\">" . _NIM_AM_EXTENTION_NOT_INSTALLED . "</h3>";
            echo "<br />";
            echo "<form action=\"extention.php\" method=\"post\">";
            echo "<input type=\"hidden\" name=\"step\" value=\"install\" />";
            echo "<input class=\"formButton\" value=\"" . _NIM_AM_INSTALL_EXTENTION . "\" type=\"submit\" />";
            echo "</form>";
        } else {
            echo "<h3 style=\"color:green;\">" . _NIM_AM_EXTENTION_INSTALLED_OK . "</h3>";
            if(!extentionActivated()) {
                echo "<h3 style=\"color:red;\">" . _NIM_AM_EXTENTION_NOT_ACTIVATED . "</h3>";
                echo "<br />";
                echo "<form action=\"extention.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"step\" value=\"activate\" />";
                echo "<input class=\"formButton\" value=\"" . _NIM_AM_ACTIVATE_EXTENTION . "\" type=\"submit\" />";
                echo "</form>";

            } else {
                echo "<h3 style=\"color:green;\">" . _NIM_AM_EXTENTION_ACTIVATED_OK . "</h3>";
                echo "<p>" . _NIM_AM_EXTENTION_NOTICE . "</p>";
                echo "<br />";
                echo "<form action=\"extention.php\" method=\"post\">";
                echo "<input type=\"hidden\" name=\"step\" value=\"desactivate\" />";
                echo "<input class=\"formButton\" value=\"" . _NIM_AM_DESACTIVATE_EXTENTION . "\" type=\"submit\" />";
                echo "</form>";
            }
        }
    echo '</fieldset>';
    
    echo '<br />';
    echo '<br />';
    echo '<br />';
    
    echo '<fieldset><legend style="font-weight:bold; color:#990000;">' . _NIM_AM_OPTIONS_MANAGER . '</legend>';
    
    $myts =& MyTextSanitizer::getInstance();
    
    $form = new XoopsThemeForm(_NIM_AM_ADDOPTION, 'adoption', $current_file, 'post');
    $form->setExtra('enctype="multipart/form-data"');
    $form->addElement(new XoopsFormText(_NIM_AM_OPTIONNAME, 'option_name', 30, 30, ''), true);
    $form->addElement(new XoopsFormText(_NIM_AM_OPTIONVALUE, 'option_value', 50, 255, ''), true);
    $form->addElement(new XoopsFormTextArea(_NIM_AM_OPTIONDESCRIPTION, 'option_description', '', 5, 100));
    $form->addElement(new XoopsFormHidden('op', 'save'));
    $form->addElement(new XoopsFormHidden('oid', $oid));
    // Submit button		
    $button_tray = new XoopsFormElementTray('' ,'');
    $button_tray->addElement(new XoopsFormButton('', 'post', _NIM_AM_ADDOPTION, 'submit'));
    $form->addElement($button_tray);
	$output = $form->render();
	echo $output;


    echo '</fieldset>';

    xoops_cp_footer();
    break;
    } // switch ( $step )
?>
