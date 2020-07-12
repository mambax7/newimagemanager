<?php
/**
 * Manage admin menu (from TDMDOWNLOAD)
 *
 */
function newimagemanager_adminmenu ($currentoption = 0, $breadcrumb = '') {
    /* Nice buttons styles */
    echo "
        <style type='text/css'>
        #buttontop { float:left; width:100%; background: #e7e7e7; font-size:93%; line-height:normal; border-top: 1px solid black; border-left: 1px solid black; border-right: 1px solid black; margin: 0; }
        #buttonbar { float:left; width:100%; background: #e7e7e7 url('" . XOOPS_URL . "/modules/newimagemanager/images/deco/bg.png') repeat-x left bottom; font-size:93%; line-height:normal; border-left: 1px solid black; border-right: 1px solid black; margin-bottom: 12px; }
        #buttonbar ul { margin:0; margin-top: 15px; padding:10px 10px 0; list-style:none; }
        #buttonbar li { display:inline; margin:0; padding:0; }
        #buttonbar a { float:left; background:url('" . XOOPS_URL . "/modules/newimagemanager/images/deco/left_both.png') no-repeat left top; margin:0; padding:0 0 0 9px; border-bottom:1px solid #000; text-decoration:none; }
        #buttonbar a span { float:left; display:block; background:url('" . XOOPS_URL . "/modules/newimagemanager/images/deco/right_both.png') no-repeat right top; padding:5px 15px 4px 6px; font-weight:bold; color:#765; }
        /* Commented Backslash Hack hides rule from IE5-Mac \*/
        #buttonbar a span {float:none;}
        /* End IE5-Mac hack */
        #buttonbar a:hover span { color:#333; }
        #buttonbar #current a { background-position:0 -150px; border-width:0; }
        #buttonbar #current a span { background-position:100% -150px; padding-bottom:5px; color:#333; }
        #buttonbar a:hover { background-position:0% -150px; }
        #buttonbar a:hover span { background-position:100% -150px; }
        </style>
    ";

    global $xoopsConfig;
    $myts = &MyTextSanitizer::getInstance();
    $Adminmenu = $GLOBALS["xoopsModule"]->getAdminMenu();
    $tblColors = Array();
    $tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = '';
    $tblColors[5] = $tblColors[6] = $tblColors[7] = $tblColors[8] = $tblColors[9] = '';
    $tblColors[10] = $tblColors[11] = $tblColors[12] = $tblColors[13] = $tblColors[14] = '';
    $tblColors[$currentoption] = 'current';
    if (file_exists(XOOPS_ROOT_PATH . '/modules/' . $GLOBALS["xoopsModule"]->getVar('dirname') . '/language/' . $xoopsConfig['language'] . '/modinfo.php')) {
        include_once XOOPS_ROOT_PATH . '/modules/newimagemanager/language/' . $xoopsConfig['language'] . '/modinfo.php';
    } else {
        include_once XOOPS_ROOT_PATH . '/modules/newimagemanager/english/modinfo.php';
    }

    echo "<div id='buttontop'>";
    echo "<table style=\"width: 100%; padding: 0; \" cellspacing=\"0\"><tr>";
    //echo "<td style=\"width: 45%; font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;\"><a class=\"nobutton\" href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $xoopsModule->getVar('mid') . "\">" . _AM_SF_OPTS . "</a> | <a href=\"import.php\">" . _AM_SF_IMPORT . "</a> | <a href=\"../index.php\">" . _AM_SF_GOMOD . "</a> | <a href=\"../help/index.html\" target=\"_blank\">" . _AM_SF_HELP . "</a> | <a href=\"about.php\">" . _AM_SF_ABOUT . "</a></td>";
    echo "<td style='font-size: 10px; text-align: left; color: #2F5376; padding: 0 6px; line-height: 18px;'>
      <a class='nobutton' href='" . XOOPS_URL . "/modules/system/admin.php?fct=preferences&amp;op=showmod&amp;mod=" . $GLOBALS["xoopsModule"]->getVar('mid') . "'>" . _PREFERENCES . "</a>
    | <a href='" . XOOPS_URL . "/modules/newimagemanager/index.php'>" . $GLOBALS["xoopsModule"]->getVar("name") . "</a>
    </td>";
    echo "<td style='font-size: 10px; text-align: right; color: #2F5376; padding: 0 6px; line-height: 18px;'><b>" . $myts->displayTarea($GLOBALS["xoopsModule"]->name()) . " </b> </td>";
    echo "</tr></table>";
    echo "</div>";
    echo "<div id='buttonbar'>";
    echo "<ul>";
    foreach ($Adminmenu as $key => $item) {
        echo "<li id='" . $tblColors[$key] . "'><a href=\"" . XOOPS_URL . "/modules/newimagemanager/" . $item['link'] . "\"><span>" . $item['title'] . "</span></a></li>";
    }
    if ( $GLOBALS["xoopsModule"]->getVar("hasconfig") || $GLOBALS["xoopsModule"]->getVar("hascomments") || $GLOBALS["xoopsModule"]->getVar("hasnotification") ) {
        echo '<li><a href="' . XOOPS_URL . '/modules/system/admin.php?fct=preferences&op=showmod&mod=' . $GLOBALS["xoopsModule"]->getVar("mid") . '"><span>' . _PREFERENCES . '</span></a></li>';
    }

    echo "</ul></div>&nbsp;";
}

