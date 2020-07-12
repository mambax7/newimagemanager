<?php
include 'admin_header.php';

// Call Admin Header
switch ( $op ) {
    case 'list' :
    case 'category.list':
    case 'image.delete' :
    case 'image.edit' :
    case 'image.edit_update' :
    case 'image.edit_save_as_form' :
    case 'image.clone_form' :
    case 'category.edit_form' :
    case 'category.delete' :
        xoops_cp_header();
        if ( !is_readable(XOOPS_ROOT_PATH . "/Frameworks/art/functions.admin.php"))	{
            newimagemanager_adminmenu(2, _NIM_MI_ADMENU_IMAGES);
        } else {
            include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
            loadModuleAdminMenu (2, _NIM_MI_ADMENU_IMAGES);
        }
        break;
    }



// COMMON ADMIN CONTENT - start --------------------------------------------------------------------



// init
// get current filename
$current_file = basename(__FILE__);
// load classes
xoops_load('xoopsformloader');
// get handlers
$imgcat_handler = xoops_getModuleHandler('newimagecategory', 'newimagemanager');
$image_handler =& xoops_getModuleHandler('newimage', 'newimagemanager');
$gperm_handler =& xoops_gethandler('groupperm');
$errors = array();
$output = '';

// Get user groups array
$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
// $admin is true if user is an admin
$admin = (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) ? true : false;



// get permissions
// get READ/WRITE/EDIT permissions by category
if ( isset($imgcat_id) ) {
    if (!$admin) {
        $perm_imgcat_read = ($gperm_handler->checkRight( 'newimagemanager_cat_read', $imgcat_id, $groups, $xoopsModule->mid())) ? true : false;
        $perm_imgcat_write = ($gperm_handler->checkRight( 'newimagemanager_cat_write', $imgcat_id, $groups, $xoopsModule->mid())) ? true : false;
        $perm_imgcat_edit = ($gperm_handler->checkRight( 'newimagemanager_cat_edit', $imgcat_id, $groups, $xoopsModule->mid())) ? true : false;
    } else {
        $perm_imgcat_read = true;
        $perm_imgcat_write = true;
        $perm_imgcat_edit = true;
    }
}
// get OTHERS permissions
$perm_4 = ($gperm_handler->checkRight('newimagemanager_ac', 4, $groups, $xoopsModule->getVar('mid'))) ? true : false;
$perm_8 = ($gperm_handler->checkRight('newimagemanager_ac', 8, $groups, $xoopsModule->getVar('mid'))) ? true : false;
$perm_16 = ($gperm_handler->checkRight('newimagemanager_ac', 16, $groups, $xoopsModule->getVar('mid'))) ? true : false;
$perm_32 = ($gperm_handler->checkRight('newimagemanager_ac', 32, $groups, $xoopsModule->getVar('mid'))) ? true : false;



// check permissions
if (!$admin) {
    // check READ permissions before continue
    if ( $op == 'category.list' ) {
        if (!$perm_imgcat_read && !$perm_imgcat_write) {
            redirect_header($current_file, 1);
        }
    }
    // check WRITE permissions before continue
    if ( $op == 'image.add' || $op == 'category.edit_form' || $op == 'category.edit' || $op == 'category.delete_ok' || $op == 'category.delete' ) {
        if (!$perm_imgcat_write) {
            redirect_header($current_file, 1);
        }
    }
    // check EDIT permissions before continue
    if ( $op == 'image.edit' || $op == 'image.edit_update' || $op == 'image.edit_save_as_form') {
        if (!$perm_imgcat_edit) {
            redirect_header($current_file, 1);
        }
    }
    // Only administator can delete categories or images
    if ($op == 'image.delete' || $op == 'image.delete_ok' || $op == 'category.delete_ok' || $op == 'category.delete') {
        redirect_header($current_file, 1);
    }
}



