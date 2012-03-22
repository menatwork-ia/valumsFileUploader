window.addEvent('domready', function(){

    if($$('.tl_formbody_submit')[0])
    {
        var arrElem = $$('div.tl_formbody_submit')[0].getElements('input[name=upload]')
        arrElem.each(function(elem){
           elem.dispose(); 
        });
    }
	
});