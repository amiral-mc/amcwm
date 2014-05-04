<table cellpadding="1" cellspacing="2" border="0">
    <tr>
        <td valign="top" nowrap="nowrap">
            <?php echo AmcWm::t("msgsbase.core", "Action"); ?>:
        </td>
        <td id="create_date" valign="top">            
            <?php echo $logInfo['action_name']; ?>
        </td>        
    </tr>
    <tr>
        <td valign="top" nowrap="nowrap">
            <?php echo AmcWm::t("msgsbase.core", "Username"); ?>:
        </td>
        <td id="create_date" valign="top">            
            <?php echo $logInfo['username']; ?>
        </td>        
    </tr>
    <tr>
        <td valign="top" nowrap="nowrap">
            <?php echo AmcWm::t("msgsbase.core", "IP"); ?>:
        </td>
        <td id="create_date" valign="top">            
            <?php echo $logInfo['ip']; ?>
        </td>        
    </tr>
    <tr>
        <td valign="top" nowrap="nowrap">
            <?php echo AmcWm::t("msgsbase.core", "Date"); ?>:
        </td>
        <td id="create_date" valign="top">            
            <?php echo $logInfo['action_date']; ?>
        </td>        
    </tr>
</table>