switch ( $op ) {
// DEFAULT -----------------------------------------------------------------------------------------
    case 'list':
        $output.= '<a href="' . $current_file . '">'. _NIM_AM_IMGMAIN .'</a><br /><br />';
        // list categories
        $imagecategories = $imgcat_handler->getObjects();
        $catcount = count($imagecategories);
        if ($catcount > 0) {
            $form = new XoopsThemeForm(_NIM_AM_IMGCAT, 'cat_list', $current_file, 'post', true);
            for ($i = 0; $i < $catcount; $i++) {
                $perm_imgcat_read = $gperm_handler->checkRight( 'newimagemanager_cat_read', $imagecategories[$i]->getVar('imgcat_id'), $groups, $xoopsModule->mid() );
                $perm_imgcat_write = $gperm_handler->checkRight( 'newimagemanager_cat_write', $imagecategories[$i]->getVar('imgcat_id'), $groups, $xoopsModule->mid() );
                if ( $perm_imgcat_read || $perm_imgcat_write ) {
                    $count = $image_handler->getCount(new Criteria('imgcat_id', $imagecategories[$i]->getVar('imgcat_id')));
                        $buttonTray = new XoopsFormElementTray ($imagecategories[$i]->getVar('imgcat_name'));
                            $imgcat_info = array();
                            $imgcat_info[] = '(' . sprintf(_NIM_AM_NUMIMAGES, '<strong>' . $count . '</strong>') . ')';

                            $storetype = $imagecategories[$i]->getVar('imgcat_storetype');
                            if ($storetype == 'db')
                                $imgcat_info[] = _NIM_AM_INDB;
                            else
                                $imgcat_info[] = sprintf(_NIM_AM_ASFILE, XOOPS_ROOT_PATH . '/' . $imagecategories[$i]->getVar('imgcat_relativepath') . '/');
                            $imgcat_info[] = _NIM_AM_MIMETYPES . ' ' . $imagecategories[$i]->getVar('imgcat_mimetypes');
                            $buttonTray->setDescription(implode('<br />', $imgcat_info));
                            $buttonList = new XoopsFormButton ('', _LIST, _LIST, "button");
                            $buttonList->setExtra('onclick="location.href=\'' . $current_file . '?op=category.list&amp;imgcat_id='.$imagecategories[$i]->getVar('imgcat_id').'\'"');
                        $buttonTray->addElement($buttonList);
                            unset($buttonList);
                    if (in_array(XOOPS_GROUP_ADMIN, $groups)) {
                            $buttonEdit = new XoopsFormButton ('', _EDIT, _EDIT, "button");
                            $buttonEdit->setExtra('onclick="location.href=\'' . $current_file . '?op=category.edit_form&amp;imgcat_id='.$imagecategories[$i]->getVar('imgcat_id').'\'"');
                        $buttonTray->addElement($buttonEdit);
                            unset($buttonEdit);
                        }
                    if (in_array(XOOPS_GROUP_ADMIN, $groups) && ($imagecategories[$i]->getVar('imgcat_type') == 'C')) {
                            $buttonDelete = new XoopsFormButton ('', _DELETE, _DELETE, "button");
                            $buttonDelete->setExtra('onclick="location.href=\'' . $current_file . '?op=category.delete&amp;imgcat_id='.$imagecategories[$i]->getVar('imgcat_id').'\'"');
                        $buttonTray->addElement($buttonDelete);
                            unset($buttonDelete);
                    }
                    $form->addElement($buttonTray);
                    unset($buttonTray);
                }
            }
            $output.= $form->render();
        }

        // create new image
        $imagecategories = $imgcat_handler->getObjects();
        $catcount = count($imagecategories);
        if (!empty($catcount)) {
            $form = new XoopsThemeForm(_NIM_AM_ADDIMAGE, 'image_form', $current_file, 'post', true);
            $form->setExtra('enctype="multipart/form-data"');
            $form->addElement(new XoopsFormText(_NIM_AM_IMAGENAME, 'image_nicename', 50, 255), true);
            $form->addElement(new XoopsFormText(_NIM_AM_IMAGEALTERNATIVE, 'image_alternative', 50, 255), true);
                $image_descriptionTextArea = new XoopsFormTextArea(_NIM_AM_IMAGEDESCRIPTION, 'image_description', '', 5, 100);
                $image_descriptionTextArea->setDescription (_NIM_AM_IMAGEDESCRIPTION_DESC);
            $form->addElement($image_descriptionTextArea);
            $select = new XoopsFormSelect(_NIM_AM_IMAGECAT, 'imgcat_id');
            $select->addOptionArray($imgcat_handler->getList($groups, 'newimagemanager_cat_write'));
            $form->addElement($select, true);
                $image_fileFile = new XoopsFormFile(_NIM_AM_IMAGEFILE, 'image_file', 5000000);
                $image_fileFile->setDescription (_NIM_AM_IMAGEFILE_DESC);
            $form->addElement($image_fileFile);
            $form->addElement(new XoopsFormText(_NIM_AM_IMGWEIGHT, 'image_weight', 3, 4, 0));
            $form->addElement(new XoopsFormRadioYN(_NIM_AM_IMGDISPLAY, 'image_display', 1, _YES, _NO));
            $form->addElement(new XoopsFormHidden('op', 'image.add'));
            $form->addElement(new XoopsFormHidden('fct', 'images'));
            $form->addElement(new XoopsFormButton('', 'img_button', _SUBMIT, 'submit'));
            $output.= $form->render();
        }

        // create new category
        if (in_array(XOOPS_GROUP_ADMIN, $groups)) {
            $form = new XoopsThemeForm(_NIM_AM_ADDIMGCAT, 'imagecat_form', $current_file, 'post', true);
            $form->addElement(new XoopsFormText(_NIM_AM_IMGCATNAME, 'imgcat_name', 50, 255), true);
            $form->addElement(new XoopsFormSelectGroup(_NIM_AM_IMGCATRGRP, 'readgroup', true, XOOPS_GROUP_ADMIN, 5, true));
            $form->addElement(new XoopsFormSelectGroup(_NIM_AM_IMGCATWGRP, 'writegroup', true, XOOPS_GROUP_ADMIN, 5, true));

                $image_mimetypes = new XoopsFormTextArea(_NIM_AM_MIMETYPES, 'imgcat_mimetypes', 'image/gif;image/jpeg;image/pjpeg;image/x-png;image/png;image/bmp', 5, 100);
                $image_mimetypes->setDescription (_NIM_AM_MIMETYPES_DESC);
            $form->addElement($image_mimetypes);

            $form->addElement(new XoopsFormText(_NIM_AM_IMGMAXSIZE, 'imgcat_maxsize', 10, 10, 50000));
            $form->addElement(new XoopsFormText(_NIM_AM_IMGMAXWIDTH, 'imgcat_maxwidth', 3, 4, 120));
            $form->addElement(new XoopsFormText(_NIM_AM_IMGMAXHEIGHT, 'imgcat_maxheight', 3, 4, 120));

            $form->addElement(new XoopsFormText(_NIM_AM_IMGCATWEIGHT, 'imgcat_weight', 3, 4, 0));
            $form->addElement(new XoopsFormRadioYN(_NIM_AM_IMGCATDISPLAY, 'imgcat_display', 1, _YES, _NO));

                $storetype = new XoopsFormRadio(_NIM_AM_IMGCATSTRTYPE, 'imgcat_storetype', 'file', '<br />');
                $storetype->setDescription('<span style="color:#ff0000;">'._NIM_AM_IMGCATSTRTYPE_DESC.'</span>');
                $storetype->addOptionArray(array('file' => sprintf(_NIM_AM_ASFILE, NIM_MODULE_UPLOAD_URL . '/uploaded' . '/foldername'), 'db' => _NIM_AM_INDB)); // in progress
                $storetype->setExtra('onchange="if (this.value == \'file\'){document.getElementById(\'imgcat_relativepath\').disabled = false;}else{document.getElementById(\'imgcat_relativepath\').value = \'\';document.getElementById(\'imgcat_relativepath\').disabled = true;}"');
            $form->addElement($storetype);
            // folder name... 
                $fname = new XoopsFormText(_NIM_AM_FOLDERNAME, 'imgcat_relativepath', 50, 255, '');
                $fname->setDescription('<span style="color:#ff0000;">'._NIM_AM_FOLDERNAME_DESC1.'<br />'._NIM_AM_FOLDERNAME_DESC2.'</span>');
                $js = 'var fname = document.getElementById("imgcat_relativepath");';
                $js .= 'if (fname.disabled == false && fname.value == ""){alert("'.sprintf( _FORM_ENTER, _NIM_AM_FOLDERNAME ).'"); return false;}';
                $fname->customValidationCode[] = $js;
            $form->addElement($fname,true);

            $form->addElement(new XoopsFormHidden('op', 'category.add'));
            $form->addElement(new XoopsFormHidden('fct', 'images'));
            $form->addElement(new XoopsFormButton('', 'imgcat_button', _SUBMIT, 'submit'));
            $output.= $form->render();
        }
        echo $output;
        break;

// LIST IMAGES IN A CATEGORY -----------------------------------------------------------------------
    case 'category.list':
        // get/check parameters
        $imgcat_id = intval($imgcat_id);
        if ($imgcat_id <= 0) {redirect_header($current_file, 1);}

        $perm_imgcat_write = $gperm_handler->checkRight( 'newimagemanager_cat_write', $imgcat_id, $groups, $xoopsModule->mid() );

        // load category object
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}

        $output.= '<a href="' . $current_file . '">' . _NIM_AM_IMGMAIN . '</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;' . $imagecategory->getVar('imgcat_name') . '<br /><br />';
        $criteria = new Criteria('imgcat_id', $imgcat_id);
        $imgcount = $image_handler->getCount($criteria);
        $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
        $criteria->setStart($start);
        $criteria->setLimit(20);
        $images = $image_handler->getObjects($criteria, true, true); // LENTO SE UTILIZZA DATABASE? COME POSSO VELOCIZZARE!

        if ($perm_imgcat_write) {
            $output.= '<form action="' . $current_file . '" method="post">';
        }
        foreach (array_keys($images) as $i) {

            // check if image stored in db/as file - start
            $storetype = $imagecategory->getVar('imgcat_storetype');
            if ($storetype == 'db') {
                $image_src = XOOPS_URL . '/modules/newimagemanager/image.php?id=' . $i . '&amp;nocache';
                $image_path = XOOPS_ROOT_PATH . '/modules/newimagemanager/image.php?id=' . $i . '&amp;nocache';
            } else {
                $image_src = XOOPS_URL . '/' . $imagecategory->getVar('imgcat_relativepath') . '/' . $images[$i]->getVar('image_name');
                $image_path = XOOPS_ROOT_PATH . '/' . $imagecategory->getVar('imgcat_relativepath') . '/' . $images[$i]->getVar('image_name');
            }
            // check if image stored in db/as file - end
            $imagefile_size = $images[$i]->image_size();
            $image_width = $images[$i]->image_width();
            $image_height = $images[$i]->image_height();

            // set right output image width - start
            $max_image_width = 150; // IN PROGRESS
            $output_image_width = ($image_width > $max_image_width) ? $max_image_width : $image_width;
            // set right image width - end


            $rowspan = ($perm_imgcat_write) ? 11 : 5;
            $output.= '<table width="100%" class="outer">';
            $output.= '<tr><td width="30%" rowspan="' . $rowspan . '">';
            $output.= '<img style="width:' . $output_image_width . 'px;height:auto;"';
            $output.= ' id="imageid' . $images[$i]->getVar('image_id') . '"';
            $output.= ' src="' . $image_src . '"';
            $output.= ' alt="' . $images[$i]->getVar('image_alternative', 'E') . '"';
            $output.= ' title="' . $images[$i]->getVar('image_nicename', 'E') . '"';
            $output.= ' onclick="javascript:alert(\'ZOOM IN PROGRESS\');"'; // IN PROGRESS
            $output.= ' />';
            $output.= '</td>';

            $output.= '<td class="head">' . _NIM_AM_IMAGENAME . '</td><td class="odd">';
            if ($perm_imgcat_write) {
                $output.= '<input type="hidden" name="image_id[]" value="' . $i . '" /><input type="text" name="image_nicename[]" value="' . $images[$i]->getVar('image_nicename', 'E') . '" size="20" maxlength="255" />';
            } else {
                $output.= '<strong>' . $images[$i]->getVar('image_nicename') . '</strong>';
            }
            $output.= '</td></tr>';

            $output.= '<tr><td class="head">' . _NIM_AM_IMAGEALTERNATIVE . '</td><td class="even">';
            if ($perm_imgcat_write) {
                $output.= '<input type="text" name="image_alternative[]" value="' . $images[$i]->getVar('image_alternative', 'E') . '" size="20" maxlength="255" />';
            } else {
                $output.= '<strong>' . $images[$i]->getVar('image_alternative') . '</strong>';
            }
            $output.= '</td></tr>';

            $output.= '<tr><td class="head">' . _NIM_AM_IMAGEDESCRIPTION . '</td><td class="odd">';
            if ($perm_imgcat_write) {
                $output.= '<textarea type="text" name="image_description[]" cols="50" rows="5" />';
                $output.= $images[$i]->getVar('image_description', 'E');
                $output.= '</textarea>';
            } else {
                $output.= '<strong>' . $images[$i]->getVar('image_description') . '</strong>';
            }
            $output.= '</td></tr>';

            $output.= '<tr><td class="head">' . _NIM_AM_IMAGEID . '</td><td class="even">' . $images[$i]->getVar('image_id') . '</td></tr>';
            //$output.= '<tr><td class="head">' . _NIM_AM_IMAGEDATE . '</td><td class="odd">' . gmdate(_NIM_DATESTRING, $images[$i]->getVar('image_created')) . '</td></tr>';
            $output.= '<tr><td class="head">' . _NIM_AM_IMAGEDATE . '</td><td class="odd">' . formatTimestamp(xoops_getUserTimestamp($images[$i]->getVar('image_created')), _NIM_AM_DATESTRING) . '</td></tr>'; // IN PROGRESS
            $output.= '<tr><td class="head">' . _NIM_AM_IMAGEMIME . '</td><td class="even">' . $images[$i]->getVar('image_mimetype') . '</td></tr>';
            $output.= '<tr><td class="head">' . _NIM_AM_IMAGESIZE . '</td><td class="odd">' . sprintf(_NIM_AM_IMAGESIZE_CONTENT, $image_width, $image_height, $imagefile_size) . '</td></tr>';

            if ($perm_imgcat_write) {
                $output.= '<tr><td class="head">' . _NIM_AM_IMAGECAT . '</td><td class="even">';
                $output.= '<select name="imgcat_id[]" size="1">';
                $list =& $imgcat_handler->getList($groups, 'newimagemanager_cat_write', null, $imagecategory->getVar('imgcat_storetype'));
                foreach ($list as $value => $name) {
                    $sel = '';
                    if ($value == $images[$i]->getVar('imgcat_id')) {
                        $sel = ' selected="selected"';
                    }
                    $output.= '<option value="'.$value.'"'.$sel.'>'.$name.'</option>';
                }
                $output.= '</select></td></tr>';

                $output.= '<tr><td class="head">' . _NIM_AM_IMGWEIGHT . '</td><td class="odd"><input type="text" name="image_weight[]" value="' . $images[$i]->getVar('image_weight') . '" size="3" maxlength="4" /></td></tr>';
                $output.= '<tr><td class="head">' . _NIM_AM_IMGDISPLAY . '</td><td class="even"><input type="checkbox" name="image_display[]" value="1"';
                if ($images[$i]->getVar('image_display') == 1) {
                    $output.= ' checked="checked"';
                }
                $output.= ' /></td></tr>';
            }
            if (in_array(XOOPS_GROUP_ADMIN, $groups)) {
                $options = array();
                $options[] = '<a href="' . $current_file . '?op=image.delete&amp;image_id='.$i.'">' . _DELETE . '</a>';
                $options[] = '<a href="' . $current_file . '?op=image.edit&amp;image_id=' . $i . '">' . _EDIT . '</a>';
                $options[] = '<a href="' . $current_file . '?op=image.clone_form&amp;image_id='.$i.'">' . _CLONE . '</a>';
                // IN PROGRESS //$options[] = '<a href="' . $current_file . '?op=watermark_img&amp;image_id='.$i.'">' . _WATERMARK . '</a>'; // IN PROGRESS
                $option_bar = implode(' | ', $options);

                $output.= '<tr><td class="head">&nbsp;</td><td class="odd">';
                $output.= $option_bar;
                $output.= '</td></tr>';
            }
            $output.= '</table><br />';
        }
        if ($imgcount > 0) {
            if ($imgcount > 20) {
                xoops_load('pagenav.php');
                $nav = new XoopsPageNav($imgcount, 20, $start, 'start', 'fct=images&amp;op=category.list&amp;imgcat_id=' . $imgcat_id);
                $output.= '<div text-align="right">' . $nav->renderNav() . '</div>';
            }
            if ($perm_imgcat_write) {
                $output.= '<div style="text-align:center;">';
                $output.= '<input type="hidden" name="op" value="image.modify" />';
                $output.= '<input type="hidden" name="this_imgcat_id" value="' . $imgcat_id . '" />';
                $output.= '<input type="hidden" name="fct" value="images" />';
                $output.= $GLOBALS['xoopsSecurity']->getTokenHTML();
                $output.= '<input type="submit" name="submit" value="' . _SUBMIT . '" />';
                $output.= '</div>';
                $output.= '</form>';
            }
        }
        echo $output;
        break;

    case 'image.modify' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        $count = count($image_id);
        if ($count > 0) {
            for ($i = 0; $i < $count; $i++) {
                $image =& $image_handler->get($image_id[$i]);
                if (!is_object($image)) {
                    $errors[] = sprintf(_NIM_AM_FAILGETIMG, $image_id[$i]);
                    continue;
                }
                $image_display[$i] = empty($image_display[$i]) ? 0 : 1;
                $image->setVar('image_display', $image_display[$i]);
                $image->setVar('image_weight', $image_weight[$i]);
                $image->setVar('image_nicename', $image_nicename[$i]);
                $image->setVar('image_alternative', $image_alternative[$i]);
                $image->setVar('imgcat_id', $imgcat_id[$i]);
                $image->setVar('image_description', $image_description[$i]); // IN PROGRESS
                if (!$image_handler->insert($image)) {
                    $errors[] = sprintf(_NIM_AM_FAILSAVEIMG, $image_id[$i]);
                }
            }
            if (count($errors) > 0) {
                foreach ($errors as $error) {
                    echo $error . '<br />';
                }
                exit();
            }
        }
        redirect_header($current_file . "?op=category.list&amp;imgcat_id=" . $this_imgcat_id, 2, _NIM_AM_DBUPDATED);
        break;

    case 'image.add' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // get/check parameters
        $imgcat_id = intval($imgcat_id);
        if ($imgcat_id <= 0) {redirect_header($current_file, 1);}
        
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}

        $mimetypes = explode('|', $imagecategory->getVar('imgcat_mimetypes'));
        //$mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png', 'image/bmp');

        $uploader = new XoopsMediaUploader(XOOPS_ROOT_PATH .  '/' . $imagecategory->getVar('imgcat_relativepath'), $mimetypes, xoops_getModuleOption('upload_maxsize', 'newimagemanager'), xoops_getModuleOption('upload_maxwidth', 'newimagemanager'), xoops_getModuleOption('upload_maxheight', 'newimagemanager'));
       
        $uploader->setPrefix('img');
        $errors = array();
        $ucount = count($_POST['xoops_upload_file']);
        for ($i = 0; $i < $ucount; $i++) {
            if ($uploader->fetchMedia($_POST['xoops_upload_file'][$i])) {
                if (!$uploader->upload()) {
                    $errors[] = $uploader->getErrors();
                }
                else {
                    $image =& $image_handler->create();
                    $image->setVar('image_name', $uploader->getSavedFileName());
                    $image->setVar('image_nicename', $image_nicename);
                    $image->setVar('image_alternative', $image_alternative);
                    $image->setVar('image_mimetype', $uploader->getMediaType());
                    $image->setVar('image_created', time());
                    $image_display = empty($image_display) ? 0 : 1;
                    $image->setVar('image_display', $image_display);
                    $image->setVar('image_weight', $image_weight);
                    $image->setVar('imgcat_id', $imgcat_id);
                    $image->setVar('image_description', $image_description); // IN PROGRESS

                        // resize image when image is bigger than category sizes... ;-) 
                        require_once(XOOPS_ROOT_PATH . '/modules/newimagemanager/libs/wideimage/lib/WideImage.php');
                        $in = WideImage::load($uploader->getSavedDestination());
                        // Get the original geometry and calculate scales
                        $width = $in->getWidth();
                        $height = $in->getHeight();
                        $xscale = $width / $imagecategory->getVar('imgcat_maxwidth');
                        $yscale = $height / $imagecategory->getVar('imgcat_maxwidth');
                        // Recalculate new size with default ratio
                        if ($yscale > $xscale){
                            $new_width = round($width * (1/$yscale));
                            $new_height = round($height * (1/$yscale));
                        }
                        else {
                            $new_width = round($width * (1/$xscale));
                            $new_height = round($height * (1/$xscale));
                        }
                        // Resize the original image
                        $out = $in->resize($new_width, $new_height, 'fill');
                        $out->saveToFile($uploader->getSavedDestination());
                        $in->destroy();
                        $out->destroy();
                            
                    $fbinary = @file_get_contents($uploader->getSavedDestination());
                    if (strlen($fbinary) > $imagecategory->getVar('imgcat_maxsize')) {
                        $errors[] = sprintf(_FAILSAVEIMG . _IMAGESIZE_TO_BIG_FOR_CATEGORY, $image->getVar('image_nicename'));
                        break;
                    }
                    
                    $image->setVar('image_body', $fbinary, true);
                    @unlink($uploader->getSavedDestination());

                    if (!$image_handler->insert($image)) {
                        $errors[] = sprintf(_FAILSAVEIMG, $image->getVar('image_nicename'));
                    }
                }
            }
            else {
                $errors[] = sprintf(_FAILFETCHIMG, $i);
                $errors = array_merge($errors, $uploader->getErrors(false));
            }
        }
        if (count($errors) > 0) {
            // Call Header
            xoops_cp_header();
            xoops_error($errors);
            xoops_cp_footer();
            exit();
        }
        redirect_header($current_file, 2, _NIM_AM_DBUPDATED);
        
