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
 * Class ValumsFile
 */
class ValumsFile extends Controller
{

    /**
     * Storage for the overload file information 
     * 
     * @var array 
     */
    private $file = array();
    protected $objFiles;

    /**
     * Initialize the object
     * 
     * @param array
     * @throws Exception
     */
    public function __construct($uploadFolder = FALSE, $type = FALSE, $arrValues = FALSE)
    {
        $this->objInput = Input::getInstance();
        $this->objFiles = Files::getInstance();

        if ($type == 'SESSION')
        {
            $this->insertSessionValues($arrValues);
        }
        else
        {
            $this->insertAjaxValues();
        }

        $this->uploadFolder = $uploadFolder;
        $this->timestamp = time();
        $this->newName = $this->getPathInfo('filename') . '.' . $this->getPathInfo('extension');
    }

    /**
     * Create the file information from the given array.
     *  
     * @param array $arrValues 
     */
    private function insertSessionValues($arrValues)
    {
        foreach ($arrValues AS $key => $value)
        {
            switch ($key)
            {
                case 'uploaded':
                    if ($value == '')
                    {
                        $value = FALSE;
                    }
                    break;
                case 'error':
                    if ($value == '')
                    {
                        $value = FALSE;
                    }
                    break;
            }
            $this->$key = $value;
        }
    }

    /**
     * Get the file information from xhr or ffl methode and set them. 
     * If no file information can get set error to true.
     */
    private function insertAjaxValues()
    {


        if (strlen($this->objInput->get('qqfile')))
        {
            $this->name = $this->objInput->get('qqfile');
            $this->size = (int) $_SERVER['CONTENT_LENGTH'];
            $this->tmpFile = tmpfile();
            $this->methode = 'xhr';
            $this->error = FALSE;
        }
        elseif (isset($_FILES['qqfile']))
        {
            $this->name = basename($_FILES['qqfile']['name']);
            $this->size = $_FILES['qqfile']['size'];
            $this->tmpFile = $_FILES['qqfile']['tmp_name'];
            $this->methode = 'ffl';
            $this->error = FALSE;
        }
        else
        {
            $this->methode = NULL;
            $this->error = TRUE;
        }
    }

    /**
     * Set a parameter
     * 
     * @param string $strKey
     * @param mixed $varValue
     * @throws Exception
     */
    public function __set($strKey, $varValue)
    {
        switch ($strKey)
        {
            case 'name':
            case 'newName':
                $varValue = utf8_romanize($varValue);
            default:
                $this->file[$strKey] = $varValue;
                break;
        }
    }

    /**
     * Return a parameter
     * 
     * @return string
     * @throws Exception
     */
    public function __get($strKey)
    {
        switch ($strKey)
        {
            case 'path':
                $varValue = $this->uploadFolder . "/" . $this->name;
                break;
            case 'newPath':
                $varValue = $this->uploadFolder . "/" . $this->newName;
                break;
            default:
                $varValue = $this->file[$strKey];
                break;
        }
        return $varValue;
    }

    /**
     * Return pathinfo from given param
     * 
     * @param type $path
     * @return type 
     */
    private function getCreatedPathInfo($path)
    {
        return pathinfo($path);
    }

    /**
     * Return original pathinfo or spezific one like 'filename' or 'extension' which can filtert by the param. 
     * 
     * @param type $spezific
     * @return mixed 
     */
    public function getPathInfo($spezific = FALSE)
    {
        $info = $this->getCreatedPathInfo($this->path);
        $info['filename'] = utf8_romanize($info['filename']);
        if ($spezific && isset($info[$spezific]))
        {
            return $info[$spezific];
        }
        return $info;
    }

    /**
     * Return new pathinfo or spezific one like 'filename' or 'extension' which can filtert by the param. 
     * 
     * @param type $spezific
     * @return mixed 
     */
    public function getNewPathInfo($spezific = FALSE)
    {
        $info = $this->getCreatedPathInfo($this->newPath);
        $info['filename'] = utf8_romanize($info['filename']);
        if ($spezific && isset($info[$spezific]))
        {
            return $info[$spezific];
        }
        return $info;
    }

