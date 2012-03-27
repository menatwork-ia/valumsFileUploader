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
 * Class ValumsFileUploader
 */
class ValumsFileUploader extends Backend
{

    /**
     * First form field
     * 
     * @var array 
     */
    protected $firstFf = array();

    /**
     * Last Form field
     * 
     * @var array
     */
    protected $lastFf = array();

    /**
     * Form fiel error
     * 
     * @var bool
     */
    protected $hasError = FALSE;

    /**
     * Form id
     * 
     * @var int 
     */
    protected $intFormId = NULL;

    /**
     * Objects 
     */
    protected $objHelper;


    /**
     * Initialize the object
     */
    public function __construct()
    {
        parent::__construct();
        $this->objHelper = new ValumsHelper();
    }

    /**
     * Delete the given file and unset it in session
     * 
     * @param string $strFileName 
     */
    public function deleteFile($strFileName)
    {
        if(isset($_SESSION['VALUM_FILES'][$strFileName]))
        {      
            $objFile = new ValumsFile('', 'SESSION', $_SESSION['VALUM_FILES'][$strFileName]);
            unset($_SESSION['VALUM_FILES'][$strFileName]);
            if(!$objFile->delete())
            {
                $this->objHelper->sendJsonEncode(array('deleteSuccess' => FALSE));
            }
            $this->objHelper->sendJsonEncode(array('deleteSuccess' => TRUE));
        }            
    }
    
