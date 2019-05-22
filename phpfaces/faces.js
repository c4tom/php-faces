/**
 * @name Faces.js
 * @author Hüseyin Bora Abacı
 * @copyright Hüseyin Bora Abacı
 * @version 1.0
 *
 */
function getEventType(name)
{
    var EventsType =
    {
        "click":"ActionEvent" ,
        "onclick":"ActionEvent",
        "change" :"ValueChangedEvent",
        "mouseover":"MouseEvent",
        "mouseout":"MouseEvent", 
        "mousedown":"MouseEvent",
        "mouseup":"MouseEvent",
        "dblclick":"MouseEvent"
    };
  
    return EventsType[name];
}

function getEventName(name)
{
    var Events={
        "click":"actionPerformed",
        "onclick":"actionPerformed",
        "change" :"valueChanged",
        "mouseover":"mouseOver",
        "mouseout":"mouseExited",
        "mousedown":"mouseDown", 
        "mouseup":"mouseUp",
        "dblclick":"mousedbClick"
    };
    return Events[name];
}
function createInput(name,id,value)
{
    var element = dojo.doc.createElement("input");//document.createElement("input");
    element.setAttribute("type", "hidden");
    element.setAttribute("name", name);
    element.setAttribute("id", id);
    element.setAttribute("value",value);
    return element;

}
function eventElements(form,comid,eventType,eventName)
{
    //dijit.byId("combox1").store.inn
    var type_element = dojo.doc.createElement("input");//document.createElement("input");
    type_element.setAttribute("type", "hidden");
    type_element.setAttribute("name", "eventtype");
    type_element.setAttribute("id", "element_id");
    type_element.setAttribute("value",eventType);
  
    form.appendChild(type_element); 
    var event_element = document.createElement("input");
    event_element.setAttribute("type", "hidden");
    event_element.setAttribute("name", "eventmetot");
    event_element.setAttribute("id", "element_id1");
    event_element.setAttribute("value",eventName);
    form.appendChild(event_element);
    var validation = dojo.byId("__EVENT__VALIDATION__");
    var view_state = dojo.byId("__VIEW__STATE__");
    if(validation)
        form.appendChild(createInput("__EVENT__VALIDATION__","",validation.value));
    if(view_state)
        form.appendChild(createInput("__VIEW__STATE__","",view_state.value));
    //form.appendChild(view_state);
    var com_element = document.createElement("input");
    com_element.setAttribute("type", "hidden");
    com_element.setAttribute("name", "component");
    com_element.setAttribute("id", "_COMPONENT_ID_");
    com_element.setAttribute("value",dojo.byId(comid).getAttribute("name"));
    form.appendChild(com_element);
/*/var str="eventAttributes =array(";
    var arr = Array();
    for(var key in evt)
        arr[key]=evt[key];
    return arr;*/
}

function tunel(evt,eventmetot)
{
    var arr = Array();

    if(evt.type){

        arr[0]=getEventName(evt.type);
        arr[1] =getEventType(evt.type);
    }
    else
    { 
        arr[0]=eventmetot;
        arr[1] =evt;
    }
    return arr;
}

function actionPost(id,evt,eventmetot)
{
    var event= tunel(evt,eventmetot);
    var form = addForm(dojo.byId(id))
    eventElements(form,id,event[1],event[0]);
    form.submit();
}
function actionPostForm(formid,comid,evt,eventmetot)
{
    var event= tunel(evt,eventmetot);
    var form = dojo.byId(formid);
    addComponentName(dojo.byId(comid), form);
    eventElements(form,comid,event[1],event[0]);
    form.submit();
}
function ajaxPostForm(formid,comid,evt,eventmetot) {

    var event= tunel(evt,eventmetot);
    var  aform = dojo.byId(formid);
    eventElements(aform,comid,event[1],event[0]);
   
    var kw = {
        form: aform,
        url: location.href, //aform.target,
        handleAs: "json",
        load: function(data,ioargs){
            ajaxUpdate(data);

        },
  
        timeout:5000
    };
    dojo.xhrPost(kw);
    aform.removeChild(dojo.byId("_COMPONENT_ID_"));

}


//------------------------------------------------------
function ajaxPost(id,evt,eventmetot,updater) {
    
     var events= tunel(evt,eventmetot);
    
    var aform = addForm(dojo.byId(id));
    eventElements(aform,id,events[1],events[0]);
    var kw = {
        form: aform,
        url: location.href,
        handleAs: "json",
        load: function(data,ioargs){
            if(updater==null)
            ajaxUpdate(data);
            else{
                var code = updater+"(data);";
                eval(code);
            }

        },
      
        timeout: 5000
    };
    dojo.xhrPost(kw);
    document.body.removeChild(aform); 
}



function ajaxUpdate(data)
{
    dojo.require("dijit.dijit");
    var items = eval(data);
    ifade = /^on/;
    for(var obj in items){
        // alert(items[obj].name);
        for(var obj2 in items[obj].keys)
        {
            var attributename = items[obj].keys[obj2];
            
            node =dijit.byId(items[obj].id);

            if(!node||node instanceof dijit.form.Button)
            node =dojo.byId(items[obj].id);
      
            if(!ifade.exec(attributename))
            {
               if(attributename=="_AJAX_UPDATER_")
                   {
                       
                         var code = items[obj][attributename]+"(items[obj].id,items[obj]);";
                         eval(code);
                          continue;
                   }

               if(attributename=="text"){
                    dojo.attr(node, "value",items[obj][attributename]);
                    continue;
                }
                else{
                    if(node.type!=null)
                        if(node.type.toLowerCase()=="select-one" && attributename.toLowerCase()=="model"){
                            listUpdate(node, items[obj][attributename]);
                            continue;
                        }
                    
                    if(attributename=="style")
                    {
                        var  temp = new Array();
                        temp = items[obj][attributename].split(":");
                        dojo.style(items[obj].id, temp[0], temp[1]);
                        continue;
                    }else{
                        // alert(attributename);
                        dojo.attr(node, attributename , items[obj][attributename]);
                        continue;
                    }
                }    
            }
            else
                dojo.connect(node , attributename, function() {
                    items[obj][attributename]
                });
        }
    }
}


function addForm(elem)
{
   
    var form= dojo.doc.createElement("form");
    dojo.attr(form, "action",location.href);
    dojo.attr(form, "method","post");
    dojo.attr(form, "name","_FACES_FORM_");
    var text= dojo.doc.createElement("input");
    dojo.attr(text, "type","hidden");
    dojo.attr(text, "name",elem.name);
    if(elem.type=="select-one")
        dojo.attr(text, "value",elem.options[elem.selectedIndex].text);
    else if(elem.type=="checkbox")
    {
        dojo.attr(text, "value",elem.value);
        var text1= dojo.doc.createElement("input");
        dojo.attr(text1, "type","hidden");
        dojo.attr(text1, "name","checked");
        dojo.attr(text1, "value",elem.checked);
        form.appendChild(text1);
    }
    else
        dojo.attr(text, "value",elem.value);
    form.appendChild(text);
 
    document.body.appendChild(form);
    return form ;
//body.removeChild(form); 
}
function addComponentName(elem,form)
{
    var text= dojo.doc.createElement("input");
    dojo.attr(text, "type","hidden");
    dojo.attr(text, "name",elem.name);
    dojo.attr(text, "value",elem.value);
    form.appendChild(text);
}

function listUpdate(node,data)
{
    node.innerHTML="";
    for(var text in data){
        optn = dojo.doc.createElement("option");
        optn.text = data[text];
        node.options.add(optn);
    }
}