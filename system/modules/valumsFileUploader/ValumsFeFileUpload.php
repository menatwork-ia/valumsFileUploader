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
 * Class ValumsFeFileUpload
 */
class ValumsFeFileUpload extends FormFileUpload implements uploadable
{

    /**
     * Template
     * 
     * @var string
     */
    protected $strTemplate = 'form_valums';

    /**
     * Objects
     * 
     * @var type 
     */
    protected $objHelper;
    protected $objUploader;
    protected $objInput;
    protected $objSession;

    /**
     * Initialize the object and set configurations
     * 
     * @param array
     */
    public function __construct($arrAttributes = FALSE)
    {
        parent::__construct($arrAttributes);
        
        $this->objInput = Input::getInstance();

        $this->objHelper = new ValumsHelper();
        $this->objHelper->setHeaderData();
        
        $this->objSession = Session::getInstance();        

        $this->objUploader = new ValumsFileUploader();
    }

    /**
     * Add specific attributes and Store config for ajax upload.
     * 
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
        switch ($strKey)
        {
            case 'id':
                $this->cssID = $varValue;
                $this->strId = $varValue;
                break;            
            case 'class':
                $this->class = $varValue;
                $this->strClass = $varValue;
                break;
            case 'val_uploader_debug':
                $this->debug = $varValue;
                break;
            case 'details_failure_message':
                $this->detailsFailureMessage = $varValue;
                break;
            case 'val_max_file_length':
                $this->maxFileSize = $varValue;
                break;
            case 'val_drop_text':
                $this->dropTextLabel = $varValue;
                break;
            case 'val_init_text':
                $this->dropButtonLabel = $varValue;
                break;
            case 'max_file_count':                
                $this->maxFileCount = $varValue;
                break;            
            case 'allow_delete':
                $this->allowDelete = $varValue;
                break;
            case 'val_do_not_overwrite':
                $this->doNotOverwrite = $varValue;
                break;
            case 'resize_resolution':
                $this->resizeResolution = $varValue;
                break;
            case 'val_image_size':
                $this->imageSize = $varValue;
                break;
            default:
                parent::__set($strKey, $varValue);
                break;
        }                
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
     * 
     * @return string
     */
    public function generate(){}

    /**
     * Parse the template file and return it as string
     * 
     * @param array
     * @return string
     */
    public function parse($arrAttributes = false)
    {
        $this->setDefaultValues();
        $this->setSessionData();

        return parent::parse($arrAttributes);
    }

    /**
     * Set all necessary session information
     */
    protected function setSessionData()
    {
        $arrConf = array(
            'fileCount' => ((count($_SESSION['VALUM_FILES']) > 0) ? count($_SESSION['VALUM_FILES']) : 0),
            'maxFileCount' => $this->maxFileCount,            
            'uploadFolder' => 'system/tmp',
            'maxFileLength' => $this->maxFileSize,
            'extension' => $this->extensions,
            'doNotOverwrite' => $this->doNotOverwrite,
            'resizeResolution' => (($this->resizeResolution) ? TRUE : FALSE)
        );

        $imageSize = deserialize($this->imageSize);
        if (is_array($imageSize) && $imageSize[0] != '' && $imageSize[1] != '')
        {
            $arrConf['imageSize'] = $imageSize;
        }
        
        $this->objSession->set('VALUM_CONFIG', $arrConf);
    }

    /**
     * Set all values that are necessary
     */
    protected function setDefaultValues()
    {
        $this->uploaderId       = 'file-uploader-' . $this->strId;        
        $this->action           = 'ajax.php';
        $this->actionParam      = 'ffl';
        $this->params           = "{action: 'ffl', id: '" . $this->strId . "', type:'valumsFileUploader'}";
        $this->pos              = 'fe';
        $this->arrSessionFiles  = $_SESSION['VALUM_FILES'];
    }

    /**
     * Process ajax request
     */
    public function generateAjax()
    {
        $arrConf = $this->objSession->get('VALUM_CONFIG');
        $arrConf['specialSessionAttr']['formFieldId'] = $this->Input->get('id');
        $arrConf['specialSessionAttr']['formId'] = $this->pid;
        $this->objSession->set('VALUM_CONFIG', $arrConf);

        if($this->objInput->get('value') == 'deleteFile')
        {
            $this->objUploader->deleteFile($this->objInput->get('file'));
        }
        else
        {
            $this->objUploader->generateAjax();
        }
    }

}

?>