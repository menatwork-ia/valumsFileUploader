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

/**
 * Class ValumsHelper
 */
class ValumsHelper extends Backend
{

    /**
     * Initialize the object
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set the json encode with the given params
     * 
     * @param string $type
     * @param string $strMessage
     * @param array $arrLog
     * @param string $strLogPos
     * @param array $arrJson 
     */
    public function setJsonEncode($type, $strMessage, $arrLog, $strLogPos, $arrJson)
    {
        $this->log(vsprintf($GLOBALS['TL_LANG'][$type][$strMessage], $arrLog), $strLogPos, ($type == 'ERR') ? TL_ERROR : TL_FILES);
        echo json_encode($arrJson);
        exit();
    }

    /**
     * Return the given comma separatet extensions as string
     * 
     * @param string $extension
     * @return string 
     */
    public function getStrExt($extension)
    {
        return "'" . implode("', '", $this->getArrExt($extension)) . "'";
    }

    /**
     * Return the given comma separatet extensions as array
     * 
     * @param string $extension
     * @return array 
     */
    public function getArrExt($extension)
    {
        return trimsplit(',', $extension);
    }

    /**
     * Check the required extensions and files
     * 
     * HOOK: $GLOBALS['TL_HOOKS']['parseBackendTemplate']
     * 
     * @param string $strContent
     * @param string $strTemplate
     * @return string
     */
    public function checkExtensions($strContent, $strTemplate)
    {
        if ($strTemplate == 'be_main')
        {
            if (!is_array($_SESSION["TL_INFO"]))
            {
                $_SESSION["TL_INFO"] = array();
            }

            // required files
            $arrRequiredFiles = array(
                'ajax-upload' => $GLOBALS['UPLOADER']['valumsFileUploader']['UPLOADER_JS'],
                'ajax' => 'ajax.php',
            );

            // check for required files
            foreach ($arrRequiredFiles as $key => $val)
            {
                if (!file_exists(TL_ROOT . '/' . $val))
                {
                    $_SESSION["TL_INFO"] = array_merge($_SESSION["TL_INFO"], array($val => 'Please install the required file/extension <strong>' . $key . '</strong>'));
                }
                else
                {
                    if (is_array($_SESSION["TL_INFO"]) && key_exists($val, $_SESSION["TL_INFO"]))
                    {
                        unset($_SESSION["TL_INFO"][$val]);
                    }
                }
            }
        }

        return $strContent;
    }

    /**
     * Set the GET-Param for the user id so the subpalette can work
     * 
     * @param string
     */
    public function setUser($strTable)
    {
        if ($strTable == 'tl_user' && $this->Input->get('do') == 'login')
        {
            $this->import('BackendUser', 'User');
            $this->Input->setGet('id', $this->User->id);
        }
    }

    /**
     * Add uploader css and js
     * 
     * @param array uploader
     */
    public static function setHeaderData()
    {
        if (version_compare(VERSION, '2.10', '<'))
        {
            $GLOBALS['TL_CSS'][] = 'plugins/ajax-upload/css/ajaxupload.css';
        }
        else
        {
            $GLOBALS['TL_CSS'][] = TL_PLUGINS_URL . 'plugins/ajax-upload/css/ajaxupload.css';
        }

        if (version_compare(VERSION, '2.10', '<'))
        {
            $GLOBALS['TL_JAVASCRIPT'][] = 'plugins/ajax-upload/js/ajaxupload.js';
        }
        else
        {
            $GLOBALS['TL_JAVASCRIPT'][] = TL_PLUGINS_URL . 'plugins/ajax-upload/js/ajaxupload.js';
        }

        if (TL_MODE == 'BE' && Input::getInstance()->get('do') != 'form')
        {
            $GLOBALS['TL_CSS'][] = 'system/modules/valumsFileUploader/html/css/valumsFileUploader.css';
            $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/valumsFileUploader/html/js/valumsFileUploader.js';
        }
    }
    
    /**
     * Formated the given bytes in a readable size
     * 
     * @param  $bytes
     * @return string 
     */
    public function getFormatedSize($bytes)
    {        
        $desc = array('kB', 'MB', 'GB', 'TB', 'PB', 'EB');
        
        $i = -1;        
        do {
            $bytes = $bytes / 1024;
            $i++;  
        } while ($bytes > 99);
        
        if($bytes > 0.1)
        {
            return round($bytes, 1) . $desc[$i];
        }
        else
        {
            return 0.1 . $desc[$i];
        }
    }    

}

?>