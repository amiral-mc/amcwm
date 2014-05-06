<?php

/**
 * @author Amiral Management Corporation amc.amiral.com
 * @copyright Copyright &copy;2012, Amiral Management Corporation. All Rights Reserved.
 * @license http://amc.amiral.com/license/amcwm.txt
 */

/**
 * @author Amiral Management Corporation
 * @version 1.0
 */

class CheckBoxColumn extends CCheckBoxColumn {

    public $checked = false;

    /**
     * Show or hide button depend on expression sent
     * @var string 
     */
    public $showOn = null;

    protected function renderDataCellContent($row, $data) {
        $show = true;
        if ($this->showOn !== null) {
            $show = $this->evaluateExpression($this->showOn, array('data' => $data, 'row' => $row));
        }
        if ($show) {
            if ($this->value !== null)
                $value = $this->evaluateExpression($this->value, array('data' => $data, 'row' => $row));
            else if ($this->name !== null)
                $value = CHtml::value($data, $this->name);
            else
                $value = $this->grid->dataProvider->keys[$row];
            $options = $this->checkBoxHtmlOptions;
            $options['value'] = $value;
            $options['id'] = $this->id . '_' . $row;
            if (isset($this->checked) && $this->evaluateExpression($this->checked, array('data' => $data, 'row' => $row)))
                echo CHtml::checkBox($options['name'] . '[]', true, $options);
            else
                echo CHtml::checkBox($options['name'] . '[]', false, $options);
        }
    }

    /**
     * Renders the header cell content.
     * This method will render a checkbox in the header when {@link selectableRows} is greater than 1
     * or in case {@link selectableRows} is null when {@link CGridView::selectableRows} is greater than 1.
     */
    protected function renderHeaderCellContent() {
        $options = $this->checkBoxHtmlOptions;
        if ($this->selectableRows === null && $this->grid->selectableRows > 1)
            echo CHtml::checkBox($options['name'] . '_all', false, array('class' => 'select-on-check-all'));
        else if ($this->selectableRows > 1)
            echo CHtml::checkBox($options['name'] . '_all', false);
        else
            parent::renderHeaderCellContent();
    }

}
