<?php
if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
$GLOBALS['TL_DCA']['tl_files']['config']['dataContainer'] = tl_files_ext::getDataContainer();
$GLOBALS['TL_DCA']['tl_files']['config']['uploadScript'] = tl_files_ext::getUploadScript();

class tl_files_ext
{

    static private function getUploader()
    {
        $objBeUser = BackendUser::getInstance();
        $GLOBALS['TL_CONFIG']['fancyUpload'] = FALSE;
        if($objBeUser->uploader == 'fancyUpload')
        {
            $GLOBALS['TL_CONFIG']['fancyUpload'] = TRUE;
        }        
        return $objBeUser->uploader;
    }

    static public function getDataContainer()
    {
        if (self::getUploader() == 'fancyUpload' || self::getUploader() == 'default')
        {
            return 'Folder';
        }
        return 'Upload';
    }

    static public function getUploadScript()
    {
        if (self::getUploader() == 'default')
        {
            return 'fancyUpload';
        }
        return self::getUploader();
    }

}