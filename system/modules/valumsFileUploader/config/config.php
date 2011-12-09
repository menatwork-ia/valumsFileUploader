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
 * Form fields
 */
$GLOBALS['TL_FFL']['valumsFileUploader'] = 'valumsFeFileUpload';

/**
 * Hook
 */
$GLOBALS['TL_HOOKS']['validateFormField'][] = array('valumsFileUploader', 'validateFormField');
$GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = array('valumsHelper', 'checkExtensions');
$GLOBALS['TL_HOOKS']['executePreActions'][] = array('valumsBeFileUpload', 'generateAjax');

/**
 * Config
 */
$GLOBALS['UPLOADER'] = array(
    'valumsFileUploader' => array(
        'UPLOADER_JS' => 'plugins/ajax-upload/js/ajaxupload.js',
        'UPLOADER_CSS' => 'plugins/ajax-upload/css/ajaxupload.css|screen',        
        'BE' => array(
            'ACTION' => 'system/modules/valumsFileUploader/valumsAjaxRequest.php',
            'CSS' => 'system/modules/valumsFileUploader/html/valumsFileUploader.css|screen',
            'TEMPLATE' => 'be_valums',
            'DATA' => array(
                'debug' => "'false'"
            )
        ),
        'FE' => array(
            'ACTION' => 'ajax.php',
            'TMP_FOLDER' => 'system/tmp',
            'DEBUG' => "'false'"
        )
    )
);

?>
