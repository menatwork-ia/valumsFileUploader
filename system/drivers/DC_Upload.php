<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 * Copyright (C) 2005-2010 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * PHP version 5
 * @copyright  MEN AT WORK 2011
 * @package    valumsFileUploader
 * @license    GNU/GPL 2 
 * @filesource
 */

require_once TL_ROOT . '/system/drivers/DC_Folder.php';

class DC_Upload extends DC_Folder
{

    /**
     * Generate the ajax uploader
     * @param boolean
     */
    public function move($blnIsAjax = false)
    {
        $objHelper = new valumsHelper();
        $strFolder = $this->Input->get('pid', true);

        // Empty clipboard
        if (!$blnIsAjax)
        {
            $arrClipboard = $this->Session->get('CLIPBOARD');
            $arrClipboard[$this->strTable] = array();
            $this->Session->set('CLIPBOARD', $arrClipboard);
        }

        $this->import('BackendUser', 'User');
        $uploader  = $this->User->uploader;
        $imageSize = deserialize($this->User->val_image_size);

        if (array_key_exists($uploader, $GLOBALS['UPLOADER']))
        {
            $arrUploader = $GLOBALS['UPLOADER'][$uploader];
        }
        else
        {
            if (!is_array($_SESSION['TL_ERROR']))
            {
                $_SESSION['TL_ERROR'] = array();
            }

            $_SESSION['TL_ERROR'][] = sprintf($GLOBALS['TL_LANG']['ERR']['val_wrong_config'], $uploader, $this->Environment->scriptName . '?do=login');
        }

        $arrAttributes = array(
            'path' => $strFolder,
            'template' => $arrUploader['BE']['TEMPLATE'],
            'action' => $arrUploader['BE']['ACTION'],
            'paramAction' => $uploader,
            'debug' => $this->User->uploader_debug,
            'doNotOverwrite' => $this->User->do_not_overwrite_type,
            'resize' => $imageSize,
            'tl_help' => TRUE
        );

        if (is_array($arrUploader['BE']['DATA']))
        {
            foreach ($arrUploader['BE']['DATA'] AS $k => $v)
            {
                $arrAttributes[$k] = $v;
            }
        }

        $strReturn = '
            <div id="tl_buttons">
                <a href="' . $this->getReferer(true) . '" class="header_back" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) . '" accesskey="b" onclick="Backend.getScrollOffset();">' . $GLOBALS['TL_LANG']['MSC']['backBT'] . '</a>
            </div>

            <h2 class="sub_headline">' . sprintf($GLOBALS['TL_LANG']['tl_files']['uploadFF'], basename($strFolder)) . '</h2>
            ' . $this->getMessages();

        $uploadWidget = new valumsBeFileUpload($arrAttributes);
        $strReturn .= $uploadWidget->parse();

        return $strReturn;
    }

}

?>