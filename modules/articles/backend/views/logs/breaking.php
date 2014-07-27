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
        </td>
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
    <td valign="top" nowrap="nowrap">
        <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Expire Date"); ?>:
    </td>
    <td valign="top">            
        <?php echo $logDetails['articles']['db']['master']['expire_date']; ?>
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
        <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.news", "Source"); ?>:
    </td>        
    <td valign="top">            
        <?php if (isset($logDetails['news_sources']['db']['translation']['db'][$contentLang]['source'])): ?>
            <?php echo $logDetails['news_sources']['db']['translation']['db'][$contentLang]['source']; ?>
        <?php endif; ?>
    </td>    
</tr>
</table>