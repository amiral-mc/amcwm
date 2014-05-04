<?php
$mediaSettings = $this->module->appModule->mediaSettings;
$options = $this->module->appModule->options;

$data = $details['record'];

$this->beginClip('tendersList');
?>
<div id="tender_view">
    <p class="tender_intro"><?php echo AmcWm::t('app', '_TENDER_INTRO_'); ?></p>
    <div>
        <div class="tender_title">
            <span class="title"><?php echo $data['title']; ?></span>
            <span class="qa"><?php echo CHtml::link(AmcWm::t('msgsbase.core', 'Questions'), '#comments'); ?></span>
            <span class="doc"><?php echo CHtml::link(AmcWm::t('msgsbase.core', 'RFP download'), array('/site/download', 'f' => "{$mediaSettings['paths']['files']['path']}/{$data['tender_id']}.{$data['file_ext']}")); ?></span>
        </div>
        <div class="tender_details">
            <div>
                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <tbody><tr>
                            <td><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Tender number'); ?></span>: <span><?php echo $data['tender_id']; ?></span></td>
                            <td><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Tender Type'); ?></span>: <span><?php echo $data['tender_type']; ?></span></td>
                        </tr>
                        <tr>
                            <td><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Department'); ?></span>: <span><?php echo TendersDepartment::model()->getDepartment($data['department_id']); ?></span></td>
                            <td><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Activities'); ?></span>: <span><?php echo implode(', ', $data['activities']); ?></span></td>
                        </tr>
                        <tr>
                            <td><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Primary insurance'); ?></span>: <span><?php echo $data['primary_insurance']; ?></span></td>
                            <td><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Status'); ?></span>: <span><?php echo $data['tender_status']; ?></span></td>
                        </tr>

                    </tbody></table>
            </div>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'RFP date'); ?></span>: <span><?php echo Yii::t('msgsbase.core', 'from {from} to {to}', array('{from}' => $data['rfp_start_date'], '{to}' => $data['rfp_start_date'])); ?></span></div>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Submit date'); ?></span>: <span><?php echo Yii::t('msgsbase.core', 'from {from} to {to}', array('{from}' => $data['submission_start_date'], '{to}' => $data['submission_end_date'])); ?></span></div>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Technical date'); ?></span>: <span><?php echo $data['technical_date']; ?></span></div>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Financial date'); ?></span>: <span><?php echo $data['financial_date']; ?></span></div>
            <?php if($data['rfp_price2'] != '0.00'):?>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Price 1'); ?></span>: <span><?php echo $data['rfp_price1']; ?></span></div>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Price 2'); ?></span>: <span><?php echo $data['rfp_price2']; ?></span></div>
            <?php else: ?>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'RFP Price'); ?></span>: <span><?php echo $data['rfp_price1']; ?></span></div>
            <?php endif; ?>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Description'); ?></span>: <span><?php echo $data['description']; ?></span></div>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Conditions'); ?></span>: <span><?php echo $data['conditions']; ?></span></div>
            <div><span class="tender_lbl"><?php echo AmcWm::t('msgsbase.core', 'Notes'); ?></span>: <span><?php echo $data['notes']; ?></span></div>
        </div>
        <div class="tender_comments" id="comments">            
            <div class="tender_title">
                <?php echo AmcWm::t('msgsbase.core', 'Questions');?>
            </div>
            <div>
                <?php 
                    $this->renderPartial("commentsList", array('commentsModal' => $commentsModal, 'details' => $details));
                ?>
            </div>
        </div>
    </div>
</div>
<?php
$this->endClip('tendersList');

$pageContentTitle = AmcWm::t('msgsbase.core', 'Tenders');
$breadcrumbs = Data::getInstance()->getBeadcrumbs(array($options['default']['text']['homeRoute']), false);
$title = AmcWm::t('msgsbase.core', 'Tenders');
$catImage = null;

$widgetImage = Data::getInstance()->getPageImage('tenders', null, $catImage, AmcWm::app()->baseUrl . "/images/front/tendersImage.jpg");


$this->widget('PageContentWidget', array(
    'id' => 'sections_list',
    'contentData' => $this->clips['tendersList'],
    'title' => $title,
    'image' => $widgetImage,
    'breadcrumbs' => $breadcrumbs,
    'pageContentTitle' => $pageContentTitle,
));


/**
 * this is for auto loading the scroll JS lib to make a smoth scrolling to the target
 */
//$this->widget('amcwm.widgets.jsImporter.JsImporter');
?>