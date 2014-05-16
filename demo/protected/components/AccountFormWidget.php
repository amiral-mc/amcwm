<?php

class AccountFormWidget extends PageContentWidget {

    /**
     *
     * @var string  
     */
    public $formTitle = "";

    /**
     *
     * @var string  
     */
    public $informationMessage = "&nbsp;";

    /**
     *
     * @var string  
     */
    public $pannelInformationMessage = "";
    
    public $formWidth = null;
    /**
     *
     * @var string  
     */
    public $pannelWidth = "415px";

    /**
     * Initializes widget.
     * If this method is overridden, make sure the parent implementation is invoked.
     */
    public function init() {
        $contentData = $this->contentData;
        $this->contentData = '<div id="box_container">';
        $this->contentData .='<h2 class="form_title">' . $this->formTitle . '</h2>';
        $this->contentData .='<div class="information message">' . $this->informationMessage . '</div>';
        $this->contentData .='<div class="frm_container_row">';
        $formStyle = "";        
        if($this->formWidth){
            $formStyle = 'style="width:'. $this->formWidth . '"';
        }
        if($this->pannelWidth){
            $pannelStyle = 'style="width:'. $this->pannelWidth . '"';
        }
        if ($this->pannelInformationMessage) {
            $formClass = "frm_container frm-container-cell";
        }
        else{
            $formClass = "frm_container";
        }
        $this->contentData .='<div class="'.$formClass.'"'.$formStyle.'>';
        $this->contentData .= $contentData;
        $this->contentData .='</div>';
        if ($this->pannelInformationMessage) {
            $this->contentData .='<div id="form_pannel" '.$pannelStyle.'>';
            $this->contentData .= $this->pannelInformationMessage;
            $this->contentData .='</div>';
        }
        $this->contentData .='</div>';
        $this->contentData .='</div>';



//        echo $this->contentData;
//        die();

        parent::init();
    }

}
