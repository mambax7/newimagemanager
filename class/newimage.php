<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
* @author  Rota Lucio <lucio.rota@gmail.com>
* @copyright copyright (c) 2000 XOOPS.org
**/

defined('XOOPS_ROOT_PATH') or die('Restricted access');
include_once('../include/common.php');
include_once('../include/functions.php');




class Newimage extends XoopsObject
{
    /**
     * Constructor
     **/
    function Newimage()
    {
        $this->XoopsObject();
        $this->initVar('image_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('image_name', XOBJ_DTYPE_OTHER, null, false, 30);
        $this->initVar('image_nicename', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('image_alternative', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('image_mimetype', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('image_created', XOBJ_DTYPE_INT, null, false);
        $this->initVar('image_display', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('image_weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('image_body', XOBJ_DTYPE_SOURCE, null, true);
        $this->initVar('imgcat_id', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('image_description', XOBJ_DTYPE_TXTAREA); // IN PROGRESS
    }

    /**
     * Returns Class Base Variable image_id
     */
    function id($format = 'N')
    {
        return $this->getVar('image_id', $format);
    }

    /**
     * Returns Class Base Variable image_id
     */
    function image_id($format = '')
    {
        return $this->getVar('image_id', $format);
    }

    /**
     * Returns Class Base Variable image_name
     */
    function image_name($format = '')
    {
        return $this->getVar('image_name', $format);
    }

    /**
     * Returns Class Base Variable image_nicename
     */
    function image_nicename($format = '')
    {
        return $this->getVar('image_nicename', $format);
    }

    /**
     * Returns Class Base Variable image_alternative
     */
    function image_alternative($format = '')
    {
        return $this->getVar('image_alternative', $format);
    }


    /**
     * Returns Class Base Variable image_mimetype
     */
    function image_mimetype($format = '')
    {
        return $this->getVar('image_mimetype', $format);
    }

    /**
     * Returns Class Base Variable image_created
     */
    function image_created($format = '')
    {
        return $this->getVar('image_created', $format);
    }

    /**
     * Returns Class Base Variable image_display
     */
    function image_display($format = '')
    {
        return $this->getVar('image_display', $format);
    }

    /**
     * Returns Class Base Variable image_weight
     */
    function image_weight($format = '')
    {
        return $this->getVar('image_weight', $format);
    }

    /**
     * Returns Class Base Variable image_Width
     */
    function image_width()
    {
        //$image_data = $this->getVar('image_body');
        //$source_image = imagecreatefromstring($image_data);
        //$image_width = imagesx($source_image);
        //return $image_width;
        return imagesx(imagecreatefromstring($this->getVar('image_body')));
    }

    /**
     * Returns Class Base Variable image_Height
     */
    function image_height()
    {
        return imagesy(imagecreatefromstring($this->getVar('image_body')));
    }

    /**
     * Returns Class Base Variable image_Size
     */
    function image_size()
    {
        return strlen($this->getVar('image_body'));
    }

    /**
     * Returns Class Base Variable image_body
     */
    function image_body($format = '')
    {
        return $this->getVar('image_body', $format);
    }

    /**
     * Returns Class Base Variable imgcat_id
     */
    function imgcat_id($format = '')
    {
        return $this->getVar('imgcat_id', $format);
    }

    /**
     * Returns Class Base Variable image_description
     */
    function image_description($format = '')
    {
        return $this->getVar('image_description', $format);
    }

}

/**
 * XOOPS image handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS image class objects.
 *
 * @package		kernel
 *
 * @author		Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	(c) 2000-2003 The Xoops Project - www.xoops.org
 */
class newimagemanagerNewimageHandler extends XoopsObjectHandler
{
    /**
     * Create a new {@link XoopsImage}
     *
     * @param   boolean $isNew  Flag the object as "new"
     * @return  object
     **/
    function &create($isNew = true)
    {
        $image = new Newimage();
        if ($isNew) {
            $image->setNew();
        }
        return $image;
    }

    /**
     * Load a {@link XoopsImage} object from the database
     *
     * @param   int     $id     ID
     * @param   boolean $getbinary
     * @return  object  {@link XoopsImage}, FALSE on fail
     **/
    function &get($id, $getbinary = true)
    {
        $image = false;
        $id = intval($id);
        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('newimage') . ' WHERE image_id=' . $id;
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $image = new Newimage();
                $image->assignVars($this->db->fetchArray($result));
                if ($getbinary == true) {
                    $imgcat_handler = xoops_getModuleHandler('newimagecategory', 'newimagemanager');
                    $imagecategory =& $imgcat_handler->get($image->getVar('imgcat_id'));
                    // if image is stored as file
                    if ($imagecategory->getVar('imgcat_storetype') == NIM_STORETYPE_DATABASE) {
                        // if image is stored in database
                        $sql = 'SELECT image_body FROM ' . $this->db->prefix('newimagebody') . ' WHERE image_id=' . $id;
                        if (!$result = $this->db->query($sql)) {
                            return false;
                        }
                        $myrow = $this->db->fetchArray($result);
                        $image->assignVars($this->db->fetchArray($result));
                    }
                    else {
                        // if image is stored as file
                        $image_path = XOOPS_ROOT_PATH . '/' . $imagecategory->getVar('imgcat_relativepath') . '/'  . $image->getVar('image_name');
                        $image->setVar('image_body', @file_get_contents($image_path), true);
                    }
                }
                
            }
        }
        return $image;
    }

    /**
     * Write a {@link XoopsImage} object to the database
     *
     * @param   object  &$image {@link XoopsImage}
     * @return  bool
     **/
    function insert(&$image)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($image, 'newimage')) {
            return false;
        }

        if (!$image->isDirty()) {
            return true;
        }
        if (!$image->cleanVars()) {
            return false;
        }
        foreach ($image->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($image->isNew()) {
            // if image is new
            $image_id = $this->db->genId($this->db->prefix('newimage').'_image_id_seq');
            $sql = sprintf("INSERT INTO %s (image_id, image_name, image_nicename, image_alternative, image_mimetype, image_created, image_display, image_weight, imgcat_id, image_description) VALUES (%u, %s, %s, %s, %s, %u, %u, %u, %u, %s)", $this->db->prefix('newimage'), $image_id, $this->db->quoteString($image_name), $this->db->quoteString($image_nicename), $this->db->quoteString($image_alternative), $this->db->quoteString($image_mimetype), time(), $image_display, $image_weight, $imgcat_id, $this->db->quoteString($image_description));
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            if (empty($image_id)) {
                $image_id = $this->db->getInsertId();
            }
            if (isset($image_body) && $image_body != '') {
                $imgcat_handler = xoops_getModuleHandler('newimagecategory', 'newimagemanager');
                $imagecategory =& $imgcat_handler->get($image->getVar('imgcat_id'));
                if ($imagecategory->getVar('imgcat_storetype') == NIM_STORETYPE_DATABASE) {
                    // if image is stored in database
                    $sql = sprintf("INSERT INTO %s (image_id, image_body) VALUES (%u, %s)", $this->db->prefix('newimagebody'), $image_id, $this->db->quoteString($image_body));
                    if (!$result = $this->db->query($sql)) {
                        $sql = sprintf("DELETE FROM %s WHERE image_id = %u", $this->db->prefix('newimage'), $image_id);
                        $this->db->query($sql);
                        return false;
                    }
                }
                else {
                    // if image is stored as file
                    $image_path = XOOPS_ROOT_PATH . '/' . $imagecategory->getVar('imgcat_relativepath') . '/'  . $image->getVar('image_name');
                    if (!file_put_contents($image_path, $image->getVar('image_body'))) {
                        $sql = sprintf("DELETE FROM %s WHERE image_id = %u", $this->db->prefix('newimage'), $image_id);
                        $this->db->query($sql);
                        return false;
                    }
                }
            }
            $image->assignVar('image_id', $image_id);
        }
        else {
            // if image is not new
            $sql = sprintf("UPDATE %s SET image_name = %s, image_nicename = %s, image_alternative = %s, image_display = %u, image_weight = %u, imgcat_id = %u, image_description = %s WHERE image_id = %u", $this->db->prefix('newimage'), $this->db->quoteString($image_name), $this->db->quoteString($image_nicename), $this->db->quoteString($image_alternative), $image_display, $image_weight, $imgcat_id, $this->db->quoteString($image_description), $image_id);
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            if (isset($image_body) && $image_body != '') {
                $imgcat_handler = xoops_getModuleHandler('newimagecategory', 'newimagemanager');
                $imagecategory =& $imgcat_handler->get($image->getVar('imgcat_id'));
                if ($imagecategory->getVar('imgcat_storetype') == NIM_STORETYPE_DATABASE) {
                    // if image is stored in database
                    $sql = sprintf("UPDATE %s SET image_body = %s WHERE image_id = %u", $this->db->prefix('newimagebody'), $this->db->quoteString($image_body), $image_id);
                    if (!$result = $this->db->query($sql)) {
                        $this->db->query(sprintf("DELETE FROM %s WHERE image_id = %u", $this->db->prefix('newimage'), $image_id));
                        return false;
                    }
                }
                else {
                    // if image is stored as file
                    $image_path = XOOPS_ROOT_PATH . '/' . $imagecategory->getVar('imgcat_relativepath') . '/'  . $image->getVar('image_name');
                    if (!file_put_contents($image_path, $image->getVar('image_body'))) {
                        $this->db->query(sprintf("DELETE FROM %s WHERE image_id = %u", $this->db->prefix('newimage'), $image_id));                        return false;
                    }
                }
            }
        }
        return true;
    }

    /**
     * Delete an image from the database
     *
     * @param   object  &$image {@link XoopsImage}
     * @return  bool
     **/
    function delete(&$image)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($image, 'newimage')) {
            return false;
        }

        $id = $image->getVar('image_id');
        $sql = sprintf("DELETE FROM %s WHERE image_id = %u", $this->db->prefix('newimage'), $id);
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        $imgcat_handler = xoops_getModuleHandler('newimagecategory', 'newimagemanager');
        $imagecategory =& $imgcat_handler->get($image->getVar('imgcat_id'));
        if ($imagecategory->getVar('imgcat_storetype') == NIM_STORETYPE_DATABASE) {
            // if image is stored in database
            $sql = sprintf("DELETE FROM %s WHERE image_id = %u", $this->db->prefix('newimagebody'), $id);
            $this->db->query($sql);
        }
        else {
            // if image is stored as file
            $image_path = XOOPS_ROOT_PATH . '/' . $imagecategory->getVar('imgcat_relativepath') . '/'  . $image->getVar('image_name');
            @unlink($image_path);
        }
        return true;
    }

    /**
     * Load {@link XoopsImage}s from the database
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @param   boolean $id_as_key  Use the ID as key into the array
     * @param   boolean $getbinary
     * @return  array   Array of {@link XoopsImage} objects
     **/
    function getObjects($criteria = null, $id_as_key = false, $getbinary = false)
    {
        $ret = array();
        $limit = $start = 0;
        if ($getbinary) {
            $sql = 'SELECT i.*, b.image_body FROM ' . $this->db->prefix('newimage') . ' i LEFT JOIN ' . $this->db->prefix('newimagebody') . ' b ON b.image_id=i.image_id';
        } else {
            $sql = 'SELECT * FROM ' . $this->db->prefix('newimage');
        }
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            $sort = ! in_array($criteria->getSort(), array(
                'image_id' ,
                'image_created' ,
                'image_mimetype' ,
                'image_display' ,
                'image_weight')) ? 'image_weight' : $criteria->getSort();
            $sql .= ' ORDER BY ' . $sort . ' ' . $criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $image = new Newimage();
            $image->assignVars($myrow);
            if ($getbinary) {
                $imgcat_handler = xoops_getModuleHandler('newimagecategory', 'newimagemanager');
                $imagecategory =& $imgcat_handler->get($image->getVar('imgcat_id'));
                if ($imagecategory->getVar('imgcat_storetype') == NIM_STORETYPE_DATABASE) {
                    // if image is stored in database
                    // NOP
                }
                else {
                    // if image is stored as file
                    $image_path = XOOPS_ROOT_PATH . '/' . $imagecategory->getVar('imgcat_relativepath') . '/'  . $image->getVar('image_name');
                    $image->setVar('image_body', @file_get_contents($image_path), true);
                }
            }
            if (!$id_as_key) {
                $ret[] =& $image;
            } else {
                $ret[$myrow['image_id']] =& $image;
            }
            unset($image);
        }
        return $ret;
    }

    /**
     * Count some images
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @return  int
     **/
    function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('newimage');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
        }
        if (!$result =& $this->db->query($sql)) {
            return 0;
        }
        list ($count) = $this->db->fetchRow($result);
        return $count;
    }

    /**
     * Get a list of images
     *
     * @param   int     $imgcat_id
     * @param   bool    $image_display
     * @return  array   Array of {@link XoopsImage} objects
     **/
    function getList($imgcat_id, $image_display = null)
    {
        $criteria = new CriteriaCompo(new Criteria('imgcat_id', intval($imgcat_id)));
        if (isset($image_display)) {
            $criteria->add(new Criteria('image_display', intval($image_display)));
        }
        $images = $this->getObjects($criteria, false, true);
        $ret = array();
        foreach (array_keys($images) as $i) {
            $ret[$images[$i]->getVar('image_name')] = $images[$i]->getVar('image_nicename');
        }
        return $ret;
    }
}
?>
