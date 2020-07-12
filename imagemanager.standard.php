<?php
defined('DS') or define('DS', DIRECTORY_SEPARATOR);
defined('NWLINE')or define('NWLINE', "\n");
// get current filename
$current_file = basename(__FILE__);

include_once '..' . DS . '..' . DS . 'mainfile.php';

xoops_load('xoopsformloader');
xoops_load('xoopsmediauploader');
include_once XOOPS_ROOT_PATH . DS . 'class' . DS . 'template.php';

// Include language file
xoops_loadLanguage('main', 'newimagemanager');

defined('MODULE_UPLOAD_PATH')or define('MODULE_UPLOAD_PATH', XOOPS_ROOT_PATH . DS . 'uploads' . DS . 'newimagemanager');



if (!isset($_REQUEST['target'])) {
    exit('Target not set');
}

$target = $_REQUEST['target'];
$op = 'list'; // default
if (isset($_GET['op']) && $_GET['op'] == 'upload') {
    $op = 'upload';
} elseif (isset($_POST['op']) && $_POST['op'] == 'doupload') {
    $op = 'doupload';
}

if (!is_object($xoopsUser)) {
    $group = array(XOOPS_GROUP_ANONYMOUS);
} else {
    $group = $xoopsUser->getGroups();
}

switch ($op) {
    case 'list' :
        $xoopsTpl = new XoopsTpl();
        $xoopsTpl->assign('lang_imgmanager', _NIM_IMGMANAGER);
        $xoopsTpl->assign('current_file', $current_file);
        $xoopsTpl->assign('sitename', htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES));
        $target = htmlspecialchars($target, ENT_QUOTES);
        $xoopsTpl->assign('target', $target);
        $imgcat_handler =& xoops_getModuleHandler('newimagecategory', 'newimagemanager');
        $catlist =& $imgcat_handler->getList($group, 'imgcat_read', 1);
        $catcount = count($catlist);
        $xoopsTpl->assign('lang_align', _ALIGN);
        $xoopsTpl->assign('lang_add', _ADD);
        $xoopsTpl->assign('lang_close', _CLOSE);
        if ($catcount > 0) {
            $xoopsTpl->assign('lang_go', _GO);
            $catshow = (!isset($_GET['cat_id'])) ? 0 : intval($_GET['cat_id']);
            $catshow = (!empty($catshow) && in_array($catshow, array_keys($catlist))) ? $catshow : 0;
            $xoopsTpl->assign('show_cat', $catshow);
            if ($catshow > 0) {
                $xoopsTpl->assign('lang_addimage', _NIM_ADDIMAGE);
            }
            $catlist = array('0' => '--') + $catlist;
            $cat_options = '';
            foreach ($catlist as $c_id => $c_name) {
                $sel = '';
                if ($c_id == $catshow) {
                    $sel = ' selected="selected"';
                }
                $cat_options .= '<option value="' . $c_id . '"' . $sel . '>' . $c_name . '</option>';
            }
            $xoopsTpl->assign('cat_options', $cat_options);
            if ($catshow > 0) {
                $image_handler =& xoops_getModuleHandler('newimage', 'newimagemanager');
                $criteria = new CriteriaCompo(new Criteria('imgcat_id', $catshow));
                $criteria->add(new Criteria('image_display', 1));
                $total = $image_handler->getCount($criteria);
                if ($total > 0) {
                    $imgcat_handler =& xoops_getModuleHandler('newimagecategory', 'newimagemanager');
                    $imgcat =& $imgcat_handler->get($catshow);
                    $xoopsTpl->assign('image_total', $total);
                    $xoopsTpl->assign('lang_image', _NIM_IMAGE);
                    $xoopsTpl->assign('lang_imagename', _NIM_IMAGENAME);
                    $xoopsTpl->assign('lang_imagealternative', _NIM_IMAGEALTERNATIVE);
                    $xoopsTpl->assign('lang_imagemime', _NIM_IMAGEMIME);
                    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
                    $criteria->setLimit(10);
                    $criteria->setStart($start);
                    $storetype = $imgcat->getVar('imgcat_storetype');
                    if ($storetype == 'db') {
                        $images = $image_handler->getObjects($criteria, false, true);
                    } else {
                        $images = $image_handler->getObjects($criteria, false, false);
                    }
                    $imgcount = count($images);
                    $max = ($imgcount > 10) ? 10 : $imgcount;

                    for ($i = 0; $i < $max; $i++) {

                        $lcode = '[img align=left id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                        $code  = '[img align=center id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                        $rcode = '[img align=right id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                        $src   = XOOPS_URL . "/image.php?id=" . $images[$i]->getVar('image_id') . "&amp;width=100";
/*
                        if ($storetype == 'db') {
                            $lcode = '[img align=left id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                            $code  = '[img align=center id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                            $rcode = '[img align=right id=' . $images[$i]->getVar('image_id') . ']' . $images[$i]->getVar('image_nicename') . '[/img]';
                            $src   = XOOPS_URL . "/image.php?id=" . $images[$i]->getVar('image_id') . "&amp;width=100";
                        } else {
                            $lcode = '[img align=left]' . XOOPS_UPLOAD_URL . '/newimagemanager/uploaded/' . $images[$i]->getVar('image_name') . '[/img]';
                            $code  = '[img align=center]' . XOOPS_UPLOAD_URL . '/newimagemanager/uploaded/' . $images[$i]->getVar('image_name') . '[/img]';
                            $rcode = '[img align=right]' . XOOPS_UPLOAD_URL . '/newimagemanager/uploaded/' . $images[$i]->getVar('image_name') . '[/img]';
                            $src   = XOOPS_URL . "/image.php?id=" . $images[$i]->getVar('image_id') . "&amp;width=100";
                        }
*/
                        $xoopsTpl->append('images', array('id' => $images[$i]->getVar('image_id'), 'nicename' => $images[$i]->getVar('image_nicename'), 'alternative' => $images[$i]->getVar('image_alternative'), 'mimetype' => $images[$i]->getVar('image_mimetype'), 'src' => $src, 'lxcode' => $lcode, 'xcode' => $code, 'rxcode' => $rcode));
                    }
                    if ($total > 10) {
                        include_once $GLOBALS['xoops']->path('class/pagenav.php');
                        $nav = new XoopsPageNav($total, 10, $start, 'start', 'target='.$target.'&amp;cat_id='.$catshow);
                        $xoopsTpl->assign('pagenav', $nav->renderNav());
                    }
                } else {
                    $xoopsTpl->assign('image_total', 0);
                }
            }
            $xoopsTpl->assign('xsize', 800);
            $xoopsTpl->assign('ysize', 600);
        } else {
            $xoopsTpl->assign('xsize', 800);
            $xoopsTpl->assign('ysize', 600);
        }
        $xoopsTpl->display('db:newimagemanager_imagemanager.standard.list.html');
        exit();
        break;

    case 'upload' :
        $imgcat_handler =& xoops_getModuleHandler('newimagecategory', 'newimagemanager');
        $imgcat_id = intval($_GET['imgcat_id']);
        $imgcat =& $imgcat_handler->get($imgcat_id);
        $error = false;
        if (!is_object($imgcat)) {
            $error = true;
        } else {
            $imgcatperm_handler =& xoops_gethandler('groupperm');
            if (is_object($xoopsUser)) {
                if (! $imgcatperm_handler->checkRight('newimagemanager_cat_write', $imgcat_id, $xoopsUser->getGroups())) {
                    $error = true;
                }
            } else {
                if (! $imgcatperm_handler->checkRight('newimagemanager_cat_write', $imgcat_id, XOOPS_GROUP_ANONYMOUS)) {
                    $error = true;
                }
            }
        }
        if ($error != false) {
            xoops_header(false);
            echo '</head><body><div style="text-align:center;"><input value="'._BACK.'" type="button" onclick="javascript:history.go(-1);" /></div>';
            xoops_footer();
            exit();
        }
        $xoopsTpl = new XoopsTpl();
        $xoopsTpl->assign('show_cat', $imgcat_id);
        $xoopsTpl->assign('lang_imgmanager', _IMGMANAGER);
        $xoopsTpl->assign('current_file', $current_file);
        $xoopsTpl->assign('sitename', htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES));
        $xoopsTpl->assign('target', htmlspecialchars($_GET['target'], ENT_QUOTES));
        $form = new XoopsThemeForm('', 'image_form', $current_file, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(_NIM_IMAGENAME, 'image_nicename', 20, 255), true);
        $form->addElement(new XoopsFormText(_NIM_IMAGEALTERNATIVE, 'image_alternative', 20, 255), true);
        /* IN PROGRESS
            $image_descriptionTextArea = new XoopsFormTextArea(_NIM_IMAGEDESCRIPTION, 'image_description', '', 5, 100);
            $image_descriptionTextArea->setDescription (_NIM_IMAGEDESCRIPTION_DESC);
        $form->addElement($image_descriptionTextArea);
        */
        $form->addElement(new XoopsFormLabel(_NIM_IMAGECAT, $imgcat->getVar('imgcat_name')));
        $form->addElement(new XoopsFormFile(_NIM_IMAGEFILE, 'image_file', $imgcat->getVar('imgcat_maxsize')), true);
        $form->addElement(new XoopsFormText(_NIM_IMGWEIGHT, 'image_weight', 3, 4, 0));
        $form->addElement(new XoopsFormLabel(_NIM_IMGMAXSIZE, $imgcat->getVar('imgcat_maxsize')));
        $form->addElement(new XoopsFormLabel(_NIM_IMGMAXWIDTH, $imgcat->getVar('imgcat_maxwidth')));
        $form->addElement(new XoopsFormLabel(_NIM_IMGMAXHEIGHT, $imgcat->getVar('imgcat_maxheight')));
        $form->addElement(new XoopsFormHidden('imgcat_id', $imgcat_id));
        $form->addElement(new XoopsFormHidden('op', 'doupload'));
        $form->addElement(new XoopsFormHidden('target', $target));
        $form->addElement(new XoopsFormButton('', 'img_button', _SUBMIT, 'submit'));
        $form->assign($xoopsTpl);
        $xoopsTpl->assign('lang_close', _CLOSE);
        $xoopsTpl->assign('xsize', 800);
        $xoopsTpl->assign('ysize', 600);
        $xoopsTpl->display('db:newimagemanager_imagemanager.standard.upload.html');
        exit();
        break;

    case 'doupload' :
        if ($GLOBALS['xoopsSecurity']->check()) {
            $image_nicename = isset($_POST['image_nicename']) ? $_POST['image_nicename'] : '';
            $image_alternative = isset($_POST['image_alternative']) ? $_POST['image_alternative'] : '';
            $xoops_upload_file = isset($_POST['xoops_upload_file']) ? $_POST['xoops_upload_file'] : array();
            $imgcat_id = isset($_POST['imgcat_id']) ? intval($_POST['imgcat_id']) : 0;
            $imgcat_handler =& xoops_getModuleHandler('newimagecategory', 'newimagemanager');
            $imagecategory =& $imgcat_handler->get($imgcat_id);
            $error = false;
            if (!is_object($imagecategory)) {
                $error = true;
            } else {
                $imgcatperm_handler =& xoops_gethandler('groupperm');
                if (is_object($xoopsUser)) {
                    if (!$imgcatperm_handler->checkRight('newimagemanager_cat_write', $imgcat_id, $xoopsUser->getGroups())) {
                        $error = true;
                    }
                } else {
                    if (!$imgcatperm_handler->checkRight('newimagemanager_cat_write', $imgcat_id, XOOPS_GROUP_ANONYMOUS)) {
                        $error = true;
                    }
                }
            }
        }
        else {
            $error = true;
        }
        if ($error != false) {
            xoops_header(false);
            echo '</head><body><div style="text-align:center;">' . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()) . '<br /><input value="' . _BACK . '" type="button" onclick="javascript:history.go(-1);" /></div>';
            xoops_footer();
            exit();
        }
        $mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/bmp');
        $uploader = new XoopsMediaUploader(MODULE_UPLOAD_PATH . DS . 'uploaded'. DS . $imagecategory->getVar('imgcat_relativepath'), $mimetypes, $imagecategory->getVar('imgcat_maxsize'), $imagecategory->getVar('imgcat_maxwidth'), $imagecategory->getVar('imgcat_maxheight'));
        $uploader->setPrefix('img');
        $errors = array();
        $ucount = count($_POST['xoops_upload_file']);
        if ($uploader->fetchMedia($xoops_upload_file[0])) {
            if (!$uploader->upload()) {
                $err = $uploader->getErrors();
            } else {
                $image_handler =& xoops_getModuleHandler('newimage', 'newimagemanager');
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
                    $err = sprintf(_FAILSAVEIMG, $image->getVar('image_nicename'));
                }
            }
        } else {
            $err = sprintf(_FAILFETCHIMG, 0);
            $err .= '<br />' . implode('<br />', $uploader->getErrors(false));
        }
        if (isset($err)) {
            xoops_header(false);
            xoops_error($err);
            echo '</head><body><div style="text-align:center;"><input value="' . _BACK . '" type="button" onclick="javascript:history.go(-1);" /></div>';
            xoops_footer();
            exit();
        }
        header('location: ' . $current_file . '?cat_id=' . $imgcat_id . '&target=' . $target);
        break;
}
?>