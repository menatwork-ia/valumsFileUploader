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
class valumsFeFileUpload extends FormFileUpload implements uploadable
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'form_valums';

    /**
     * Initialize the object and set configurations
     * @param array
     * @throws Exception
     */
    public function __construct($arrAttributes = FALSE)
    {
        parent::__construct($arrAttributes);

        $this->action = $GLOBALS['UPLOADER']['valumsFileUploader']['FE']['ACTION'];
        $this->params = "action: 'ffl', id: '" . $this->strId . "'";
        $this->debug = $GLOBALS['UPLOADER']['valumsFileUploader']['FE']['DEBUG'];
        
        $this->loadLanguageFile('default');
        $this->import('valumsHelper', 'helper');
        $this->import('valumsFileUploader', 'main');
        
        $_SESSION['VALUM_CONFIG'] = array(
            'uploadFolder' => $GLOBALS['UPLOADER']['valumsFileUploader']['FE']['TMP_FOLDER'],
            'maxFileLength' => $this->val_max_file_length,
            'extension' => $this->extensions,
            'doNotOverwrite' => $this->val_do_not_overwrite,
            'specialSessionAttr' => array(
                'formFieldId' => $this->Input->get('id'), 
                'formId' => $this->pid
            )       
        );
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
        $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['UPLOADER']['valumsFileUploader']['UPLOADER_JS'];
        $GLOBALS['TL_CSS'][] = $GLOBALS['UPLOADER']['valumsFileUploader']['UPLOADER_CSS'];

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
     * Call the real generateAjax in valumsFileUploader
     */
    public function generateAjax()
    {     
        $this->main->generateAjax();
    }    

}

?>