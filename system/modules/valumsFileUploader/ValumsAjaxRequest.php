<?php

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
 * Initialize the system
 */
define('TL_MODE', 'BE');

// Initialize the system
require_once('../../initialize.php');

/**
 * Class ValumsAjaxRequest
 */
class ValumsAjaxRequest extends Backend
{

    /**
     * Current Ajax object
     * 
     * @var object
     */
    protected $objAjax;

    /**
     * Initialize the controller
     * 
     * 1. Import user
     * 2. Call parent constructor
     * 3. Authenticate user
     * 4. Load language files
     * DO NOT CHANGE THIS ORDER!
     */
    public function __construct()
    {
        $this->import('BackendUser', 'User');
        parent::__construct();

        $this->User->authenticate();

        $this->loadLanguageFile('default');
        $this->loadLanguageFile('modules');
    }

    /**
     * Run controller
     */
    public function run()
    {
        if ($_POST['action'] && $this->Environment->isAjaxRequest)
        {
            $this->objAjax = new Ajax($this->Input->post('action'));
            $this->objAjax->executePreActions();
        }
        elseif ($_GET['action'] && $this->Environment->isAjaxRequest)
        {
            $this->objAjax = new Ajax($this->Input->get('action'));
            $this->objAjax->executePreActions();
        }
    }

}

/**
 * Instantiate controller
 */
$valumsAjaxRequest = new ValumsAjaxRequest();
$valumsAjaxRequest->run();
?>


