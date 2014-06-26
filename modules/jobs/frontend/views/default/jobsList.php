<div class="jobs_list">
    <?php
    if (count($jobs)) {
        echo '<p><strong>' .AmcWm::t("msgsbase.core", 'CURRENT JOB VACANCIES'). '</strong></p>';
        foreach ($jobs as $job) {
            echo "<div class='job_data'>";
            echo "  <h2>".Html::link($job['name'], $job['link'])."</h2>";
            echo "  <div class='date'>" . AmcWm::t('msgsbase.core', 'Publish Date') . ": {$job['publish_date']}</div>";
            if($job['expire_date']){
                echo "  <div class='date'>" . AmcWm::t('msgsbase.core', 'Expire Date') . ": {$job['expire_date']}</div>";
            }
//            echo "  <div>{$job['description']}</div>";
            echo "</div>";
        }
    } else {
       echo '<p><strong>' .AmcWm::t("msgsbase.core", 'Sorry there is no jobs available right now.'). '</strong></p>';
       echo AmcWm::t('app', '_JOBS_MODULE_NO_JOBS_DESC_');        
    }
    ?>
</div><!-- form -->
