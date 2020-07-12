<?php
defined('XOOPS_ROOT_PATH') or die('Restricted access');
include_once('../include/common.php');
include_once('../include/functions.php');




class NewimgOption extends XoopsObject
{
    /**
     * Constructor
     **/
// constructor
	function __construct()
	{
        $this->XoopsObject();
        $this->initVar('option_id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('option_name', XOBJ_DTYPE_TXTBOX, null, false, 30);
        $this->initVar('option_value', XOBJ_DTYPE_TXTBOX, null, true, 100);
        $this->initVar('option_description', XOBJ_DTYPE_TXTAREA);
    }
    function NewimgOption()
    {
        $this->__construct();
    }

    /**
     * Returns Class Base Variable option_id
     */
    function id($format = 'N')
    {
        return $this->getVar('option_id', $format);
    }

    /**
     * Returns Class Base Variable option_id
     */
    function option_id($format = 'N')
    {
        return $this->getVar('option_id', $format);
    }

    /**
     * Returns Class Base Variable option_name
     */
    function option_name($format = '')
    {
        return $this->getVar('option_name', $format);
    }

    /**
     * Returns Class Base Variable option value
     */
    function option_value($format = '')
    {
        return $this->getVar('option_value', $format);
    }

    /**
     * Returns Class Base Variable option description
     */
    function option_description($format = '')
    {
        return $this->getVar('option_description', $format);
    }

}



class newimagemanagerNewimgOptionHandler extends XoopsObjectHandler
{
    /**
     * Create a new {@link NewimgOption}
     *
     * @param   boolean $isNew  Flag the object as "new"
     * @return  object
     **/
    function &create($isNew = true)
    {
        $option = new NewimgOption();
        if ($isNew) {
            $option->setNew();
        }
        return $option;
    }

    /**
     * Load a {@link NewimgOption} object from the database
     *
     * @param   int     $id     ID
     * @return  object  {@link NewimgOption}, FALSE on fail
     **/
    function &get($id)
    {
        $option = false;
        $id = intval($id);
        if ($id > 0) {
            $sql = 'SELECT * FROM ' . $this->db->prefix('newimgoptions') . ' WHERE option_id=' . $id;
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $option = new NewimgOption();
                $option->assignVars($this->db->fetchArray($result));
                
            }
        }
        return $option;
    }

    /**
     * Write a {@link NewimgOption} object to the database
     *
     * @param   object  &$option {@link NewimgOption}
     * @return  bool
     **/
    function insert(&$option)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($option, 'newimgoption')) {
            return false;
        }

        if (!$option->isDirty()) {
            return true;
        }
        if (!$option->cleanVars()) {
            return false;
        }
        foreach ($option->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($option->isNew()) {
            // if option is new
            $option_id = $this->db->genId($this->db->prefix('newimgoptions').'_option_id_seq');
            $sql = sprintf("INSERT INTO %s (option_id, option_name, option_value, option_description) VALUES (%u, %s, %s, %s)", $this->db->prefix('newimgoptions'), $option_id, $this->db->quoteString($option_name), $this->db->quoteString($option_value), $this->db->quoteString($option_description));
            if (!$result = $this->db->query($sql)) {
                return false;
            }
            if (empty($option_id)) {
                $option_id = $this->db->getInsertId();
            }

            $image->assignVar('option_id', $option_id);
        }
        else {
            // if option is not new
            $sql = sprintf("UPDATE %s SET option_name = %s, option_value = %s, option_description = %s WHERE option_id = %u", $this->db->prefix('newimgoptions'), $this->db->quoteString($option_name), $this->db->quoteString($option_value), $this->db->quoteString($option_description), $option_id);
            if (!$result = $this->db->query($sql)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Delete an image from the database
     *
     * @param   object  &$image {@link NewimgOption}
     * @return  bool
     **/
    function delete(&$option)
    {
        /**
         * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
         */
        if (!is_a($option, 'newimgoption')) {
            return false;
        }

        $id = $option->getVar('option_id');
        $sql = sprintf("DELETE FROM %s WHERE option_id = %u", $this->db->prefix('newimgoptions'), $id);
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }

    /**
     * Load {@link NewimgOption}s from the database
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @param   boolean $id_as_key  Use the ID as key into the array
     * @return  array   Array of {@link NewimgOption} objects
     **/
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;

        $sql = 'SELECT * FROM ' . $this->db->prefix('newimgoptions');

        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            $sort = ! in_array($criteria->getSort(), array(
                'option_id' ,
                'option_name' ,
                'option_value' ,
                'option_description')) ? 'option_id' : $criteria->getSort();
            $sql .= ' ORDER BY ' . $sort . ' ' . $criteria->getOrder();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $option = new NewimgOption();
            $option->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $option;
            } else {
                $ret[$myrow['option_id']] =& $option;
            }
            unset($option);
        }
        return $ret;
    }

    /**
     * Count some options
     *
     * @param   object  $criteria   {@link CriteriaElement}
     * @return  int
     **/
    function getCount($criteria = null)
    {
        $sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('newimgoptions');
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
     * Get a list of options
     *
     * @return  array   Array of {@link NewimgOption} objects
     **/
    function getList()
    {
        $options = $this->getObjects();
        $ret = array();
        foreach (array_keys($options) as $i) {
            $ret[$options[$i]->getVar('option_name')] = $options[$i]->getVar('option_value');
        }
        return $ret;
    }
}
?>
