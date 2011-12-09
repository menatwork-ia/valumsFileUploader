<?php
if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
class valumsHelper extends Backend
{

    /**
     *
     * @param type $type
     * @param type $strMessage
     * @param type $arrLog
     * @param type $strLogPos
     * @param type $arrJson 
     */
    public function setJsonEncode($type, $strMessage, $arrLog, $strLogPos, $arrJson)
    {
        $this->log(vsprintf($GLOBALS['TL_LANG'][$type][$message], $arrLog), $strLogPos, ($type == 'ERR') ? TL_ERROR : TL_FILES);
        echo json_encode($arrJson);
        exit;
    }

    /**
     * Return the given comma separatet extensions as string
     * @param string $extension
     * @return string 
     */
    public function getStrExt($extension)
    {                
        return "'" . implode("', '", $this->getArrExt($extension)) . "'";
    }
    
    /**
     * Return the given comma separatet extensions as array
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
                'ajax-upload' => $GLOBALS['UPLOADER']['valumsFileUploader']['UPLOADER_JS']
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

}

?>