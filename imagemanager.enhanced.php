<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('NWLINE')or define('NWLINE', "\n");
// get current filename
$current_file = basename(__FILE__);
$current_path = dirname(__FILE__);

include_once '..' . DS . '..' . DS . 'mainfile.php';
xoops_load('xoopsformloader');
xoops_load('xoopsmediauploader');
include_once XOOPS_ROOT_PATH . DS . 'class' . DS . 'template.php';
include_once XOOPS_ROOT_PATH . DS . 'include' . DS . 'cp_functions.php';
include_once XOOPS_ROOT_PATH . DS . 'modules' . DS . 'system' . DS . 'constants.php';

// Include language file
xoops_loadLanguage('main', 'newimagemanager');
// load language definitions
//xoops_loadLanguage('admin', 'system');
//xoops_loadLanguage('/admin/images', 'system');

defined('MODULE_UPLOAD_PATH')or define('MODULE_UPLOAD_PATH', XOOPS_ROOT_PATH . DS . 'uploads' . DS . 'newimagemanager');

//$gperm_handler =& xoops_gethandler('groupperm');
$xoopsModule =& XoopsModule::getByDirname('newimagemanager');

// Get user groups array
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);

// Check users rights
$isadmin = false;
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    // NOPredirect_header(XOOPS_URL . DS ,3 ,_NOPERM);
    exit();
} else {
    $isadmin = true;
}

// check categories readability/writability
$imgcat_handler =& xoops_getModuleHandler('newimagecategory', 'newimagemanager');
$image_handler =& xoops_getModuleHandler('newimage', 'newimagemanager');
$catreadlist =& $imgcat_handler->getList($groups, 'newimagemanager_cat_read', 1);    // get readable categories
$catwritelist =& $imgcat_handler->getList($groups, 'newimagemanager_cat_write', 1);  // get writable categories

$catreadcount = count($catreadlist);        // count readable categories
$catwritecount = count($catwritelist);      // count writable categories



// check/set parameters - start
if (!isset($target)) {
    if (!isset($_REQUEST['target'])) {
        $target = '';
    } else {
        $target = $_REQUEST['target'];
    }
}


if (!isset($return_mode)) {
    if (!isset($_REQUEST['return_mode'])) {
        $return_mode = 'bbcode';
    } else {
        $return_mode = $_REQUEST['return_mode'];
    }
}


