<div class="tools">
    <div class="file-upload">
        <img src= "<?php echo $iconsPath ?>/upload_file.png" />
        <span><?php echo AmcWm::t("msgsbase.core", "_upload_file_") ?></span>
    </div>
    <div class="file-delete">
        
        <span><?php echo AmcWm::t("msgsbase.core", "_delete_file_") ?></span>
    </div>
</div>
<div id="uploads_list">
    <div class="xform">
        <?php
        echo $msg;
        $form = $this->beginWidget('Form', array(
            'id' => 'login-form',
            'enableClientValidation' => true,
            'method' => 'get',
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                )
        );
//    print_r($list['records']);
        foreach ($list['records'] as $row) {
            echo '<div class="file-container">';            
            //echo CHtml::checkBox('file_select[]', false, array('id' => "file_select_{$row['id']}", "value" => $row['id']));
            echo '<div class="file-box" data-type="' . $row['type'] . '" data-url="' . $row['url'] . '">';
            switch ($row['type']) {
                case AttachmentList::IMAGE:
                    echo '<div class="file-box-img"><img src= "' . $row['url'] . '" title="' . CHtml::encode($row['title']) . '" /></div>';
//                    echo '<span>' . $row['title'] . '</span>';
                    break;
                case AttachmentList::INTERNAL_VIDEO:
                    echo '<div class="file-box-internal"><img src= "' .$iconsPath .'/link_media.png" title="' . CHtml::encode($row['title']) . '" width="40"/></div>';
                    break;
                case AttachmentList::LINK:
                    echo '<div class="file-box-internal"><img src= "' .$iconsPath .'/link_files.png" title="' . CHtml::encode($row['title']) . '" width="40"/></div>';
                    break;
            }
            echo '</div>';
            echo '<a href="javascript:void(0);" class="file-delete" data-id="' . $row['id'] . '"><img src= "' .$iconsPath .'/delete.png" /></a>';
            echo '</div>';
        }
        $this->endWidget();
        ?>    
    </div>
    <?php
    
    $pages = new CPagination($list['pager']['count']);
    $pages->route = "/backend/uploads/default/index";
    $pages->params = array("op" => AmcWm::app()->request->getParam("op"), "dialog" => AmcWm::app()->request->getParam("dialog"), "component" => "uploadsFiles");
    $pages->setPageSize($list['pager']['pageSize']);
    echo '<div class="pager_container">';
    $this->widget('CLinkPager', array(
        'pages' => $pages,
        'header' => ""
        ));
    echo '</div>';
    ?>
</div>
<div class="file-result" style="border: 1px solid #004891;width: 65%; height: 20px;margin-top: 50px;direction: ltr;"></div>
