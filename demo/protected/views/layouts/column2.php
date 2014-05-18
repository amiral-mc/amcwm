<?php //$this->beginContent('//layouts/main');     ?>
<?php $this->beginContent('layouts.main'); ?>
<div id="Internal_content">

    <div class="page_path">
        <?php $this->widget('Breadcrumbs', array('links' => $this->breadcrumbs)) ?>
    </div>

    <div id="block_area">
        <div class="clm_right">		
            <?php
            //success, info, warning, error or danger
            if ($flashes = AmcWm::app()->user->getFlashes(false)) {
                $alerts = array();
                foreach($flashes as $flashId => $flash){
                    $alerts[$flashId] = array('block' => true, 'fade' => true,);
                }
                $this->widget('bootstrap.widgets.TbAlert', array(
                    'block' => true, // display a larger alert block?
                    'fade' => true, // use transitions?
                    'closeText' => '&times;', // close link text - if set to false, no close link is displayed
                    'alerts' => $alerts,
                ));
            }
            echo $content;
            ?>
        </div>
        <div class="clm_left">							
            <?php echo $this->generatePositions("sideColumn"); ?>
        </div>
    </div>




</div>


<?php $this->endContent(); ?>