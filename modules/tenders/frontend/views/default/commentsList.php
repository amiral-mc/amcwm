<div id="normalWebsiteComments">
    <?php
    $output = "";
    $comments = $details["comments"];

    if ($comments['pager']['count']) {

        $commentSeparator = "";
        $output .= CHtml::openTag("table", array("cellpadding" => "0", "cellspacing" => "0", "border" => "0", "width" => "100%"));

        foreach ($comments['records'] AS $comment) {
            $output .= $commentSeparator;
            $output .= CHtml::openTag("tr", array("class" => "comment_section"));
            $output .= "<td style='vertical-align:top;width:60px;'><img class='comment_avatar' src='".Yii::app()->baseUrl."/images/front/avatar_icon.png' alt='' /></td>";
            $output .= CHtml::openTag("td", array());

            $output .= CHtml::openTag("div", array("class" => "comment_data_user"));
            $output .= $comment["owner"];
            $output .= CHtml::closeTag("div");

            $output .= CHtml::openTag("table", array("class" => "comment_data_header", "cellpadding" => "0"));
            $output .= CHtml::openTag("tr");
            $output .= CHtml::openTag("td");
            $output .= $comment["title"];
            $output .= CHtml::closeTag("td");
            $output .= CHtml::openTag("td", array("class" => "comment_data_time"));
            $output .= "({$comment["date"]})";
            $output .= CHtml::closeTag("td");
            $output .= CHtml::closeTag("tr");
            $output .= CHtml::closeTag("table");

            $output .= CHtml::openTag("div", array("class" => "comment_data_absract", "style" => "width:530px;white-space:pre-line;word-wrap: break-word;"));
            $output .= strip_tags($comment["details"], "<br><br />");
            $output .= CHtml::closeTag("div");

            $replies = $comment["replies"];
            if (count($replies)) {
                $output .= CHtml::openTag("div", array("class" => "replies_area"));
                $output .= CHtml::openTag("table", array("cellpadding" => "0", "cellspacing" => "0", "border" => "0", "width" => "100%"));
                $replaySeparator = "";

                foreach ($replies AS $replay) {
                    $output .= $replaySeparator;
                    $output .= CHtml::openTag("tr", array("class" => "comment_reply_item"));
                    $output .= "<td style='vertical-align:top;width:30px;'><img class='comment_avatar' src='".Yii::app()->baseUrl."/images/front/reply_avatar_icon.png' alt='' /></td>";
                    $output .= CHtml::openTag("td");
                    $output .= CHtml::openTag("div", array("class" => "comment_data_user"));
                    $output .= $replay["owner"];
                    $output .= CHtml::closeTag("div");
                    $output .= CHtml::openTag("div", array("class" => "comment_data_header", 'style'=>'float:right; margin-left: 5px;'));
                    $output .= $replay["title"] . "  ";
                    $output .= CHtml::closeTag("div");
                    $output .= CHtml::openTag("span", array("class" => "comment_data_time"));
                    $output .= $replay["date"];
                    $output .= CHtml::closeTag("span");
                    $output .= CHtml::openTag("div", array("class" => "comment_data_absract", "style" => "width:500px;white-space:pre-line;word-wrap: break-word;"));
                    $output .= strip_tags($replay["details"], "<br><br />");
                    $output .= CHtml::closeTag("div");
                    $output .= CHtml::closeTag("td");
                    $output .= CHtml::closeTag("tr");

                    $replaySeparator = "<tr><td colspan='2' class='comment_reply_sep'></td></tr>";
                }

                $output .= CHtml::closeTag("table");
                $output .= CHtml::closeTag("div");
            }

            $output .= CHtml::closeTag("td");
            $output .= CHtml::closeTag("tr");

            $commentSeparator = "<tr><td colspan='2' class='comment_sep'></td></tr>";
        } // end foreach 

        $pages = new CPagination($comments['pager']['count']);
        $pages->setPageSize($comments['pager']['pageSize']);
        $pages->params = array('id' => $comments["content"]["id"], 'lang' => Controller::getCurrentLanguage(), 'title'=> CHtml::encode($comments["content"]["title"]), '#' => 'comments');

        $output .= $commentSeparator;
        $output .= "<tr><td colspan='2' align='center'>";
        $output .= '<div class="pager_container">';
        $output .= $this->widget('CLinkPager', array('pages' => $pages), true);
        $output .= '</div>';
        $output .= "</td></tr>";

        $output .= CHtml::closeTag("table");
        echo $output;
    }
    ?>
</div>
<div class="comment_frm">
    <div class="comment_frm_title"><?php echo AmcWm::t('msgsbase.core', 'Add new comment');?></div>
    <?php $this->renderPartial("commentForm", array('formId' => 'commentsForm', 'model' => $commentsModal, "action" => array("/" . $this->getModule()->getId() . "/default/addComment", "id" => $details["record"]["tender_id"]))); ?>
</div>