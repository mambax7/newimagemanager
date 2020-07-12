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




class Newimagecategory extends XoopsObject
{
    var $_imageCount;

    /**
     * Constructor
     **/
    function Newimagecategory()
    {
        $this->XoopsObject();
        $this->initVar('imgcat_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('imgcat_name', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('imgcat_display', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('imgcat_weight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('imgcat_maxsize', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('imgcat_maxwidth', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('imgcat_maxheight', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('imgcat_type', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('imgcat_storetype', XOBJ_DTYPE_OTHER, null, false);
        $this->initVar('imgcat_relativepath', XOBJ_DTYPE_TXTBOX, null, true, 255);
        $this->initVar('imgcat_description', XOBJ_DTYPE_TXTAREA); // IN PROGRESS
        $this->initVar('imgcat_cattype', XOBJ_DTYPE_OTHER, null, false); // IN PROGRESS
        $this->initVar('imgcat_user_id', XOBJ_DTYPE_INT, null, false); // IN PROGRESS
        $this->initVar('imgcat_module_id', XOBJ_DTYPE_INT, null, false); // IN PROGRESS
        $this->initVar('imgcat_mimetypes', XOBJ_DTYPE_TXTAREA, null, false);
    }

    /**
     * Returns Class Base Variable imgcat_id
     */
    function id($format = 'N')
    {
        return $this->getVar('imgcat_id', $format);
    }

    /**
     * Returns Class Base Variable imgcat_id
     */
    function imgcat_id($format = '')
    {
        return $this->getVar('imgcat_id', $format);
    }

    /**
     * Returns Class Base Variable imgcat_name
     */
    function imgcat_name($format = '')
    {
        return $this->getVar('imgcat_name', $format);
    }

    /**
     * Returns Class Base Variable imgcat_display
     */
    function imgcat_display($format = '')
    {
        return $this->getVar('imgcat_display', $format);
    }

    /**
     * Returns Class Base Variable imgcat_weight
     */
    function imgcat_weight($format = '')
    {
        return $this->getVar('imgcat_weight', $format);
    }

    /**
     * Returns Class Base Variable imgcat_maxsize
     */
    function imgcat_maxsize($format = '')
    {
        return $this->getVar('imgcat_maxsize', $format);
    }

    /**
     * Returns Class Base Variable imgcat_maxwidth
     */
    function imgcat_maxwidth($format = '')
    {
        return $this->getVar('imgcat_maxwidth', $format);
    }

    /**
     * Returns Class Base Variable imgcat_maxheight
     */
    function imgcat_maxheight($format = '')
    {
        return $this->getVar('imgcat_maxheight', $format);
    }

    /**
     * Returns Class Base Variable imgcat_type
     */
    function imgcat_type($format = '')
    {
        return $this->getVar('imgcat_type', $format);
    }

    /**
     * Returns Class Base Variable imgcat_storetype
     */
    function imgcat_storetype($format = '')
    {
        return $this->getVar('imgcat_storetype', $format);
    }

    /**
     * Returns Class Base Variable imgcat_mimetypes
     */
    function imgcat_mimetypes($format = '')
    {
        return $this->getVar('imgcat_mimetypes', $format);
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $value
     */
    function setImageCount($value)
    {
        $this->_imageCount = intval($value);
    }

    /**
     * Enter description here...
     *
     * @return unknown
     */
    function getImageCount()
    {
        return $this->_imageCount;
    }

}

/**
 * XOOPS image caetgory handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS image category class objects.
 *
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 */

class newimagemanagerNewimagecategoryHandler extends XoopsObjectHandler
{
    /**
     * Create a new {@link XoopsImageCategory}
     *
     * @param   boolean $isNew  Flag the object as "new"
     * @return  object
     **/
    function &create($isNew = true)
    {
        $imgcat = new Newimagecategory();
        if ($isNew) {
            $imgcat->setNew();
        }
        return $imgcat;
    }

    /**
     * Load a {@link XoopsImageCategory} object from the database
     *
     * @param   int     $id     ID
     * @param   boolean $getbinary
     * @return  object  {@link XoopsImageCategory}, FALSE on fail
     **/
    function &get($id)
    {
        $id = intval($id);
        $imgcat = false;
        if ($id > 0) {
            $sql = "SELECT * ";
            $sql.= "FROM " . $this->db->prefix('newimagecategory') . " ";
            $sql.= "WHERE imgcat_id=" . $id;
            if (!$result = $this->db->query($sql)) {
                return $imgcat;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $imgcat = new Newimagecategory();
                $imgcat->assignVars($this->db->fetchArray($result));
            }
        }
        return $imgcat;
    }

    /**
     * Write a {@link XoopsImageCategory} object to the database
     *
     * @param   object  &$imgcat {@link XoopsImageCategory}
     * @return  bool
     **/
    function insert(&$imgcat)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($imgcat, 'newimagecategory')) {
            return false;
        }

        if (!$imgcat->isDirty()) {
            return true;
        }
        if (!$imgcat->cleanVars()) {
            return false;
        }
        foreach ($imgcat->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($imgcat->isNew()) {
            $imgcat_id = $this->db->genId($this->db->prefix('newimagecategory').'_imgcat_id_seq');
            $sql = sprintf("INSERT INTO %s (imgcat_id, imgcat_name, imgcat_display, imgcat_weight, imgcat_maxsize, imgcat_maxwidth, imgcat_maxheight, imgcat_type, imgcat_storetype, imgcat_relativepath, imgcat_mimetypes) VALUES (%u, %s, %u, %u, %u, %u, %u, %s, %s, %s, %s)", $this->db->prefix('newimagecategory'), $imgcat_id, $this->db->quoteString($imgcat_name), $imgcat_display, $imgcat_weight, $imgcat_maxsize, $imgcat_maxwidth, $imgcat_maxheight, $this->db->quoteString($imgcat_type), $this->db->quoteString($imgcat_storetype), $this->db->quoteString($imgcat_relativepath), $this->db->quoteString($imgcat_mimetypes));
            if ($imgcat_storetype == NIM_STORETYPE_AS_FILE) {
                // CREATE IMAGE FOLDER... IN PROGRESS
                if (!file_exists(XOOPS_ROOT_PATH . '/' . $imgcat_relativepath)) {
                    if (!makeDir(XOOPS_ROOT_PATH . '/' . $imgcat_relativepath))
                        return false;
                }
            }
        } 
        else {
            if ($imgcat_storetype == NIM_STORETYPE_AS_FILE) {
                // CHECK IF FOLDER EXIST... IN PROGRESS
                if (!file_exists(XOOPS_ROOT_PATH . '/' . $imgcat_relativepath))
                    return false;
                }
            $sql = sprintf("UPDATE %s SET imgcat_name = %s, imgcat_display = %u, imgcat_weight = %u, imgcat_maxsize = %u, imgcat_maxwidth = %u, imgcat_maxheight = %u, imgcat_type = %s, imgcat_relativepath = %s, imgcat_mimetypes = %s WHERE imgcat_id = %u", $this->db->prefix('newimagecategory'), $this->db->quoteString($imgcat_name), $imgcat_display, $imgcat_weight, $imgcat_maxsize, $imgcat_maxwidth, $imgcat_maxheight, $this->db->quoteString($imgcat_type), $this->db->quoteString($imgcat_relativepath), $this->db->quoteString($imgcat_mimetypes), $imgcat_id);
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($imgcat_id)) {
            $imgcat_id = $this->db->getInsertId();
        }
        $imgcat->assignVar('imgcat_id', $imgcat_id);
        return true;
    }

    /**
     * Delete a category from the database
     *
     * @param   object  &$imgcat {@link XoopsImageCategory}
     * @return  bool
     **/
    function delete(&$imgcat)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($imgcat, 'newimagecategory')) {
            return false;
        }

        $imgcat_id = $imgcat->getVar('imgcat_id');
        $categ_path = XOOPS_ROOT_PATH . '/' . $imgcat->getVar('imgcat_relativepath');
 
         // delete all images in category
        $image_handler =& xoops_getModuleHandler('newimage', 'newimagemanager');
        $images = $image_handler->getObjects(new Criteria('imgcat_id', $imgcat_id), true, false);
        foreach (array_keys($images) as $i) {
            if (!$image_handler->delete($images[$i])) {
                return false;
            }
        }

        // delete category folder if exist
/* 
IN PROGRESS
        if ($imgcat->getVar('imgcat_storetype') == NIM_STORETYPE_AS_FILE) {
            if (!delDir($categ_path, false)) {
                return false;
            }
        }
*/

        // delete category record
        $sql = sprintf("DELETE FROM %s WHERE imgcat_id = %u", $this->db->prefix('newimagecategory'), $imgcat_id);
        if (!$result = $this->db->query($sql)) {
            return false;
        }
    return true;
    }

    /**
     * Enter description here...
     *
     * @param unknown_type $criteria
     * @param unknown_type $id_as_key
     * @return unknown
     */
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = "SELECT DISTINCT c.* ";
        $sql.= "FROM " . $this->db->prefix('newimagecategory') . " c LEFT JOIN " . $this->db->prefix('group_permission') . " l ON l.gperm_itemid=c.imgcat_id ";
        $sql.= "WHERE (l.gperm_name = 'newimagemanager_cat_read' OR l.gperm_name = 'newimagemanager_cat_write')";
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $where = $criteria->render();
            $sql .= ($where != '') ? ' AND ' . $where : '';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $sql .= ' ORDER BY imgcat_weight, imgcat_id ASC';
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $imgcat = new Newimagecategory();
            $imgcat->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $imgcat;
            } else {
                $ret[$myrow['imgcat_id']] =& $imgcat;
            }
            unset($imgcat);
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
        $sql = "SELECT COUNT(*) ";
        $sql.= "FROM " . $this->db->prefix('newimagecategory') . " i LEFT JOIN " . $this->db->prefix('group_permission') . " l ON l.gperm_itemid=i.imgcat_id ";
        $sql.= "WHERE (l.gperm_name = 'newimagemanager_cat_read' OR l.gperm_name = 'newimagemanager_cat_write')";
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $where = $criteria->render();
            $sql .= ($where != '') ? ' AND ' . $where : '';
        }
        if (!$result =& $this->db->query($sql)) {
            return 0;
        }
        list ($count) = $this->db->fetchRow($result);
        return $count;
    }

