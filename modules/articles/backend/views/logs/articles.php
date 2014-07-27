<?php
$contentLang = $logDetails['articles']['db']['translation']['contentLang'];
$published = $logDetails['articles']['db']['master']['published'];
$actionMsg = "ACTION_" . strtoupper($logInfo['action'] ." {DATE} {USER}");
if(!$published && $logInfo['action']=="publish"){
    $actionMsg = "ACTION_UNPUBLISH {DATE} {USER}";    
}
?>
<table cellpadding="1" cellspacing="2" border="0">
    <tr>
        <td valign="top" nowrap="nowrap" colspan="2">
            <?php echo AmcWm::t("amcwm.modules.logger.backend.messages.core", $actionMsg, array('{USER}' => $logInfo['username'], '{DATE}' => $logInfo['action_date'])); ?>
        </td>
    </tr>
    <tr>
        <td valign="top" nowrap="nowrap">
            <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Creation Date"); ?>:
        </td>
        <td valign="top">            
            <?php echo $logDetails['articles']['db']['master']['create_date']; ?>
        </td>        
    </tr>
    <tr>
        <td valign="top" nowrap="nowrap">
            <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Publish Date"); ?>:
        </td>
        <td valign="top">            
            <?php echo $logDetails['articles']['db']['master']['publish_date']; ?>
        </td>       
    </tr>
</tr>
<tr>
    <td valign="top" valign="top" nowrap="nowrap">
        <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Article Primary Header"); ?>:
    </td>
    <td valign="top">            
        <?php echo $logDetails['articles']['db']['translation']['db'][$contentLang]['article_pri_header']; ?>
    </td>       
</tr>
<tr>
    <td  valign="top" nowrap="nowrap">
        <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Article Header"); ?>:
    </td>
    <td valign="top">            
        <?php echo $logDetails['articles']['db']['translation']['db'][$contentLang]['article_header']; ?>
    </td>     
</tr>
<tr>
    <td  valign="top" nowrap="nowrap">
        <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Details"); ?>:
    </td>
    <td valign="top">            
        <?php $this->widget('amcwm.widgets.zeroClipboard.ZeroClipboard', array('htmlOptions' => array('targetId' => 'article_detail', 'title' => AmcWm::t("msgsbase.core", "Copy Content")))); ?>
        <div id="article_detail">
            <?php echo $logDetails['articles']['db']['translation']['db'][$contentLang]['article_detail']; ?>                        
        </div>
        <?php $this->widget('amcwm.widgets.zeroClipboard.ZeroClipboard', array('htmlOptions' => array('targetId' => 'article_detail', 'title' => AmcWm::t("msgsbase.core", "Copy Content")))); ?>
    </td>
</tr>
<tr>
    <td  valign="top" nowrap="nowrap">
        <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Tags"); ?>:
    </td>
    <td id="tags" valign="top">            
        <?php echo nl2br($logDetails['articles']['db']['translation']['db'][$contentLang]['tags']); ?>
    </td>        
</tr>
</table>