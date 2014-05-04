<div class="form">
    <div class="row">
        <?php
        if (isset($logDetails['data'][0]['viewFeilds'])) {
            $viewFeilds = $logDetails['data'][0]['viewFeilds'];

            echo '<table>';
            foreach ($viewFeilds as $feild) {
                $txt = array_search_recursive($feild, $logDetails['data']);
                if ($txt) {
                    echo '<tr>';
                    echo '<td>';
                    echo $feild;
                    echo '</td>';
                    echo '<td>';
                    echo '<div id="txt' . $feild . '">';
                    echo $txt;
                    echo '</div>';
                    echo '</td>';
                    echo '<td>';
                    $this->widget('amcwm.widgets.zeroClipboard.ZeroClipboard', array('htmlOptions' => array('targetId' => 'txt' . $feild, 'title' => AmcWm::t("msgsbase.core", "Copy"))));
                    echo '</td>';
                    echo '</tr>';
                }
            }
            echo '</table>';
        } else {

            if (is_array($logDetails['data'])) {
                array_walk_recursive($logDetails['data'], 'text_print');
            } else {
                echo AmcWm::t("msgsbase.core", "No details for this action");
            }
        }

        function text_print($item, $key) {
            echo "<table>
                    <tr>
                        <td valign='top'>
                            <label>$key</label> 
                        </td>
                        <td valign='top'>
                            $item
                        </td>
                    </tr>
                  </table>";
        }

        function array_search_recursive($needle, $haystack) {
            $result = null;
            foreach ($haystack as $key => $val) {
                if ($key === $needle) {
                    $result = $val;
                    break;
                } else if (is_array($val)) {
                    $found = array_search_recursive($needle, $val);
                    if ($found) {
                        $result = $found;
                        break;
                    }
                }
            }
            return $result;
        }
        ?>
    </div>
</div>