// DELETE AN IMAGE ---------------------------------------------------------------------------------
    case 'image.delete' :
        // get/check parameters
        $image_id = intval($image_id);
        if ($image_id <= 0) {redirect_header($current_file, 1);}

        xoops_confirm(array('op' => 'image.delete_ok', 'image_id' => $image_id, 'fct' => 'images'), $current_file, _NIM_AM_RUDELIMG);
        break;

    case 'image.delete_ok' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // get/check parameters
        $image_id = intval($image_id);
        if ($image_id <= 0) {redirect_header($current_file, 1);}

        // load image object
        $image =& $image_handler->get($image_id);
        if (!is_object($image)) {redirect_header($current_file, 1);}
        // load category object
        $imgcat_id = $image->getVar('imgcat_id');
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}
        // delete image from database
        if (!$image_handler->delete($image)) {
            xoops_error(sprintf(_NIM_AM_FAILDEL, $image->getVar('image_id')));
            exit();
        }
        redirect_header($current_file . "?op=category.list&amp;imgcat_id=" . $imgcat_id, 2, _NIM_AM_DBUPDATED);
        break;
    } // switch ( $op )



// EDIT AN IMAGE -----------------------------------------------------------------------------------
// paths for image editor... DO NOT CHANGE
$originalDirectory = NIM_MODULE_UPLOAD_PATH . '/imageeditor/original/';
$activeDirectory = NIM_MODULE_UPLOAD_PATH . '/imageeditor/active/';
$activeUrl = XOOPS_URL . '/uploads/newimagemanager/imageeditor/active/';
$editDirectory = NIM_MODULE_UPLOAD_PATH . '/imageeditor/edit/';
$undoDirectory = NIM_MODULE_UPLOAD_PATH . '/imageeditor/undo/';

