<?php if(!$this->doFiles): ?>     
    <h3><?php echo $GLOBALS['TL_LANG']['UPL']['be_upload_file']['0']; ?></h3>
<?php endif; ?>
<div class="backend" id="file-uploader">       
    <noscript>          
        <p><?php echo sprintf($GLOBALS['TL_LANG']['ERR']['val_be_noscript'], $this->noJsBeLink); ?></p>
    </noscript>
</div>

<?php if ($this->arrSessionFiles): ?>
    <ul id="vfu_reload" class="qq-upload-list">
        <?php foreach ($this->arrSessionFiles as $arrFile): ?>
            <li class=" qq-upload-success">
                <a class="qq-upload-delete" href="#" onclick="return false;"></a>
                <span class="qq-upload-file"><?php echo $arrFile['name']; ?></span>
                <span class="qq-upload-size" style="display: inline;"><?php echo $this->objHelper->getFormatedSize($arrFile['size']); ?></span>
                <span class="qq-upload-failed-text"><?php echo $arrFile['error']; ?></span>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<script type="text/javascript">
/* <![CDATA[ */
function createUploader(vfu){
    var uploader = new qq.FileUploader({
        element: document.getElementById('<?php echo $this->uploaderId; ?>'),            
        action: '<?php echo $this->action; ?>',            
        params: <?php echo $this->params; ?>,
        allowedExtensions: [<?php echo $this->objHelper->getStrExt($this->extensions); ?>],                    
        debug: <?php echo ($this->debug) ? "true" : "false"; ?>,            
        sizeLimit: <?php echo ($this->maxFileSize) ? $this->maxFileSize : '""'; ?>,            
        template: '<div class="qq-uploader">' + 
            '<div class="qq-upload-drop-area"><span><?php echo (strlen($this->dropTextLabel) != 0) ? $this->dropTextLabel : $GLOBALS['TL_LANG']['UPL'][$this->pos . '_upload_drop_area']; ?><\/span><\/div>' +
            '<div class="qq-upload-button"><?php echo (strlen($this->dropButtonLabel) != 0) ? $this->dropButtonLabel :  $GLOBALS['TL_LANG']['UPL'][$this->pos . '_upload_button']; ?><\/div>' +
            '<ul class="qq-upload-list"><\/ul>' + 
            '<\/div>',
        fileTemplate: '<li>' +
            <?php if($this->allowDelete) echo "'<a class=\"qq-upload-delete\" href=\"#\" onclick=\"return false;\"><\/a>' +"; ?>
            '<span class="qq-upload-file"><\/span>' +
            '<span class="qq-upload-spinner"><\/span>' +
            '<span class="qq-upload-size"><\/span>' +
            '<a class="qq-upload-cancel" href="#"><?php echo $GLOBALS['TL_LANG']['UPL']['upload_cancel']; ?><\/a>' +
            '<span class="qq-upload-text"><\/span>' +
            '<\/li>',
        messages: {
            typeError: <?php echo $GLOBALS['TL_LANG']['ERR']['val_type_error']; ?>,
            sizeError: <?php echo $GLOBALS['TL_LANG']['ERR']['val_size_error']; ?>,
            minSizeError: <?php echo $GLOBALS['TL_LANG']['ERR']['val_min_size_error']; ?>,
            emptyError: <?php echo $GLOBALS['TL_LANG']['ERR']['val_empty_error']; ?>,
            onLeave: <?php echo $GLOBALS['TL_LANG']['ERR']['val_on_leave']; ?>          
        },
        onComplete: function(id, fileName, responseJSON){
            vfu.setId(id);
            vfu.setfileName(fileName);
            vfu.setResponseJSON(responseJSON);                
            vfu.run(id, fileName, responseJSON);
        }
    });
}
window.addEvent('domready', function(){  
    var vfu = new ValumsFileUploader({
        'action' : '<?php echo $this->action; ?>',
        'actionParam' : '<?php echo $this->actionParam; ?>',
        'fflId' : '<?php echo $this->strId; ?>',
        'fflIdName' : '<?php echo $this->uploaderId; ?>',            
        'failureMassage' : '<?php echo $GLOBALS['TL_LANG']['UPL']['upload_failed_text']; ?>',
        'detailsFailureMessage' : <?php echo (($this->detailsFailureMessage) ? 'true' : 'false'); ?>,
        'allowDelete' : <?php echo ($this->allowDelete) ? 'true' : 'false'; ?>,
        'reloadList' : $('vfu_reload')
    });
    createUploader(vfu);
    <?php if($this->allowDelete): ?>    
    if($('vfu_reload'))
    {
        vfu.addDeleteToReloadElem($('vfu_reload'));
    }
    <?php endif; ?>    
});
/* ]]> */
</script>

<?php if(!$this->doFiles && $this->description == NULL): ?>
    <p class="tl_help tl_tip"><?php echo $GLOBALS['TL_LANG']['UPL']['be_upload_file']['1']; ?></p>
<?php endif; ?>