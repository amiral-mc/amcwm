<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://www.amc.amiral.com/license/amcwm.txt
 */

/**
 * Description of ApiController
 * @author Amiral Management Corporation
 * @version 1.0
 */

class ApiController extends Controller {

    private $jsCode = null;

    public function __construct($id, $module = null) {        
        parent::__construct($id, $module);
        Yii::app()->errorHandler->errorAction = '/api/error';        
    }

    /**
     * This is the default 'index' action that is invoked
     * when an action is not explicitly requested by users useing their mobile fones.
     */
    public function actionIndex($component = "widget") {
        header("Content-type: text/javascript"); // must change output mimi type 
        $this->jsInit();
        $this->jsCode .=$this->getCustomColors();
        switch ($component) {
            case "widget":
                $this->jsCode .= 'AMC.' . $component . '.init();';
                break;
        }
        echo $this->jsCode;
        Yii::app()->end();
    }

    public function actionXml($lang = 'ar', $limit = 10, $start = 0, $sectionId = null){
        header("Content-type: text/xml"); // must change output mimi type 
        $created = Yii::app()->request->getParam('created');
        $keywords = Yii::app()->request->getParam('keywords', array());
        $newsData = new ApiNewsData($lang, $created, $sectionId, $keywords, true);
        $newsItems = $newsData->getData($limit, $start, false);
        $this->renderPartial('xml', array('newsItems'=>$newsItems, 'lang'=>$lang));        
    }
    /**
     * This is the action to handle external exceptions.
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            $output->message = $error['message'];
            $output->code = $error['code'];
            $jsonEncode = CJavaScript::jsonEncode($output);
            echo $jsonEncode;
            Yii::app()->end();
        }
    }

    public function actionHotNewsData($id, $limit = 10, $start = 0, $lang = 'ar', $sectionId = null) {
        $jsonEncode = $this->getNewsItems($id, $lang, $limit, $start, $sectionId, true);
        $callback = Yii::app()->request->getParam("callback");
        if ($callback) {
            echo $callback . '(' . $jsonEncode . ')';
        } else {
            echo $jsonEncode;
        }
    }

    private function getNewsItems($id, $lang = 'ar', $limit = 10, $start = 0, $sectionId = null, $repeated = false) {
        $created = Yii::app()->request->getParam('created');
        $keywords = Yii::app()->request->getParam('keywords', array());
        $newsData = new ApiNewsData($lang, $created, $sectionId, $keywords);
        $newsItems = $newsData->getData($limit, $start, $repeated);
        $newsItems = $newsData->generateDataLinks($newsItems);
        $news = new stdClass();
        if (count($newsItems)) {
            $news->maxCreatedDate = $newsItems[0]['publish_date'];
            foreach ($newsItems as &$newsItem) {
                $newsItem['publish_date'] = Yii::app()->dateFormatter->format("dd/MM/y hh:mm a", $newsItem['publish_date']);
                //$newsItem['title'] = CJavaScript::quote($newsItem['title']);
            }
        }
        $news->id = $id;
        $news->news = $newsItems;
        $news->count = count($newsItems);
        $news->error = 0;
        return CJavaScript::jsonEncode($news);
    }
    /**
     * @todo check dom style for the object id
     */