switch ( $op ) {
    case 'image.edit' :
        // get/check parameters
        $image_id = intval($image_id);
        if ($image_id <= 0) {redirect_header($current_file, 1);}

        // load image object
        $image =& $image_handler->get($image_id);
        if (!is_object($image)) {
            redirect_header($current_file, 1);
        }

        // load category object
        $imgcat_id = $image->getVar('imgcat_id');
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {
            redirect_header($current_file, 1);
        }

        garbage_collection($originalDirectory);
        garbage_collection($activeDirectory);
        garbage_collection($editDirectory);
        garbage_collection($undoDirectory);

        // check if image stored in db/as file
        $storetype = $imagecategory->getVar('imgcat_storetype');

        $image_src = '' . XOOPS_URL . '/modules/newimagemanager/image.php?id=' . $image_id . '&amp;nocache';
        $image_path = '' . XOOPS_ROOT_PATH . '/modules/newimagemanager/image.php?id=' . $image_id . '&amp;nocache';
        $image_type = $image->getVar('image_mimetype');
        $image_name =  $image->getVar('image_name');
        $session_image_name = session_id() . '_' . $image_name;
        // copy file from xoops database to imageedit directory
        $temp_image =& imagecreatefromstring($image->getVar('image_body'));
        if ($temp_image != false) {
            switch ($image_type) {
                case 'image/gif':
                    $outputFunction     = 'imagegif';
                    break;
                case 'image/x-png':
                case 'image/png':
                    $outputFunction     = 'imagepng';
                    break;
                default:
                    $outputFunction     = 'imagejpeg';
                    break;
            }
            if (!$outputFunction($temp_image, $originalDirectory . $session_image_name)) {
                $errors[] = 'An error occurred.'; // DEBUG
            }
            if (!copy ($originalDirectory . $session_image_name, $activeDirectory . $session_image_name)
                || !copy ($originalDirectory . $session_image_name, $editDirectory . $session_image_name)
                || !copy ($originalDirectory . $session_image_name, $undoDirectory . $session_image_name)) {
                $errors[] = 'An error occurred.'; // DEBUG
            }
            //imagedestroy($temp_image);
        }
        else {
            $errors[] = 'An error occurred.'; // DEBUG
        }

        // Define Stylesheet
        $xoTheme->addStylesheet( XOOPS_URL . '/modules/newimagemanager/libs/imageeditor/ImageEditor.css' );
        // Define scripts
		$xoTheme->addScript('browse.php?Frameworks/jquery/jquery.js');
		$xoTheme->addScript('browse.php?modules/newimagemanager/libs/imageeditor/PageInfo.js');
		$xoTheme->addScript('browse.php?modules/newimagemanager/libs/imageeditor/ImageEditor.js');
		$xoTheme->addScript('', '', 'xoopsOnloadEvent(function(){ImageEditor.init("'.$session_image_name.'", "image");})');
        $output.= '<a href="' . $current_file . '">' . _NIM_AM_IMGMAIN . '</a>';
        $output.= '&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
        $output.= '<a href="' . $current_file . '?op=category.list&amp;imgcat_id='.$imgcat_id.'">' . $imagecategory->getVar('imgcat_name') . '</a>';
        $output.= '&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
        $output.= $image->getVar('image_nicename', 'E');
        $output.= '<br /><br />';

        $output.= '<div id="image-editor">';
        $output.= '<div class="toolbar">';
        $output.= '<button onclick="ImageEditor.viewOriginal()">' . _NIM_AM_EDIT_VIEW_ORIGINAL . '</button>';
        $output.= '<button onclick="ImageEditor.viewActive()">' . _NIM_AM_EDIT_VIEW_ACTIVE . '</button>';
        $output.= '<button onclick="ImageEditor.save()">' . _NIM_AM_EDIT_SAVE_AS_ACTIVE . '</button>';
        $output.= '<span class="spacer">' . _NIM_AM_EDIT_SPACER . '</span>';
        $output.= '<button onclick="ImageEditor.undo()">' . _NIM_AM_EDIT_UNDOREDO . '</button>';
        $output.= '<span class="spacer">' . _NIM_AM_EDIT_SPACER . '</span>';
        $output.= '<button onclick="location.href=\'' . $current_file . '?op=image.edit_update&image_id=' . $image_id . '\'">' . _NIM_AM_EDIT_SAVE_ACTIVE . '</button>';
        $output.= '<button onclick="location.href=\'' . $current_file . '?op=image.edit_save_as_form&image_id=' . $image_id . '\'">' . _NIM_AM_EDIT_SAVE_EDITED_AS . '</button>';
        $output.= '</div>';

        $output.= '<div class="toolbar">';
        $output.= '' . _NIM_AM_EDIT_W . '<input id="txt-width" type="text" size="3" maxlength="4" />';
        $output.= '' . _NIM_AM_EDIT_H . '<input id="txt-height" type="text" size="3" maxlength="4" />';
        $output.= '<input id="chk-constrain" type="checkbox" checked="checked" />' . _NIM_AM_EDIT_CONSTRAIN . '';
        $output.= '<button onclick="ImageEditor.resize();">' . _NIM_AM_EDIT_RESIZE . '</button>';
        $output.= '<button onclick="ImageEditor.crop()">' . _NIM_AM_EDIT_CROP . '</button>';
        $output.= '<span id="crop-size"></span>';
        $output.= '<span class="spacer">' . _NIM_AM_EDIT_SPACER . '</span>';
        $output.= '<button onclick="ImageEditor.mirror()">' . _NIM_AM_EDIT_MIRROR . '</button>';
        $output.= '<button onclick="ImageEditor.flip()">' . _NIM_AM_EDIT_FLIP . '</button>';
        $output.= '<span class="spacer">' . _NIM_AM_EDIT_SPACER . '</span>';
        $output.= '<button onclick="ImageEditor.rotate(90)">' . _NIM_AM_EDIT_ROTATE_90CCW . '</button>';
        $output.= '<button onclick="ImageEditor.rotate(270)">' . _NIM_AM_EDIT_ROTATE_90CW . '</button>';
        $output.= '<button onclick="ImageEditor.rotate(document.getElementById(\'txt-deg\').value)">' . _NIM_AM_EDIT_ROTATE_1 . '</button>&nbsp;<input id="txt-deg" type="text" size="3" maxlength="4" />' . _NIM_AM_EDIT_ROTATE_2 . '';
        $output.= '</div>';

        $output.= '<div class="toolbar">';
        $output.= '<button onclick="ImageEditor.grayscale()">' . _NIM_AM_EDIT_GRAYSCALE . '</button>';
        $output.= '<button onclick="ImageEditor.sepia()">' . _NIM_AM_EDIT_SEPIA . '</button>';
        $output.= '<button onclick="ImageEditor.pencil()">' . _NIM_AM_EDIT_PENCIL . '</button>';
        $output.= '<button onclick="ImageEditor.emboss()">' . _NIM_AM_EDIT_EMBOSS . '</button>';
        $output.= '<button onclick="ImageEditor.sblur()">' . _NIM_AM_EDIT_BLUR . '</button>';
        $output.= '<button onclick="ImageEditor.smooth()">' . _NIM_AM_EDIT_SMOOTH . '</button>';
        $output.= '<button onclick="ImageEditor.invert()">' . _NIM_AM_EDIT_INVERT . '</button>';
        $output.= '<span class="spacer">' . _NIM_AM_EDIT_SPACER . '</span>';
        $output.= '<button onclick="ImageEditor.brightnessdec()">' . _NIM_AM_EDIT_MINUS . '</button>';
        $output.= _NIM_AM_EDIT_BRIGHTNESS;
        $output.= '<button onclick="ImageEditor.brightnessinc()">' . _NIM_AM_EDIT_PLUS . '</button>';
        $output.= '<span class="spacer">' . _NIM_AM_EDIT_SPACER . '</span>';
        $output.= '<button onclick="ImageEditor.contrastinc()">' . _NIM_AM_EDIT_MINUS . '</button>';
        $output.= _NIM_AM_EDIT_CONTRAST;
        $output.= '<button onclick="ImageEditor.contrastdec()">' . _NIM_AM_EDIT_PLUS . '</button>';
        $output.= '<span class="spacer">' . _NIM_AM_EDIT_SPACER . '</span>';
        $output.= '<button onclick="ImageEditor.colorize(document.getElementById(\'txt_red\').value, document.getElementById(\'txt_green\').value, document.getElementById(\'txt_blue\').value)">' . _NIM_AM_EDIT_COLORIZE . '</button>';
        $output.= '&nbsp;' . _NIM_AM_EDIT_RED . '<input id="txt_red" type="text" size="3" maxlength="4" />';
        $output.= '&nbsp;' . _NIM_AM_EDIT_GREEN . '<input id="txt_green" type="text" size="3" maxlength="4" />';
        $output.= '&nbsp;' . _NIM_AM_EDIT_BLUE . '<input id="txt_blue" type="text" size="3" maxlength="4" />';
        $output.= '</div>';

        $output.= '<div id="image"></div>';
        $output.= '</div>';
        echo $output;
        break;

    case 'image.edit_update' :
        // get/check parameters
        $image_id = intval($image_id);
        if ($image_id <= 0) {redirect_header($current_file, 1);}


        xoops_confirm(array('op' => 'image.edit_update_ok', 'image_id' => $image_id), $current_file, _NIM_AM_RUOVERWRITEIMG);

        break;

    case 'image.edit_update_ok' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // get/check parameters
        $image_id = intval($image_id);
        if ($image_id <= 0) {redirect_header($current_file, 1);}

        // load image object
        $image =& $image_handler->get($image_id);
        if (!is_object($image)) {redirect_header($current_file, 1);}
        // load category object
        $imgcat_id = $image->getVar('imgcat_id');
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}

        $image->setVar('image_created', time());
        $image_type = $image->getVar('image_mimetype');
        $image_name =  $image->getVar('image_name');
        $session_image_name = session_id() . '_' . $image_name;

        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        $fbinary = @file_get_contents($activeDirectory . $session_image_name);
        $image->setVar('image_body', $fbinary, true);
        $image->setVar('image_created', time());
        @unlink($activeDirectory . $session_image_name);
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        if (!$image_handler->insert($image)) {
            $errors[] = sprintf(_FAILSAVEIMG, $image->getVar('image_nicename'));
        }
        garbage_collection($originalDirectory, '', $image_name);
        garbage_collection($activeDirectory, '', $image_name);
        garbage_collection($editDirectory, '', $image_name);
        garbage_collection($undoDirectory, '', $image_name);
        redirect_header($current_file . "?op=category.list&amp;imgcat_id=" . $imgcat_id, 2, _NIM_AM_DBUPDATED);
        break;

    case 'image.edit_save_as_form' :
        // get/check parameters
        $image_id = intval($image_id);
        if ($image_id <= 0) {redirect_header($current_file, 1);}

        // load image object
        $image =& $image_handler->get($image_id);
        if (!is_object($image)) {redirect_header($current_file, 1);}
        // load category object
        $imgcat_id = $image->getVar('imgcat_id');
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}

        //image is stored as file in directory editor/active as session_id() . '_' . $image_name
        $image_name =  $image->getVar('image_name');
        $session_image_name = session_id() . '_' . $image_name;

        $image_src = $activeUrl . $session_image_name;
        $image_path = $activeDirectory . $session_image_name;
        $imagefile_size = filesize($activeDirectory . $session_image_name);

        // set right image width - start
        $max_image_width = 150;
        $image_size = getimagesize($image_src);
        $image_width = ($image_size[0] > $max_image_width) ? $max_image_width : $image_size[0];
        // set right image width - end

        $output.= '<a href="' . $current_file . '">' . _NIM_AM_IMGMAIN . '</a>';
        $output.= '&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
        $output.= '<a href="' . $current_file . '?op=category.list&amp;imgcat_id='.$imgcat_id.'">' . $imagecategory->getVar('imgcat_name') . '</a>';
        $output.= '&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
        $output.= $image->getVar('image_nicename', 'E');
        $output.= '<br /><br />';
        $form = new XoopsThemeForm(_NIM_AM_ASNEWIMAGE, 'image_form', $current_file, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(_NIM_AM_IMAGENAME, 'image_nicename', 50, 255, sprintf(_NIM_AM_EDIT_OF, $image->getVar('image_nicename'))), true);
        $form->addElement(new XoopsFormText(_NIM_AM_IMAGEALTERNATIVE, 'image_alternative', 50, 255, $image->getVar('image_alternative')), true);
            $image_descriptionTextArea = new XoopsFormTextArea(_NIM_AM_IMAGEDESCRIPTION, 'image_description', $image->getVar('image_description'), 5, 100);
            $image_descriptionTextArea->setDescription (_NIM_AM_IMAGEDESCRIPTION_DESC);
        $form->addElement($image_descriptionTextArea);
        $select = new XoopsFormSelect(_NIM_AM_IMAGECAT, 'imgcat_id', $image->getVar('imgcat_id'));
        $select->addOptionArray($imgcat_handler->getList($groups, 'newimagemanager_cat_write'));
        $form->addElement($select, true);
        //$form->addElement(new XoopsFormFile(_NIM_AM_IMAGEFILE, 'image_file', 5000000));
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGEID, $image->getVar('image_id'), 'image_id'));
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGEDATE, gmdate(_NIM_AM_DATESTRING, $image->getVar('image_created')), 'image_created'));
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGEMIME, $image->getVar('image_mimetype'), 'image_mimetype'));
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGESIZE, sprintf(_NIM_AM_IMAGESIZE_CONTENT, $image_size[0], $image_size[1], $imagefile_size), 'image_mimetype'));
            $image_html = '<img style="width:' . $image_width . 'px;height:auto;"';
            $image_html.= ' id="imageid' . $image->getVar('image_id') . '"';
            $image_html.= ' src="' . $image_src . '"';
            $image_html.= ' alt="' . $image->getVar('image_alternative', 'E') . '"';
            $image_html.= ' title="' . $image->getVar('image_nicename', 'E') . '"';
            $image_html.= ' onclick="javascript:alert(\'ZOOM IN PROGRESS\');"'; // IN PROGRESS
            $image_html.= ' />';
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGEFILE, $image_html, 'image_file'));
        $form->addElement(new XoopsFormText(_NIM_AM_IMGWEIGHT, 'image_weight', 3, 4, $image->getVar('image_weight')));
        $form->addElement(new XoopsFormRadioYN(_IMGDISPLAY, 'image_display', $image->getVar('image_display'), _YES, _NO));
        $form->addElement(new XoopsFormHidden('oldimage_id', $image_id));
        $form->addElement(new XoopsFormHidden('op', 'image.edit_save_as_ok'));
        $form->addElement(new XoopsFormHidden('fct', 'images'));
        $form->addElement(new XoopsFormButtonTray('img_button', _SUBMIT, 'submit', '', false));
        $output.= $form->render();
        echo $output;
        break;

    case 'image.edit_save_as_ok' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // get/check parameters
        $oldimage_id = intval($oldimage_id);
        if ($oldimage_id <= 0) {redirect_header($current_file, 1);}
        $newimgcat_id = intval($imgcat_id);
        if ($newimgcat_id <= 0) {redirect_header($current_file, 1);}

        // load old image object
        $oldimage =& $image_handler->get($oldimage_id);
        if (!is_object($oldimage)) {redirect_header($current_file, 1);}
        // load new category object
        $newimagecategory =& $imgcat_handler->get($newimgcat_id);
        if (!is_object($newimagecategory)) {redirect_header($current_file, 1);}

        // create new image
        $newimage =& $image_handler->create();

        // create a new name (filename) for new image
        $oldimagename = explode('.', $oldimage->getVar('image_name')); // FUNZIONA MA NON MI PIACE
        $newimagename = uniqid('img') . '.' . $oldimagename[1]; // FUNZIONA MA NON MI PIACE

        // set values of new image
        $newimage->setVar('image_name', $newimagename);
        $newimage->setVar('image_nicename', $image_nicename);
        $newimage->setVar('image_alternative', $image_alternative);
        $newimage->setVar('image_mimetype', $oldimage->getVar('image_mimetype'));
        $newimage->setVar('image_created', time());
        $newimage->setVar('image_display', (empty($image_display) ? 0 : 1));
        $newimage->setVar('image_weight', $image_weight);
        $newimage->setVar('imgcat_id', $imgcat_id);
        $newimage->setVar('image_description', $image_description); // IN PROGRESS

        // get binary image data from old image and store in $fbinary
        //image is stored as file in directory editor/active as session_id() . '_' . $image_name
        $session_image_name = session_id() . '_' . $oldimage->getVar('image_name');
        $fbinary = @file_get_contents($activeDirectory . $session_image_name);
        if (!$fbinary) {
            $errors[] = sprintf(_FAILGETOLDIMGDATA, $oldimage->getVar('image_nicename'));
            }
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        // IN PROGRESS
        // save binary image data in new image
        $newimage->setVar('image_body', $fbinary, true);

        // insert image in database
        if (!$image_handler->insert($newimage)) {
            $errors[] = sprintf(_FAILSAVEIMG, $newimage->getVar('image_nicename'));
        }
        if (count($errors) > 0) {
            // Call Header
            xoops_cp_header();
            xoops_error($errors);
            xoops_cp_footer();
            exit();
        }
        garbage_collection($originalDirectory, '', $session_image_name);
        garbage_collection($activeDirectory, '', $session_image_name);
        garbage_collection($editDirectory, '', $session_image_name);
        garbage_collection($undoDirectory, '', $session_image_name);
        redirect_header($current_file . "?op=category.list&amp;imgcat_id=" . $imgcat_id, 2, _NIM_AM_DBUPDATED); // IN PROGRESS
        break;
    } // switch ( $op )



