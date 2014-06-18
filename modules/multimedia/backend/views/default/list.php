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

        if (count($list['records'])) {
            foreach ($list['records'] as $row) {
                $row['url'] = (isset($row['link']) ? $row['link'] : $row['url']);
                echo '<div class="file-container">';                
                if(!isset($row['thumb'])){                    
                    $image =  $iconsPath . '/link_media.png';
                }else{
                    $image = $row['thumb'];
                }                   
                switch ($row['type']) {
                    case SiteData::IAMGE_TYPE:
                        echo '<div class="file-box" data-type="' . AttachmentList::IMAGE . '" data-url="' . $row['url'] . '">';
                        echo '<div class="file-box-img">
                                <img src= "' . $image . '" title="' . CHtml::encode($row['title']) . '" />
                              </div>';
                        echo '</div>';
                        break;
                    case SiteData::VIDEO_TYPE:
                        $type = (($row['internal'])) ? AttachmentList::INTERNAL_VIDEO : AttachmentList::EXTERNAL_VIDEO;
                        echo '<div class="file-box" data-type="' . $type . '" data-url="' . $row['url'] . '">';
                        echo '<div class="file-box-internal">
                                <img src="' . $image . '" title="' . CHtml::encode($row['title']) . '" width="60"/>
                              </div>';
                        echo '</div>';
                        break;
                }
                
                echo '</div>';
            }
        } else {
            echo '<div></div>';
        }
        $this->endWidget();
        ?>    
    </div>
    <?php
    $pages = new CPagination($list['pager']['count']);
    $pages->route = "/backend/multimedia/default/ajax";
    $pages->params = array(
        "op" => AmcWm::app()->request->getParam("op"),
        "dialog" => AmcWm::app()->request->getParam("dialog"),
        "do" => 'attachment',
        "component" => "uploadsFiles");
    $pages->setPageSize($list['pager']['pageSize']);
    echo '<div class="pager_container">';
    $this->widget('CLinkPager', array(
        'pages' => $pages,
        'header' => ""
    ));
    echo '</div>';
    ?>
</div>
<div class="file-result" style="border: 1px solid #004891;width: 65%; height: 20px;margin-top: 50px;direction: ltr;overflow: hidden;"></div>
