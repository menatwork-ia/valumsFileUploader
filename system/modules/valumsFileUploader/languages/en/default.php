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
$GLOBALS['TL_LANG']['FFL']['valumsFileUploader'] = array('Multiple File Uploads (Valums File Uploader)');

/**
 *  Valums
 */
$GLOBALS['TL_LANG']['UPL']['upload_drop_area'] = 'Drop files here to upload';
$GLOBALS['TL_LANG']['UPL']['upload_button'] = 'Upload a file';
$GLOBALS['TL_LANG']['UPL']['upload_cancel'] = 'Cancel';
$GLOBALS['TL_LANG']['UPL']['upload_failed_text'] = 'Failed';

// Logger
$GLOBALS['TL_LANG']['UPL']['log_success'] = 'Uploaded successfully';

// BE
$GLOBALS['TL_LANG']['UPL']['overwriteFile'] = 'Overwrite existing file';
$GLOBALS['TL_LANG']['UPL']['useSuffix'] = 'Set suffix';
$GLOBALS['TL_LANG']['UPL']['useTimeStamp'] = 'Set timestemp';

/**
 * Error
 */
$GLOBALS['TL_LANG']['ERR']['val_type_error'] = '"{file} has invalid extension. Only {extensions} are allowed."';
$GLOBALS['TL_LANG']['ERR']['val_size_error'] = '"{file} is too large, maximum file size is {sizeLimit}."';
$GLOBALS['TL_LANG']['ERR']['val_min_size_error'] = '"{file} is too small, minimum file size is {minSizeLimit}."';
$GLOBALS['TL_LANG']['ERR']['val_empty_error'] = '"{file} is empty, please select files again without it."';
$GLOBALS['TL_LANG']['ERR']['val_on_leave'] = '"The files are being uploaded, if you leave now the upload will be cancelled."';

// Logger
$GLOBALS['TL_LANG']['ERR']['val_log_no_file'] = 'Could not create file';
$GLOBALS['TL_LANG']['ERR']['val_log_not_writeable'] = 'Folder is not writeable writeable';
$GLOBALS['TL_LANG']['ERR']['val_log_file_size_zero'] = 'File is empty';
$GLOBALS['TL_LANG']['ERR']['val_log_max_size'] = 'File size is to large';
$GLOBALS['TL_LANG']['ERR']['val_log_wrong_type'] = 'Has invalid file type';
$GLOBALS['TL_LANG']['ERR']['val_log_save_error'] = 'Could not save';
?>
