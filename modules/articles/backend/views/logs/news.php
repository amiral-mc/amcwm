<?php
$contentLang = $logDetails['articles']['db']['translation']['contentLang'];
?>
<table cellpadding="1" cellspacing="2" border="0">
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
    <td valign="top" nowrap="nowrap">
        <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Expire Date"); ?>:
    </td>
    <td valign="top">            
        <?php echo $logDetails['articles']['db']['master']['expire_date']; ?>
    </td>        
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
            <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.news", "Source"); ?>:
        </td>
        <td valign="top">            
            <?php echo $logDetails['news']['db']['translation']['db'][$contentLang]['source']; ?>
        </td>    
    </tr>
<tr>
    <td valign="top" nowrap="nowrap">
        <?php echo AmcWm::t("amcwm.modules.articles.backend.messages.core", "Titles"); ?>:
    </td>
    <td valign="top">         
        <?php
        if (isset($logDetails['articles_titles']['db']['master']))
            foreach ($logDetails['articles_titles']['db']['master'] as $titleRow) {
                echo '<div>' . $titleRow['title'] . '</div>';
            }
        ?>
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