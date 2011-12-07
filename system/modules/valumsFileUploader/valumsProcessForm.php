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
 
class valumsProcessForm extends Backend
{

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
                    $objFile = new valumsFile('SESSION', $file);
                    if (!$objFile->error)
                    {
                        $this->log('File "' . $objFile->name . '" uploaded successfully', 'FormFileUpload validate()', TL_FILES);
                        $objDb = $this->Database->prepare("SELECT * FROM tl_form_field WHERE id=?")->limit(1)->execute($objFile->formFieldId);
                        if ($objDb->valumsStoreFile)
                        {
                            $objFile->uploadFolder = $objDb->uploadFolder;

                            // Overwrite upload folder with user home directory
                            if ($objDb->useHomeDir && FE_USER_LOGGED_IN)
                            {
                                $this->import('FrontendUser', 'User');
                                if ($this->User->assignDir && $this->User->homeDir && is_dir(TL_ROOT . '/' . $this->User->homeDir))
                                {
                                    $objFile->uploadFolder = $this->User->homeDir;
                                }
                            }

                            // Store the file if the upload folder exists
                            if (strlen($objFile->uploadFolder) && is_dir(TL_ROOT . '/' . $objFile->uploadFolder))
                            {
                                $objFile->move($objDb->doNotOverwriteExt, 'FILES');
                                $this->log('File "' . $objFile->name . '" has been moved to "' . $objFile->uploadFolder . '"', 'FormFileUpload validate()', TL_FILES);                                
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