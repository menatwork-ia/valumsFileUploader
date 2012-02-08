<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'] = array_merge($GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'], array('resize_resolution', 'val_store_file'));
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['valumsFileUploader'] = '{type_legend},type,name,label;{fconfig_legend},mandatory,extensions,val_max_file_length,val_init_text,val_drop_text;{store_legend:hide},val_store_file,resize_resolution;{expert_legend:hide},class,accesskey,val_uploader_debug;{submit_legend},addSubmit';
$GLOBALS['TL_DCA']['tl_form_field']['fields']['useHomeDir']['eval']['tl_class'] .= ' m12';

/**
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_form_field']['subpalettes']['val_store_file'] = 'uploadFolder,useHomeDir,val_do_not_overwrite';
$GLOBALS['TL_DCA']['tl_form_field']['subpalettes']['resize_resolution'] = 'val_image_size';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_form_field']['fields']['val_store_file'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['storeFile'],
    'exclude' => TRUE,
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => TRUE)
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['val_do_not_overwrite'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['val_do_not_overwrite'],
    'exclude' => TRUE,
    'inputType' => 'select',
    'options' => array('overwriteFile', 'useSuffix', 'useTimeStamp'),
    'reference' => &$GLOBALS['TL_LANG']['UPL'],
    'eval' => array('tl_class' => 'w50', 'mandatory' => TRUE, 'includeBlankOption' => FALSE)
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['val_max_file_length'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['maxlength'],
    'exclude' => TRUE,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['val_uploader_debug'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['val_uploader_debug'],
    'exclude' => TRUE,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'clr w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['val_init_text'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['val_init_text'],
    'exclude' => TRUE,
    'inputType' => 'text',
    'eval' => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['val_drop_text'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['val_drop_text'],
    'exclude' => TRUE,
    'inputType' => 'text',
    'eval' => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['resize_resolution'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['resize_resolution'],
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => TRUE)
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['val_image_size'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['val_image_size'],
    'inputType' => 'text',
    'eval' => array('multiple' => true, 'size' => 2, 'rgxp' => 'digit', 'nospace' => true)
);
?>