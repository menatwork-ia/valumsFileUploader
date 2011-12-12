<div id="tl_soverview">
    <h3><?php echo $GLOBALS['TL_LANG']['UPL']['be_upload_file']['0']; ?></h3>
    <div class="backend" id="file-uploader">       
        <noscript>          
        <p><?php echo sprintf($GLOBALS['TL_LANG']['ERR']['val_be_noscript'], $this->noJsBeLink); ?></p>
        </noscript>
    </div>
    <p class="tl_help tl_tip"><?php echo $GLOBALS['TL_LANG']['UPL']['be_upload_file']['1']; ?></p>
</div>

<script type="text/javascript">
    function createUploader(){  
        var uploader = new qq.FileUploader({
            element: document.getElementById('file-uploader'),
            // path to server-side upload script
            action: '<?php echo $this->action; ?>',            
            // additional data to send, name-value pairs
            params: {
                action: '<?php echo $this->paramAction; ?>',
                bypassToken: '1'
            },
            // ex. ['jpg', 'jpeg', 'png', 'gif'] or []
            allowedExtensions: ['<?php echo implode("','", $this->uploadTypes); ?>'],        
            // set to true to output server response to console
            debug: <?php echo ($this->debug) ? "'true'" : "'false'"; ?>,
            sizeLimit: <?php echo ($this->maxFileSize) ? $this->maxFileSize : '""'; ?>,
            // Template wrapper for all items
            template: '<div class="qq-uploader">' + 
                '<div class="qq-upload-drop-area"><span><?php echo $GLOBALS['TL_LANG']['UPL']['be_upload_drop_area']; ?></span></div>' +
                '<div class="qq-upload-button"><?php echo $GLOBALS['TL_LANG']['UPL']['be_upload_button']; ?></div>' +
                '<ul class="qq-upload-list"></ul>' + 
                '</div>',            
            // Template for one item in file list
            fileTemplate: '<li>' +
                '<span class="qq-upload-file"></span>' +
                '<span class="qq-upload-spinner"></span>' +
                '<span class="qq-upload-size"></span>' +
                '<a class="qq-upload-cancel" href="#"><?php echo $GLOBALS['TL_LANG']['UPL']['upload_cancel']; ?></a>' +
                '<span class="qq-upload-failed-text"><?php echo $GLOBALS['TL_LANG']['UPL']['upload_failed_text']; ?></span>' +
                '</li>',
            // Error messages
            messages: {
                typeError: <?php echo $GLOBALS['TL_LANG']['ERR']['val_type_error']; ?>,
                sizeError: <?php echo $GLOBALS['TL_LANG']['ERR']['val_size_error']; ?>,
                minSizeError: <?php echo $GLOBALS['TL_LANG']['ERR']['val_min_size_error']; ?>,
                emptyError: <?php echo $GLOBALS['TL_LANG']['ERR']['val_empty_error']; ?>,
                onLeave: <?php echo $GLOBALS['TL_LANG']['ERR']['val_on_leave']; ?>          
            }
        });        
    }
    window.onload = createUploader;
</script>