if (!isset($header_html_code)) {
    // default header 
    $header_html_code = '';
}
if (!isset($return_js_code)) {
    // default return code function
    $return_js_code = '
<script type="text/javascript">
<!--//
function returnCode(addCode) {
	var targetDom = window.opener.xoopsGetElementById(\'' . $target . '\');
	if (targetDom.createTextRange && targetDom.caretPos){
  		var caretPos = targetDom.caretPos;
		caretPos.text = caretPos.text.charAt(caretPos.text.length - 1) == \' \' ? addCode + \' \' : addCode;  
	} else if (targetDom.getSelection && targetDom.caretPos) {
		var caretPos = targetDom.caretPos;
		caretPos.text = caretPos.text.charat(caretPos.text.length - 1) == \' \' ? addCode + \' \' : addCode;
	} else {
		targetDom.value = targetDom.value + addCode;
  	}
	window.close();
	return;
}
//-->
</script>
';
}

$op = 'list'; // default
if (isset($_POST)) {foreach ( $_POST as $k => $v ) {${$k} = $v;}}
if (isset($_GET['op'])) {$op = trim($_GET['op']);}

if (isset($_GET['target'])) {$target = trim($_GET['target']);}
if (isset($_GET['return_mode'])) {$return_mode = trim($_GET['return_mode']);}
if (isset($_GET['image_id'])) {$image_id = intval($_GET['image_id']);}
if (isset($_GET['imgcat_id'])) {$imgcat_id = intval($_GET['imgcat_id']);}
// check/set parameters - end



if ( ($isadmin) || ($catreadcount > 0) || ($catwritecount > 0) ) {


switch ($op) {

    case  'save' :
// Save Image modification
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $count = count($image_id);
        if ($count > 0) {
            $error = array();
            for ($i = 0; $i < $count; $i++) {
                $image =& $image_handler->get($image_id[$i]);
                if (!is_object($image)) {
                    $error[] = sprintf(_FAILGETIMG, $image_id[$i]);
                    continue;
                }
                $image_display[$i] = empty($image_display[$i]) ? 0 : 1;
                $image->setVar('image_display', $image_display[$i]);
                $image->setVar('image_weight', $image_weight[$i]);
                $image->setVar('image_nicename', $image_nicename[$i]);
                $image->setVar('imgcat_id', $imgcat_id[$i]);
                if (!$image_handler->insert($image)) {
                    $error[] = sprintf(_FAILSAVEIMG, $image_id[$i]);
                }
            }
            if (count($error) > 0) {
                redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, xoops_error(implode('<br />', $error) ) );
            }
        }
        redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, _MD_AM_DBUPDATED);
        break;

    case 'addfile' :
// Add new image
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $imagecategory =& $imgcat_handler->get(intval($imgcat_id));
        if (!is_object($imagecategory)) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3);
        }

        $mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/bmp');
        $uploader = new XoopsMediaUploader(MODULE_UPLOAD_PATH . DS . 'uploaded'. DS . $imagecategory->getVar('imgcat_relativepath'), $mimetypes, $imagecategory->getVar('imgcat_maxsize'), $imagecategory->getVar('imgcat_maxwidth'), $imagecategory->getVar('imgcat_maxheight'));
        $uploader->setPrefix('img');

        $err = array();
        $ucount = count($_POST['xoops_upload_file']);
        for ($i = 0; $i < $ucount; $i++) {
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][$i])) {
                if (!$uploader->upload()) {
                    $err[] = $uploader->getErrors();
                } else {
                    $image =& $image_handler->create();
                    $image->setVar('image_name', $uploader->getSavedFileName());
                    $image->setVar('image_nicename', $image_nicename);
                    $image->setVar('image_alternative', $image_alternative);
                    $image->setVar('image_mimetype', $uploader->getMediaType());
                    $image->setVar('image_created', time());
                    $image_display = 1;//empty($image_display) ? 0 : 1;
                    $image->setVar('image_display', $image_display);
                    $image->setVar('image_weight', 0);
                    $image->setVar('image_weight', $image_weight);
                    $image->setVar('imgcat_id', $imgcat_id);
                    //$image->setVar('image_description', $image_description); // IN PROGRESS

                    $fbinary = @file_get_contents($uploader->getSavedDestination());
                    $image->setVar('image_body', $fbinary, true);
                    @unlink($uploader->getSavedDestination());

                    if (!$image_handler->insert($image)) {
                        $err[] = sprintf(_FAILSAVEIMG, $image->getVar('image_nicename'));
                    }
                }
            } else {
                $err[] = sprintf(_FAILFETCHIMG, $i);
                $err = array_merge($err, $uploader->getErrors(false));
            }
        }
        if (count($err) > 0) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, xoops_error(implode('<br />', $err) ) );
        }
        redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, _MD_AM_DBUPDATED);
        break;

    case 'addcat' :
