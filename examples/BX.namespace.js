var CustomStuff = BX.namespace('CustomStuff');

CustomStuff.helloWorld = function () {
    //alert(BX.message('CUSTOM_STUFF_HELLO_WORLD'));
    //console.log('CUSTOM_STUFF_HELLO_WORLD');
};

CustomStuff.checkBtn = function () {
    console.log("checkBtn");
    let calc = function () {
        let dealId = $("div.crm-entity-card-container-content").attr("id");
        let fieldOutput = $("div[data-cid='UF_CUSTOM_5'] div.crm-entity-widget-content-block-inner");
        $.ajax({
            type: "POST",
            url: "/local/php_interface/lib/UserType/CUserTest.php",
            data: "id=" + dealId,
            success: function (data) {
                if (data != 0) {
                    console.log("Успешный запрос " + data);
                    //location.reload();
                    fieldOutput.html("");
                    fieldOutput.text(data);
                    //console.log(fieldOutput);
                }
                else
                    console.log("Ошибка запроса " + data);
            }
        })
    }
    calc();
}

CustomStuff.inConsole = function () {
    /*
     function checkBtn() {
         console.log("checkBtn 1");
     }
     checkBtn();
     */
    //console.log("check inConsole");
    //console.log(BX.message('CUSTOM_STUFF_HELLO_WORLD'));
    /*let btnOpen = BX.findChildren(BX("workarea-content"), {
        "data-cid" : "UF_CUSTOM_2"
    }, true);
    */
    //let btnOpen = BX.findChildren(BX("body"));
    //let btnOpen = BX.findChildByClassName(document, 'crm-entity-widget-content-block', true);
    //let btnOpen = document.querySelectorAll("div");
    $(document).ready(function () {
        let setValueUf = function () {
            let dealId = $("div.crm-entity-card-container-content").attr("id");
            let uField = "UF_CUSTOM_6";
            let fieldOutput = $("div[data-cid='UF_CUSTOM_6'] div.crm-entity-widget-content-block-inner");
            $.ajax({
                type: "POST",
                url: "/local/php_interface/lib/UserType/CUserTest.php",
                data: "id=" + dealId + "&key=" + uField,
                success: function (data) {
                    if (data != 0) {
                        console.log("Успешный запрос " + data);
                        //location.reload();
                        if(fieldOutput.html() == "не заполнено"){
                            console.log("UF_CUSTOM_6 is empty");
                        }
                        else{
                            console.log(fieldOutput.html());
                        }
                        //fieldOutput.html("");
                        //fieldOutput.text(data);
                        //console.log(fieldOutput);
                    }
                    else
                        console.log("Ошибка запроса " + data);
                }
            })
        }
        /*
        $("div[data-cid='UF_CUSTOM_1'] div.crm-entity-widget-content-block-inner").on("click", function () {
            calc();
        });
        */
        //let btnOpen = $("div[data-cid='UF_CUSTOM_2'] div.crm-entity-widget-content-block-title span.crm-entity-widget-content-block-title-text");
        //let output = $("div[data-cid='UF_CUSTOM_2'] div.crm-entity-widget-content-block-inner");
        //btnOpen.addClass("ui-btn ui-btn-danger");
        //output.addClass("ui-btn ui-btn-primary");
        //console.log(btnOpen);
        //console.log(output);
        /*
        btnOpen.on("click", function () {
            let dealId = $("div.crm-entity-card-container-content").attr("id");
            console.log(dealId);
            $.ajax({
                type: "POST",
                url: "/local/php_interface/lib/UserType/CUserTest.php",
                data: "id=" + dealId,
                success: function (data) {
                    if (data != 0) {
                        console.log("Успешный запрос " + data);
                        output.text(data);
                    }
                    else
                        console.log("Ошибка запроса " + data);
                }
            })
        })
        */
        /*
         let btnCalc = $("div[data-cid='UF_CUSTOM_1'] div.crm-entity-widget-content-block-inner");
         let output = $("div[data-cid='UF_CUSTOM_5'] div.crm-entity-widget-content-block-inner");
         console.log(btnCalc);
         console.log(output);
         btnCalc.on("click", function () {
             let dealId = $("div.crm-entity-card-container-content").attr("id");
             console.log(dealId);
             $.ajax({
                 type: "POST",
                 url: "/local/php_interface/lib/UserType/CUserTest.php",
                 data: "id=" + dealId,
                 success: function (data) {
                     if (data != 0) {
                         console.log("Успешный запрос " + data);
                         //location.reload();
                         output.html("");
                         output.text(data);
                         console.log(output);
                     }
                     else
                         console.log("Ошибка запроса " + data);
                 }
             })
         })
 */
        //btnOpen.html('');
        //btnOpen.append('<a href="#" class="ui-btn ui-btn-danger">Открыть бланк заказа</a>');

        $("button[title='[Ctrl+Enter]'").on("click", function () {
            console.log("Ctrl+Enter");
            setValueUf();
            /*
            $("div[data-cid='UF_CUSTOM_1'] div.crm-entity-widget-content-block-inner").on("click", function () {
                //console.log("Ctrl+Enter");
                calc();
            });
            */
        });
        $("a[title='[Esc]'").on("click", function () {
            /*
            $("div[data-cid='UF_CUSTOM_1'] div.crm-entity-widget-content-block-inner").on("click", function () {
                calc();
            });
            */
        });

        //let btnBack = $("button[title='[Ctrl+Enter]'");
        //sconsole.log(btnBack);
        /*
         function checkBtn() {
             console.log("checkBtn");
         }
         checkBtn();
         */
    });
};

