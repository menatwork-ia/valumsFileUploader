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
class valumsFileUploader extends FormFileUpload implements uploadable
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'valums_form_widget';

    /**
     * Initialize the object and set configurations
     * @param array
     * @throws Exception
     */
    public function __construct($arrAttributes = FALSE)
    {
        parent::__construct($arrAttributes);

        $this->action = "'ajax.php?action=ffl&id=" . $this->strId . "'";
        $this->debug = ($GLOBALS['valumsFileUploader']['DEBUG'] ? 'true' : 'false');

        $this->loadLanguageFile('default');
    }

    /**
     * Add specific attributes and Store config for ajax upload.
     * @param string
     * @param mixed
     */
    public function __set($strKey, $varValue)
    {
        if ($strKey == 'id')
        {
            $_SESSION['AJAX-FFL'][$varValue] = array('type' => 'valumsFileUploader');
        }
        $_SESSION['AJAX-FFL'][$this->strId][$strKey] = $varValue;
        parent::__set($strKey, $varValue);
    }

    /**
     * Validate input and set value.
     * If JavaScript is disabled call parent
     */
    public function validate()
    {
        if ($this->mandatory)
        {
            if ((!$_SESSION['VALUM_FILES'] || !count($_SESSION['VALUM_FILES'])) && (!isset($_FILES[$this->strName]) || empty($_FILES[$this->strName]['name'])))
            {
                $this->addError(sprintf($GLOBALS['TL_LANG']['ERR']['mandatory'], $this->strLabel));
            }

            $this->mandatory = false;
        }

        // Call parent validate() to upload files without javascript.
        return parent::validate();
    }

    /**
     * Generate the widget and return it as string
     * @return string
     */
    public function generate()
    {
        // Include ValumsFileUploader scripts
        $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['valumsFileUploader']['AJAX_UPLOADER_JS'];
        $GLOBALS['TL_CSS'][] = $GLOBALS['valumsFileUploader']['AJAX_UPLOADER_CSS'];

        $return = sprintf('
            <div id="file-uploader-%s" class="%s">       
                <noscript>          
                    <input type="file" name="%s" id="ctrl_%s" class="upload%s"
                </noscript>         
            </div>', $this->strId, $this->strClass, $this->strName, $this->strId, (strlen($this->strClass) ? ' ' . $this->strClass : '')
        );

        $tmpReturn = '';

        // Check for uploaded files for this formField
        if ($_SESSION['VALUM_FILES'])
        {
            foreach ($_SESSION['VALUM_FILES'] as $arrFile)
            {
                if ($arrFile['formFieldId'] == $this->id)
                {
                    $tmpReturn .= '<li class=" qq-upload-success">
                            <span class="qq-upload-file">' . $arrFile['name'] . '</span>
                            <span class="qq-upload-size" style="display: inline;">' . number_format(($arrFile['size'] / 1024) / 1024, 1, '.', ',') . 'MB</span>
                            <span class="qq-upload-failed-text">' . $arrFile['error'] . '</span>
                        </li>';
                }
            }
        }

        // Add already uploaded files
        if ($tmpReturn != '')
        {
            $return .= '<ul class="qq-upload-list">';
            $return .= $tmpReturn;
            $return .= '</ul>
                </div>';
        }

        return $return . $this->addSubmit();
    }

    /**
     * Get the file information, checked specific values and save the file temporary 
     * @return array
     */
    public function generateAjax()
    {
        $objFile = new valumsFile();
        $strLogPos = __CLASS__ . " " . __FUNCTION__ . "()";

        // Check if file could not create
        if ($objFile->error)
        {
            $this->setJsonEncode('ERR', 'val_no_file', array(), $strLogPos, array("success" => FALSE, "reason" => $GLOBALS['TL_LANG']['ERR']['val_no_file']));
        }

        // Check if folder is writeable
        if (!is_writable($objFile->uploadFolder))
        {
            $this->setJsonEncode('ERR', 'val_not_writeable', array(), $strLogPos, array("success" => FALSE, "reason" => $GLOBALS['TL_LANG']['ERR']['val_not_writeable']));
        }

        // Check for empty file
        if ($objFile->size == 0)
        {
            $this->setJsonEncode('ERR', 'val_file_size_zero', array(), $strLogPos, array("success" => FALSE, "reason" => $GLOBALS['TL_LANG']['ERR']['val_file_size_zero']));
        }

        // Check file size 
        if ($this->maxfilelength > 0 && $objFile->size > $this->maxfilelength)
        {
            $this->setJsonEncode('ERR', 'val_max_size', array(), $strLogPos, array("success" => FALSE, "reason" => vsprintf($GLOBALS['TL_LANG']['ERR']['val_max_size'], array($this->getSizeLimit()))));
        }

        // Check file type
        if (!in_array(strtolower($objFile->getPathInfo('extension')), $this->getExtensions('array')))
        {
            $this->setJsonEncode('ERR', 'val_wrong_type', array(), $strLogPos, array("success" => FALSE, "reason" => vsprintf($GLOBALS['TL_LANG']['ERR']['val_wrong_type'], array($objFile->getPathInfo('extension'), $this->getExtensions()))));
        }

        // Check if save was successful
        if (!$objFile->save($this->doNotOverwriteExt))
        {
            $this->setJsonEncode('ERR', 'val_save_error', array(), $strLogPos, array("success" => FALSE, "reason" => $GLOBALS['TL_LANG']['ERR']['val_save_error']));
        }

        $objFile->writeFileToSession(array('formFieldId' => $this->Input->get('id'), 'formId' => $this->pid));

        $this->setJsonEncode('UPL', 'log_success', array($objFile->newName, $objFile->uploadFolder), $strLogPos, array("success" => TRUE, "filename" => $objFile->newName));
    }

    /**
     *
     * @param type $type
     * @param type $strMessage
     * @param type $arrLog
     * @param type $strLogPos
     * @param type $arrJson 
     */
    private function setJsonEncode($type, $strMessage, $arrLog, $strLogPos, $arrJson)
    {
        $this->log(vsprintf($GLOBALS['TL_LANG'][$type][$message], $arrLog), $strLogPos, ($type == 'ERR') ? TL_ERROR : TL_FILES);
        echo json_encode($arrJson);
        exit;
    }

    /**
     * Get the configured file extensions and return them specified by the param as array or as string
     * @param string $type 'array' : 'string'
     * @return mixed 
     */
    public function getExtensions($type = 'string')
    {
        $uploadTypes = trimsplit(',', $this->extensions);

        if (count($uploadTypes))
        {
            if ($type == 'array')
            {
                return $uploadTypes;
            }
            return "'" . implode("', '", $uploadTypes) . "'";
        }
        else
        {
            if ($type == 'array')
            {
                return array();
            }
            return '';
        }
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
                'ajax-upload' => 'plugins/ajax-upload/js/ajaxupload.js'
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