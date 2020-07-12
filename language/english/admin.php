<?php
define("_NIM_AM_ERRORMODNOTACTIVE","Module not active");
define("_NIM_AM_INDEX_ERRORPFOLDER","ERROR:'newimagemanager' directory in '%s/uploads/' cannot be created, you have to create it manually <br><br>just copy the folder 'newimagemanager' (you can find it in 'extra' folder inside the module) in the 'uploads' directory mentioned above");
define("_NIM_AM_INDEX_ERRORPHP","ERROR: this menu requires at least PHP 5.0 or more");

//%%%%%%    File Name  admin/admin.php    %%%%%
define('_NIM_AM_DBUPDATED', 'Database Updated Successfully!');
define('_NIM_AM_DBERROR', 'Database was not updated due to some error!');

define('_NIM_AM_CONFIG', 'System Configuration');

define('_NIM_AM_IMGMAIN','Image Manager Main');
define('_NIM_AM_IMGMANAGER','New Image Manager');

// admin/images.php - Add/list images
define('_NIM_AM_ADDIMAGE','Add Image');
define('_NIM_AM_IMAGENAME','Name:');
define('_NIM_AM_IMAGEALTERNATIVE','Alternative:');
define('_NIM_AM_IMAGEDESCRIPTION','Description:');
    define('_NIM_AM_IMAGEDESCRIPTION_DESC','IN PROGRESS');
define('_NIM_AM_IMAGECAT','Category:');
define('_NIM_AM_IMAGEFILE','Image file:');
    define('_NIM_AM_IMAGEFILE_DESC','Image will be automatically resized if bigger than max category sizes');
define('_NIM_AM_IMGWEIGHT','Display order in image manager:');
define('_NIM_AM_IMGDISPLAY','Display this image?');
define('_NIM_AM_IMAGEID','Image Id:');
define('_NIM_AM_IMAGEDATE','Creation date:');
define('_NIM_AM_DATESTRING','D, d M Y H:i:s');
/**
* The following characters are recognized in the format string:
* a - 'am' or 'pm'
* A - 'AM' or 'PM'
* d - day of the month, 2 digits with leading zeros; i.e. '01' to '31'
* D - day of the week, textual, 3 letters; i.e. 'Fri'
* F - month, textual, long; i.e. 'January'
* h - hour, 12-hour format; i.e. '01' to '12'
* H - hour, 24-hour format; i.e. '00' to '23'
* g - hour, 12-hour format without leading zeros; i.e. '1' to '12'
* G - hour, 24-hour format without leading zeros; i.e. '0' to '23'
* i - minutes; i.e. '00' to '59'
* j - day of the month without leading zeros; i.e. '1' to '31'
* l (lowercase 'L') - day of the week, textual, long; i.e. 'Friday'
* L - boolean for whether it is a leap year; i.e. '0' or '1'
* m - month; i.e. '01' to '12'
* n - month without leading zeros; i.e. '1' to '12'
* M - month, textual, 3 letters; i.e. 'Jan'
* s - seconds; i.e. '00' to '59'
* S - English ordinal suffix, textual, 2 characters; i.e. 'th', 'nd'
* t - number of days in the given month; i.e. '28' to '31'
* T - Timezone setting of this machine; i.e. 'MDT'
* U - seconds since the epoch
* w - day of the week, numeric, i.e. '0' (Sunday) to '6' (Saturday)
* Y - year, 4 digits; i.e. '1999'
* y - year, 2 digits; i.e. '99'
* z - day of the year; i.e. '0' to '365'
* Z - timezone offset in seconds (i.e. '-43200' to '43200')
*/
define('_NIM_AM_IMAGEMIME','MIME type:');
define('_NIM_AM_IMAGESIZE','Image size [width x height x size]:');
define('_NIM_AM_IMAGESIZE_CONTENT','%spx x %spx x %sbytes');
define('_NIM_AM_RUDELIMG','Are you sure that you want to delete this images file?');
    define('_NIM_AM_RUOVERWRITEIMG','Are you sure that you want to overwrite this image file?');

