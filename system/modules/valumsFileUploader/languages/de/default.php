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
 * Form fields
 */
$GLOBALS['TL_LANG']['FFL']['valumsFileUploader'] = array('Mehrfacher Datei-Upload (valumsFileUploader)');

/**
 *  Valums
 */
$GLOBALS['TL_LANG']['UPL']['upload_drop_area'] = 'Zum Hochladen die Datei in dieses Feld ziehen';
$GLOBALS['TL_LANG']['UPL']['upload_button'] = 'Datei Hochladen';
$GLOBALS['TL_LANG']['UPL']['upload_cancel'] = 'Abbrechen';
$GLOBALS['TL_LANG']['UPL']['upload_failed_text'] = 'Fehlgeschlagen';

// Logger
$GLOBALS['TL_LANG']['UPL']['log_success'] = 'Erfolgreich hochgeladen';

// BE
$GLOBALS['TL_LANG']['UPL']['overwriteFile'] = 'Datei überschreiben';
$GLOBALS['TL_LANG']['UPL']['useSuffix'] = 'Suffix setzen';
$GLOBALS['TL_LANG']['UPL']['useTimeStamp'] = 'Zeitstempel setzen';

/**
 * Error
 */
$GLOBALS['TL_LANG']['ERR']['val_type_error'] = '"{file} ist ein nicht erlaubter Dateityp. Nur die Dateitypen {extensions} sind erlaubt."';
$GLOBALS['TL_LANG']['ERR']['val_size_error'] = '"{file} ist zu groß, die maximal erlaubte Dateigröße ist {sizeLimit}."';
$GLOBALS['TL_LANG']['ERR']['val_min_size_error'] = '"{file} ist zu klein, die minimal erlaubte Dateigröße ist {minSizeLimit}."';
$GLOBALS['TL_LANG']['ERR']['val_empty_error'] = '"{file} ist leer, bitte wählen Sie diese Datei nicht mehr aus."';
$GLOBALS['TL_LANG']['ERR']['val_on_leave'] = '"Die Daten werden hochgeladen, wenn Sie die Seite jetzt verlassen wird der Prozess abgebrochen."';

// Logger
$GLOBALS['TL_LANG']['ERR']['val_log_no_file'] = 'Konnte Datei nicht erstellen';
$GLOBALS['TL_LANG']['ERR']['val_log_not_writeable'] = 'Verzeichnis ist nicht beschreibbar';
$GLOBALS['TL_LANG']['ERR']['val_log_file_size_zero'] = 'Datei ist leer';
$GLOBALS['TL_LANG']['ERR']['val_log_max_size'] = 'Dateigröße zu groß';
$GLOBALS['TL_LANG']['ERR']['val_log_wrong_type'] = 'Dateiendung nicht korrekt';
$GLOBALS['TL_LANG']['ERR']['val_log_save_error'] = 'Konnte nicht gespeichert werden';
?>