// CLONE AN IMAGE ----------------------------------------------------------------------------------
switch ( $op ) {
    case 'image.clone_form' :
        // get/check parameters
        $image_id = intval($image_id);
        if ($image_id <= 0) {redirect_header($current_file, 1);}

        // load image object
        $image =& $image_handler->get($image_id);
        if (!is_object($image)) {redirect_header($current_file, 1);}
        // load category object
        $imgcat_id = $image->getVar('imgcat_id');
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}

        $storetype = $imagecategory->getVar('imgcat_storetype');
        $image_src = XOOPS_URL . '/modules/newimagemanager/image.php?id=' . $image->getVar('image_id') . '&amp;nocache';
        $image_path = XOOPS_PATH . '/modules/newimagemanager/image.php?id=' . $image->getVar('image_id') . '&amp;nocache';
        $imagefile_size = $image->image_size();
        $image_width = $image->image_width();
        $image_height = $image->image_height();

        // set right output image width - start
        $max_image_width = 150; // IN PROGRESS
        $output_image_width = ($image_width > $max_image_width) ? $max_image_width : $image_width;
        // set right image width - end

        $output.= '<a href="' . $current_file . '">' . _NIM_AM_IMGMAIN . '</a>';
        $output.= '&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
        $output.= '<a href="' . $current_file . '?op=category.list&amp;imgcat_id='.$imgcat_id.'">' . $imagecategory->getVar('imgcat_name') . '</a>';
        $output.= '&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;';
        $output.= $image->getVar('image_nicename', 'E');
        $output.= '<br /><br />';
        $form = new XoopsThemeForm(_NIM_AM_CLONEIMAGE, 'image_form', $current_file, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormText(_NIM_AM_IMAGENAME, 'image_nicename', 50, 255, sprintf(_NIM_AM_COPY_OF, $image->getVar('image_nicename'))), true);
        $form->addElement(new XoopsFormText(_NIM_AM_IMAGEALTERNATIVE, 'image_alternative', 50, 255, $image->getVar('image_alternative')), true);
            $image_descriptionTextArea = new XoopsFormTextArea(_NIM_AM_IMAGEDESCRIPTION, 'image_description', $image->getVar('image_description'), 5, 100);
            $image_descriptionTextArea->setDescription (_NIM_AM_IMAGEDESCRIPTION_DESC);
        $form->addElement($image_descriptionTextArea);
        $select = new XoopsFormSelect(_NIM_AM_IMAGECAT, 'imgcat_id', $image->getVar('imgcat_id'));
        $select->addOptionArray($imgcat_handler->getList($groups, 'newimagemanager_cat_write'));
        $form->addElement($select, true);
        //$form->addElement(new XoopsFormFile(_NIM_AM_IMAGEFILE, 'image_file', 5000000));
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGEID, $image->getVar('image_id'), 'image_id'));
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGEDATE, gmdate(_NIM_AM_DATESTRING, $image->getVar('image_created')), 'image_created'));
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGEMIME, $image->getVar('image_mimetype'), 'image_mimetype'));
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGESIZE, sprintf(_NIM_AM_IMAGESIZE_CONTENT, $image_width, $image_height, $imagefile_size), 'image_mimetype'));
            $image_html = '<img style="width:' . $image_width . 'px;height:auto;"';
            $image_html.= ' id="imageid' . $image->getVar('image_id') . '"';
            $image_html.= ' src="' . $image_src . '"';
            $image_html.= ' alt="' . $image->getVar('image_alternative', 'E') . '"';
            $image_html.= ' title="' . $image->getVar('image_nicename', 'E') . '"';
            $image_html.= ' onclick="javascript:alert(\'ZOOM IN PROGRESS\');"'; // IN PROGRESS
            $image_html.= ' />';
        $form->addElement(new XoopsFormLabel (_NIM_AM_IMAGEFILE, $image_html, 'image_file'));
        $form->addElement(new XoopsFormText(_NIM_AM_IMGWEIGHT, 'image_weight', 3, 4, $image->getVar('image_weight')));
        $form->addElement(new XoopsFormRadioYN(_IMGDISPLAY, 'image_display', $image->getVar('image_display'), _YES, _NO));
        $form->addElement(new XoopsFormHidden('oldimage_id', $image_id));
        $form->addElement(new XoopsFormHidden('op', 'image.clone'));
        $form->addElement(new XoopsFormHidden('fct', 'images'));
        $form->addElement(new XoopsFormButtonTray('img_button', _CLONE, 'submit', '', false));
        $output.= $form->render();
        echo $output;
        break;

    case 'image.clone' : // IN PROGRESS
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // get/check parameters
        $oldimage_id = intval($oldimage_id);
        if ($oldimage_id <= 0) {redirect_header($current_file, 1);}
        $newimgcat_id = intval($imgcat_id);
        if ($newimgcat_id <= 0) {redirect_header($current_file, 1);}

        // load category object
        $newimagecategory =& $imgcat_handler->get($newimgcat_id);
        if (!is_object($newimagecategory)) {redirect_header($current_file, 1);}

        // get old image
        $oldimage =& $image_handler->get($oldimage_id);

        // create new image
        $newimage =& $image_handler->create();

        // create a new name (filename) for new image
        $oldimagename = explode('.', $oldimage->getVar('image_name')); // FUNZIONA MA NON MI PIACE
        $newimagename = uniqid('img') . '.' . $oldimagename[1]; // FUNZIONA MA NON MI PIACE

        // set values of new image
        $newimage->setVar('image_name', $newimagename);
        $newimage->setVar('image_nicename', $image_nicename);
        $newimage->setVar('image_alternative', $image_alternative);
        $newimage->setVar('image_mimetype', $oldimage->getVar('image_mimetype'));
        $newimage->setVar('image_created', time());
        $newimage->setVar('image_display', (empty($image_display) ? 0 : 1));
        $newimage->setVar('image_weight', $image_weight);
        $newimage->setVar('imgcat_id', $imgcat_id);
        $newimage->setVar('image_description', $image_description); // IN PROGRESS

        // get binary image data from old image and store in $fbinary
        $fbinary = $oldimage->getVar('image_body');

        if (!$fbinary) {
            $errors[] = sprintf(_FAILGETOLDIMGDATA, $oldimage->getVar('image_nicename'));
            }

        // save binary image data in new image
        $newimage->setVar('image_body', $fbinary, true);

        // insert image in database
        if (!$image_handler->insert($newimage)) {
            $errors[] = sprintf(_FAILSAVEIMG, $newimage->getVar('image_nicename'));
        }
        if (count($errors) > 0) {
            // Call Header
            xoops_error($errors);
            exit();
        }
        redirect_header($current_file, 2, _NIM_AM_DBUPDATED); // IN PROGRESS
        break;
    } // switch ( $op )