// admin/images.php - Clone images
define('_NIM_AM_CLONEIMAGE','Clone Image');
define('_NIM_AM_COPY_OF','Copy of %s');

// admin/images.php - List categories
define('_NIM_AM_IMGCAT','Categories');
define('_NIM_AM_NUMIMAGES','%s images');

// admin/images.php - Add/edit categories
define('_NIM_AM_ADDIMGCAT','Add Image Category');
define('_NIM_AM_EDITIMGCAT','Edit Image Category:');
define('_NIM_AM_IMGCATNAME','Category Name:');
define('_NIM_AM_IMGCATRGRP','Select groups for image manager use:<br /><br /><span style="font-weight: normal;">These are groups allowed to use the image manager for selecting images but not uploading. Webmaster has automatic access.</span>');
define('_NIM_AM_IMGCATWGRP','Select groups allowed to upload images:<br /><br /><span style="font-weight: normal;">Typical usage is for moderator and admin groups.</span>');
define('_NIM_AM_IMGCATWEIGHT','Display order in image manager:');
define('_NIM_AM_IMGCATDISPLAY','Display this category?');
define('_NIM_AM_MIMETYPES','Mimetypes allowed for this category:');
define('_NIM_AM_MIMETYPES_DESC','Mimetypes separed by <b>\';\'</b> // IN PROGRESS');
define('_NIM_AM_IMGMAXSIZE','Max file size (bytes)?');
define('_NIM_AM_IMGMAXWIDTH','Max image width (pixels)?');
define('_NIM_AM_IMGMAXHEIGHT','max image height (pixels)?');
define('_NIM_AM_IMGCATSTRTYPE','Images are uploaded to:');
define('_NIM_AM_IMGCATSTRTYPE_DESC','This can not be changed afterwards!');
define('_NIM_AM_INDB','Stored in the database (as binary "blob" data)');
define('_NIM_AM_ASFILE','Stored as files in "%s" directory');
define('_NIM_AM_FOLDERNAME','Folder name');
define('_NIM_AM_FOLDERNAME_DESC1','Do not use spaces or especial chars!');
define('_NIM_AM_FOLDERNAME_DESC2','This can not be changed afterwards!');
define('_NIM_AM_RUDELIMGCAT','Are you sure that you want to delete this category and all of its images files?');

// admin/images.php - Image editor
define('_NIM_AM_ASNEWIMAGE','Save edited image as');
define('_NIM_AM_EDIT_OF','Edited copy of %s');
define('_NIM_AM_EDIT_VIEW_ORIGINAL','View Original');
define('_NIM_AM_EDIT_VIEW_ACTIVE','View Active');
define('_NIM_AM_EDIT_SAVE_AS_ACTIVE','Save As Active');
define('_NIM_AM_EDIT_SPACER','&nbsp;||&nbsp;');
define('_NIM_AM_EDIT_UNDOREDO','Undo/Redo');
define('_NIM_AM_EDIT_SAVE_ACTIVE','Save Active');
define('_NIM_AM_EDIT_SAVE_EDITED_AS','Save Active as new in');
define('_NIM_AM_EDIT_W','<span title="width">w:</span>');
define('_NIM_AM_EDIT_H','<span title="height">h:</span>');
define('_NIM_AM_EDIT_CONSTRAIN','Constrain');
define('_NIM_AM_EDIT_RESIZE','Resize');
define('_NIM_AM_EDIT_CROP','Crop');
define('_NIM_AM_EDIT_MIRROR','Mirror');
define('_NIM_AM_EDIT_FLIP','Flip');
define('_NIM_AM_EDIT_ROTATE_90CCW','Rotate 90&deg;CCW');
define('_NIM_AM_EDIT_ROTATE_90CW','Rotate 90&deg;CW');
define('_NIM_AM_EDIT_ROTATE_1','Rotate');
define('_NIM_AM_EDIT_ROTATE_2','&deg; (from -360&deg; to +360&deg;)');
define('_NIM_AM_EDIT_GRAYSCALE','Gray Scale');
define('_NIM_AM_EDIT_SEPIA','Sepia');
define('_NIM_AM_EDIT_PENCIL','Pencil');
define('_NIM_AM_EDIT_EMBOSS','Emboss');
define('_NIM_AM_EDIT_BLUR','Blur');
define('_NIM_AM_EDIT_SMOOTH','Smooth');
define('_NIM_AM_EDIT_INVERT','Invert');
define('_NIM_AM_EDIT_PLUS','+');
define('_NIM_AM_EDIT_MINUS','-');
define('_NIM_AM_EDIT_BRIGHTNESS','Brightness');
define('_NIM_AM_EDIT_CONTRAST','Contrast');
define('_NIM_AM_EDIT_COLORIZE','Colorize');
define('_NIM_AM_EDIT_RED','<span style="color:#ff0000">R</span>');
define('_NIM_AM_EDIT_GREEN','<span style="color:#00ff00">G</span>');
define('_NIM_AM_EDIT_BLUE','<span style="color:#0000ff">B</span>');