/**
 * Get variables passed by GET or POST method
 *
 */
function system_CleanVars( &$global, $key, $default = '', $type = 'int' ) {
    switch ( $type ) {
        case 'string':
            $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_MAGIC_QUOTES ) : $default;
            break;
        case 'int': default:
            $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_NUMBER_INT ) : $default;
            break;
    }
    if ( $ret === false ) {
        return $default;
    }
    return $ret;
}

/**
 * Create a new directory that contains the file index.html
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

/**
 * Create a new directory that contains the file index.html
 *
 * $source: is the original directory
 * $destination: is the destination directory
 * Returns TRUE on success or FALSE on failure
 *
 */
function copyDir($source, $destination) {
    if (!$dir = opendir($source))
        return false;
    @mkdir($destination);
    while(false !== ( $file = readdir($dir)) ) {
        if (( $file != '.' ) && ( $file != '..' )) {
            if ( is_dir($source . '/' . $file) ) {
                if (!copyDir($source . '/' . $file, $destination . '/' . $file))
                    return false;
            }
            else {
                if (!copy($source . '/' . $file, $destination . '/' . $file))
                    return false;
            }
        }
    }
    closedir($dir);
    return true;
}

/**
 * Delete a not empty directory
 *
 * $dir: is the directory to delete
 * $if_not_empty: if FALSE it delete directory only if false
 * Returns TRUE on success or FALSE on failure
 */
function delDir($dir, $if_not_empty = true) {
    if (!file_exists($dir)) return true;

    if ($if_not_empty == true) {
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!delDir($dir . DIRECTORY_SEPARATOR . $item)) return false;
        }
    }
    else {
    }

    return rmdir($dir);
}



/**
 * Extention functions
 *
 */

function extentionInstalled() {
    return file_exists(XOOPS_ROOT_PATH . '/class/textsanitizer/newimage/newimage.php');
}
function extentionActivated() {
    $conf = include XOOPS_ROOT_PATH . '/class/textsanitizer/config.php';
    return $conf['extensions']['newimage'];
}
function activateExtention() {
    $conf = include XOOPS_ROOT_PATH . '/class/textsanitizer/config.php';
    $conf['extensions']['newimage'] = 1;
    file_put_contents(XOOPS_ROOT_PATH . '/class/textsanitizer/config.php', "<?php\rreturn \$config = " . var_export($conf, true) . "\r?>");
}
function desactivateExtention() {
    $conf = include XOOPS_ROOT_PATH . '/class/textsanitizer/config.php';
    $conf['extensions']['newimage'] = 0;
    file_put_contents(XOOPS_ROOT_PATH . '/class/textsanitizer/config.php', "<?php\rreturn \$config = " . var_export($conf, true) . "\r?>");
}



/**
 * Garbage collection IN PROGRESS
 *
 */
function garbage_collection($directory, $session_id = null, $filename = null) {
    global $SessionHandler, $xoopsDB;
    // create a handler for the directory
    $directory_handler = opendir($directory);
    $SessionHandler = new XoopsSessionHandler($xoopsDB);
    // keep going until all files in directory have been read
    while ($file = readdir($directory_handler)) {
        // if $file isn't this directory or its parent,
        // add it to the results array
        if ($file != '.' && $file != '..') {
            $file_as_array = explode('_', $file);
            if (count($file_as_array) != 2)
                continue;
            $file_session_id = $file_as_array[0];
            $file_filename = $file_as_array[1];
            if (is_null($session_id)) {
                if ($SessionHandler->read($file_session_id) != '') {
                    // session is active
                    // NOP
                }
                else {
                    // if session is not active, delete file
                    unlink($directory . $file); // IN PROGRESS check if all right!
                    continue;
                }
            }
            else {
                if ($SessionHandler->read($session_id) != '') {
                    // if $session_id session is active, delete file
                    @unlink($directory . $file); // IN PROGRESS check if all right!
                    continue;
                }
                else {
                    // session is not active
                    // NOP
                }
            }
            if (is_null($filename)) {
                // NOP
            }
            else {
            if ($file_filename == $filename)
                // if $filename file exist, delete file
                @unlink($directory . $file); // IN PROGRESS check if all right!
                continue;
            }
        }
    }
    // tidy up: close the handler
    closedir($directory_handler);
}
?>