    private function jsInit() {
        $this->jsCode = '            
            if(!window.AMC){
                window.AMC = {                    
                    dataStructure : {
                        hash : function (){
                            this.length = 0;
                            this.items = new Array();
                            for (var i = 0; i < arguments.length; i += 2) {
                                if (typeof(arguments[i + 1]) != "undefined") {
                                    this.items[arguments[i]] = arguments[i + 1];
                                    this.length++;
                                }
                            }   
                            this.removeItem = function(inKey){
                                var deletedItem;
                                if (typeof(this.items[inKey]) != "undefined") {
                                    this.length--;
                                    deletedItem = this.items[inKey];
                                    delete this.items[inKey];
                                }	   
                                return deletedItem;
                            }
                            this.getItem = function(inKey) {
                                var item = null;
                                if (typeof(this.items[inKey]) != "undefined") {
                                    item = this.items[inKey];
                                }    
                                return item;
                            }
                            this.setItem = function(inKey, inValue){
                                var oldItem = null;
                                if (typeof(inValue) != "undefined") {
                                    if (typeof(this.items[inKey]) == "undefined") {
                                        this.length++;
                                    }
                                    else {
                                        oldItem = this.items[inKey];
                                    }
                                    this.items[inKey] = inValue;
                                }	 
                                return oldItem;
                            }
                            this.hasItem = function(inKey){
                                return typeof(this.items[inKey]) != "undefined";
                            }
                            this.clear = function(){
                                for (var i in this.items) {
                                    delete this.items[i];
                                }
                                this.length = 0;
                            }
                        }
                    },
                    widget : {
                        activeList : null,
                        loadingImg : null,
                        useCss : true,                        
                        background : "#063356",
                        gradientFrom : "#042037",
                        gradientTo : "#0D68B3",
                        titleColor : "#FCC105",
                        titleBorderColor : "#336389",
                        titleBorderSize : 1,
                        footerBorderColor : "#174C75",
                        footerBorderSize : 1,
                        footerColor : "#FEC94A",
                        scrollerBackground : "#EFF0F1",
                        contentColor : "#FFFFFA",
                        listColor : " #FFFFFF",
                        listBorderColor  : " #0B5999",
                        dateTimeColor  : " #B6CCDE",
                        listBorderSize: 1,           
			theme : "dark",             
                        setThemeColors : function(){
                            switch(this.theme){
                                case "light":
                                    this.background = "#F6F6F6";
                                    this.scrollerBackground = "#303030";
                                    this.listColor = "#000000";
                                    this.dateTimeColor = "#838C8B";
                                    this.contentColor = "#000000";
                                    this.listBorderColor = "#838C8B";
                                    this.titleColor = "#9E0100";
                                    this.titleBorderColor = "#000000";
                                    this.footerColor = "#000000";
                                    this.footerBorderColor = "#000000";
                                break;
                                
                            }
                        },
                        setContentColor : function(color){
                            this.contentColor = color;
                        },
                        setDateTimeColor : function(color){
                            this.dateTimeColor = color;
                        },
                        setListColor : function(color, borderColor , borderSize){
                            if(borderSize){
                                this.listBorderSize = borderSize;
                            }                            
                            if(borderColor){
                                this.listBorderColor = borderColor;
                            }                            
                            this.listColor = color;
                        },
                        setFooterColor : function(color, borderColor , borderSize){
                            if(borderSize){
                                this.footerBorderSize = borderSize;
                            }                            
                            if(borderColor){
                                this.footerBorderColor = borderColor;
                            }                            
                            this.footerColor = color;
                        },
                        setBackground : function(background, gradientFrom, gradientTo){
                            this.background = background;
                            this.gradientFrom = gradientFrom;
                            this.gradientTo = gradientTo;
                        },                        
                        setScrollerBackground : function(background){
                            this.scrollerBackground = background;
                        },
			setTheme : function(theme){
				this.theme = theme;
			},
                        setTitleColor : function(color, borderColor , borderSize){
                            if(borderSize){
                                this.titleBorderSize = borderSize;
                            }                            
                            if(borderColor){
                                this.titleBorderColor = borderColor;
                            }
                            this.titleColor = color;
                        },                        
                        init : function (){                        	
                            if(typeof this.hasInit == "undefined"){
				this.loadingImg = "' . Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . '/images/api/widget/"+AMC.widget.theme+"/loadwidget.gif",                                
                                this.generateCss();
                                this.activeList = new AMC.dataStructure.hash();                                
                                this.hasInit = true;
                                
                            }
                        },
                        create : function(id, width, height, useScroll, orientation){                        
                            if(!width){
                                width = 200;
                            }
                            if(!height){
                                height = 400;
                            }
                            if(!useScroll){
                                useScroll = 0;
                            }
                            if(!orientation){
                                orientation = "rtl";
                            }
                            this.id = id;                            
                            this.useScroll = useScroll;
                            this.width = width;
                            this.height = height;
                            this.orientation = orientation;
                            if(!AMC.dom.$(id)){
                                document.write("<div id=\""+id+"\"></div>");                                
                            }
                            this.domObj = AMC.dom.$(id);
                            this.insideConatainer = AMC.dom.createElement("div");
                            this.domObj.innerHTML = "<img src=\"' . Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . '/images/api/loading.gif\" border=\"0\" />";
                            this.links = new Array();                            
                            this.loader = null;
                            this.addLink = function(text, link){
                                linkItem = {text:text, link:link};
                                this.links.push(linkItem);
                            };
                            this.addHtmlContent = function(content){
                                this.insideConatainer.innerHTML = content;   
                            };
                            this.appendChild2Content = function(child){
                                this.insideConatainer.appendChild = child;   
                            };
                            this.scrollUp = function(e){
                                AMC.dom.$(this.id + "Inside").scrollTop = AMC.dom.$(this.id + "Inside").scrollTop + 10;
                            };
                            this.scrollDown = function(e){
                                AMC.dom.$(this.id + "Inside").scrollTop = AMC.dom.$(this.id + "Inside").scrollTop - 10;
                            };
                            this.draw = function(title, poweredBy){                           
                                this.domObj.innerHTML = "";
                                var scrollbarWidth = (this.useScroll) ? 9 : 0;
                                var insideWidth = this.width - scrollbarWidth - 9;
                                var titleHeight = 25;
                                var ownerHeight = 50;
                                var insideHeight = this.height - (titleHeight + ownerHeight);
                                var scrollbarHeight = insideHeight - 10;    
                                this.domObj.style.width = this.width;                                
                                this.domObj.style.height = this.height;                        
                                this.domObj.className  = "amc_widget_conatainer";                        
                                this.domObj.dir  = this.orientation;
                                
                                this.insideConatainer.id = id + "Inside";
                                this.insideConatainer.className = "amc_widget_inside_conatainer";
                                this.insideConatainer.style.width = insideWidth + "px";
                                this.insideConatainer.style.height = insideHeight + "px";                                                    
                                scrollbarConatainer = AMC.dom.createElement("div");
                                scrollbarConatainer.style.height = scrollbarHeight + "px";
                                scrollbarConatainer.className = "amc_widget_scrollbar";
                                titleConatainer = AMC.dom.createElement("div");
                                footerConatainer = AMC.dom.createElement("div");
                                footerConatainer.className = "amc_widget_footer";
                                
                                if(this.orientation == "rtl"){
                                    this.insideConatainer.style.marginRight = "5px";                                    
                                    this.insideConatainer.style.cssFloat = "right";
                                    scrollbarConatainer.style.cssFloat = "right";
                                    this.insideConatainer.style.styleFloat = "right";
                                    scrollbarConatainer.style.styleFloat = "right";
                                    titleConatainer.style.textAlign = "right";
                                    this.insideConatainer.style.textAlign = "right";
                                    footerConatainer.style.textAlign = "right";
                                    var float = "left";                    
                                }
                                else{                                    
                                    this.insideConatainer.style.marginLeft = "5px";
                                    this.insideConatainer.style.cssFloat = "left";
                                    scrollbarConatainer.style.cssFloat = "left";                                    
                                    this.insideConatainer.style.styleFloat = "left";
                                    scrollbarConatainer.style.styleFloat = "left";                                    
                                    titleConatainer.style.textAlign = "left";
                                    this.insideConatainer.style.textAlign = "left";
                                    footerConatainer.style.textAlign = "left";
                                    var float = "right";                    
                                }                                
                                scrollbarConatainerValue = \'<img class="amc_widget_scrollbar_up" id ="\' + this.id + \'ScrollbarDown" src="' . Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . '/images/api/widget/\'+AMC.widget.theme+\'/scrollbar_up.png" border="0" />\';
                                scrollbarConatainerValue += \'<div class="amc_widget_scrollbar_bg" style="height:\' + (scrollbarHeight - 18) + \'px;"></div>\';
                                scrollbarConatainerValue  += \'<img class="amc_widget_scrollbar_down" id ="\' + this.id + \'ScrollbarUp" src="' . Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . '/images/api/widget/\'+AMC.widget.theme+\'/scrollbar_down.png" border="0" />\';                                
                                scrollbarConatainer.innerHTML = scrollbarConatainerValue;                                
                                titleConatainer.className = "amc_widget_title";
                                titleConatainer.innerHTML = \'<span>\' + title + \'</span>&nbsp;<span class="loader" id="\' + this.id + \'Loader"></span>\';
                                footerValue = \'<span class="amc_widget_footer_label">&nbsp;&nbsp;<a href="' . Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . '" target="_blank">\' + poweredBy + \'</a>&nbsp;</span>\';
                                footerValue += \'<span><a href="http://amc.amiral.com" target="_blank"><img src="' . Yii::app()->request->getHostInfo() . Yii::app()->request->baseUrl . '/images/api/widget/\'+AMC.widget.theme+\'/logo_life.png" border="0"></a></span>\';
                                    linksArea = "";
                                for(var i = 0 ; i < this.links.length ; i++){
                                    linksArea +=\'<a href="\' + this.links[0].text +\'" target="_blank" >\' + this.links[0].text +\'</a>\';
                                }
                                footerValue += \'<span class="amc_widget_links_area" style="float:\' + float + \';padding-\'+float+\':20px;">\' + linksArea + \'</span>\';
                                sepConatainer = AMC.dom.createElement("div");
                                sepConatainer.innerHTML = \'<div style="clear:both;"></div>\'; 
                                footerConatainer.innerHTML = footerValue;                                
                                this.domObj.appendChild(titleConatainer);
                                this.domObj.appendChild(this.insideConatainer);
                                if(this.useScroll){
                                    this.domObj.appendChild(scrollbarConatainer);
                                    var self = this;
                                    AMC.dom.addListener(AMC.dom.$(this.id+"ScrollbarUp"), "click", function(){self.scrollUp()});
                                    AMC.dom.addListener(AMC.dom.$(this.id+"ScrollbarDown"), "click", function(){self.scrollDown()});
                                }
                                this.domObj.appendChild(sepConatainer);
                                this.domObj.appendChild(footerConatainer);                                
                                this.loader = AMC.dom.$(this.id+"Loader");
                            };
                            this.startLoader = function(){
                                this.loader.innerHTML = \'<img src="\' + AMC.widget.loadingImg + \'" border="0"  alt="" />\' ;
                            }                            
                            this.stopLoader = function(){
                                this.loader.innerHTML = "";
                            }
                            AMC.widget.activeList.setItem(this.id, this);
                            
                        },
                        getWidegtsList : function (){
                            return AMC.widget.activeList;
                        },
                        generateCss : function(){                                                        
                            if(this.useCss && typeof this.cssCode == "undefined"){                                
                                this.cssCode = ".amc_widget_conatainer{";
                                this.cssCode += "    width:100%;";  
				this.cssCode += "    -webkit-border-radius : 6px;";
                                this.cssCode += "    -moz-border-radius: 6px;";
                                this.cssCode += "    border-radius: 6px;";
                                this.cssCode += "   /* gecko based browsers */";
                                this.cssCode += "    background: -moz-linear-gradient(top, "+this.gradientFrom+", "+this.gradientTo+");";
                                this.cssCode += "   /* webkit based browsers */";
                                this.cssCode += "    background: -webkit-gradient(linear, left top, left bottom, from("+this.gradientFrom+"), to("+this.gradientTo+"));"
                                this.cssCode += "    background: " + this.background + ";";
                                this.cssCode += "}";                                                
                                this.cssCode += ".amc_widget_inside_conatainer{";
                                this.cssCode += "    font-family: Arial, Tahoma, Verdana, sans-serif;";
                                this.cssCode += "    font-size: 11px;";
                                this.cssCode += "    padding-top:4px;";
                                this.cssCode += "    color:" + this.contentColor + ";";
                                this.cssCode += "    overflow:hidden;";
                                this.cssCode += "}";                                
                                this.cssCode += ".amc_widget_scrollbar{";    
                                this.cssCode += "   padding-top:5px;";    
                                this.cssCode += "   width:9px;";    
                                this.cssCode += "}";                                    
                                this.cssCode += ".amc_widget_scrollbar_bg{";                                               
                                this.cssCode += "   background-color:" + this.scrollerBackground +";";                    
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_scrollbar_up{";               
                                this.cssCode += " cursor: pointer;";
                                this.cssCode += " margin-bottom:2px;";
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_scrollbar_down{";                                                                           
                                this.cssCode += "cursor: pointer;";
                                this.cssCode += "margin-top:2px;";                    
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_title{";
                                this.cssCode += " font-family: Arial, Tahoma, Verdana, sans-serif;";                                
                                this.cssCode += " font-weight: bold;";
                                this.cssCode += " font-size: 17px;";
                                this.cssCode += " color: " + this.titleColor + ";";                                        
                                this.cssCode += " padding:8px 15px 5px 10px;";
                                this.cssCode += " border-bottom: " + this.titleBorderSize + "px " + this.titleBorderColor +" solid;";
                                //this.cssCode += " text-shadow: 0px 2px 3px #000;";
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_title span{";
                                this.cssCode += " font-family: Arial, Tahoma, Verdana, sans-serif;";                                
                                this.cssCode += " font-weight: bold;";
                                this.cssCode += " font-size: 17px;";
                                this.cssCode += " color: " + this.titleColor + ";";                                        
                                this.cssCode += "}";                                
                                this.cssCode += ".amc_widget_title .loader{";                    
                                this.cssCode += "    vertical-align: top;";                                
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_title .loader img{";                    
                                this.cssCode += "    vertical-align: middle;";
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_footer{";
                                this.cssCode += "   border-top: " + this.footerBorderSize + "px "  + this.footerBorderColor + " solid;";
                                this.cssCode += "   margin-right:0px;";
                                this.cssCode += "   margin-top:5px;";
                                this.cssCode += "   padding-top:8px;";
                                this.cssCode += "   padding-right:5px;";
                                this.cssCode += "   padding-bottom:5px;";                                
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_footer img{";                    
                                this.cssCode += "    vertical-align: bottom;";
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_footer_label,";                                                    
                                this.cssCode += ".amc_widget_footer_label a,";                   
                                this.cssCode += ".amc_widget_footer_label a:link,";
                                this.cssCode += ".amc_widget_footer_label a:hover,";
                                this.cssCode += ".amc_widget_footer_label a:visited,";                    
                                this.cssCode += ".amc_widget_footer_label a:active{";
                                this.cssCode += "    font-size: 10px;";
                                this.cssCode += "    font-weight:600;";
                                this.cssCode += "    text-decoration : none;";
                                this.cssCode += "    color:" + this.footerColor + ";";
                                this.cssCode += "}";                    
                                this.cssCode += ".amc_widget_links_area,";
                                this.cssCode += ".amc_widget_links_area a,";                   
                                this.cssCode += ".amc_widget_links_area a:link,";
                                this.cssCode += ".amc_widget_links_area a:hover,";
                                this.cssCode += ".amc_widget_links_area a:visited,";                    
                                this.cssCode += ".amc_widget_links_area a:active{";
                                this.cssCode += "    font-family: Arial, Tahoma, Verdana, sans-serif;";
                                this.cssCode += "    font-size: 11px;";
                                this.cssCode += "    color:" + this.footerColor + ";";
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_list{";
                                this.cssCode += "    padding:0px;";
				this.cssCode += "    margin:0px;";
                                this.cssCode += "    list-style:none;";
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_list li{";
                                this.cssCode += "    font-family: Arial, Tahoma, Verdana, sans-serif;";
                                this.cssCode += "    font-size: 14px;";
                                this.cssCode += "    background-image: none;";
                                this.cssCode += "    color:" + this.listColor + ";";
                                this.cssCode += "    font-weight:600;";
                                this.cssCode += "    padding:4px 10px 4px 5px;";
                                this.cssCode += "    border-bottom:" + this.listBorderSize + "px " + this.listBorderColor + " solid;";
                                this.cssCode += "}";                
                                this.cssCode += ".amc_widget_list a:link,";
                                this.cssCode += ".amc_widget_list a:visited,";
                                this.cssCode += ".amc_widget_list a:active{";
                                this.cssCode += "    font-family: Arial, Tahoma, Verdana, sans-serif;";
                                this.cssCode += "    font-size: 14px;";
                                this.cssCode += "    font-weight: bold;";
                                this.cssCode += "    color:" + this.listColor + ";";                                
                                this.cssCode += "    line-height:1px;";                                
                                this.cssCode += "    text-decoration: none;";
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_list a:hover{";
                                this.cssCode += "    text-decoration:underline;";
                                this.cssCode += "}";
                                this.cssCode += ".amc_widget_date{";
                                this.cssCode += "    font-size: 10px;";                                
                                this.cssCode += "    color:" + this.dateTimeColor + ";";
                                this.cssCode += "}";
                                AMC.dom.includeCSSCode(this.cssCode);
                            }                            
                        }
                    },
                    dom : {
                        nodeType : {
                            ELEMENT_NODE: 1,
                            ATTRIBUTE_NODE: 2,
                            TEXT_NODE: 3,
                            CDATA_SECTION_NODE: 4,
                            ENTITY_REFERENCE_NODE: 5,
                            ENTITY_NODE: 6,
                            PROCESSING_INSTRUCTION_NODE: 7,
                            COMMENT_NODE: 8,
                            DOCUMENT_NODE: 9,
                            DOCUMENT_TYPE_NODE: 10,
                            DOCUMENT_FRAGMENT_NODE: 11,
                            NOTATION_NODE: 12
                        },
                        getHead : function(){
                            return document.getElementsByTagName("head")[0];
                        },
                        includeJS : function(script){
                            var scriptElmenent = document.createElement("script");
                            scriptElmenent.type= "text/javascript";                
                            scriptElmenent.src = script;
                            this.getHead().appendChild(scriptElmenent);
                            return scriptElmenent;
                        },        
                        includeCSSCode : function(cssCode){
                            cssElmenent= document.createElement("style");                                                       
                            cssElmenent.setAttribute("type", "text/css");                            
                            if (cssElmenent.styleSheet) { 
                                /* IE compitabilty */
                                cssElmenent.styleSheet.cssText = cssCode;
                            } else {                
                                /* Others */
                                cssElmenent.appendChild(document.createTextNode(cssCode));
                            }
                            this.getHead().appendChild(cssElmenent);           
                        },
                        insertAfter: function (parent, node, referenceNode) {                            
                            parent.insertBefore(node, referenceNode.nextSibling);
                        },
                        includeCSS : function(css, media){
                            var cssElement = document.createElement("link");
                            cssElement.type = "text/css";
                            cssElement.rel = "stylesheet";
                            if(!media){
                                media = "screen";
                            }
                            cssElement.media = media;
                            cssElement.href=css;                
                            this.getHead().appendChild(cssElement);
                            return cssElement;
                        },
                        createElement : function(elm, obj){
                           var element = document.createElement(elm);
                           if(obj){
                               for(prop in obj){
                                    element[prop] = obj[prop];
                               }
                           }
                           return element;
                        },
                        appendChilds : function(parent, childs){                                                    
                            if(parent && childs){                            
                                for(var i = 0; i < childs.length; i++){                           
                                    if(childs[i]){                                    
                                        parent.appendChild(childs[i]);
                                    }
                                }
                            }
                            else{
                                parent = null;
                            }
                            return parent;
                        },
                        removeChildren : function(node){
                            if(node != null){
                                while(node.hasChildNodes()){
                                    node.removeChild(node.firstChild);
                                }
                            }
                        },
                        addListener : function(obj, eventName, listener){
                            attached = false;
                            if (obj.attachEvent){
                                obj.attachEvent("on"+eventName, listener);
                                attached = true;
                            }
                            else if(obj.addEventListener){
                                obj.addEventListener(eventName, listener, false);
                                attached = true;
                            }
                            return attached;
                        },
                        removeListener : function(obj, eventName, listener){
                            detach = false;
                            if(obj.detachEvent){
                                obj.detachEvent("on"+eventName, listener);
                                detach = true;
                            }
                            else if(obj.removeEventListener){
                                obj.removeEventListener(eventName, listener, false);
                                detach = true;
                            }
                            return detach;
                        },
                        getChildNodes : function(node){
                            childs = null;
                            if(node != null && node.hasChildNodes()){
                                childs = node.childNodes;
                            }
                            return childs;
                        },
                        $ : function(id){
                            return document.getElementById(id);
                        },
                        changeOpac : function(elm, opacity){                        
                            if(typeof elm == "string"){
                                var object = this.$(elm).style;
                            }
                            else{
                                var object = elm.style;
                            }                            
                            object.opacity = (opacity / 100);
                            object.MozOpacity = (opacity / 100);
                            object.KhtmlOpacity = (opacity / 100);
                            object.filter = "alpha(opacity=" + opacity + ")";
                        },
                        getPosOffset : function(what, offsetType){
                            return (what.offsetParent)? what[offsetType] + this.getPosOffset(what.offsetParent, offsetType) : what[offsetType]
                        },
                        createUrl : function(route, params){
                            var base = "' . Yii::app()->request->getHostInfo() . Yii::app()->baseUrl . '/index.php";
                            var urlPath = ' . ((Yii::app()->getUrlManager()->getUrlFormat() == "path") ? 1 : 0) . ';                                
                            if(urlPath){
                                for(var i = 0 ; i < params.length; i++){
                                    route += "/" + params[i].name + "/" + this.urlEncode(params[i].value);                
                                }      
                                url = base + route;
                            }
                            else{        
                                for(var i = 0 ; i < params.length; i++){
                                    route += "&" + params[i].name +"=" + this.urlEncode(params[i].value);
                                }        
                                url = base + "?r=" + route;
                            }
                            return url;
                        },
                        urlEncode : function (url){
                            return encodeURI(url);
                        },
                        fadeEffect : {
                            init:function(id, flag, interval, target){                                
                                    if(typeof id == "string"){
                                        this.elem = AMC.dom.$(id);
                                    }
                                    else{
                                        this.elem = id;
                                    }                                
                                    clearInterval(this.elem.si);
                                    this.target = target ? target : flag ? 100 : 0;
                                    
                                    this.interval = interval ? interval : 30;
                                    
                                    this.flag = flag || -1;                                    
                                    this.alpha = this.elem.style.opacity ? parseFloat(this.elem.style.opacity) * 100 : 0;
                                    var self = this;
                                    this.si = setInterval(function(){self.tween()}, this.interval);
                            },
                            tween:function(){
                                    if(this.alpha == this.target){
                                        clearInterval(this.si);
                                    }else{
                                        var value = Math.round(this.alpha + ((this.target - this.alpha) * .05)) + (1 * this.flag);
                                        this.elem.style.opacity = value / 100;
                                        this.elem.style.filter = \'alpha(opacity=\' + value + \')\';
                                        this.alpha = value                                       
                                    }
                            }
                        }            
                    }                    
                };                                                   
            }
        ';
    }

    private function getCustomColors() {
        $background = Yii::app()->request->getParam("bg");
        $contentColor = Yii::app()->request->getParam("cc");
        $scrollerBackground = Yii::app()->request->getParam("sbg");
        $gradientFrom = Yii::app()->request->getParam("gf", $background);
        $gradientTo = Yii::app()->request->getParam("gt", $gradientFrom);
        $listColor = Yii::app()->request->getParam("lc");
        $listBorderColor = Yii::app()->request->getParam("lbc", $listColor);
        $dateColor = Yii::app()->request->getParam("dc");
        $useCss = Yii::app()->request->getParam("ucss", 1);
        $titleColor = Yii::app()->request->getParam("tc");
        $borderTitleColor = Yii::app()->request->getParam("btc", $titleColor);
        $footerColor = Yii::app()->request->getParam("fc");
        $borderFooterColor = Yii::app()->request->getParam("bfc", $footerColor);
        $theme = Yii::app()->request->getParam("theme", "dark");
        //echo  "alert('{$_GET['theme']}');";
        $custom = "";
        $custom .= 'AMC.widget.setTheme("' . $theme . '");';
        $custom .= 'AMC.widget.setThemeColors();';
        if ($background) {
            $custom .= 'AMC.widget.setBackground("' . $background . '", "' . $gradientTo . '", "' . $gradientFrom . '");';
        }
        if ($scrollerBackground) {
            $custom .= 'AMC.widget.setScrollerBackground("' . $scrollerBackground . '");';
        }
        if ($listColor) {
            $custom .= 'AMC.widget.setListColor("' . $listColor . '", "' . $listBorderColor . '");';
        }
        if ($dateColor) {
            $custom .= 'AMC.widget.setDateTimeColor("' . $dateColor . '");';
        }
        if ($contentColor) {
            $custom .= 'AMC.widget.setContentColor("' . $contentColor . '");';
        }
        if ($titleColor) {
            $custom .= 'AMC.widget.setTitleColor("' . $titleColor . '", "' . $borderTitleColor . '");';
        }
        if ($footerColor) {
            $custom .= 'AMC.widget.setFooterColor("' . $footerColor . '", "' . $borderFooterColor . '");';
        }
        return $custom;
    }

    public function actionHotNews($id, $limit = 10, $sectionId = null, $width=200, $height=400, $repeat = 1, $delay = 10000, $init = false, $scroll = 1, $lang = 'ar') {
        header("Content-type: text/javascript"); // must change output mimi type 
        Yii::app()->language = $lang;
        $scroll = (int) $scroll;
        $delay = (int) $delay;
        $repeat = (int) $repeat;
        $width = (int) $width;
        if ($width < 200) {
            $width = 200;
        }
        $height = (int) $height;
        if ($height < 400) {
            $height = 400;
        }
        $sectionId = (int) $sectionId;
        $news = $this->getNewsItems($id, $lang, $limit, 0, $sectionId);
        if ($init) {
            $this->jsInit();
            $this->jsCode .=$this->getCustomColors();
            $this->jsCode .= 'AMC.widget.init();';
        } else {
            $this->jsCode = '';
        }
        $keywords = Yii::app()->request->getParam('keywords', array());
        $extraParams = array();
        foreach ($keywords as $keyword) {
            $extraParams[] = array(
                "name" => "keywords[]",
                "value" => $keyword,
            );
        }
        $mediaPath = '/' . str_replace('/', DIRECTORY_SEPARATOR, ArticlesListData::getSettings()->mediaPaths['list']['path']) . DIRECTORY_SEPARATOR;
        $this->jsCode .= '                                    
            if(!window.AMC.widget.newsWidget){
                window.AMC.widget.newsWidget = AMC.widget.create;
                AMC.widget.newsWidget.changeContent = function (id){                        
                    var widget = AMC.widget.getWidegtsList().getItem(id);
                    //alert(widget.maxCreatedDate);
                    widget.urlParams[widget.urlParams.length] = {name:"created", value:widget.maxCreatedDate};
                    var url = AMC.dom.createUrl("/api/hotNewsData", widget.urlParams);                                                            
                    widget.script = AMC.dom.includeJS(url);                                                    
                };                                   
                AMC.widget.newsWidget.getContent = function(jsonData){
                    var widget = AMC.widget.getWidegtsList().getItem(jsonData.id);                    
                    if(widget){
                        if(jsonData.news.length){       
                            widget.maxCreatedDate = jsonData.maxCreatedDate;                            
                            for(var i=0; i < jsonData.news.length; i++){                                                     
                                newsItem = AMC.dom.createElement("li");                            
                                html = \'<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td width="68">\';                        
                                if(jsonData.news[i].image_ext){
                                    html += \'<a href="\' +jsonData.news[i].link+ \'" target="_blank"><img width="68" src="' . Yii::app()->request->getHostInfo() . Yii::app()->baseUrl . $mediaPath . '\' +jsonData.news[i].id+ \'.\' +jsonData.news[i].image_ext+ \'" /></a>\';
                                }
                                html += \'</td><td valign="top" style="padding-right:3px;">\';
                                html += \'<a href="\' +jsonData.news[i].link+ \'" target="_blank">\' +jsonData.news[i].title+ \'</a>\';
                                html += \'</td></tr></table>\';
                                //html += \'<br><span class="amc_widget_date">\' +jsonData.news[i].publish_date+ \'</span>\';
                                newsItem.innerHTML = html;
                                AMC.dom.fadeEffect.init(newsItem, 1);
                                if(widget.newsList.firstChild){
                                    widget.newsList.insertBefore(newsItem, widget.newsList.firstChild);
                                }
                                else{
                                    widget.newsList.appendChild(newsItem);
                                }                            
                            }
                            if(widget.newsList.lastChild){
                                widget.newsList.removeChild(widget.newsList.lastChild);
                            }                            
                        }
                        widget.stopLoader();                        
                        //AMC.widget.getWidegtsList().setItem(widget.id, widget);                    
                    }                    
                    if(widget.script && widget.script.parentNode){
                        if(widget.script.parentNode == AMC.dom.getHead()){                            
                            AMC.dom.getHead().removeChild(widget.script);
                        }
                    }
                };
                AMC.widget.newsWidget.prototype.init = function(jsonData, delay, repeat, lang, sectionId , extraParams){                                    
                    this.urlParams = new Array();
                    this.maxCreatedDate = jsonData.maxCreatedDate;                   
                    this.urlParams[0] = {name:"sectionId", value:sectionId};                    
                    this.urlParams[1] = {name:"lang", value:lang};                    
                    this.urlParams[2] = {name:"limit", value:1};                                      
                    this.urlParams[3] = {name:"callback", value:"AMC.widget.newsWidget.getContent"};                    
                    this.urlParams[4] = {name:"id", value:this.id};                    
                    for(var i = 0 ; i< extraParams.length ; i++){
                        this.urlParams[6 + i] =  extraParams[i];
                    }
                    this.startLoader();
                    this.newsList = AMC.dom.createElement("ul");
                    this.newsList.className = "amc_widget_list";
                    this.newsList.id = this.id + "List";
                    this.delay = delay;                    
                    for(var i=0; i < jsonData.news.length; i++){
                        newsItem = AMC.dom.createElement("li");                            
                        html = \'<table cellpadding=0 cellspacing=0 border=0 width="100%"><tr><td width="68">\';                        
                        if(jsonData.news[i].image_ext){
                            html += \'<a href="\' +jsonData.news[i].link+ \'" target="_blank"><img width="68" src="' . Yii::app()->request->getHostInfo() . Yii::app()->baseUrl . $mediaPath . '\' +jsonData.news[i].id+ \'.\' +jsonData.news[i].image_ext+ \'" /></a>\';
                        }
                        html += \'</td><td valign="top" style="padding-right:3px;">\';
                        html += \'<a href="\' +jsonData.news[i].link+ \'" target="_blank">\' +jsonData.news[i].title+ \'</a>\';
                        html += \'</td></tr></table>\';
                        //html += \'<br><span class="amc_widget_date">\' +jsonData.news[i].publish_date+ \'</span>\';
                        newsItem.innerHTML = html;                        
                        this.newsList.appendChild(newsItem);
                    }
                    //AMC.dom.fadeEffect.init(this.newsList, 1);
                    this.insideConatainer.appendChild(this.newsList);
                    this.stopLoader();          
                    this.script = null;                    
                    var self = this;
                    if(repeat){                            
                        setInterval(function(){                    
                            self.startLoader();
                            AMC.widget.newsWidget.changeContent(self.id);                            
                        },self.delay);                    
                    }

                };
            }     
            
           
           AMC.widget.run = function(){    
                var news = ' . $news . ';                                
                if(news.count){                    
                    var widget = new AMC.widget.newsWidget("' . $id . '", ' . $width . ', ' . $height . ', ' . $scroll . ' , "' . Yii::app()->getLocale()->getOrientation() . '");
                    //widget.addLink("t","http://yahoo.com");
                    widget.draw("' . AmcWm::t("amcFront", 'Hot News') . '", "' . AmcWm::t("amcFront", 'Powered by') . '");                                 
                    widget.init(news, ' . $delay . ', ' . $repeat . ', "' . $lang . '", ' . $sectionId . ', ' . CJavaScript::jsonEncode($extraParams) . ');
                }
           }();
        ';
        echo $this->jsCode;
        Yii::app()->end();
    }

}

?>