define('_NIM_AM_FAILDEL', 'Failed deleting image %s from the database');
define('_NIM_AM_FAILDELCAT', 'Failed deleting image category %s from the database');
define('_NIM_AM_AILUNLINK', 'Failed deleting image %s from the server directory');

// admin/extention.php
define('_NIM_AM_EXTENTION_INFO',"Extention information");
define('_NIM_AM_EXTENTION_NOT_INSTALLED',"Extention not installed");
define('_NIM_AM_EXTENTION_NOT_ACTIVATED',"Extention not activated");
define('_NIM_AM_INSTALL_EXTENTION',"Install extention");
define('_NIM_AM_ACTIVATE_EXTENTION',"Activate extention");
define('_NIM_AM_EXTENTION_ACTIVATED',"Extention activated");
define('_NIM_AM_DESACTIVATE_EXTENTION',"Desactivate extention");
define('_NIM_AM_EXTENTION_DESACTIVATED',"Extention desactivated");
define('_NIM_AM_EXTENTION_INSTALLED_OK',"Extention installed");
define('_NIM_AM_EXT_FILE_NOT_INSTALLABLE',"Extention not installable");
define('_NIM_AM_EXTENTION_ACTIVATED_OK',"Extention activated");
define('_NIM_AM_EXTENTION_NOTICE',"This extention allow you to display images on all the site just by adding a <b>newimg</b> tag to your text. A button (<img src=\"../images/image_button.png\" />) is displayed on XOOPS editor.");
define('_NIM_AM_EXT_FILE_DONT_EXIST',"Extention file don't exist on repository :<br /><b>Server : </b>%s<br /><b>File : </b>%s");
define('_NIM_AM_EXT_FILE_DONT_EXIST_SHORT',"Extention file don't exist");
define('_NIM_AM_EXTENTION_INSTALLED',"Extention installed");

define('_NIM_AM_OPTIONS_MANAGER', '[newimg] options manager');
define('_NIM_AM_ADDOPTION', 'Add new option');
define('_NIM_AM_OPTIONNAME', 'Option name');
define('_NIM_AM_OPTIONVALUE', 'Option value');
define('_NIM_AM_OPTIONDESCRIPTION', 'Description');

// admin/permissions.php
define("_NIM_AM_PERM_VIEW", "View Permission");
define("_NIM_AM_PERM_VIEW_DSC", "Choose group than can view images in categories");
define("_NIM_AM_PERM_SUBMIT", "Submit Permission");
define("_NIM_AM_PERM_SUBMIT_DSC", "Choose groups that can submit images to categories");
define("_NIM_AM_PERM_EDIT", "Edit Permission");
define("_NIM_AM_PERM_EDIT_DSC", "Choose groups that can edit, clone, modify images to categories");
define("_NIM_AM_PERM_OTHERS", "Other permissions");
define("_NIM_AM_PERM_OTHERS_DSC", "Select groups that can:");
    define("_NIM_AM_PERMISSIONS_4","perm 4 - IN PROGRESS");
    define("_NIM_AM_PERMISSIONS_8","perm 8 - IN PROGRESS");
    define("_NIM_AM_PERMISSIONS_16","perm 16 - IN PROGRESS");
    define("_NIM_AM_PERMISSIONS_32","perm 32 - IN PROGRESS");
