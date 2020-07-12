<?php
// _LANGCODE: en
// _CHARSET : UTF-8

/**
* New Image manager
*/
//%%%%%%    File Name  imagemanager.standard.php    %%%%%
//%%%%%%    File Name  imagemanager.enhanced.php    %%%%%

define('_NIM_AM_DBUPDATED', 'Database Updated Successfully!');
define('_NIM_AM_DBERROR', 'Database was not updated due to some error!');

define('_NIM_AM_CONFIG', 'System Configuration');

define('_NIM_IMGMAIN','Image Manager Main');
define('_NIM_IMGMANAGER','New Image Manager');

// Add/list images
define('_NIM_ADDIMAGE','Add Image');
define('_NIM_IMAGENAME','Name:');
define('_NIM_IMAGEALTERNATIVE','Alternative:');
define('_NIM_IMAGEDESCRIPTION','Description:');
    define('_NIM_IMAGEDESCRIPTION_DESC','IN PROGRESS');
define('_NIM_IMAGECAT','Category:');
define('_NIM_IMAGEFILE','Image file:');
define('_NIM_IMAGE','Image:');
define('_NIM_IMGWEIGHT','Display order in image manager:');
define('_NIM_IMGDISPLAY','Display this image?');
define('_NIM_IMAGEID','Image Id:');
define('_NIM_IMAGEDATE','Creation date:');
define('_NIM_DATESTRING','D, d M Y H:i:s');
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
define('_NIM_IMAGEMIME','MIME type:');
define('_NIM_IMAGESIZE','Image size [width x height x size]:');
define('_NIM_IMAGESIZE_CONTENT','%spx x %spx x %sbytes');
define('_NIM_RUDELIMG','Are you sure that you want to delete this images file?');
define('_NIM_RUOVERWRITEIMG','Are you sure that you want to overwrite this image file?');

// Add/edit categories
define('_NIM_ADDIMGCAT','Add Image Category');
define('_NIM_EDITIMGCAT','Edit Image Category:');
define('_NIM_IMGCATNAME','Category Name:');
define('_NIM_IMGCATRGRP','Select groups for image manager use:<br /><br /><span style="font-weight: normal;">These are groups allowed to use the image manager for selecting images but not uploading. Webmaster has automatic access.</span>');
define('_NIM_IMGCATWGRP','Select groups allowed to upload images:<br /><br /><span style="font-weight: normal;">Typical usage is for moderator and admin groups.</span>');
define('_NIM_IMGCATWEIGHT','Display order in image manager:');
define('_NIM_IMGCATDISPLAY','Display this category?');
define('_NIM_IMGMAXSIZE','Max image size (bytes)?');
define('_NIM_IMGMAXWIDTH','Max image width (pixels)?');
define('_NIM_IMGMAXHEIGHT','max image height (pixels)?');
define('_NIM_IMGCATSTRTYPE','Images are uploaded to:');
define('_NIM_IMGCATSTRTYPE_DESC','This can not be changed afterwards!');
define('_NIM_INDB','Stored in the database (as binary "blob" data)');
define('_NIM_ASFILE','Stored as files<br />(in "%s" directory)');
define('_NIM_FOLDERNAME','Folder name');
define('_NIM_FOLDERNAME_DESC1','Do not use spaces or especial chars!');
define('_NIM_FOLDERNAME_DESC2','This can not be changed afterwards!');
define('_NIM_RUDELIMGCAT','Are you sure that you want to delete this category and all of its images files?');
?>