    /**
     * Get a list of imagesCategories
     *
     * @param   int     $imgcat_id
     * @param   bool    $image_display
     * @return  array   Array of {@link XoopsImage} objects
     **/
    function getList($groups = array(), $perm = 'newimagemanager_cat_read', $display = null, $storetype = null)
    {
        $criteria = new CriteriaCompo();
        if (is_array($groups) && !empty($groups)) {
            $criteriaTray = new CriteriaCompo();
            foreach ($groups as $gid) {
                $criteriaTray->add(new Criteria('gperm_groupid', $gid), 'OR');
            }
            $criteria->add($criteriaTray);
            if ($perm == 'newimagemanager_cat_read' || $perm == 'newimagemanager_cat_write') {
                $criteria->add(new Criteria('gperm_name', $perm));
                $criteria->add(new Criteria('gperm_modid', 1));
            }
        }
        if (isset($display)) {
            $criteria->add(new Criteria('imgcat_display', intval($display)));
        }
        if (isset($storetype)) {
            $criteria->add(new Criteria('imgcat_storetype', $storetype));
        }
        $categories =& $this->getObjects($criteria, true);
        $ret = array();
        foreach (array_keys($categories) as $i) {
            $ret[$i] = $categories[$i]->getVar('imgcat_name');
        }
        return $ret;
    }
}
?>
