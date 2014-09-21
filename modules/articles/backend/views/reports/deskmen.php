<?php
echo $formOutput;

if ($viewResult) {
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

    <div id="tabel-view">

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
                $class = $key % 2 == 0 ? 'even' : 'odd'
                ?>
                <tr class="<?php echo $class ?>">
                    <td class="serial"><?php echo $key + 1 ?></td>
                    <td><?php echo $value['deskman'] ?></td>
                    <td><?php echo $value['count'] ?></td>
                    <td><?php echo $value['published'] ?></td>
                    <td><?php echo $value['count'] - $value['published'] ?></td>
                    <td><?php echo $value['reporters'] ?></td>
                </tr>
            <?php } ?>
        </table>

    </div>
    <?php
}
?>