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

require 'DC_Folder.php';

class DC_Upload extends DC_Folder
{

    /**
     * Generate the ajax uploader
     * @param boolean
     */
    public function move($blnIsAjax=false)
    {     
        $strFolder = $this->Input->get('pid', true);
        
        // Empty clipboard
        if (!$blnIsAjax)
        {
                $arrClipboard = $this->Session->get('CLIPBOARD');
                $arrClipboard[$this->strTable] = array();
                $this->Session->set('CLIPBOARD', $arrClipboard);
        }        
        
        $this->import('BackendUser', 'User');
        $uploader = $this->User->uploader;        
        
        if(array_key_exists($uploader, $GLOBALS['UPLOADER']))
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

        // Add uploader css and js
        if (version_compare(VERSION . '.' . BUILD, '2.10.0', '<'))
        {
            $GLOBALS['TL_CSS'][] = $arrUploader['UPLOADER_CSS'];
        }
        else
        {
            $GLOBALS['TL_CSS'][] = TL_PLUGINS_URL . $arrUploader['UPLOADER_CSS'];
        }
        
        if (version_compare(VERSION . '.' . BUILD, '2.10.0', '<'))
        {
            $GLOBALS['TL_JAVASCRIPT'][] = $arrUploader['UPLOADER_JS'];
        }
        else
        {
            $GLOBALS['TL_JAVASCRIPT'][] = TL_PLUGINS_URL . $arrUploader['UPLOADER_JS'];
        }
        
        if ($arrUploader['BE']['CSS'])
        {
            $GLOBALS['TL_CSS'][] = $arrUploader['BE']['CSS'];
        }

        // Create uploader template
        $objTemplate = new BackendTemplate($arrUploader['BE']['TEMPLATE']);

        // Add upload types and key
        $objTemplate->uploadTypes = trimsplit(',', strtolower($GLOBALS['TL_CONFIG']['uploadTypes']));
        $objTemplate->action = $arrUploader['BE']['ACTION'];
        $objTemplate->paramAction = $uploader; 
        $objTemplate->maxFileSize = $GLOBALS['TL_CONFIG']['maxFileSize'];
        $objTemplate->noJsBeLink = $this->Environment->scriptName . '?do=login';
        $objTemplate->debug = $this->User->uploader_debug;
        
        if(is_array($arrUploader['BE']['DATA']))
        {
            foreach($arrUploader['BE']['DATA'] AS $k => $v)
            {
                $objTemplate->$k = $v;
            }               
        }
            
        
        // Set config for valumsFileUploader
        $_SESSION['VALUM_CONFIG'] = array(
            'uploadFolder' => $strFolder,
            'maxFileLength' => $GLOBALS['TL_CONFIG']['maxFileSize'],
            'extension' => $GLOBALS['TL_CONFIG']['uploadTypes'],
            'doNotOverwrite' => ''
        );
        
        if($this->User->do_not_overwrite)
        {
            $_SESSION['VALUM_CONFIG']['doNotOverwrite'] = $this->User->do_not_overwrite_type;
        }
        
        $strReturn = '
            <div id="tl_buttons">
                <a href="' . $this->getReferer(true) . '" class="header_back" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['backBT']) . '" accesskey="b" onclick="Backend.getScrollOffset();">' . $GLOBALS['TL_LANG']['MSC']['backBT'] . '</a>
            </div>

            <h2 class="sub_headline">' . sprintf($GLOBALS['TL_LANG']['tl_files']['uploadFF'], basename($strFolder)) . '</h2>
            ' . $this->getMessages();
        
        $strReturn .=  $objTemplate->parse();
        
        // Display uploader
        return $strReturn;
    }

}

?>