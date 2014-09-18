<?php
echo $formOutput;

if ($viewResult) {
    ?>
    <div id="report-header">
        <div class="report-name"><?php echo AmcWm::t('amcBack', "Reporter Report") ?></div>
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
            <div><?php echo AmcWm::t('amcBack', 'Name') ?><span><?php echo $reporter['records']['reporter'] ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of Articles') ?><span><?php echo $reporter['records']['count'] ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of published Articles') ?><span><?php echo $reporter['records']['published'] ?></span></div>
            <div><?php echo AmcWm::t('amcBack', 'Total number of unpublished Articles') ?><span><?php echo $reporter['records']['count'] - $reporter['records']['published'] ?></span></div>
        </div>
    </div>

    <div id="tabel-view">

        <table style="width: 100%" cellpadding="2">
            <tr class="header">
                <td class="serial"><?php echo AmcWm::t('amcBack', 'ID') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Header') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Date') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Views') ?></td>
                <td><?php echo AmcWm::t('amcBack', 'Comments') ?></td>
            </tr>
            <?php
            foreach ($records as $key => $value) {
                $class = $key % 2 == 0 ? 'even' : 'odd'
                ?>
                <tr class="<?php echo $class ?>">
                    <td class="serial"><?php echo $key ?></td>
                    <td><?php echo $value['header'] ?></td>
                    <td><?php echo $value['date'] ?></td>
                    <td><?php echo $value['views'] ?></td>
                    <td><?php echo $value['comments'] ?></td>
                </tr>
            <?php } ?>
        </table>

    </div>
    <?php
}
?>