    /**
     * Get the file information, checked specific values and save the file
     * 
     * @return array
     */
    public function generateAjax()
    {
        // Declare log position
        $strLogPos = __CLASS__ . " " . __FUNCTION__ . "()";
        
        // Get config
        if ($_SESSION['VALUM_CONFIG'])
            $arrConf = $_SESSION['VALUM_CONFIG'];

        // Check if maxFileCount is reached
        if($_SESSION['VALUM_CONFIG']['maxFileCount'] != 0 && $_SESSION['VALUM_CONFIG']['fileCount'] >= $_SESSION['VALUM_CONFIG']['maxFileCount'] || $_SESSION['VALUM_CONFIG']['maxFileCount'] != 0 && count($_SESSION['VALUM_FILES']) >= $_SESSION['VALUM_CONFIG']['maxFileCount'])
        {
            $this->objHelper->setJsonEncode('ERR', 'val_max_files', array(), $strLogPos, array("success" => FALSE, "reason" => "val_max_files", "reasonText" => $GLOBALS['TL_LANG']['ERR']['val_max_files']));
        }
        
        // Create ValumsFile object
        $objFile   = new ValumsFile($arrConf['uploadFolder']);
        
        // Check if file could not create
        if ($objFile->error)
        {
            $this->objHelper->setJsonEncode('ERR', 'val_no_file', array(), $strLogPos, array("success" => FALSE, "reason" => "val_no_file", "reasonText" => $GLOBALS['TL_LANG']['ERR']['val_no_file']));
        }

        // Check if folder is writeable
        if (!is_writable(TL_ROOT . '/' . $objFile->uploadFolder))
        {
            $this->objHelper->setJsonEncode('ERR', 'val_not_writeable', array(), $strLogPos, array("success" => FALSE, "reason" => "val_not_writeable", "reasonText" => $GLOBALS['TL_LANG']['ERR']['val_not_writeable']));
        }

        // Check for empty file
        if ($objFile->size == 0)
        {
            $this->objHelper->setJsonEncode('ERR', 'val_file_size_zero', array(), $strLogPos, array("success" => FALSE, "reason" => "val_file_size_zero", "reasonText" => $GLOBALS['TL_LANG']['ERR']['val_file_size_zero']));
        }

        // Check file size 
        if ($arrConf['maxFileLength'] > 0 && $objFile->size > $arrConf['maxFileLength'])
        {
            $this->objHelper->setJsonEncode('ERR', 'val_max_size', array(), $strLogPos, array("success" => FALSE, "reason" => "val_max_size", "reasonText" => vsprintf($GLOBALS['TL_LANG']['ERR']['val_max_size'], array($this->getReadableSize($arrConf['maxFileLength'])))));
        }

        // Check file type
        if (!in_array(strtolower($objFile->getPathInfo('extension')), $this->objHelper->getArrExt($arrConf['extension'])))
        {
            $this->objHelper->setJsonEncode('ERR', 'val_wrong_type', array(), $strLogPos, array("success" => FALSE, "reason" => "val_wrong_type", "reasonText" => vsprintf($GLOBALS['TL_LANG']['ERR']['val_wrong_type'], array($objFile->getPathInfo('extension'), $arrConf['extension']))));
        }

        // Declare json array
        $arrJson =  array(
            'success' => FALSE,            
            'resized' => FALSE,
            'exceeds' => FALSE
        );        
        
        // Check if save was successful
        if (!$objFile->save($arrConf['doNotOverwrite']))
        {
            $this->objHelper->setJsonEncode('ERR', 'val_save_error', array(), $strLogPos, array("success" => FALSE, "reason" => "val_save_error", "reasonText" => $GLOBALS['TL_LANG']['ERR']['val_save_error']));           
        }
        else
        {
            $arrJson['success'] = TRUE;
        }
        
        $arrJson['filename'] = $objFile->newName;
        
        //Check and resize resolution
        if ($arrConf['resizeResolution'])
        {
            if (is_array($arrConf['imageSize']))
            {
                $arrResizeResult = $objFile->resize($objFile->uploadFolder . '/' . $objFile->newName, $arrConf['imageSize']);                
                $arrTmp['size'] = $arrConf['imageSize'];
            }
            else
            {
                $arrResizeResult = $objFile->resize($objFile->uploadFolder . '/' . $objFile->newName);
                $arrTmp['size'] = array(
                    $GLOBALS['TL_CONFIG']['imageWidth'],
                    $GLOBALS['TL_CONFIG']['imageHeight']
                );
            }
            
            // Notify user
            if ($arrResizeResult['blnExceeds'])
            {
                $arrJson['exceeds'] = TRUE;
                $arrJson['resized_message'] = sprintf($GLOBALS['TL_LANG']['MSC']['fileExceeds'], $objFile->newName);             
            }
            elseif ($arrResizeResult['blnResized'])
            {
                $arrJson['resized'] = TRUE;
                $arrJson['resized_message'] = sprintf($GLOBALS['TL_LANG']['MSC']['fileResized'], $objFile->newName);
            }
            
        }

        // Add special attributes
        $arrSpecialSession = array();
        if (is_array($arrConf['specialSessionAttr']))
        {
            $arrSpecialSession = $arrConf['specialSessionAttr'];
        }

        // Write files to session
        $objFile->writeFileToSession($arrSpecialSession);

        // Increment maxFileCount
        $_SESSION['VALUM_CONFIG']['fileCount']++;
        
        // Set json encoding
        $this->objHelper->setJsonEncode('UPL', 'log_success', array($objFile->newName, $objFile->uploadFolder), $strLogPos, $arrJson);
    }

    /**
     * Get all specific information to the given form id and save them
     * 
     * @param type $strFormId 
     */
    public function createForm($strFormId)
    {
        $this->intFormId = preg_replace("/[^0-9]/", '', $strFormId);
        $objDb = $this->Database->prepare("SELECT a.id FROM tl_form_field a, tl_form b WHERE b.id = %s AND a.pid = b.id AND a.invisible = '' ORDER BY a.sorting")->execute($this->intFormId);
        $form  = $objDb->fetchAllAssoc();
        $this->firstFf = $form[0];
        $this->lastButOneFf = $form[count($form) - 2];
        $this->lastFf = $form[count($form) - 1];
    }

    /**
     * Check if some given form field has an error. 
     * If not move the temporary files in SESSON to the right upload folder
     * 
     * HOOK: $GLOBALS['TL_HOOKS']['validateFormField']
     * 
     * @param object $objWidget Form fiels object
     * @param string $strFormId Form id
     * @param array $arrData Form data
     * @return object
     */
    public function validateFormField($objWidget, $strFormId, $arrData)
    {
        if ($this->intFormId == NULL)
        {
            $this->createForm($strFormId);
        }

        if ($objWidget->hasErrors() == TRUE)
        {
            $this->hasError = TRUE;
        }

        if ($this->lastFf['id'] == $objWidget->id && !$this->hasError)
        {
            // Move the temporary files to the right location
            $this->moveUploadedFiles($this->intFormId);
        }

        return $objWidget;
    }

