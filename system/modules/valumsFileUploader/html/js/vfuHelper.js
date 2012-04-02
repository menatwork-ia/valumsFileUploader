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
        fflIdName : false,
        id : false,
        fileName : false,
        responseJSON : false,
        currentElem : false,
        failureMassage : false,
        detailsFailureMessage : false,
        allowDelete : false,
        action : false,
        actionParam : false
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
        // Convert given param to string
        id = id.toString();
        
        // Special id handling for IE and Opera
        if(id.test('qq-upload-handler-iframe'))
        {
            this.options.id = id.replace('qq-upload-handler-iframe', '');
        }
        else
        {
            this.options.id = id;
        }        
        
        this.options.currentElem = $(this.options.fflIdName).getElement('ul.qq-upload-list').getChildren()[this.options.id];        
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
        this.options.currentElem = $(this.options.fflIdName).getElement('ul.qq-upload-list').getChildren()[this.options.id];                   
        
        if(this.options.responseJSON.success)
        {
            this.updateFileName();
            this.updateSuccessMsg();
        }
        else
        {
           this.updateFailureMsg();
        }
    },
    
    /**
     * Update the filename from current element in list
     */
    updateFileName: function()
    {
        this.options.currentElem.getElement("span.qq-upload-file").set({
            html : this.options.responseJSON.filename
        });     
    },
    
    /**
     * Update the failure message from current element in list
     */
    updateFailureMsg: function()
    {
        var failureMsg = '';
        if(this.options.detailsFailureMessage && this.options.responseJSON.reasonText)
        {
            failureMsg = this.options.responseJSON.reasonText;
        }
        else
        {
            failureMsg = this.options.failureMassage;
        }
        
        this.options.currentElem.getElement('span.qq-upload-text').set({
            'html' : '<br />' + failureMsg,
            'class' : 'qq-upload-failed-text'
        });
    },
    
    /**
     * Update the success message from current element in list
     */
    updateSuccessMsg: function()
    {
        if(this.options.allowDelete)
        {
            this.addDeleteButton(this.options.currentElem,
                this.options.fflId,
                this.options.responseJSON.filename,
                this.options.action,
                this.options.actionParam);                
        }
        
        var successMsg = '';
        if(this.options.responseJSON.resized || this.options.responseJSON.exceeds)
        {
            this.options.currentElem.getElement('span.qq-upload-size').set({
                'html' : this.options.responseJSON.resized_size
            });
            successMsg = this.options.responseJSON.resized_message;            
        }
        
        if(this.options.responseJSON.overwritten) this.removeOverwrittenFilesFromList();
        
        this.options.currentElem.getElement('span.qq-upload-text').set({
            'html' : '' + ((successMsg) ? '<br />' + successMsg : '') +  
                ((this.options.responseJSON.overwritten) ? '<br />' + this.options.responseJSON.overwritten_message : ''), 
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
    addDeleteButton: function(elem, fflId, fileName, action, actionParam)
    {
        elem.getElement('a.qq-upload-delete').set({
            html: 'x ',                    
            events: {
                click: function(){
                    new Request.JSON({
                        method:'get',
                        url: action,
                        data: {'action': actionParam, 'id':fflId, 'type':'valumsFileUploader', 'value':'deleteFile', 'file':fileName},
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
                el.getElement('span.qq-upload-file').textContent,
                this.options.action,
                this.options.actionParam);
        }.bind(this));
    },
    
    /**
     * Remove overwritten files from file list
     */
    removeOverwrittenFilesFromList: function()
    {
        var arrElemLists = [this.options.currentElem.getParent().getChildren().erase(this.options.currentElem)];
        if(this.options.reloadList)
        {
            arrElemLists.push(this.options.reloadList.getChildren());
        }        
        
        arrElemLists.each(function(elemList, index){
            elemList.each(function(elem, index){
                if(elem.getChildren().length > 0)
                {
                    var strImg = elem.getElement('span.qq-upload-file').textContent;
                    if(strImg == this.options.currentElem.getElement('span.qq-upload-file').textContent)
                    {
                        var childElems = elem.getChildren();
                        childElems.each(function(el, index){
                            elem.removeChild(el);
                        }.bind(elem));
                        elem.toggle();
                    }
                }
            }.bind(this));
        }.bind(this));
    }
    
});