// -------------------------------------------------------------------------------------------------
// CATEGORY OPTIONS --------------------------------------------------------------------------------
// -------------------------------------------------------------------------------------------------



// ADD A NEW CATEGORY ------------------------------------------------------------------------------
switch ( $op ) {
    case 'category.add' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }

        $imagecategory =& $imgcat_handler->create();
        $imagecategory->setVar('imgcat_name', $imgcat_name);

        $imagecategory->setVar('imgcat_mimetypes', $imgcat_mimetypes);

        $imagecategory->setVar('imgcat_maxsize', $imgcat_maxsize);
        $imagecategory->setVar('imgcat_maxwidth', $imgcat_maxwidth);
        $imagecategory->setVar('imgcat_maxheight', $imgcat_maxheight);

        $imgcat_display = empty($imgcat_display) ? 0 : 1;
        $imagecategory->setVar('imgcat_display', $imgcat_display);
        $imagecategory->setVar('imgcat_weight', $imgcat_weight);

        $imagecategory->setVar('imgcat_storetype', $imgcat_storetype);
        if ($imgcat_storetype == 'file'){
            $categ_rel_path = NIM_MODULE_REL_UPLOAD_PATH . '/uploaded/' . $imgcat_relativepath; // in progress
            $imagecategory->setVar('imgcat_relativepath', $categ_rel_path);
        }
        $imagecategory->setVar('imgcat_type', 'C');

        if (!$imgcat_handler->insert($imagecategory)) {
            exit();
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
        redirect_header($current_file, 2, _NIM_AM_DBUPDATED);
        break;

// EDIT AN EXISTENT CATEGORY -----------------------------------------------------------------------
    case 'category.edit_form' :
        // get/check parameters
        $imgcat_id = intval($imgcat_id);
        if ($imgcat_id <= 0) {redirect_header($current_file, 1);}

        // load category object
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}

        $imagecategoryperm_handler =& xoops_gethandler('groupperm');

        $output.= '<a href="' . $current_file . '">'. _NIM_AM_IMGMAIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$imagecategory->getVar('imgcat_name').'<br /><br />';
        $form = new XoopsThemeForm(_NIM_AM_EDITIMGCAT, 'imagecat_form', $current_file, 'post', true);
        $form->addElement(new XoopsFormText(_NIM_AM_IMGCATNAME, 'imgcat_name', 50, 255, $imagecategory->getVar('imgcat_name')), true);
        $form->addElement(new XoopsFormSelectGroup(_NIM_AM_IMGCATRGRP, 'readgroup', true, $imagecategoryperm_handler->getGroupIds('newimagemanager_cat_read', $imgcat_id), 5, true));
        $form->addElement(new XoopsFormSelectGroup(_NIM_AM_IMGCATWGRP, 'writegroup', true, $imagecategoryperm_handler->getGroupIds('newimagemanager_cat_write', $imgcat_id), 5, true));

            $image_mimetypesTextArea = new XoopsFormTextArea(_NIM_AM_MIMETYPES, 'image_mimetypes', $imagecategory->getVar('imgcat_mimetypes'), 5, 100);
            $image_mimetypesTextArea->setDescription (_NIM_AM_MIMETYPES_DESC);
        $form->addElement($image_mimetypesTextArea);

        $form->addElement(new XoopsFormText(_NIM_AM_IMGMAXSIZE, 'imgcat_maxsize', 10, 10, $imagecategory->getVar('imgcat_maxsize')));
        $form->addElement(new XoopsFormText(_NIM_AM_IMGMAXWIDTH, 'imgcat_maxwidth', 3, 4, $imagecategory->getVar('imgcat_maxwidth')));
        $form->addElement(new XoopsFormText(_NIM_AM_IMGMAXHEIGHT, 'imgcat_maxheight', 3, 4, $imagecategory->getVar('imgcat_maxheight')));

        $form->addElement(new XoopsFormText(_NIM_AM_IMGCATWEIGHT, 'imgcat_weight', 3, 4, $imagecategory->getVar('imgcat_weight')));
        $form->addElement(new XoopsFormRadioYN(_NIM_AM_IMGCATDISPLAY, 'imgcat_display', $imagecategory->getVar('imgcat_display'), _YES, _NO));

            $storetype = array('db' => _NIM_AM_INDB, 'file' => sprintf(_NIM_AM_ASFILE, NIM_MODULE_UPLOAD_PATH . '/uploaded/' . $imagecategory->getVar('imgcat_relativepath') . '/')); // in progress
        $form->addElement(new XoopsFormLabel(_NIM_AM_IMGCATSTRTYPE, $storetype[$imagecategory->getVar('imgcat_storetype')]));
        $form->addElement(new XoopsFormHidden('imgcat_id', $imgcat_id));
        $form->addElement(new XoopsFormHidden('op', 'category.edit'));
        $form->addElement(new XoopsFormHidden('fct', 'images'));
        $form->addElement(new XoopsFormButton('', 'imgcat_button', _SUBMIT, 'submit'));
        $output.= $form->render();
        echo $output;
        break;

    case 'category.edit' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 1, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // get/check parameters
        $imgcat_id = intval($imgcat_id);
        if ($imgcat_id <= 0) {redirect_header($current_file, 1);}

        // load category object
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}

        $imagecategory->setVar('imgcat_name', $imgcat_name);

        $imagecategory->setVar('imgcat_mimetypes', $imgcat_mimetypes);

        $imagecategory->setVar('imgcat_maxsize', $imgcat_maxsize);
        $imagecategory->setVar('imgcat_maxwidth', $imgcat_maxwidth);
        $imagecategory->setVar('imgcat_maxheight', $imgcat_maxheight);

        $imgcat_display = empty($imgcat_display) ? 0 : 1;
        $imagecategory->setVar('imgcat_display', $imgcat_display);
        $imagecategory->setVar('imgcat_weight', $imgcat_weight);

        if (!$imgcat_handler->insert($imagecategory)) {
            exit();
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
        redirect_header($current_file, 2, _NIM_AM_DBUPDATED);
        break;
    } // switch ( $op )



