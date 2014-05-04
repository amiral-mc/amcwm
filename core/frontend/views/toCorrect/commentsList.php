<div id="normalWebsiteComments">
    <?php
    $output = "";
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

            $output .= CHtml::openTag("table", array("class" => "comment_data_header", "cellpadding" => "5"));
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

            $output .= CHtml::openTag("div", array("class" => "comment_action_area"));
            $output .= CHtml::openTag("span", array("class" => "comment_noofreplies"));
            $output .= Yii::t("comments", 'Replies count') . " (<strong>" . count($comment["replies"]) . "</strong>)&nbsp;&nbsp;&nbsp;";
            $output .= "<span><a href='javascript:void(0)' onclick='openRepliesDialog({$comment["id"]}, {$comments['content']["id"]}); return false;' >" . Yii::t("comments", 'Add Replay') . "</a></span>";
            $output .= CHtml::closeTag("span");
            //$output .= "<span class='comment_bad'><a href='#'>" . Yii::t("comments", 'Bad comment') . "</a></span>";
            $output .= "<span class='comment_bad'>" . CHtml::ajaxLink(CHtml::image(Yii::app()->request->baseUrl . "/images/front/bad.png", "bad", array("border" => 0, "align" => "middle")), array("/" . $this->getModule()->getId() . "/comments/like", "like" => "0", "id" => $comment["id"], 'aid'=> $comments['content']["id"]), array("type" => "post", "update" => "#badCount_" . $comment["id"]), array('id' => "xbad_" . $comment["id"])) . "<span id='badCount_" . $comment["id"] . "'>" . $comment["bad_imp"] . "</span> </span>";
            $output .= "<span class='comment_good'>" . CHtml::ajaxLink(CHtml::image(Yii::app()->request->baseUrl . "/images/front/good.png", "good", array("border" => 0, "align" => "middle")), array("/" . $this->getModule()->getId() . "/comments/like", "like" => "1", "id" => $comment["id"], 'aid'=> $comments['content']["id"]), array("type" => "post", "update" => "#goodCount_" . $comment["id"]), array('id' => "xgood_" . $comment["id"])) . "<span id='goodCount_" . $comment["id"] . "'>" . $comment["good_imp"] . "</span> </span>";

            $output .= CHtml::closeTag("div");

            $replies = $comment["replies"];
            if (count($replies)) {
                $output .= CHtml::openTag("div", array("class" => "replies_area"));
                $output .= CHtml::openTag("table", array("cellpadding" => "0", "cellspacing" => "0", "border" => "0", "width" => "100%"));
                $replaySeparator = "";

                foreach ($replies AS $replay) {
                    $output .= $replaySeparator;
                    $output .= CHtml::openTag("tr", array("class" => "comment_reply_item"));
                    $output .= "<td style='vertical-align:top;width:30px;'><img class='comment_avatar' src='images/front/reply_avatar_icon.png' alt='' /></td>";
                    $output .= CHtml::openTag("td");
                    $output .= CHtml::openTag("div", array("class" => "comment_data_user"));
                    $output .= $replay["owner"];
                    $output .= CHtml::closeTag("div");
                    $output .= CHtml::openTag("div", array("class" => "comment_data_header"));
                    $output .= $replay["title"] . "  ";
                    $output .= CHtml::openTag("span", array("class" => "comment_data_time"));
                    $output .= $replay["date"];
                    $output .= CHtml::closeTag("span");
                    $output .= CHtml::closeTag("div");
                    $output .= CHtml::openTag("div", array("class" => "comment_data_absract", "style" => "width:500px;white-space:pre-line;word-wrap: break-word;"));
                    $output .= strip_tags($replay["details"], "<br><br />");
                    $output .= CHtml::closeTag("div");

                    $output .= CHtml::openTag("div", array("class" => "comment_action_area"));
                    $output .= "<span class='comment_bad'>" . CHtml::ajaxLink(CHtml::image(Yii::app()->request->baseUrl . "/images/front/bad.png", "bad", array("border" => 0, "align" => "middle")), array("/" . $this->getModule()->getId() . "/comments/like", "like" => "0", "id" => $replay["id"], 'aid'=> $comments['content']["id"]), array("type" => "post", "update" => "#badCount_" . $replay["id"]), array('id' => "xbad_" . $replay["id"])) . "<span id='badCount_" . $replay["id"] . "'>" . $replay["bad_imp"] . "</span> </span>";
                    $output .= "<span class='comment_good'>" . CHtml::ajaxLink(CHtml::image(Yii::app()->request->baseUrl . "/images/front/good.png", "good", array("border" => 0, "align" => "middle")), array("/" . $this->getModule()->getId() . "/comments/like", "like" => "1", "id" => $replay["id"], 'aid'=> $comments['content']["id"]), array("type" => "post", "update" => "#goodCount_" . $replay["id"]), array('id' => "xgood_" . $replay["id"])) . "<span id='goodCount_" . $replay["id"] . "'>" . $replay["good_imp"] . "</span> </span>";
                    //$output .= "<span class='comment_bad'><a href='#'>" . Yii::t("comments", 'Bad comment') . "</a></span>";
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
        $pages->params = array('id' => $comments['content']["id"], 'lang' => Controller::getCurrentLanguage(), 'title'=> CHtml::encode($comments['content']["title"]), '#' => 'comments');

        $output .= $commentSeparator;
        $output .= "<tr><td colspan='2' align='center'>";
        $output .= '<div class="pager_container">';
        $output .= $this->widget('CLinkPager', array('pages' => $pages), true);
        $output .= '</div>';
        $output .= "</td></tr>";

        $output .= CHtml::closeTag("table");
        echo $output;
    }


    $this->beginWidget('zii.widgets.jui.CJuiDialog', array(
        'id' => 'dialogReplies',
        'options' => array(
            'title' => Yii::t("comments", "Add new replay"),
            'width' => '600',
            'autoOpen' => false,
            'modal' => true,
            'buttons' => array(
                Yii::t("comments", 'Add Comment') => 'js:function(){ $("#repliesForm").submit(); return false;}',
                AmcWm::t("amcFront", 'Close') => 'js:function(){ $(this).dialog("close");}',
            ),
        ),
    ));
    $this->renderPartial("application.views.site.commentForm", array('formId' => 'repliesForm', 'model' => $repliesModel, "action" => array("/" . $this->getModule()->getId() . "/replies/create", "id" => $comments['content']["id"])));
    $this->endWidget('zii.widgets.jui.CJuiDialog');
    $cs = Yii::app()->getClientScript();
    $cs->registerCoreScript('jquery');
    $cs->registerScript('commentsReply', "
            openRepliesDialog = function(commentId){                
                $('#repliesForm #repliesForm_commentId').val(commentId);
                $('#repliesForm #repliesForm_submit').hide();
                $('#dialogReplies').dialog('open');
            }                    
        ");
    ?>                
</div>
<div class="comment_frm">
    <div class="comment_frm_title"><?php echo Yii::t("comments", 'Add new comment') ?></div>
    <?php 
    $this->renderPartial("application.views.site.commentForm", array('formId' => 'commentsForm', 'model' => $commentsModel, "action" => array("/" . $this->getModule()->getId() . "/comments/create", "id" => $comments['content']["id"]))); ?>
    <div class="comment_note">
        <?php echo Yii::t("comments", 'CommentsNotes') ?>
    </div>
</div>