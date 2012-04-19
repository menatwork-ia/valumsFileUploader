valumsFileUploader
======================

Screenshots
-----------

![personal configuration](http://img7.imagebanana.com/img/z1xt0aph/tl_user.jpg)

Other screenshots
https://github.com/menatwork/valumsFileUploader/wiki/Screenshots

System requirements
-------------------

* Contao 2.9.x or higher
* ajax 1.0.6 or higher
* ajax-upload 1.0.0 or higher


Installation & Configuration
----------------------------

* Unpack the archive on your server
* Open the installation directory in your web browser
* Update the database
* Activate the valumsFileUploader functionality in your personal data


Possible attributes for BE-Widget
---------------------------------

```php
$GLOBALS['TL_DCA']['tl_example']['fields']['vfuExample'] = array
(
    // Set the button label and the help text
    'label' => array('Button label', 'Help text'),
 
    // Set field to "valumsFileUploader"
    'inputType' => 'valumsFileUploader',
 
    // Set special options
    'eval' => array(
 
        // Set path for files to save
        'path' => 'tl_files',
 
        // Set the maximum allowed file size
        'maxFileSize' => 2048000000,
 
        // Set the allowed file extensions comma seperated
        'extensions' => 'jpeg, jpg, png',
 
        // Set the the value to not override existing files and 
        // choose the value to handle the files (useSuffix, useTimeStamp)
        'overwrite' => 'useSuffix',
 
        // Set the this value with height and with to resize the file
        'resize' => array('800', '800'),
 
        // Set debug mode
        'debug' => true,
 
        // Set label for drop field
        'dropTextLabel' => 'Drop text label'
    )
);
```


Troubleshooting
---------------

If you are having problems using the valumsFileUploader Extension, please visit the issue tracker at https://github.com/menatwork/valumsFileUploader