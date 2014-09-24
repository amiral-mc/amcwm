<?php
echo $formOutput;

if ($viewResult) {
    $fromDate = AmcWm::app()->request->getParam('datepicker-from');
    $toDate = AmcWm::app()->request->getParam('datepicker-to');
    $printUrl = Yii::app()->controller->createUrl('reports', array('result' => 1, 'rep' => 'deskmen', 'module' => AmcWm::app()->request->getParam('module'), 'print' => 1, 'datepicker-from' => $fromDate, 'datepicker-to' => $toDate));
    ?>
    <div id="report-header">
        <div class="report-name"><?php echo AmcWm::t('amcBack', "Deskmen Report") ?></div>
        <div class="report-date">
            <?php if (Yii::app()->request->getParam('datepicker-from') && Yii::app()->request->getParam('datepicker-to')) { ?>
                <?php echo AmcWm::t('amcBack', 'From') ?><span><?php echo Yii::app()->request->getParam('datepicker-from') ?></span><?php echo AmcWm::t('amcBack', 'To') ?><span><?php echo Yii::app()->request->getParam('datepicker-to') ?></span>
                <?php
            } else {
                if (!Yii::app()->request->getParam('datepicker-from') && !Yii::app()->request->getParam('datepicker-to')) {
                    
                } else {
                    if (Yii::app()->request->getParam('datepicker-from')) {
                        ?>
                        <?php echo AmcWm::t('amcBack', 'From') ?><span><?php echo Yii::app()->request->getParam('datepicker-from') ?></span><?php echo AmcWm::t('amcBack', 'To') ?><span><?php echo date("Y-m-d", strtotime("NOW")) ?></span>
                    <?php } else {
                        ?>
                        <?php echo AmcWm::t('amcBack', 'To') ?><span><?php echo Yii::app()->request->getParam('datepicker-to') ?></span>
                        <?php
                    }
                }
            }
            ?>

        </div>
    </div>

    <div class="tabel-view">
        <?php echo CHtml::link(AmcWm::t("amcTools", 'Print'), $printUrl, array('target' => '_blank', 'class' => 'doc-print')); ?>
        <table style="width: 100%" cellpadding="2">
            <tr class="header">
                <td class="serial">م</td>
                <td><?php echo AmcWm::t('amcBack', 'Name') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Total number of Articles') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Total number of published Articles') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Total number of unpublished Articles') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Reporters') ?></td>
            </tr>
            <?php
            foreach ($records as $key => $value) {
                $class = $key % 2 == 0 ? 'even' : 'odd';
                $id = Yii::app()->request->getParam('page') ? ((Yii::app()->request->getParam('page') - 1) * Deskman::REPORTS_PAGE_COUNT) + $key + 1 : $key + 1;
                ?>
                <tr class="<?php echo $class ?>">
                    <td class="serial"><?php echo $id ?></td>
                    <td><?php echo $value['deskman'] ?></td>
                    <td><?php echo $value['count'] ?></td>
                    <td><?php echo $value['published'] ?></td>
                    <td><?php echo $value['count'] - $value['published'] ?></td>
                    <td><?php echo $value['reporters'] ?></td>
                </tr>
            <?php } ?>
        </table>
        <?php
        $this->widget('CLinkPager', array(
            'pages' => $pagination,
        ));
        ?>
    </div>
    <?php
}
?>