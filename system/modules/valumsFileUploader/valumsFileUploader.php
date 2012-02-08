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
 * Class valumsFileUploader
 */
class valumsFileUploader extends Backend
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
     * Load database object
     */
    protected function __construct()
    {
        parent::__construct();
        $this->import('valumsHelper', 'helper');
    }

    /**
     * Get the file information, checked specific values and save the file
     * 
     * @return array
     */
    public function generateAjax()
    {
        if ($_SESSION['VALUM_CONFIG'])
            $arrConf = $_SESSION['VALUM_CONFIG'];

        $objFile   = new valumsFile($arrConf['uploadFolder']);
        $strLogPos = __CLASS__ . " " . __FUNCTION__ . "()";

        // Check if file could not create
        if ($objFile->error)
        {
            $this->helper->setJsonEncode('ERR', 'val_no_file', array(), $strLogPos, array("success" => FALSE, "reason" => $GLOBALS['TL_LANG']['ERR']['val_no_file']));
        }

        // Check if folder is writeable
        if (!is_writable(TL_ROOT . '/' . $objFile->uploadFolder))
        {
            $this->helper->setJsonEncode('ERR', 'val_not_writeable', array(), $strLogPos, array("success" => FALSE, "reason" => $GLOBALS['TL_LANG']['ERR']['val_not_writeable']));
        }

        // Check for empty file
        if ($objFile->size == 0)
        {
            $this->helper->setJsonEncode('ERR', 'val_file_size_zero', array(), $strLogPos, array("success" => FALSE, "reason" => $GLOBALS['TL_LANG']['ERR']['val_file_size_zero']));
        }

        // Check file size 
        if ($arrConf['maxFileLength'] > 0 && $objFile->size > $arrConf['maxFileLength'])
        {
            $this->helper->setJsonEncode('ERR', 'val_max_size', array(), $strLogPos, array("success" => FALSE, "reason" => vsprintf($GLOBALS['TL_LANG']['ERR']['val_max_size'], array($this->getReadableSize($arrConf['maxFileLength'])))));
        }

        // Check file type
        if (!in_array(strtolower($objFile->getPathInfo('extension')), $this->helper->getArrExt($arrConf['extension'])))
        {
            $this->helper->setJsonEncode('ERR', 'val_wrong_type', array(), $strLogPos, array("success" => FALSE, "reason" => vsprintf($GLOBALS['TL_LANG']['ERR']['val_wrong_type'], array($objFile->getPathInfo('extension'), $arrConf['extension']))));
        }

        // Check if save was successful
        if (!$objFile->save($arrConf['doNotOverwrite']))
        {
            $this->helper->setJsonEncode('ERR', 'val_save_error', array(), $strLogPos, array("success" => FALSE, "reason" => $GLOBALS['TL_LANG']['ERR']['val_save_error']));
        }

        //Check and resize resolution
        if ($arrConf['resizeResolution'])
        {
            if (is_array($arrConf['imageSize']))
            {
                $objFile->resize($objFile->uploadFolder . '/' . $objFile->newName, $arrConf['imageSize']);
            }
            else
            {
                $objFile->resize($objFile->uploadFolder . '/' . $objFile->newName);
            }
        }

        $arrSpecialSession = array();

        if (is_array($arrConf['specialSessionAttr']))
        {
            $arrSpecialSession = $arrConf['specialSessionAttr'];
        }

        $objFile->writeFileToSession($arrSpecialSession);

        $this->helper->setJsonEncode('UPL', 'log_success', array($objFile->newName, $objFile->uploadFolder), $strLogPos, array("success" => TRUE, "filename" => $objFile->newName));
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

                        $objFile = new valumsFile($uploadFolder, 'SESSION', $file);
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

}

?>