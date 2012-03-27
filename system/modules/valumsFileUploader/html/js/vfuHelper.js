/**
 * Class ValumsFileUploader
 *
 * Provide methods to handle the ValumsFileUploader functionality
 * @copyright  MEN AT WORK 2012
 * @package    valumsFileUploader
 * @license    GNU/GPL 2
 */
var ValumsFileUploader = new Class(
{
    /**
     * Implements options class
     */
    Implements: Options,    
    
    /**
     * Options array
     */
    options: {
        fflId : false,
        id : false,
        fileName : false,
        responseJSON : false,
        currentElem : false,
        detailsFailureMessage : false
    },
    
    /**
     * Initialize class
     * 
     * @param array options
     */
    initialize: function(options){
        this.setOptions(options);
    },
    
    /**
     * Set the number of the current element in list
     * 
     * @param integer id
     */
    setId: function(id)
    {
        this.options.id = id;
        
        var tmpId = 'file-uploader-' + this.options.fflId;
        this.options.currentElem = $(tmpId).getElement('ul.qq-upload-list').getChildren()[this.options.id];        
    },
    
    /**
     * Set the filename from current element in list
     * 
     * @param string fileName
     */
    setfileName: function(fileName)
    {        
        this.options.fileName = fileName;
    },
    
    /**
     * Set the json response from current element in list
     * 
     * @param object responseJSON
     */
    setResponseJSON: function(responseJSON)
    {
        this.options.responseJSON = responseJSON;
    },   

    /**
     * Run
     */
    run: function(){
        var tmpId = 'file-uploader-' + this.options.fflId;
        this.options.currentElem = $(tmpId).getElement('ul.qq-upload-list').getChildren()[this.options.id];

        this.updateFileName();
        
        if(this.options.responseJSON.success && this.options.responseJSON.resized || 
            this.options.responseJSON.success && this.options.responseJSON.exceeds)
        {
            this.updateSuccessResizedMsg();                      
        }
        else if(this.options.responseJSON.success)
        {
            this.updateSuccessMsg();
        }
        
        if(this.options.detailsFailureMessage)
        {
           
        }              
    },
    
    /**
     * Update the filename from current element in list
     */
    updateFileName: function()
    {        
        this.options.currentElem.getElement('span.qq-upload-file').set({
            html : this.options.responseJSON.filename
        });     
    },
    
    /**
     * Update the success message from current element in list when image was
     * resized
     */
    updateSuccessResizedMsg: function()
    {
        this.addDeleteButton(this.options.currentElem,
            this.options.fflId,
            this.options.responseJSON.filename);
        
        this.options.currentElem.getElement('span.qq-upload-text').set({
            'html' : this.options.responseJSON.resized_message, 
            'class' : 'qq-upload-success-text'
        }); 
    },
    
    /**
     * Update the success message from current element in list
     */
    updateSuccessMsg: function()
    {
        this.addDeleteButton(this.options.currentElem,
            this.options.fflId,
            this.options.responseJSON.filename);
        
        this.options.currentElem.getElement('span.qq-upload-text').set({
            'html' : '', 
            'class' : 'qq-upload-success-text'
        });
    },
    
    /**
     * Add delete button and event to current element in list
     * 
     * @param object elem
     * @param integer fflId
     * @param string fileName
     */
    addDeleteButton: function(elem, fflId, fileName)
    {
        elem.getElement('a.qq-upload-delete').set({
            html: 'x ',                    
            events: {
                click: function(){
                    new Request.JSON({
                        method:'get',
                        url:'ajax.php',
                        data: {'action': 'ffl', 'id':fflId, 'type':'valumsFileUploader', 'value':'deleteFile', 'file':fileName},
                        evalScripts:false,
                        evalResponse:false,
                        onSuccess:function(responseJSON){                    
                            var childElems = elem.getChildren();
                            childElems.each(function(el, index){
                                elem.removeChild(el);
                            }.bind(elem));
                            elem.toggle();
                        }
                    }).send(); 
                }.bind()
            }                        
        });
    },
    
    /**
     * Update the deatialed failure message from current element in list
     */
    updateDetailedFailureMsg: function()
    {
        this.options.currentElem.getElement('span.qq-upload-text').set({
            html : this.options.responseJSON.reasonText, 
            'class': 'qq-upload-failed-text'
        });         
    },

    /**
     * Add delete button and event to all elements from list after reload the
     * page
     * 
     * @param object elem
     */
    addDeleteToReloadElem: function(elem)
    {
        elem.getChildren().each(function(el, index){
            this.addDeleteButton(el, 
                this.options.fflId, 
                el.getElement('span.qq-upload-file').textContent);
        }.bind(this));
    }
    
});



