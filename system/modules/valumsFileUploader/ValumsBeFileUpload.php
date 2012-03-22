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

    /**
     * Initialize the object and set configurations
     * 
     * @param array
     */
    public function __construct($arrAttributes = FALSE)
    {        
        parent::__construct($arrAttributes);
        if(is_array($_SESSION['VALUM_FILES']))
        {
            unset($_SESSION['VALUM_FILES']);
        }
     
        $this->objInput = Input::getInstance();        
        $this->objEnvironment = Environment::getInstance();
        
        if($this->objInput->get('do') != 'files' || !strstr($this->objEnvironment->request, 'contao/files.php'))
        {
            $this->strTemplate = 'be_valums_widget';
        }
        
        $this->objHelper = new ValumsHelper();
        $this->objHelper->setHeaderData(($this->css) ? array('css' => $this->css) : FALSE);
        
        $this->objUploader = new ValumsFileUploader();
        $this->objBeUser = BackendUser::getInstance();
    }

    public function generate() {}

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
            'doNotOverwrite' => (($this->doNotOverwrite) ? $this->doNotOverwrite : FALSE),
            'resizeResolution' => $this->resizeResolution,
            'imageSize' => $this->imageSize
        );
    }

    /**
     * Set all necessary values
     */
    protected function setDefaultValues()
    {
        $this->action = 'system/modules/valumsFileUploader/ValumsAjaxRequest.php';
        $this->paramAction = 'valumsFileUploader';
        $this->doFiels = FALSE;
        
        if($this->objInput->get('do') == 'files' || strstr($this->objEnvironment->request, 'contao/files.php'))
        {
            $this->detailsFailureMessage = $this->objBeUser->details_failure_message;
            $this->maxFileCount = $this->objBeUser->max_file_count;
            $this->doFiles = TRUE;
            $this->path = $this->objInput->get('pid');
            $this->debug = $this->objBeUser->uploader_debug;
            if($this->objBeUser->do_not_overwrite == TRUE)
            {
                $this->doNotOverwrite = $this->objBeUser->do_not_overwrite_type;
            }
            if($this->objBeUser->resize_resolution == TRUE)
            {
                $this->resizeResolution = $this->objBeUser->resize_resolution;
                $resize = deserialize($this->objBeUser->val_image_size);
                if(is_array($resize) && strlen($resize[0]) > 0 && strlen($resize[1]))
                {
                    $this->imageSize = $resize;
                }                
            }
        }
        
        if($this->resize != NULL)
        {
            $this->resizeResolution = TRUE;
            if(is_array($this->resize) && strlen($this->resize[0]) > 0 && strlen($this->resize[1]))
            {
                $this->imageSize = $this->resize;
            }
        }
        
        $this->maxFileSize = (($this->maxFileSize) ? $this->maxFileSize : $GLOBALS['TL_CONFIG']['maxFileSize']);
        
        if ($this->overwrite != NULL)
        {
            $this->doNotOverwrite = $this->overwrite;
        }

        if ($this->path == NULL )
        {
            $this->path = 'system/tmp';
        }

        if ($this->extensions == NULL)
        {
            $this->extensions = strtolower($GLOBALS['TL_CONFIG']['uploadTypes']);
        }
        $this->uploadTypes = $this->objHelper->getStrExt($this->extensions);

        if ($this->name != $this->label)
        {
            $this->dropButtonLabel = $this->label;
        }

        $this->noJsBeLink = $this->Environment->scriptName . '?do=login';
    }

    /**
     * Call the real generateAjax in valumsFileUploader
     * 
     * @param string $strAction 
     */
    public function generateAjax($strAction)
    {
        if ($strAction == 'valumsFileUploader')
        {
            $this->objUploader->generateAjax();
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

}

?>