// Group permission phrases
define('_NIM_AM_PERMADDNG', 'Could not add %s permission to %s for group %s');
define('_NIM_AM_PERMADDOK', 'Added %s permission to %s for group %s');
define('_NIM_AM_PERMRESETNG', 'Could not reset group permission for module %s');
define('_NIM_AM_PERMADDNGP', 'All parent items must be selected.');

// admin/about.php
define("_NIM_AM_ABOUT_AUTHOR","Author");
define("_NIM_AM_ABOUT_DESCRIPTION","Description");
define("_NIM_AM_ABOUT_HELP","Help");
define("_NIM_AM_ABOUT_CHANGELOG","Change log");
define("_NIM_AM_ABOUT_CREDITS","Credits");
define("_NIM_AM_ABOUT_LICENSE","License");
define("_NIM_AM_ABOUT_MODULEINFOS","Module Informations");
define("_NIM_AM_ABOUT_MODULEWEBSITE","Support Website");
define("_NIM_AM_ABOUT_RELEASEDATE","Date of launch");
define("_NIM_AM_ABOUT_STATUS","Status");

// admin/import.php
define("_NIM_AM_IMPORT1","Import");
define("_NIM_AM_IMPORT_CAT_IMP","Categories: '%s' imported");
//define("_NIM_AM_IMPORT_CONF_MYDOWNLOADS","Are you sure you want to import data from Mydownloads module to TDMDownloads");
//define("_NIM_AM_IMPORT_CONF_WFDOWNLOADS","Are you sure you want to import data from WF-Downloads modules to TDMDownloads");
//define("_NIM_AM_IMPORT_DONT_DOWNLOADS","there is no files to import");
//define("_NIM_AM_IMPORT_DONT_TOPIC","there is no files to import");
//define("_NIM_AM_IMPORT_DOWNLOADS","files Importation");
//define("_NIM_AM_IMPORT_DOWNLOADS_IMP","files: '%s' imported;");
//define("_NIM_AM_IMPORT_ERREUR","Select Upload Directory (the path)");
//define("_NIM_AM_IMPORT_ERROR_DATA","Error during the importation of data");
define("_NIM_AM_IMPORT_XOOPS","Import multiple images from standard Xoops Image Manager");
define("_NIM_AM_IMPORT_EXTGALLERY","Import multiple images from ExtGallery Module");
define("_NIM_AM_IMPORT_BATCH","Import multiple images From Batch");
define("_NIM_AM_IMPORT_MULTIUPLOAD","Upload multiple images");
//define("_NIM_AM_IMPORT_MYDOWNLOADS_PATH","Select Upload Directory (the path) for screen shots of Mydownloads");
//define("_NIM_AM_IMPORT_MYDOWNLOADS_URL","Choose the corresponding URL  for screen shots of Mydownloads");
define("_NIM_AM_IMPORT_NB_CAT","There are %s categories to import");
define("_NIM_AM_IMPORT_NB_DOWNLOADS","There are %s images to import");
define("_NIM_AM_IMPORT_NUMBER","Data to import");
define("_NIM_AM_IMPORT_OK","Import successfuly done !!!");
define("_NIM_AM_IMPORT_WARNING","<span style='color:#FF0000; font-size:16px; font-weight:bold'>Attention !</span><br><br> Importation will delete all data in NewImageManager. It's highly recomended that you make a backup of your data, also of your website.<br /><br />NewImageManager is not responsible if you lose your data.");
//define("_NIM_AM_IMPORT_WFDOWNLOADS","Import from WF Downloads(only for V3.2 RC2)");
//define("_NIM_AM_IMPORT_WFDOWNLOADS_CATIMG","Select Upload Directory (the path) for categories inages of WF-Downloads");
//define("_NIM_AM_WFDOWNLOADS_SHOTS","Select Upload Directory (the path) for screen shots of WF-Downloads");
?>
