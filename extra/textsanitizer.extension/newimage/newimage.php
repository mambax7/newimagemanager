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
 * TextSanitizer extension
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: mp3.php 3575 2009-09-05 19:35:11Z trabis $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
xoops_loadLanguage('extention', 'newimagemanager');

class MytsNewimage extends MyTextSanitizerExtension
{
    function encode($textarea_id)
    {
        $code = "<img src='" . XOOPS_URL ."/modules/newimagemanager/images/image_button.png' title='" . _NIM_AM_NEWIMAGEBUTTON_ALT . "' alt='" . _NIM_AM_NEWIMAGEBUTTON_ALT . "'  onclick='xoopsCodeNewimage(\"{$textarea_id}\",\"" . htmlspecialchars(_NIM_AM_NEWIMAGEBUTTON_ENTERID, ENT_QUOTES) . "\");'  onmouseover='style.cursor=\"hand\"'/>&nbsp;";
        $javascript = <<<EOF
            function xoopsCodeNewimage(id, enterNewimagePhrase)
            {
                var selection = xoopsGetSelect(id);
                if (selection.length > 0) {
                    var text = selection;
                } else {
                    var text = prompt(enterNewimagePhrase, "");
                }
                var domobj = xoopsGetElementById(id);
                if ( text.length > 0 ) {
                    var result = "[newimg]" + text + "[/newimg]";
                    xoopsInsertText(domobj, result);
                }
                domobj.focus();
            }
EOF;

        return array(
            $code ,
            $javascript);
    }

    function load(&$ts)
    {
        $ts->patterns[] = "/\[newimg\](.*?)\[\/newimg\]/es";
        $ts->replacements[] = __CLASS__ . "::decode( '\\1' )";

        $ts->patterns[] = "/\[newimg (.*?)\](.*?)\[\/newimg\]/es";
        $ts->replacements[] = __CLASS__ . "::decode( '\\2', '\\1' )";

        return true;
    }

    function decode($id, $parameters = null)
    {
        $rp = "<img src='" . XOOPS_URL ."/modules/newimagemanager/image.php?id=" . $id . ($parameters != null ? "&amp;" . $parameters : "") . "'>";
        return $rp;
    }
}
?>
