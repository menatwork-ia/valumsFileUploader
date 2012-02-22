<?php if (!defined('TL_ROOT'))
     die('You cannot access this file directly!');

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
 * @copyright  MEN AT WORK 2012
 * @package    valumsFileUploader
 * @license    GNU/GPL 2
 * @filesource
 */

// Check  if the valumsFormUploader is a field from current form
if(tl_form_vfu::isVfuInForm())
{
    /**
    * Config 
    */
    $GLOBALS['TL_DCA']['tl_form']['config']['onload_callback'][] = array('tl_form_vfu', 'disableContaoStoreValues');

    /**
    * Palettes
    */
    $GLOBALS['TL_DCA']['tl_form']['palettes']['__selector__'][] = 'vfu_storeValues';
    $GLOBALS['TL_DCA']['tl_form']['palettes']['default'] = str_replace('storeValues', 'vfu_storeValues', $GLOBALS['TL_DCA']['tl_form']['palettes']['default']);
    $GLOBALS['TL_DCA']['tl_form']['subpalettes']['vfu_storeValues'] = $GLOBALS['TL_DCA']['tl_form']['subpalettes']['storeValues'];

    /**
    * Fields 
    */
    $GLOBALS['TL_DCA']['tl_form']['fields']['vfu_storeValues'] = $GLOBALS['TL_DCA']['tl_form']['fields']['storeValues'];
}
    
/**
 * Class tl_vfu_form
 */
class tl_form_vfu extends tl_form
{

     /**
     * Initialize the object
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Return if the valumsFormUploader is a field from current form
     * 
     * @return boolean 
     */
    public static function isVfuInForm()
    {
        $objFormFields = Database::getInstance()
            ->prepare("SELECT COUNT(*) AS count FROM `tl_form_field` WHERE pid = ? AND type = ?  AND invisible != 1")
            ->limit(1)
            ->execute(Input::getInstance()->get('id'), 'valumsFileUploader');
        
        if($objFormFields->count > 0)
        {
            return TRUE;
        }
        
        return FALSE;
    }
    
    /**
     * Set the old sendViaEmail field in the database to FALSE
     * 
     * @param DataContainer $dc 
     */
    public function disableContaoStoreValues(DataContainer $dc)
    {
        $this->Database->prepare("UPDATE tl_form SET storeValues = ? WHERE id = ?")->execute(FALSE, $dc->id);
    }    
}

?>