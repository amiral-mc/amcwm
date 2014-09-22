<?php
echo $formOutput;

if ($viewResult) {
    $fromDate = AmcWm::app()->request->getParam('datepicker-from');
    $toDate = AmcWm::app()->request->getParam('datepicker-to');
    $printUrl = Yii::app()->controller->createUrl('reports', array('result' => 1, 'rep' => 'deskman', 'module' => AmcWm::app()->request->getParam('module'), 'print' => 1, 'user_id' => Yii::app()->request->getParam('user_id'), 'datepicker-from' => $fromDate, 'datepicker-to' => $toDate));
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
        <div id="reporter-tasks">
            <div><?php echo AmcWm::t('amcBack', 'Name') ?><span><?php echo $deskman['deskman'] ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of Articles') ?><span><?php echo $deskman['count'] ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of published Articles') ?><span><?php echo $deskman['published'] ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of unpublished Articles') ?><span><?php echo $deskman['count'] - $deskman['published'] ?></span></div>
        </div>
    </div>

    <div id="tabel-view">
        <?php echo CHtml::link(AmcWm::t("amcTools", 'Print'), $printUrl, array('target' => '_blank', 'class' => 'doc-print')); ?>
        <table style="width: 100%" cellpadding="2">
            <tr class="header">
                <td class="serial">م</td>
                <td><?php echo AmcWm::t('amcBack', 'Header') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Date') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Views') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Comments') ?></td>
            </tr>
            <?php
            foreach ($records as $key => $value) {
                $class = $key % 2 == 0 ? 'even' : 'odd';
//                die(Yii::app()->request->getParam('page'));
                $id = Yii::app()->request->getParam('page') ? ((Yii::app()->request->getParam('page') - 1) * Deskman::REPORTS_PAGE_COUNT) + $key + 1 : $key + 1;
                ?>
                <tr class="<?php echo $class ?>">
                    <td class="serial"><?php echo $id ?></td>
                    <td><?php echo $value['header'] ?></td>
                    <td><?php echo $value['date'] ?></td>
                    <td><?php echo $value['views'] ?></td>
                    <td><?php echo $value['comments'] ?></td>
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