// Add new category
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $imagecategory =& $imgcat_handler->create();
        $imagecategory->setVar('imgcat_name', $imgcat_name);
        $imagecategory->setVar('imgcat_maxsize', $imgcat_maxsize);
        $imagecategory->setVar('imgcat_maxwidth', $imgcat_maxwidth);
        $imagecategory->setVar('imgcat_maxheight', $imgcat_maxheight);
        $imgcat_display = empty($imgcat_display) ? 0 : 1;
        $imagecategory->setVar('imgcat_display', $imgcat_display);
        $imagecategory->setVar('imgcat_weight', $imgcat_weight);
        $imagecategory->setVar('imgcat_storetype', $imgcat_storetype);
        $imagecategory->setVar('imgcat_type', 'C');
        if (!$imgcat_handler->insert($imagecategory)) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3);
        }
        $newid = $imagecategory->getVar('imgcat_id');
        $imagecategoryperm_handler =& xoops_gethandler('groupperm');
        if (!isset($readgroup)) {
            $readgroup = array();
        }
        if (!in_array(XOOPS_GROUP_ADMIN, $readgroup)) {
            array_push($readgroup, XOOPS_GROUP_ADMIN);
        }
        foreach ($readgroup as $rgroup) {
            $imagecategoryperm =& $imagecategoryperm_handler->create();
            $imagecategoryperm->setVar('gperm_groupid', $rgroup);
            $imagecategoryperm->setVar('gperm_itemid', $newid);
            $imagecategoryperm->setVar('gperm_name', 'newimagemanager_cat_read');
            $imagecategoryperm->setVar('gperm_modid', 1);
            $imagecategoryperm_handler->insert($imagecategoryperm);
            unset($imagecategoryperm);
        }
        if (!isset($writegroup)) {
            $writegroup = array();
        }
        if (!in_array(XOOPS_GROUP_ADMIN, $writegroup)) {
            array_push($writegroup, XOOPS_GROUP_ADMIN);
        }
        foreach ($writegroup as $wgroup) {
            $imagecategoryperm =& $imagecategoryperm_handler->create();
            $imagecategoryperm->setVar('gperm_groupid', $wgroup);
            $imagecategoryperm->setVar('gperm_itemid', $newid);
            $imagecategoryperm->setVar('gperm_name', 'newimagemanager_cat_write');
            $imagecategoryperm->setVar('gperm_modid', 1);
            $imagecategoryperm_handler->insert($imagecategoryperm);
            unset($imagecategoryperm);
        }
        redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3,_MD_AM_DBUPDATED);
        break;

    case 'updatecat' :
// Update category
        if (!$GLOBALS['xoopsSecurity']->check() || $imgcat_id <= 0) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3);
        }
        $imagecategory->setVar('imgcat_name', $imgcat_name);
        $imgcat_display = empty($imgcat_display) ? 0 : 1;
        $imagecategory->setVar('imgcat_display', $imgcat_display);
        $imagecategory->setVar('imgcat_maxsize', $imgcat_maxsize);
        $imagecategory->setVar('imgcat_maxwidth', $imgcat_maxwidth);
        $imagecategory->setVar('imgcat_maxheight', $imgcat_maxheight);
        $imagecategory->setVar('imgcat_weight', $imgcat_weight);
        if (!$imgcat_handler->insert($imagecategory)) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3);
        }
        $imagecategoryperm_handler =& xoops_gethandler('groupperm');
        $criteria = new CriteriaCompo(new Criteria('gperm_itemid', $imgcat_id));
        $criteria->add(new Criteria('gperm_modid', 1));
        $criteria2 = new CriteriaCompo(new Criteria('gperm_name', 'newimagemanager_cat_write'));
        $criteria2->add(new Criteria('gperm_name', 'newimagemanager_cat_read'), 'OR');
        $criteria->add($criteria2);
        $imagecategoryperm_handler->deleteAll($criteria);
        if (!isset($readgroup)) {
            $readgroup = array();
        }
        if (!in_array(XOOPS_GROUP_ADMIN, $readgroup)) {
            array_push($readgroup, XOOPS_GROUP_ADMIN);
        }
        foreach ($readgroup as $rgroup) {
            $imagecategoryperm =& $imagecategoryperm_handler->create();
            $imagecategoryperm->setVar('gperm_groupid', $rgroup);
            $imagecategoryperm->setVar('gperm_itemid', $imgcat_id);
            $imagecategoryperm->setVar('gperm_name', 'newimagemanager_cat_read');
            $imagecategoryperm->setVar('gperm_modid', 1);
            $imagecategoryperm_handler->insert($imagecategoryperm);
            unset($imagecategoryperm);
        }
        if (!isset($writegroup)) {
            $writegroup = array();
        }
        if (!in_array(XOOPS_GROUP_ADMIN, $writegroup)) {
            array_push($writegroup, XOOPS_GROUP_ADMIN);
        }
        foreach ($writegroup as $wgroup) {
            $imagecategoryperm =& $imagecategoryperm_handler->create();
            $imagecategoryperm->setVar('gperm_groupid', $wgroup);
            $imagecategoryperm->setVar('gperm_itemid', $imgcat_id);
            $imagecategoryperm->setVar('gperm_name', 'newimagemanager_cat_write');
            $imagecategoryperm->setVar('gperm_modid', 1);
            $imagecategoryperm_handler->insert($imagecategoryperm);
            unset($imagecategoryperm);
        }
        redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, _MD_AM_DBUPDATED);
        break;

    case 'delcat':
