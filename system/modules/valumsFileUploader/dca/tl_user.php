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
 * Palettes
 */
foreach ($GLOBALS['TL_DCA']['tl_user']['palettes'] as $key => $row)
{
    if ($key == '__selector__') continue;
    if (!stristr($row, 'fancyUpload,')) continue;
    $GLOBALS['TL_DCA']['tl_user']['palettes'][$key] = str_replace('oldBeTheme;', 'oldBeTheme;{upload_legend},uploader,doNotOverwrite;', str_replace('fancyUpload,', '', $GLOBALS['TL_DCA']['tl_user']['palettes'][$key]));;
}

$GLOBALS['TL_DCA']['tl_user']['palettes']['__selector__'][] = 'doNotOverwrite';

/**
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_user']['subpalettes']['doNotOverwrite'] = 'doNotOverwriteType';

/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_user']['fields']['doNotOverwrite'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['doNotOverwrite'],
    'exclude' => TRUE,
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => TRUE, 'tl_class' => 'w50 m12'),
);

$GLOBALS['TL_DCA']['tl_user']['fields']['uploader'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['uploader'],
    'exclude' => TRUE,
    'inputType' => 'select',
    'options' => array('default', 'fancyUpload', 'valumsFileUploader'),
    'reference' => &$GLOBALS['TL_LANG']['UPL'],
    'eval' => array('tl_class' => 'w50', 'mandatory' => TRUE, 'includeBlankOption' => FALSE),
);

$GLOBALS['TL_DCA']['tl_user']['fields']['doNotOverwriteType'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['doNotOverwriteType'],
    'exclude' => TRUE,
    'inputType' => 'select',
    'options' => array('useSuffix', 'useTimeStamp'),
    'reference' => &$GLOBALS['TL_LANG']['UPL'],
    'eval' => array('tl_class' => 'clr', 'mandatory' => TRUE, 'includeBlankOption' => FALSE),
);

?>