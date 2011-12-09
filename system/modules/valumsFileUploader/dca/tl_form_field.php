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
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['__selector__'][] = 'valumsStoreFile';
$GLOBALS['TL_DCA']['tl_form_field']['palettes']['valumsFileUploader'] = '{type_legend},type,name,label;{fconfig_legend},mandatory,extensions,maxfilelength;{store_legend:hide},valumsStoreFile;{expert_legend:hide},class,accesskey;{submit_legend},addSubmit';

/**
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_form_field']['subpalettes']['valumsStoreFile'] = 'uploadFolder,useHomeDir,doNotOverwriteExt';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_form_field']['fields']['valumsStoreFile'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['storeFile'],
    'exclude' => TRUE,
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => TRUE)
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['doNotOverwriteExt'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['doNotOverwriteExt'],
    'exclude' => TRUE,
    'inputType' => 'select',
    'options' => array('overwriteFile', 'useSuffix', 'useTimeStamp'),
    'reference' => &$GLOBALS['TL_LANG']['UPL'],
    'eval' => array('tl_class' => 'w50', 'mandatory' => TRUE, 'includeBlankOption' => FALSE)
);

$GLOBALS['TL_DCA']['tl_form_field']['fields']['maxfilelength'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_form_field']['maxlength'],
    'exclude' => TRUE,
    'inputType' => 'text',
    'eval' => array('rgxp' => 'digit', 'tl_class' => 'w50')
);
?>