// Confirm delete categoriy
        xoops_header();
        echo "<link href='css/xoopsimagebrowser.css' rel='stylesheet' type='text/css' />";
        xoops_confirm(array('op' => 'delcatok', 'imgcat_id' => $imgcat_id, 'target' => $target, 'return_mode' => $return_mode), 'xoopsimagebrowser.php', _MD_RUDELIMGCAT);
        xoops_footer();
        exit();
        break;

    case 'delcatok' :
// Delete category
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        $imgcat_id = intval($imgcat_id);
        if ($imgcat_id <= 0) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3);
        }
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3);
        }
        if ($imagecategory->getVar('imgcat_type') != 'C') {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, _MD_SCATDELNG);
        }
        $images =& $image_handler->getObjects(new Criteria('imgcat_id', $imgcat_id), true, false);
        $errors = array();
        foreach (array_keys($images) as $i) {
            if (!$image_handler->delete($images[$i])) {
                $errors[] = sprintf(_MD_FAILDEL, $i);
            } else {
                if (file_exists(XOOPS_UPLOAD_PATH.'/'.$images[$i]->getVar('image_name')) && !unlink(XOOPS_UPLOAD_PATH.'/'.$images[$i]->getVar('image_name'))) {
                    $errors[] = sprintf(_MD_FAILUNLINK, $i);
                }
            }
        }
        if (!$imgcat_handler->delete($imagecategory)) {
            $errors[] = sprintf(_MD_FAILDELCAT, $imagecategory->getVar('imgcat_name'));
        }
        if (count($errors) > 0) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, xoops_error(implode('<br />', $error) ) );
        }
        redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, _MD_AM_DBUPDATED);
        break;


// ************************* NOT USED ************************************
// ************************* NOT USED ************************************
// ************************* NOT USED ************************************
/*
   // Confirm delete file - start
   if ( !empty($_GET['op']) && $op == 'delfile' ) {
        xoops_header();
        echo "<link href='css/xoopsimagebrowser.css' rel='stylesheet' type='text/css' />";
        xoops_confirm(array('op' => 'delfileok', 'image_id' => $image_id, 'target' => $target, 'return_mode' => $return_mode), 'xoopsimagebrowser.php', _MD_RUDELIMG);
        xoops_footer();
        exit();
    }
   // Confirm delete file - end

   // Delete file - start
   if ($op == 'delfileok') {
       if (!$GLOBALS['xoopsSecurity']->check()) {
           redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
       }
       $image_id = intval($image_id);
       if ($image_id <= 0) {
           redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3);
       }
       $image =& $image_handler->get($image_id);
       if (!is_object($image)) {
           redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3);
       }
       if (!$image_handler->delete($image)) {
            redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, xoops_error(sprintf(_MD_FAILDEL, $image->getVar('image_id'))) );
        }
        @unlink(XOOPS_UPLOAD_PATH.'/'.$image->getVar('image_name'));
        redirect_header($current_file . '?target=' . $target . '&amp;return_mode=' . $return_mode, 3, _MD_AM_DBUPDATED);
    }
   // Delete file - end
*/
// ************************* NOT USED ************************************
// ************************* NOT USED ************************************
// ************************* NOT USED ************************************
    } // switch ($op)
} // if ( ($isadmin) || ($catreadcount > 0) || ($catwritecount > 0) ) {




echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="' . _LANGCODE . '" lang="' . _LANGCODE . '">';
echo '<head>';
echo '<meta http-equiv="content-type" content="text/html; charset=' . _CHARSET . '" />';
echo '<meta http-equiv="content-language" content="' . _LANGCODE . '" />';
echo '<title>' . htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES) . ' ' . _NIM_IMGMANAGER . '</title>';
echo '<script type="text/javascript" src="js/xoopsimagebrowser.js"></script>';

echo $header_html_code;

echo $return_js_code;

echo '<link href="' . xoops_getcss($xoopsConfig['theme_set']) . '" rel="stylesheet" type="text/css" />';
echo '<link href="' . XOOPS_URL . '/modules/newimagemanager/imagemanager.enhanced.css" rel="stylesheet" type="text/css" />';
echo '<base target="_self" />';
echo '</head>';
echo '<body onload="window.resizeTo(800, 600);">';
echo '<img src="' . XOOPS_URL . '/modules/newimagemanager/images/imagemanager_slogo.png" alt="" />';
echo '<div class="panel_wrapper">';
    echo '<div id="imagebrowser_panel" class="panel current" style="overflow:auto;">';

    //list Categories - start
    if ($op == 'list') {
        if (!empty($catreadlist)) {
            echo '<table width="100%" class="outer" cellspacing="1">';
            // get all categories
            $imagecategories =& $imgcat_handler->getObjects();
            $catcount = count($imagecategories);
            for ($i = 0; $i < $catcount; $i++) {
                echo '<tr valign="top" align="left"><td class="head">';
                if ( in_array($imagecategories[$i]->getVar('imgcat_id'), array_keys($catreadlist)) ) {
                    // count images stored in this category
                    $this_imgcat_id = $imagecategories[$i]->getVar('imgcat_id');
                    $countimagesincat = $image_handler->getCount(new Criteria('imgcat_id', $this_imgcat_id));
                    echo $this_imgcat_id . ' - ' . $imagecategories[$i]->getVar('imgcat_name') . ' (' . sprintf(_NUMIMAGES, '<strong>' . $countimagesincat . '</strong>') . ')';
                    echo '</td><td class="even">';
                    echo '&nbsp;[<a href="' . $current_file . "?target=" . $target . "&amp;return_mode=" . $return_mode . '&amp;op=listimg&amp;imgcat_id=' . $this_imgcat_id . '">' . _LIST . '</a>]';
                    if ($isadmin) {
                        echo '&nbsp;[<a href="'.$current_file . "?target=" . $target . "&amp;return_mode=" . $return_mode . '&amp;op=editcat&amp;imgcat_id=' . $this_imgcat_id . '">' . _EDIT . '</a>]';
                    }
                    if ($isadmin && $imagecategories[$i]->getVar('imgcat_type') == 'C') {
                        echo '&nbsp;[<a href="' . $current_file . '?target=' . $target . '&amp;op=delcat&amp;imgcat_id=' . $this_imgcat_id . '">' . _DELETE . '</a>]';
                    }
                }
                echo '</td></tr>';
            }
            echo '</table>';
        }
    }
    //list Categories - end

    //list images - start
    if ($op == 'listimg') {
        $imgcat_id = intval($imgcat_id);
        if ($imgcat_id <= 0) {
            redirect_header($current_file . '?target=' . $target, 1);
        }
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {
            redirect_header($current_file . '?target=' . $target, 1);
        }

        $criteria = new Criteria('imgcat_id', $imgcat_id);
        $imgcount = $image_handler->getCount($criteria);
        $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
        $criteria->setStart($start);
        $criteria->setLimit(20);
        $images =& $image_handler->getObjects($criteria, true, false);

        echo '<a href="' . $current_file . "?target=" . $target . "&amp;return_mode=" . $return_mode . '">' . _NIM_IMGMAIN . '</a>&nbsp;<span style="font-weight:bold;">&gt;</span>&nbsp;'.$imagecategory->getVar('imgcat_name');
        echo '<br /><br /><strong>_SELECT_IMAGE</strong>';
        echo '<form action="'.$current_file . "?target=" . $target . "&amp;return_mode=" . $return_mode .'" method="post">';
        $rowspan = ($catwritelist) ? 5 : 2;
        foreach (array_keys($images) as $i) {
            $image_src = '' . XOOPS_URL . '/image.php?id=' . $i . '';

            // set the returned code: bbcode or src or wysiwyg editor? - start
            switch ($return_mode) {
            case 'src':
                $returned_value = $image_src;
                break;
            case 'wysiwyg':
                $returned_value = '' . $target . '\',\'' . $images[$i]->getVar('image_id') . '\',\'' . $image_src . '\',\'' . $images[$i]->getVar('image_nicename', 'E') . '\',\'' . $images[$i]->getVar('image_nicename', 'E') . '';
                break;
            case 'bbcode':
            default:
                $returned_value = '[img]' . $image_src . '[/img]';
                break;
            case 'xoops':
                $lcode = '[img align=left id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                $code  = '[img align=center id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                $rcode = '[img align=right id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                $src   = XOOPS_URL . "/image.php?id=" . $images[$i]->getVar('image_id');
                $returned_value['lcode'] = $lcode;
                $returned_value['code'] = $code;
                $returned_value['rcode'] = $rcode;
                $returned_value['src'] = $src;
            }

            // set right image width - start
            $max_image_width = 150;
            $image_size = getimagesize($image_src);
            $image_width = ($image_size[0] > $max_image_width) ? $max_image_width : $image_size[0];
            // set right image width - end            

            echo '<table width="100%" class="outer">';
            echo '<tr>';
            echo '<td rowspan="' . $rowspan . '" class="xoopsimage">';

            echo '<img style="width:' . $image_width. 'px;height:auto;"';
            echo ' id="image_id_' . $images[$i]->getVar('image_id') . '"';
            echo ' src="' . $image_src . '"';
            echo ' alt="' . $images[$i]->getVar('image_nicename', 'E') . '"';
            echo ' title="' . $images[$i]->getVar('image_nicename', 'E') . '"';
            echo ' onclick="javascript:returnCode(\'' . $returned_value . '\');"';
            echo ' />';
            echo '<br />';
            echo ''.$image_size[0].'x'.$image_size[1].'';
            echo '</td>';
            echo '<td class="head">'._NIM_IMAGENAME,'</td>';
            echo '<td class="even"><input type="hidden" name="image_id[]" value="'.$i.'" />';
            echo '<input type="text" name="image_nicename[]" value="'.$images[$i]->getVar('image_nicename', 'E').'" size="20" maxlength="255" />';
            echo '</td>';
            echo '</tr>';

            echo '<tr>';
            echo '<td class="head">'._NIM_IMAGEMIME.'</td>';
            echo '<td class="odd">'.$images[$i]->getVar('image_mimetype').'</td>';
            echo '</tr>';

            if ( $catwritelist ) {
                echo '<tr>';
                echo '<td class="head">'._NIM_IMAGECAT.'</td>';
                echo '<td class="even">';
                echo '<select name="imgcat_id[]" size="1">';
                $list = $imgcat_handler->getList($groups, null, null, $imagecategory->getVar('imgcat_storetype'));
                foreach ($list as $value => $name) {
                    echo '<option value="'.$value.'"'.(($value == $images[$i]->getVar('imgcat_id'))?' selected="selected"':'').'>'.$name.'</option>';
                }
                echo '</select>';
                echo '</td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td class="head">'._NIM_IMGWEIGHT.'</td>';
                echo '<td class="odd"><input type="text" name="image_weight[]" value="'.$images[$i]->getVar('image_weight').'" size="3" maxlength="4" /></td>';
                echo '</tr>';

                echo '<tr>';
                echo '<td class="head">'._NIM_IMGDISPLAY.'</td>';
                echo '<td class="even">';
                echo '<input type="checkbox" name="image_display[]" value="1"'.(($images[$i]->getVar('image_display') == 1)?' checked="checked"':'').' />';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
            echo '<br />';
        }

        if ($imgcount > 0) {
            if ($imgcount > 20) {
                include_once XOOPS_ROOT_PATH.'/class/pagenav.php';
                $nav = new XoopsPageNav($imgcount, 20, $start, 'start', 'op=listimg&amp;target='.$target.'&amp;imgcat_id='.$imgcat_id);
                echo '<div text-align="right">'.$nav->renderNav().'</div>';
            }
            if ( $catwritelist ) {
                echo '<input type="hidden" name="op" value="save" />'.$GLOBALS['xoopsSecurity']->getTokenHTML().'<input type="submit" name="submit" value="'._SUBMIT.'" />';
                echo '</form>';
            }
        }
    }
    //list images - end

    //edit category - start
    if ($op == 'editcat') {
        if ($imgcat_id <= 0) {
            redirect_header($current_file . "?target=" . $target . "&amp;return_mode=" . $return_mode, 1);
        }
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {
            redirect_header($current_file . "?target=" . $target . "&amp;return_mode=" . $return_mode, 1);
        }
        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        $imagecategoryperm_handler =& xoops_gethandler('groupperm');
        $form = new XoopsThemeForm(_NIM_EDITIMGCAT, 'imagecat_form', ''.$current_file.'?target='.$target.'', 'post', true);
        $form->addElement(new XoopsFormText(_NIM_IMGCATNAME, 'imgcat_name', 50, 255, $imagecategory->getVar('imgcat_name')), true);
        $form->addElement(new XoopsFormSelectGroup(_NIM_IMGCATRGRP, 'readgroup', true, $imagecategoryperm_handler->getGroupIds('newimagemanager_cat_read', $imgcat_id), 5, true));
        $form->addElement(new XoopsFormSelectGroup(_NIM_IMGCATWGRP, 'writegroup', true, $imagecategoryperm_handler->getGroupIds('newimagemanager_cat_write', $imgcat_id), 5, true));
        $form->addElement(new XoopsFormText(_NIM_IMGMAXSIZE, 'imgcat_maxsize', 10, 10, $imagecategory->getVar('imgcat_maxsize')));
        $form->addElement(new XoopsFormText(_NIM_IMGMAXWIDTH, 'imgcat_maxwidth', 3, 4, $imagecategory->getVar('imgcat_maxwidth')));
        $form->addElement(new XoopsFormText(_NIM_IMGMAXHEIGHT, 'imgcat_maxheight', 3, 4, $imagecategory->getVar('imgcat_maxheight')));
        $form->addElement(new XoopsFormText(_NIM_IMGCATWEIGHT, 'imgcat_weight', 3, 4, $imagecategory->getVar('imgcat_weight')));
        $form->addElement(new XoopsFormRadioYN(_NIM_IMGCATDISPLAY, 'imgcat_display', $imagecategory->getVar('imgcat_display'), _YES, _NO));
        $storetype = array('db' => _NIM_INDB, 'file' => _NIM_ASFILE);
        $form->addElement(new XoopsFormLabel(_NIM_IMGCATSTRTYPE, $storetype[$imagecategory->getVar('imgcat_storetype')]));
        $form->addElement(new XoopsFormHidden('imgcat_id', $imgcat_id));
        $form->addElement(new XoopsFormHidden('op', 'updatecat'));
        $form->addElement(new XoopsFormButton('', 'imgcat_button', _SUBMIT, 'submit'));
        echo '<a href="'.$current_file . "?target=" . $target . "&amp;return_mode=" . $return_mode.'">'. _MD_IMGMAIN .'</a>&nbsp;<span style="font-weight:bold;">&gt;</span>&nbsp;'.$imagecategory->getVar('imgcat_name').'<br /><br />';
        $form->display();
    }
    echo '</div>';
    //edit category - end

    //create Image - start
    if ( $isadmin || !empty($catwritelist)) {
        echo '<div id="loadimage_panel" class="panel" style="overflow:auto;">';
        $form = new XoopsThemeForm(_NIM_ADDIMAGE, 'image_form', '' . $current_file . '?target=' . $target . '', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(_IMAGENAME, 'image_nicename', 50, 255), true);
        $select = new XoopsFormSelect(_IMAGECAT, 'imgcat_id');
        if ($isadmin) {
            $select->addOptionArray($imgcat_handler->getList());
        } else {
            $select->addOptionArray($catwritelist);
        }
        $form->addElement($select, true);
        $form->addElement(new XoopsFormFile(_NIM_IMAGEFILE, 'image_file', 5000000));
        $form->addElement(new XoopsFormText(_NIM_IMGWEIGHT, 'image_weight', 3, 4, 0));
        $form->addElement(new XoopsFormRadioYN(_IMGDISPLAY, 'image_display', 1, _YES, _NO));
        $form->addElement(new XoopsFormHidden('op', 'addfile'));
        $form->addElement(new XoopsFormButton('', 'img_button', _SUBMIT, 'submit'));
        $form->display();
        echo '</div>';
    }
    //create Image - end

    //create Category - start
    if ( $isadmin ) {
        echo '<div id="createcategory_panel" class="panel" style="overflow:auto;">';
          $form = new XoopsThemeForm(_NIM_ADDIMGCAT, 'imagecat_form', ''.$current_file . "?target=" . $target . "&amp;return_mode=" . $return_mode.'', 'post', true);
        $form->addElement(new XoopsFormText(_NIM_IMGCATNAME, 'imgcat_name', 50, 255), true);
        $form->addElement(new XoopsFormSelectGroup(_NIM_IMGCATRGRP, 'readgroup', true, XOOPS_GROUP_ADMIN, 5, true));
        $form->addElement(new XoopsFormSelectGroup(_NIM_IMGCATWGRP, 'writegroup', true, XOOPS_GROUP_ADMIN, 5, true));
        $form->addElement(new XoopsFormText(_NIM_IMGMAXSIZE, 'imgcat_maxsize', 10, 10, 50000));
        $form->addElement(new XoopsFormText(_NIM_IMGMAXWIDTH, 'imgcat_maxwidth', 3, 4, 120));
        $form->addElement(new XoopsFormText(_NIM_IMGMAXHEIGHT, 'imgcat_maxheight', 3, 4, 120));
        $form->addElement(new XoopsFormText(_NIM_IMGCATWEIGHT, 'imgcat_weight', 3, 4, 0));
        $form->addElement(new XoopsFormRadioYN(_NIM_IMGCATDISPLAY, 'imgcat_display', 1, _YES, _NO));
            $storetype = new XoopsFormRadio(_NIM_IMGCATSTRTYPE, 'imgcat_storetype', 'file');
            $storetype->setDescription('<span style="color:#ff0000;">'._NIM_IMGCATSTRTYPE_DESC.'</span>');
            $storetype->addOptionArray(array('file' => sprintf(_NIM_ASFILE, XOOPS_URL . '/uploads/newimagemanager/uploaded/foldername'), 'db' => _NIM_INDB));
            $storetype->setExtra('onchange="if (this.value == \'file\'){document.getElementById(\'imgcat_relativepath\').disabled = false;}else{document.getElementById(\'imgcat_relativepath\').value = \'\';document.getElementById(\'imgcat_relativepath\').disabled = true;}"');
        $form->addElement($storetype);
            $fname = new XoopsFormText(_NIM_FOLDERNAME, 'imgcat_relativepath', 50, 255, '');
            $fname->setDescription('<span style="color:#ff0000;">'._NIM_FOLDERNAME_DESC1.'<br />'._NIM_FOLDERNAME_DESC2.'</span>');
            $js = 'var fname = document.getElementById("imgcat_relativepath");';
            $js .= 'if (fname.disabled == false && fname.value == ""){alert("'.sprintf( _FORM_ENTER, _NIM_FOLDERNAME ).'"); return false;}';
            $fname->customValidationCode[] = $js;
        $form->addElement($fname,true);
     $form->addElement(new XoopsFormHidden('op', 'addcat'));
        $form->addElement(new XoopsFormButton('', 'imgcat_button', _SUBMIT, 'submit'));
        $form->display();
        echo '<input type="button" id="cancel" name="cancel" value="_CANCEL" onclick="javascript:window.close();" />';
        echo '</div>';
    }
    //create Category - end

echo '</div>';
xoops_footer();
?>