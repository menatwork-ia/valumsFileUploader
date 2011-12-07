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
 
class valumsValidateFormField extends Backend
{    
    /**
     * First form field
     * @var array 
     */
    protected $firstFf = array();
    
    /**
     * Last Form field
     * @var array
     */
    protected $lastFf = array();
    
    /**
     * Form fiel error
     * @var bool
     */
    protected $hasError = FALSE;
    
    /**
     * Form id
     * @var int 
     */
    protected $intFormId = NULL;

    /**
     * Get all specific information to the given form id and save them
     * @param type $strFormId 
     */
    public function createForm($strFormId)
    {
        $this->intFormId = preg_replace("/[^0-9]/", '', $strFormId);
        $objDb = $this->Database->prepare("SELECT a.id FROM tl_form_field a, tl_form b WHERE b.id = %s AND a.pid = b.id AND a.invisible = '' ORDER BY a.sorting")->execute($this->intFormId);        
        $form = $objDb->fetchAllAssoc();
        $this->firstFf = $form[0];
        $this->lastButOneFf = $form[count($form) - 2];
        $this->lastFf = $form[count($form) - 1];
    }

    /**
     * Check if some given form field has an error. 
     * If not move the temporary files in SESSON to the right upload folder
     * @param object $objWidget Form fiels object
     * @param string $strFormId Form id
     * @param array $arrData Form data
     * @return object
     */
    public function validateFormField($objWidget, $strFormId, $arrData)
    {
        if ($this->intFormId == NULL)
        {
            $this->createForm($strFormId);
        }

        if ($objWidget->hasErrors() == TRUE)
        {
            $this->hasError = TRUE;
        }

        if ($this->lastFf['id'] == $objWidget->id && !$this->hasError)
        {
            // Move the temporary files to the right location
            $objProcessFf = new valumsProcessForm();
            $objProcessFf->moveUploadedFiles($this->intFormId);
        }

        return $objWidget;
    }

}

?>
