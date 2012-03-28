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
 * @copyright  MEN AT WORK 2012
 * @package    valumsFileUploader
 * @license    GNU/GPL 2
 * @filesource
 */

/**
 * Class ValumsBeFileUpload
 */
class ValumsBeFileUpload extends Widget
{

    /**
     * Submit user input
     * @var boolean
     */
    protected $blnSubmitInput = true;

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_valums';

    /**
     * Objects
     * @var type 
     */
    protected $objHelper;
    protected $objUploader;
    protected $objBeUser;
    protected $objInput;
    protected $objEnvironment;
    protected $objDatabase;

    /**
     * Initialize the object and set configurations
     * 
     * @param array
     */
    public function __construct($arrAttributes = FALSE)
    {
        parent::__construct($arrAttributes);

        $this->objInput = Input::getInstance();
        $this->objEnvironment = Environment::getInstance();

        $this->objDatabase = Database::getInstance();

        if(!$this->isFileManager())
        {
            // Specific configurations for backend widget
            $this->strTemplate = 'be_valums_widget';
        }
        else
        {
            // Specific configurations for filemanager
            $this->removeSessionData();
        }

        $this->objHelper = new ValumsHelper();
        $this->objHelper->setHeaderData(($this->css) ? array('css' => $this->css) : FALSE);

        $this->objUploader = new ValumsFileUploader();
        $this->objBeUser = BackendUser::getInstance();
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

    public function generate()
    {
        
    }

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
        $_SESSION['VALUM_CONFIG'] = array(
            'fileCount' => 0,
            'maxFileCount' => $this->maxFileCount,
            'uploadFolder' => $this->path,
            'maxFileLength' => $this->maxFileSize,
            'extension' => $this->extensions,
            'doNotOverwrite' => (($this->doNotOverwrite) ? $this->doNotOverwrite : 'overwriteFile'),
            'resizeResolution' => $this->resizeResolution,
            'imageSize' => $this->imageSize
        );
    }

    /**
     * Remove the uploaded files from session 
     */
    protected function removeSessionData()
    {
        if (is_array($_SESSION['VALUM_FILES']))
        {
            unset($_SESSION['VALUM_FILES']);
        }

        if (is_array($_SESSION['VALUM_DB_FILES']))
        {
            unset($_SESSION['VALUM_DB_FILES']);
        }
    }

    /**
     * Set all necessary values
     */
    protected function setDefaultValues()
    {
        $this->uploaderId   = 'file-uploader';
        $this->action       = 'system/modules/valumsFileUploader/ValumsAjaxRequest.php';
        $this->actionParam  = 'valumsFileUploader';
        $this->params       = "{action: 'valumsFileUploader'}";
        $this->noJsBeLink   = $this->Environment->scriptName . '?do=login';
        $this->pos          = 'be';
        $this->maxFileSize  = (($this->maxFileSize) ? $this->maxFileSize : $GLOBALS['TL_CONFIG']['maxFileSize']);
                
        if($this->isFileManager())
        {
            $this->detailsFailureMessage    = $this->objBeUser->details_failure_message;
            $this->doFiles                  = TRUE;
            $this->path                     = $this->objInput->get('pid');
            $this->debug                    = $this->objBeUser->uploader_debug;
            $this->allowDelete              = $this->objBeUser->allow_delete;
            if ($this->objBeUser->do_not_overwrite)
                $this->doNotOverwrite       = $this->objBeUser->do_not_overwrite_type;
            if ($this->objBeUser->resize_resolution)
            {
                $this->resizeResolution     = $this->objBeUser->resize_resolution;
                $resize = deserialize($this->objBeUser->val_image_size);
                if (is_array($resize) && strlen($resize[0]) > 0 && strlen($resize[1]))
                    $this->imageSize        = $resize;
            }            
        }
        else
        {
            $this->doFiles                  = FALSE;                      
            if (!is_null($this->overwrite)) 
                $this->doNotOverwrite       = $this->overwrite;
            if ($this->resize != NULL)
            {
                $this->resizeResolution = TRUE;
                if (is_array($this->resize) && strlen($this->resize[0]) > 0 && strlen($this->resize[1]))
                    $this->imageSize = $this->resize;
            }         
        }
        
        if (is_null($this->path)) 
        {
            $this->path = 'system/tmp';
        }        
            
        if (is_null($this->extensions))
        {
            $this->extensions = strtolower($GLOBALS['TL_CONFIG']['uploadTypes']);
        }
        
        if ($this->name != $this->label)
        {
            $this->dropButtonLabel = $this->label;
        }
    }
    
    /**
     * Return true if the current module is in File Manager and false if is 
     * Be-Widget
     * 
     * @return boolean
     */
    public function isFileManager()
    {
        if($this->objInput->get('do') == 'files' || strstr($this->objEnvironment->request, 'contao/files.php'))
        {
            return TRUE;
        }
        return FALSE;
    }    

    /**
     * Process ajax request
     * 
     * @param string $strAction 
     */
    public function generateAjax($strAction)
    {
        if ($strAction == 'valumsFileUploader')
        {
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

    /**
     * Necessary function for DC_Folder
     * 
     * @param string $strFolder
     * @param string $strType
     * @return array 
     */
    public function uploadTo($strFolder, $strType)
    {
        return array();
    }

    /**
     * Necessary function for DC_Folder
     * 
     * @return boolean
     */
    public function hasError()
    {
        return FALSE;
    }

    /**
     * Necessary function for DC_Folder
     * 
     * @return boolean 
     */
    public function hasResized()
    {
        return FALSE;
    }

    /**
     * Parse the widget and return it as string
     * 
     * @return string
     */
    public function generateMarkup()
    {
        return $this->parse();
    }

    /**
     * Load callback for backend widget
     * 
     * @param mixed $varValue
     * @param DataContainer $dc 
     */
    public function onLoadCallback($varValue, DataContainer $dc)
    {
        $strField = $dc->field;
        $strDbFiles = $this->objDatabase
                ->prepare("
                    SELECT $dc->field FROM `$dc->table`
                    WHERE id = ?")
                ->limit(1)
                ->execute($dc->id);


        $_SESSION['VALUM_DB_FILES'] = ((strlen($strDbFiles->$strField) > 0) ? deserialize($strDbFiles->$strField) : array());
    }

    /**
     * Save callback for backend widget
     * 
     * @param mixed $varValue
     * @param DataContainer $dc 
     */
    public function onSaveCallback($varValue, DataContainer $dc)
    {
        $arrDbFiles = array();
        if (count($_SESSION['VALUM_DB_FILES']))
        {
            $arrDbFiles = $_SESSION['VALUM_DB_FILES'];
        }

        $arrFiles = array();
        if (count($_SESSION['VALUM_FILES']))
        {
            foreach ($_SESSION['VALUM_FILES'] as $k => $v)
            {
                $arrFiles[$k] = str_replace(TL_ROOT . '/', '', $v['tmp_name']);
            }
        }

        $arrSaveFiles = array_merge($arrDbFiles, $arrFiles);
        $strFiles = serialize($arrSaveFiles);

        if (count($arrSaveFiles) > 0)
        {
            $this->objDatabase
                    ->prepare("
                        UPDATE `$dc->table` 
                        SET $dc->field = ?
                        WHERE id = ?")
                    ->execute($strFiles, $dc->id);
        }

        $this->removeSessionData();
    }

}

?>