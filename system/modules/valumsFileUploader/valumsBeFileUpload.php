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
 * @copyright  MEN AT WORK 2012
 * @package    valumsFileUploader
 * @license    GNU/GPL 2
 * @filesource
 */

/**
 * Class valumsBeFileUpload
 */
class valumsBeFileUpload extends Widget
{

    /**
     * Template
     * @var string
     */
    protected $strTemplate = 'be_valums';

    /**
     * Objects
     * @var type 
     */
    protected $objHelper;
    protected $objUploader;

    /**
     * Initialize the object and set configurations
     * @param array
     * @throws Exception
     */
    public function __construct($arrAttributes = FALSE)
    {
        parent::__construct($arrAttributes);

        $this->objHelper = new valumsHelper();
        $this->objHelper->setBeHeaderData($GLOBALS['UPLOADER']['valumsFileUploader']);

        $this->objUploader = new valumsFileUploader();        
    }

    public function generate()
    {
        
    }

    /**
     * Parse the template file and return it as string
     * @param array
     * @return string
     */
    public function parse($arrAttributes = false)
    {
        $this->setDefaultValues();
        $this->setSessionData();
        FB::log($_SESSION['VALUM_CONFIG']['uploadFolder']);        
        
        return parent::parse($arrAttributes);
        FB::log($_SESSION['VALUM_CONFIG']['uploadFolder']);
    }

    /**
     * Set all necessary session information
     */
    protected function setSessionData()
    {
        $_SESSION['VALUM_CONFIG'] = array(
            'uploadFolder' => $this->path,
            'maxFileLength' => $this->maxFileSize,
            'extension' => $this->extensions,
            'doNotOverwrite' => $this->doNotOverwrite,
            'resizeResolution' => (($this->resize) ? TRUE : FALSE),
        );

        if (is_array($this->resize) && $this->resize[0] != '' && $this->resize[1] != '')
        {
            $_SESSION['VALUM_CONFIG']['imageSize'] = $this->resize;
        }
    }

    /**
     * Set all values that are necessary
     */
    protected function setDefaultValues()
    {
        $this->maxFileSize = (($this->maxFileSize) ? $this->maxFileSize : $GLOBALS['TL_CONFIG']['maxFileSize']);

        if ($this->overwrite != NULL)
        {
            $this->doNotOverwrite = $this->overwrite;
        }

        if ($this->path == NULL)
        {
            $this->path = $GLOBALS['UPLOADER']['valumsFileUploader']['BE']['TMP_FOLDER'];
        }

        if ($this->extensions == NULL)
        {
            $this->extensions = strtolower($GLOBALS['TL_CONFIG']['uploadTypes']);
        }
        $this->uploadTypes = $this->objHelper->getStrExt($this->extensions);

        if ($this->action == NULL)
        {
            $this->action = $GLOBALS['UPLOADER']['valumsFileUploader']['BE']['ACTION'];
        }

        if ($this->paramAction == NULL)
        {
            $this->paramAction = 'valumsFileUploader';
        }

        if ($this->name != $this->label)
        {
            $this->dropButtonLabel = $this->label;
        }

        $this->noJsBeLink = $this->Environment->scriptName . '?do=login';
    }

    /**
     * Call the real generateAjax in valumsFileUploader
     */
    public function generateAjax($strAction)
    {
        if ($strAction == 'valumsFileUploader')
        {
            $this->objUploader->generateAjax();
        }
    }

}

?>