// DELETE A CATEGORY -------------------------------------------------------------------------------
switch ( $op ) {
    case 'category.delete' :
        // get/check parameters
        $imgcat_id = intval($imgcat_id);
        if ($imgcat_id <= 0) {redirect_header($current_file, 1);}

        xoops_confirm(array('op' => 'category.delete_ok', 'imgcat_id' => $imgcat_id, 'fct' => 'images'), $current_file, _NIM_AM_RUDELIMGCAT);
        break;

    case 'category.delete_ok' :
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header($current_file, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // get/check parameters
        $imgcat_id = intval($imgcat_id);
        if ($imgcat_id <= 0) {redirect_header($current_file, 1);}

        // load category object
        $imagecategory =& $imgcat_handler->get($imgcat_id);
        if (!is_object($imagecategory)) {redirect_header($current_file, 1);}

        if ($imagecategory->getVar('imgcat_type') != 'C') {
            xoops_error(_NIM_AM_SCATDELNG);
            exit();
        }

        // delete category from database
        if (!$imgcat_handler->delete($imagecategory)) {
            $errors[] = sprintf(_NIM_AM_FAILDELCAT, $imagecategory->getVar('imgcat_name'));
        }

        if (count($errors) > 0) {
            xoops_error($errors);
            exit();
        }
        redirect_header($current_file, 2, _NIM_AM_DBUPDATED);
        break;
    } //switch ( $op )



// COMMON ADMIN CONTENT - end -----------------------------------------------------------------------



// Call Admin Header
switch ( $op ) {
    case 'list' :
    case 'category.list':
    case 'image.delete' :
    case 'image.edit' :
    case 'image.edit_update' :
    case 'image.edit_save_as_form' :
    case 'image.clone_form' :
    case 'category.edit_form' :
    case 'category.delete' :
        xoops_cp_footer();
    break;
    }
?>