    /**
     * Check if the file exists in the upload folder and set new name with suffix
     */
    private function doNotOverwrite()
    {
        $offset   = 1;
        $arrAll   = scan(TL_ROOT . '/' . $this->uploadFolder);
        $arrFiles = preg_grep('/^' . preg_quote($this->getNewPathInfo('filename'), '/') . '.*\.' . preg_quote($this->getNewPathInfo('extension'), '/') . '/', $arrAll);

        foreach ($arrFiles as $strFile)
        {
            if (preg_match('/__[0-9]+\.' . preg_quote($this->getNewPathInfo('extension'), '/') . '$/', $strFile))
            {
                $strFile  = str_replace('.' . $this->getNewPathInfo('extension'), '', $strFile);
                $intValue = intval(substr($strFile, (strrpos($strFile, '_') + 1)));
                $offset   = max($offset, $intValue);
            }
        }

        $this->newName = $this->getNewPathInfo('filename') . '__' . ++$offset . '.' . $this->getNewPathInfo('extension');
    }
    
    /**
     * Check if the current file exists and whould be overwritten
     * 
     * @param string $doNotOverwrite
     * @return boolean 
     */
    public function checkIfIsFileOverwrite($doNotOverwrite)
    {
        if($doNotOverwrite == 'overwriteFile')
        {
            if(file_exists(TL_ROOT . '/' . $this->newPath))
            {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * Write the current file information to the SESSION. 
     * Additional arguments can be given and if the tmp_name should be full path or not
     * 
     * @param array $arrArgument Default is empty array. Need key for SESSION key
     * @param bool $boolCompletePath Default is FALSE
     */
    public function writeFileToSession($arrArgument = array(), $boolCompletePath = FALSE, $strSessionName = 'VALUM_FILES')
    {
        $name = $this->getNewPathInfo('filename') . '.' . $this->getNewPathInfo('extension');
        $_SESSION[$strSessionName][$name] = array
            (
            'name' => $this->newName,
            'orgName' => $this->getPathInfo('filename'),
            'type' => $this->getPathInfo('extension'),
            'error' => $this->error,
            'size' => $this->size,
            'uploaded' => $this->uploaded,
        );

        if ($boolCompletePath)
        {
            $_SESSION[$strSessionName][$name]['tmp_name'] = TL_ROOT . '/' . $this->newPath;
        }
        else
        {
            $_SESSION[$strSessionName][$name]['tmp_name'] = $this->newPath;
        }

        foreach ($arrArgument AS $key => $value)
        {
            $_SESSION[$strSessionName][$name][$key] = $value;
        }
    }

    /**
     * Check if the uploaded image have the right dimensions and resize it if
     * not.
     * 
     * Add bugfix for resize bug. Special thanks to AndreasA for this snippet.
     * @link https://github.com/menatwork/valumsFileUploader/issues/47
     * 
     * @param string $newFile
     * @param array $arrDissolution
     */
    public function resize($newFile, $arrResizeResolution = FALSE)
    {
        $blnExceeds = false;
        $blnResized = false;

        // Resize image if necessary
        if (($arrImageSize = @getimagesize(TL_ROOT . '/' . $newFile)) !== false)
        {
            // Image is too big
            if ($arrImageSize[0] > $GLOBALS['TL_CONFIG']['gdMaxImgWidth'] || $arrImageSize[1] > $GLOBALS['TL_CONFIG']['gdMaxImgHeight'])
            {
                $blnExceeds = true;
            }
            else
            {
                if (is_array($arrResizeResolution))
                {
                    $intImageWidth = $arrResizeResolution[0];
                    $intImageHeigh = $arrResizeResolution[1];
                }
                else
                {
                    $intImageWidth = $GLOBALS['TL_CONFIG']['imageWidth'];
                    $intImageHeigh = $GLOBALS['TL_CONFIG']['imageHeight'];
                }

                // The image exceeds the maximum image width
                if ($arrImageSize[0] > $intImageWidth)
                {
                    $blnResized = true;
                    $intHeight = ceil($intImageWidth * $arrImageSize[1] / $arrImageSize[0]);
                    $arrImageSize = array($intImageWidth, $intHeight);
                }

                // The image exceeds the maximum image height
                if ($arrImageSize[1] > $intImageHeigh)
                {
                    $blnResized = true;
                    $intWidth = ceil($intImageHeigh * $arrImageSize[0] / $arrImageSize[1]);
                    $arrImageSize = array($intWidth, $intImageHeigh);
                }

                if ($blnResized)
                {
                    $this->resizeImage($newFile, $arrImageSize[0], $arrImageSize[1]);
                    $this->size = filesize(TL_ROOT . '/' . $newFile);
                }
            }
        }

        return array(
            'blnExceeds' => $blnExceeds,
            'blnResized' => $blnResized
        );    
    }

    /**
     * Check if 'doNotOverwrite' is empty or a spezific methode is set and rewrite the file name. 
     * Then move the temporary file to the upload folder 
     * 
     * @param string $doNotOverwrite Setting from the backend config 
     */
    public function move($doNotOverwrite, $strSessionName)
    {
        // Do not overwrite existing files
        if ($doNotOverwrite == 'useSuffix' && file_exists(TL_ROOT . '/' . $this->newPath))
        {
            $this->doNotOverwrite();
        }
        elseif ($doNotOverwrite == 'useTimeStamp' && file_exists(TL_ROOT . '/' . $this->newPath))
        {
            $this->newName = $this->orgName . '_' . $this->timestamp . '.' . $this->getNewPathInfo('extension');
        }

        if ($this->objFiles->rename($this->tmp_name, $this->uploadFolder . '/' . $this->newName))
        {
            //$this->resizeImage($this->uploadFolder . '/' . $this->newName);
            $this->writeFileToSession(array('uploaded' => TRUE), TRUE, $strSessionName);
        }
    }

    /**
     * Check if 'doNotOverwrite' is empty or a spezific methode is set and rewrite the file name. 
     * Then save the temporary file in his specific methode (xhr or ffl)
     * 
     * @param string $doNotOverwrite
     * @return bool 
     */
    public function save($doNotOverwrite)
    {
        // Do not overwrite existing files
        if ($doNotOverwrite == 'useSuffix' && file_exists(TL_ROOT . '/' . $this->newPath))
        {
            $this->doNotOverwrite();
        }
        elseif ($doNotOverwrite == 'useTimeStamp' && file_exists(TL_ROOT . '/' . $this->newPath))
        {
            $this->newName = $this->getNewPathInfo('filename') . '_' . $this->timestamp . '.' . $this->getNewPathInfo('extension');
        }
        elseif($doNotOverwrite == 'overwriteFile')
        {
            $this->boolOverwrittenFile = $this->checkIfIsFileOverwrite($doNotOverwrite);
        }

        if ($this->methode == NULL)
        {
            return NULL;
        }

        if ($this->methode == 'xhr')
        {
            $input    = fopen("php://input", "r");
            $realSize = stream_copy_to_stream($input, $this->tmpFile);
            fclose($input);

            if ($realSize != $this->size)
            {
                return FALSE;
            }

            $target = fopen(TL_ROOT . '/' . $this->newPath, "w");
            fseek($this->tmpFile, 0, SEEK_SET);
            stream_copy_to_stream($this->tmpFile, $target);
            fclose($target);
            unset($_FILES['qqfile'][$this->name]);
        }

        if ($this->methode == 'ffl')
        {
            if (!$this->objFiles->move_uploaded_file($this->tmpFile, $this->newPath))
            {
                return FALSE;
            }
        }

        return TRUE;
    }
    
    public function delete()
    {
        return $this->objFiles->delete($this->tmp_name);
    }

}

?>