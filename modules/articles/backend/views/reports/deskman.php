<?php
echo $formOutput;
if ($viewResult) {
    $name = $deskman['deskman'];
    if (!$name) {
        $name = Yii::app()->db->createCommand()
                ->select('name')
                ->from('persons_translation')
                ->where('person_id =' . (int) Yii::app()->request->getParam('user_id'))
                ->queryScalar();
    }
    $count = $deskman['count'] ? $deskman['count'] : '0';
    $published = $deskman['published'] ? $deskman['published'] : '0';
    $unpublished = $deskman['count'] ? $deskman['count'] - $deskman['published'] : '0';
    $fromDate = AmcWm::app()->request->getParam('datepicker-from');
    $toDate = AmcWm::app()->request->getParam('datepicker-to');
    $printUrl = Yii::app()->controller->createUrl('reports', array('result' => 1, 'rep' => 'deskman', 'module' => AmcWm::app()->request->getParam('module'), 'print' => 1, 'user_id' => Yii::app()->request->getParam('user_id'), 'datepicker-from' => $fromDate, 'datepicker-to' => $toDate));
    if (!Yii::app()->request->getParam('page')) {
        Yii::app()->session['count'] = $count;
        Yii::app()->session['published'] = $published;
        Yii::app()->session['unpublished'] = $unpublished;
    }
    ?>
    <div id="report-header">
        <div class="row report-name"><?php echo AmcWm::t('amcBack', "Deskmen Report") ?></div>
        <div class="row report-date">
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
        <div id="row reporter-tasks">
            <div><?php echo AmcWm::t('amcBack', 'Name') ?>: <span class="report-labels"><?php echo $name ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of Articles') ?>: <span class="report-labels"><?php echo Yii::app()->session['count'] ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of published Articles') ?>: <span class="report-labels"><?php echo Yii::app()->session['published'] ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of unpublished Articles') ?>: <span class="report-labels"><?php echo Yii::app()->session['unpublished'] ?></span></div>
        </div>
    </div>

    <div id="tabel-view">
        <?php echo CHtml::link(AmcWm::t("amcTools", 'Print', array('class' => 'doc-print')), $printUrl, array('target' => '_blank', 'class' => 'doc-print')); ?>
        <table style="width: 100%" cellpadding="2">
            <tr class="header">
                <td class="serial"><?php echo AmcWm::t('amcBack', 'ID') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Header') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Date') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Views') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Comments') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Reporter') ?></td>
            </tr>
            <?php
            foreach ($records as $key => $value) {
                $class = $key % 2 == 0 ? 'even' : 'odd';
                $id = Yii::app()->request->getParam('page') ? ((Yii::app()->request->getParam('page') - 1) * Deskman::REPORTS_PAGE_COUNT) + $key + 1 : $key + 1;
                ?>

                <tr class="<?php echo $class ?>">
                    <td class="serial"><?php echo $id ?></td>
                    <td><?php echo $value['header'] ?></td>
                    <td><?php echo $value['date'] ?></td>
                    <td><?php echo $value['views'] ?></td>
                    <td><?php echo $value['comments'] ?></td>
                    <td><?php echo $value['reporters'] ?></td>
                </tr>
            <?php }
            ?>
        </table>
        <?php
        $this->widget('CLinkPager', array(
            'pages' => $pagination,
        ));
        ?>
    </div>
    <?php
    if (!$records) {
        echo AmcWm::t("amcBack", "There are no results");
    }
}
?>