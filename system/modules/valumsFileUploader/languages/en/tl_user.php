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
 * Legends
 */
$GLOBALS['TL_LANG']['tl_user']['upload_legend']             = 'Uploader';

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_user']['ValumsBeFileUpload']        = 'valumsFileUploader';
$GLOBALS['TL_LANG']['tl_user']['do_not_overwrite']          = array('Do not overwrite', 'Please select this option if you do not want to overwrite the files.');
$GLOBALS['TL_LANG']['tl_user']['uploader']                  = array('Select uploader', 'Please select your favourite uploader.');
$GLOBALS['TL_LANG']['tl_user']['do_not_overwrite_type']     = array('What to do if the file already exists', 'Here you can select what happens if the file already exists.');
$GLOBALS['TL_LANG']['tl_user']['uploader_debug']            = array('Enable debug', 'Please select this option if you want to enable the debug mode from the uploader');
$GLOBALS['TL_LANG']['tl_user']['resize_resolution']         = array('Scale images', 'Select this option to scale images when uploading.');
$GLOBALS['TL_LANG']['tl_user']['val_image_size']            = array('Image width and height', 'Enter the new height and width of the images. Leave these options blank to use the contao default settings.');
$GLOBALS['TL_LANG']['tl_user']['details_failure_message']   = array('Detailed error message', 'Select this option to output detailed error messages on failures.');
$GLOBALS['TL_LANG']['tl_user']['max_file_count']            = array('Maximum file uploads', 'Enter the maximum number of possible file uploads.');

?>
