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
    if ($key == '__selector__')
    {
        $GLOBALS['TL_DCA']['tl_user']['palettes'][$key] = array_merge($GLOBALS['TL_DCA']['tl_user']['palettes'][$key], array('do_not_overwrite', 'resize_resolution'));
        continue;
    }

    if (version_compare(VERSION, '2.11', '<'))
    {
        if (!stristr($row, 'fancyUpload,'))
        {
            continue;
        }

        $row = str_replace(',fancyUpload', '', $row);
    }
    else
    {
        if (!stristr($row, 'uploader,'))
        {
            continue;
        }

        $row = str_replace(',uploader', '', $row);
    }

    
    if(tl_user_ext::checkPalettes())
    {
        $arrPalette = array('{upload_legend},uploader, uploader_debug, do_not_overwrite, resize_resolution');
    }
    else
    {
        $arrPalette = array('{upload_legend},uploader');
    }

    $arrPalettes = explode(";", $row);
    $GLOBALS['TL_DCA']['tl_user']['palettes'][$key] = implode(";", array_merge(array_slice($arrPalettes, 0, 1), $arrPalette, array_slice($arrPalettes, 1)));
}


/**
 * Subpalettes
 */
$GLOBALS['TL_DCA']['tl_user']['subpalettes']['do_not_overwrite'] = 'do_not_overwrite_type';
$GLOBALS['TL_DCA']['tl_user']['subpalettes']['resize_resolution'] = 'val_image_size';

/**
 * Fields
 */
if (version_compare(VERSION, '2.11', '='))
{
    $GLOBALS['TL_DCA']['tl_user']['fields']['showHelp']['eval']['tl_class'] .= ' clr';
}
else
{
    $GLOBALS['TL_DCA']['tl_user']['fields']['uploader']['options'] = array('default', 'fancyUpload');
}

$GLOBALS['TL_DCA']['tl_user']['fields']['uploader'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['uploader'],
    'exclude' => true,
    'inputType' => 'select',
    'options' => array_merge($GLOBALS['TL_DCA']['tl_user']['fields']['uploader']['options'], array('valumsBeFileUpload')),
    'reference' => &$GLOBALS['TL_LANG']['tl_user'],
    'eval' => array('submitOnChange' => TRUE, 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_user']['fields']['uploader_debug'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['uploader_debug'],
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50 m12'),
);

$GLOBALS['TL_DCA']['tl_user']['fields']['do_not_overwrite'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['do_not_overwrite'],
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => TRUE, 'tl_class' => 'clr w50'),
);

$GLOBALS['TL_DCA']['tl_user']['fields']['do_not_overwrite_type'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['do_not_overwrite_type'],
    'inputType' => 'select',
    'options' => array('useSuffix', 'useTimeStamp'),
    'reference' => &$GLOBALS['TL_LANG']['UPL'],
    'eval' => array('tl_class' => 'clr w50', 'mandatory' => TRUE, 'includeBlankOption' => FALSE),
);

$GLOBALS['TL_DCA']['tl_user']['fields']['resize_resolution'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_user']['resize_resolution'],
    'inputType' => 'checkbox',
    'eval' => array('submitOnChange' => TRUE, 'tl_class' => 'clr')
);

$GLOBALS['TL_DCA']['tl_user']['fields']['val_image_size'] = array
    (
    'label' => &$GLOBALS['TL_LANG']['tl_user']['val_image_size'],
    'inputType' => 'text',
    'eval' => array('multiple' => true, 'size' => 2, 'rgxp' => 'digit', 'nospace' => true, 'tl_class' => 'clr')
);

/**
 * 
 */
class tl_user_ext extends tl_user
{

    public static function checkPalettes()
    {
        $objBeUser = BackendUser::getInstance();
        
        if($objBeUser->uploader == 'valumsBeFileUpload')
            return TRUE;
                    
        return FALSE;
    }

}

?>