/*

<div class="crm-entity-widget-content-block crm-entity-widget-content-block-click-editable crm-entity-widget-content-block-click-empty" data-cid="UF_CUSTOM_2">
<div class="crm-entity-widget-content-block-title crm-entity-widget-content-block-title-edit"><span class="crm-entity-widget-content-block-title-text">Открыть бланк заказа</span></div>

<div class="crm-entity-card-container-content" id="deal_13_details_editor_container">

<div class="crm-entity-widget-content-block-inner">не заполнено</div>
<button class="crm-entity-widget-content-block-inner-pay-button ui-btn ui-btn-sm ui-btn-primary">Принять оплату</button>

<div class="crm-entity-widget-content-block-inner"><a href="#" class="ui-btn ui-btn-danger">Посчитать сумму заказа</a></div>

<div class="crm-entity-widget-content-block-inner">
<span class="fields string field-wrap">
            <span class="fields string field-item">
            120		</span>
    </span></div>

<a href="#" class="ui-btn ui-btn-link" title="[Esc]">Отменить</a>

<div class="crm-entity-widget-content-block-inner-text crm-entity-widget-content-block-inner-text-pay"><span class="crm-entity-widget-content-block-wallet"><span class="crm-entity-widget-content-block-colums-right">0</span> ₽</span><button class="crm-entity-widget-content-block-inner-pay-button ui-btn ui-btn-sm ui-btn-primary">Принять оплату</button></div>

<span class="fields string field-wrap">
			<span class="fields string field-item">
			1		</span>
	</span>

// установка фокуса на поле
VM13483:15 {eventObject: Window, eventName: "BX.Main.Filter:blur", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: XMLHttpRequest, eventName: "onAjaxSuccess", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "XMLHttpRequest"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: null, eventName: "onAjaxSuccessFinish", eventParams: Array(1), secureParams: undefined}

// при изменении поля
{eventObject: Window, eventName: "BX.Main.Filter:blur", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: XMLHttpRequest, eventName: "onAjaxSuccess", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "XMLHttpRequest"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: null, eventName: "onAjaxSuccessFinish", eventParams: Array(1), secureParams: undefined}
VM13483:15 {eventObject: XMLHttpRequest, eventName: "onAjaxSuccess", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "XMLHttpRequest"}
VM13483:15 {eventObject: null, eventName: "onAjaxSuccessFinish", eventParams: Array(1), secureParams: undefined}
VM13483:15 {eventObject: Window, eventName: "BX.Main.Filter:blur", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Main.Filter:blur", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: XMLHttpRequest, eventName: "onAjaxSuccess", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "XMLHttpRequest"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: null, eventName: "onAjaxSuccessFinish", eventParams: Array(1), secureParams: undefined}
VM13483:15 {eventObject: XMLHttpRequest, eventName: "onAjaxSuccess", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "XMLHttpRequest"}
VM13483:15 {eventObject: null, eventName: "onAjaxSuccessFinish", eventParams: Array(1), secureParams: undefined}

// при сохранении изменений
VM13483:15 {eventObject: Window, eventName: "BX.Main.Filter:blur", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditor:onRelease", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorSection:onLayout", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorSection:onLayout", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorSection:onLayout", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorSection:onLayout", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditor:onRefreshLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: XMLHttpRequest, eventName: "onAjaxSuccess", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "XMLHttpRequest"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: XMLHttpRequest, eventName: "onAjaxSuccess", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "XMLHttpRequest"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: null, eventName: "onAjaxSuccessFinish", eventParams: Array(1), secureParams: undefined}


// при отмене изменений
VM13483:15 {eventObject: Window, eventName: "BX.Main.Filter:blur", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditor:onCancel", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditor:onRelease", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorSection:onLayout", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorSection:onLayout", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorSection:onLayout", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorSection:onLayout", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditor:onRefreshLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: XMLHttpRequest, eventName: "onAjaxSuccess", eventParams: Array(2), secureParams: undefined, eventObjectClassName: "XMLHttpRequest"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: Window, eventName: "BX.Crm.EntityEditorField:onLayout", eventParams: Array(1), secureParams: undefined, eventObjectClassName: "Window"}
VM13483:15 {eventObject: null, eventName: "onAjaxSuccessFinish", eventParams: Array(1), secureParams: undefined}


(function(){"use strict";BX.namespace("BX.Main.UF");if(typeof BX.Main.UF.Manager!=="undefined"){return}var e={};BX.Main.UF.Manager=function(){this.mode=this.mode||"";this.ajaxUrl="/bitrix/tools/uf.php"};BX.Main.UF.Manager.getEdit=function(e,t){return BX.Main.UF.EditManager.get(e,t)};BX.Main.UF.Manager.getView=function(e,t){return BX.Main.UF.ViewManager.get(e,t)};BX.Main.UF.Manager.prototype.get=function(e,t){if(!this.mode){this.displayError(["No mode set. Use BX.UF.EditManager or BX.UF.ViewManager"]);return}return this.query(this.mode,{FIELDS:e.FIELDS,FORM:e.FORM||"",CONTEXT:e.CONTEXT||"",MEDIA_TYPE:e.MEDIA_TYPE||""},t)};BX.Main.UF.Manager.prototype.add=function(e,t){if(!this.mode){this.displayError(["No mode set. Use BX.UF.EditManager or BX.UF.ViewManager"]);return}return this.query(this.mode,{action:"add",FIELDS:e.FIELDS,FORM:e.FORM||""},t)};BX.Main.UF.Manager.prototype.update=function(e,t){if(!this.mode){this.displayError(["No mode set. Use BX.UF.EditManager or BX.UF.ViewManager"]);return}return this.query(this.mode,{action:"update",FIELDS:e.FIELDS,FORM:e.FORM||""},t)};BX.Main.UF.Manager.prototype.delete=function(e,t){if(!this.mode){this.displayError(["No mode set. Use BX.UF.EditManager or BX.UF.ViewManager"]);return}return this.query(this.mode,{action:"delete",FIELDS:e.FIELDS,FORM:e.FORM||""},t)};BX.Main.UF.Manager.prototype.query=function(e,t,n){BX.ajax({dataType:"json",url:this.ajaxUrl,method:"POST",data:this.prepareQuery(e,t),onsuccess:this.queryCallback(n)})};BX.Main.UF.Manager.prototype.prepareQuery=function(e,t){var n=t||{};n.mode=e;n.lang=BX.message("LANGUAGE_ID")||"";n.tpl=BX.message("UF_SITE_TPL")||"";n.tpls=BX.message("UF_SITE_TPL_SIGN")||"";n.sessid=BX.bitrix_sessid();return n};BX.Main.UF.Manager.prototype.queryCallback=function(e){var t=BX.proxy(this.processResult,this);return function(n){t(n,e)}};BX.Main.UF.Manager.prototype.processResult=function(e,t){var n="";if(BX.type.isArray(e.ASSET)){n+=e.ASSET.join("\n")}if(!!e.ERROR){this.displayError(e.ERROR)}return BX.html(null,n).then(function(){if(!!t){t(e.FIELD)}})};BX.Main.UF.Manager.prototype.displayError=function(e){for(var t in e){if(e.hasOwnProperty(t)){console.error(e[t])}}};BX.Main.UF.Manager.prototype.registerField=function(t,n,i){e[t]={FIELD:n,NODE:i}};BX.Main.UF.Manager.prototype.unRegisterField=function(t){if(!!e[t]){delete e[t]}};BX.Main.UF.ViewManager=function(){BX.Main.UF.ViewManager.superclass.constructor.apply(this,arguments);this.mode="main.view"};BX.extend(BX.Main.UF.ViewManager,BX.Main.UF.Manager);BX.Main.UF.EditManager=function(){BX.Main.UF.EditManager.superclass.constructor.apply(this,arguments);this.mode="main.edit"};BX.extend(BX.Main.UF.EditManager,BX.Main.UF.Manager);BX.Main.UF.EditManager.prototype.validate=function(t,n){if(t.length>0){var i=[];for(var a=0;a<t.length;a++){var r=BX.Main.UF.Factory.getValue(t[a]);if(r!==null){i.push({ENTITY_ID:e[t[a]].FIELD.ENTITY_ID,FIELD:e[t[a]].FIELD.FIELD,ENTITY_VALUE_ID:e[t[a]].FIELD.ENTITY_VALUE_ID,VALUE:r})}}return this.query(this.mode,{action:"validate",FIELDS:i},n)}else{this.queryCallback(n)({FIELD:[]})}};BX.Main.UF.BaseType=function(){};BX.Main.UF.BaseType.prototype.addRow=function(e,t){var n=t.parentNode.getElementsByTagName("span");if(n&&n.length>0&&n[0]){var i=n[0].parentNode;var a=this.getClone(n[n.length-1],e);if(i===t.parentNode){i.insertBefore(a,t)}else{i.appendChild(a)}}};BX.Main.UF.BaseType.prototype.addMobileRow=function(e,t){var n=t.parentNode.getElementsByTagName("span");if(n&&n.length&&n[0]){var i=n[0].parentNode;var a=this.getClone(n[n.length-1],e);var r=a.firstElementChild;var o=r.getAttribute("name");var s=/\[(\d)]/;var p=o.replace(s,function(e,t){t=parseInt(t)+1;return"["+t+"]"});var F=false;var l=false;var u=null;r.setAttribute("name",p);if(r.hasChildNodes()){r.childNodes.forEach(function(e,t,n){if(!l&&e.attributes!==undefined&&e.tagName==="INPUT"){e.setAttribute("name",p);l=e.getAttribute("id");F=l+"_1";u=e.getAttribute("data-user-field-type-name")}if(l&&e.attributes!==undefined&&e.id!==undefined){var i=e.getAttribute("id");if(i!==l){e.setAttribute("id",i.replace(l,F))}else{e.setAttribute("id",F)}}})}if(i===t.parentNode){i.insertBefore(a,t)}else{i.appendChild(a)}if(F){BX.onCustomEvent("onAddMobileUfField",[F,u])}}};BX.Main.UF.BaseType.prototype.getClone=function(e,t){var n=e.cloneNode(true);var i=this.findInput(n,t);for(var a=0;a<i.length;a++){i[a].value=""}return n};BX.Main.UF.BaseType.prototype.findInput=function(e,t){return BX.findChildren(e,{tagName:/INPUT|TEXTAREA|SELECT/i,attribute:{name:t}},true)};BX.Main.UF.BaseType.prototype.isEmpty=function(t){var n=this.getNode(t),i=t+(e[t].FIELD.MULTIPLE==="Y"?"[]":"");if(!BX.isNodeInDom(n)){console.error("Node for field "+t+" is already removed from DOM")}var a=this.findInput(n,i);if(a.length<=0){console.error("Unable to find field "+t+" in the registered node")}else{for(var r=0;r<a.length;r++){if(a[r].value!==""){return false}}}return true};BX.Main.UF.BaseType.prototype.getValue=function(t){var n=this.getNode(t),i=t+(e[t].FIELD.MULTIPLE==="Y"?"[]":""),a=e[t].FIELD.MULTIPLE==="Y"?[]:"";if(!BX.isNodeInDom(n)){console.error("Node for field "+t+" is already removed from DOM")}var r=this.findInput(n,i);if(r.length<=0){var o=n.children.length?n.children[0]:false;if(!BX.util.in_array(e[t].FIELD.USER_TYPE_ID,["crm","employee"])&&(!o||o.getAttribute("data-has-input")!=="no")){console.error("Unable to find field "+t+" in the registered node")}}else{for(var s=0;s<r.length;s++){if(r[s].tagName==="INPUT"&&(r[s].type==="radio"||r[s].type==="checkbox")&&!r[s].checked){continue}if(e[t].FIELD.MULTIPLE==="Y"){a.push(r[s].value)}else{a=r[s].value;break}}}return a};BX.Main.UF.BaseType.prototype.focus=function(t){var n=this.getNode(t),i=t+(e[t].FIELD.MULTIPLE==="Y"?"[]":"");if(!BX.isNodeInDom(n)){console.error("Node for field "+t+" is already removed from DOM")}var a=this.findInput(n,i);if(a.length>0){BX.focus(a[0])}};BX.Main.UF.BaseType.prototype.getNode=function(t){return e[t].NODE};BX.Main.UF.TypeBoolean=function(){};BX.extend(BX.Main.UF.TypeBoolean,BX.Main.UF.BaseType);BX.Main.UF.TypeBoolean.USER_TYPE_ID="boolean";BX.Main.UF.TypeBoolean.prototype.isEmpty=function(e){return false};BX.Main.UF.TypeInteger=function(){};BX.extend(BX.Main.UF.TypeInteger,BX.Main.UF.BaseType);BX.Main.UF.TypeInteger.USER_TYPE_ID="integer";BX.Main.UF.TypeDouble=function(){};BX.extend(BX.Main.UF.TypeDouble,BX.Main.UF.BaseType);BX.Main.UF.TypeDouble.USER_TYPE_ID="double";BX.Main.UF.TypeSting=function(){};BX.extend(BX.Main.UF.TypeSting,BX.Main.UF.BaseType);BX.Main.UF.TypeSting.USER_TYPE_ID="string";BX.Main.UF.TypeUrl=function(){};BX.extend(BX.Main.UF.TypeUrl,BX.Main.UF.BaseType);BX.Main.UF.TypeUrl.USER_TYPE_ID="url";BX.Main.UF.TypeStingFormatted=function(){};BX.extend(BX.Main.UF.TypeStingFormatted,BX.Main.UF.TypeSting);BX.Main.UF.TypeStingFormatted.USER_TYPE_ID="string_formatted";BX.Main.UF.TypeEnumeration=function(){};BX.extend(BX.Main.UF.TypeEnumeration,BX.Main.UF.BaseType);BX.Main.UF.TypeEnumeration.USER_TYPE_ID="enumeration";BX.Main.UF.TypeEnumeration.prototype.findInput=function(e,t){var n=BX.Main.UF.TypeEnumeration.superclass.findInput.apply(this,arguments);if(n.length>0){for(var i=0;i<n.length;i++){if(n[i].tagName==="INPUT"&&n[i].type==="hidden"&&n.length>1){delete n[i];break}}}return BX.util.array_values(n)};BX.Main.UF.TypeEnumeration.prototype.focus=function(t){if(e[t]&&e[t].FIELD.SETTINGS.DISPLAY==="UI"&&BX.type.isElementNode(e[t].NODE)){BX.fireEvent(e[t].NODE,"focus")}else{BX.Main.UF.TypeEnumeration.superclass.focus.apply(this,arguments)}};BX.Main.UF.TypeDate=function(){};BX.extend(BX.Main.UF.TypeDate,BX.Main.UF.BaseType);BX.Main.UF.TypeDate.USER_TYPE_ID="date";BX.Main.UF.TypeDate.prototype.focus=function(t){var n=t+(e[t].FIELD.MULTIPLE==="Y"?"[]":"");var i=this.findInput(this.getNode(t),n);if(i.length>0){BX.fireEvent(i[0],"click")}BX.Main.UF.TypeDate.superclass.focus.apply(this,arguments)};BX.Main.UF.TypeDateTime=function(){};BX.extend(BX.Main.UF.TypeDateTime,BX.Main.UF.TypeDate);BX.Main.UF.TypeDateTime.USER_TYPE_ID="datetime";BX.Main.UF.TypeFile=function(){};BX.extend(BX.Main.UF.TypeFile,BX.Main.UF.BaseType);BX.Main.UF.TypeFile.USER_TYPE_ID="file";BX.Main.UF.TypeFile.prototype.findInput=function(e,t){var n=BX.Main.UF.TypeFile.superclass.findInput.apply(this,arguments);if(n.length<=0){n=BX.findChildren(e,{tagName:/INPUT/i,attribute:{type:"file",name:/^bxu_files/}},true)}return n};BX.Main.UF.TypeFile.prototype.getValue=function(t){var n=BX.Main.UF.TypeFile.superclass.getValue.apply(this,arguments),i=e[t].NODE,a=[],r;if(e[t].FIELD.MULTIPLE==="Y"){var o=t+"_del[]";if(BX.type.isArray(n)&&n.length>0){a=BX.Main.UF.TypeFile.superclass.findInput.apply(this,[i,o]);for(r=0;r<a.length;r++){var s=BX.util.array_search(a[r].value,n);if(s>=0){n[s]={old_id:a[r].value,del:"Y",tmp_name:""}}}}return BX.util.array_values(n)}else if(n>0){var o=t+"_del";a=BX.Main.UF.TypeFile.superclass.findInput.apply(this,[i,o]);for(r=0;r<a.length;r++){if(n==a[r].value){n={old_id:n,del:"Y",tmp_name:""};break}}return n}};BX.Main.UF.Factory=function(){this.defaultTypeHandler=BX.Main.UF.BaseType;this.typeHandlerList={};this.objectCollection={}};BX.Main.UF.Factory.prototype.setTypeHandler=function(e,t){this.typeHandlerList[e]=t;if(typeof this.objectCollection[e]!=="undefined"){delete this.objectCollection[e]}};BX.Main.UF.Factory.prototype.get=function(e){if(typeof this.objectCollection[e]==="undefined"){this.objectCollection[e]=this.getObject(e)}return this.objectCollection[e]};BX.Main.UF.Factory.prototype.getObject=function(e){return new(this.typeHandlerList[e]||this.defaultTypeHandler)};BX.Main.UF.Factory.prototype.getFieldObject=function(t){if(typeof e[t]==="undefined"){console.error("Field "+t+"is not registered. Use BX.Main.UF.Factory.registerField to register");return null}return this.get(e[t]["FIELD"]["USER_TYPE_ID"])};BX.Main.UF.Factory.prototype.isEmpty=function(t){if(typeof e[t]==="undefined"){console.error("Field "+t+"is not registered. Use BX.Main.UF.Factory.registerField to register");return true}return this.get(e[t]["FIELD"]["USER_TYPE_ID"]).isEmpty(t)};BX.Main.UF.Factory.prototype.getValue=function(t){if(typeof e[t]==="undefined"){console.error("Field "+t+"is not registered. Use BX.Main.UF.Factory.registerField to register");return null}return this.get(e[t]["FIELD"]["USER_TYPE_ID"]).getValue(t)};BX.Main.UF.Factory.prototype.focus=function(t){if(typeof e[t]==="undefined"){console.error("Field "+t+"is not registered. Use BX.Main.UF.Factory.registerField to register")}return this.get(e[t]["FIELD"]["USER_TYPE_ID"]).focus(t)};BX.Main.UF.EditManager=new BX.Main.UF.EditManager;BX.Main.UF.ViewManager=new BX.Main.UF.ViewManager;BX.Main.UF.Factory=new BX.Main.UF.Factory;BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeBoolean.USER_TYPE_ID,BX.Main.UF.TypeBoolean);BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeInteger.USER_TYPE_ID,BX.Main.UF.TypeInteger);BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeDouble.USER_TYPE_ID,BX.Main.UF.TypeDouble);BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeSting.USER_TYPE_ID,BX.Main.UF.TypeSting);BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeStingFormatted.USER_TYPE_ID,BX.Main.UF.TypeStingFormatted);BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeEnumeration.USER_TYPE_ID,BX.Main.UF.TypeEnumeration);BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeFile.USER_TYPE_ID,BX.Main.UF.TypeFile);BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeDate.USER_TYPE_ID,BX.Main.UF.TypeDate);BX.Main.UF.Factory.setTypeHandler(BX.Main.UF.TypeDateTime.USER_TYPE_ID,BX.Main.UF.TypeDateTime)})();
*/