    /**
     * Moves temporary files in SESSION to final location and renew SESSION data.
     * 
     * @param integer $intFormId 
     */
    public function moveUploadedFiles($intFormId)
    {
        $this->import('Database');
        if ($_SESSION['VALUM_FILES'])
        {
            foreach ($_SESSION['VALUM_FILES'] AS $key => $file)
            {
                if ($file['formId'] == $intFormId)
                {
                    $objDb = $this->Database->prepare("SELECT * FROM tl_form_field WHERE id=?")->limit(1)->execute($file['formFieldId']);
                    if ($objDb->val_store_file)
                    {
                        $uploadFolder = $objDb->uploadFolder;

                        // Overwrite upload folder with user home directory
                        if ($objDb->useHomeDir && FE_USER_LOGGED_IN)
                        {
                            $this->import('FrontendUser', 'User');
                            if ($this->User->assignDir && $this->User->homeDir && is_dir(TL_ROOT . '/' . $this->User->homeDir))
                            {
                                $uploadFolder = $this->User->homeDir;
                            }
                        }

                        $objFile = new ValumsFile($uploadFolder, 'SESSION', $file);
                        if (!$objFile->error)
                        {
                            // Store the file if the upload folder exists
                            if (strlen($objFile->uploadFolder) && is_dir(TL_ROOT . '/' . $objFile->uploadFolder))
                            {
                                $objFile->move($objDb->val_do_not_overwrite, 'FILES');
                                $this->log('File "' . $objFile->name . '" uploaded successfully', __CLASS__ . ' ' . __FUNCTION__ . '()', TL_FILES);
                            }
                        }
                    }
                }
            }
        }
        unset($_SESSION['VALUM_FILES']);
    }
    
    /**
     * Store form values in the database
     * 
     * HOOK: $GLOBALS['TL_HOOKS']['processFormData']
     * 
     * @param type $arrPost
     * @param type $arrForm
     * @param type $arrFiles
     * @param type $arrLabels 
     */
    public function processFormData($arrPost, $arrForm, $arrFiles, $arrLabels = array())
    {
        if ($arrForm['vfu_storeValues'] == 1 && strlen($arrForm['targetTable']))
        {

            $arrSet = array();

            // Add timestamp
            if ($this->Database->fieldExists('tstamp', $arrForm['targetTable']))
            {
                $arrSet['tstamp'] = time();
            }

            $objFields = $this->Database
                    ->prepare("SELECT * FROM tl_form_field WHERE pid=? AND invisible!=1 ORDER BY sorting")
                    ->execute($arrForm['id']);
            
            $arrSubmitted = array();
            while($objFields->next())
            {
                if(isset($arrPost[$objFields->name]))
                {
                    $arrSubmitted[$objFields->name] = $arrPost[$objFields->name];
                }
            }

            // Fields
            foreach ($arrSubmitted as $k => $v)
            {
                if ($k != 'cc' && $k != 'id')
                {
                    $arrSet[$k] = $v;
                }
            }
            
            $objFormFields = $this->Database
                    ->prepare("SELECT * FROM `tl_form_field` WHERE pid = ? AND type = ?  AND invisible!=1")
                    ->limit(1)
                    ->execute($arrForm['id'], 'valumsFileUploader');

            // Files
            if (count($_SESSION['FILES']))
            {
                foreach ($_SESSION['FILES'] as $k => $v)
                {
                    if ($v['uploaded'])
                    {
                        if (count($objFormFields))
                        {
                            $arrSet[$objFormFields->name][] = str_replace(TL_ROOT . '/', '', $v['tmp_name']);
                        }
                        else
                        {
                            $arrSet[$k] = str_replace(TL_ROOT . '/', '', $v['tmp_name']);
                        }
                    }
                }
                if (is_array($arrSet[$objFormFields->name]))
                {
                    $arrSet[$objFormFields->name] = serialize($arrSet[$objFormFields->name]);
                }
            }

            $this->Database
                    ->prepare("INSERT INTO `" . $arrForm['targetTable'] . "` %s")
                    ->set($arrSet)
                    ->execute();
        }
    }    

}

?>