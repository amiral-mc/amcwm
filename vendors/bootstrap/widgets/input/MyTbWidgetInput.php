<?php

/**
 * MyTbWidgetInput form input widget.
 */
class MyTbWidgetInput extends CInputWidget {

    /**
     * @var string the input label text.
     */
    public $label;

    /**
     * @var array error html attributes.
     */
    public $errorOptions = array();

    /**
     * @var array label html attributes.
     */
    public $labelOptions = array();

    /**
     * @var TbActiveForm the associated form widget.
     */
    public $form;

    /**
     * @var string the widget name.
     */
    public $widget;

    /**
     * @var string text to prepend.
     */
    public $prependText;

    /**
     * @var string text to append.
     */
    public $appendText;

    /**
     * @var array prepend html attributes.
     */
    public $prependOptions = array();

    /**
     * @var array append html attributes.
     */
    public $appendOptions = array();

    /**
     *
     * @var array extra widget options
     */
    public $widgetOptions = array();

    /**
     * @var string the hint text.
     */
    public $hintText;

    /**
     * @var array hint html attributes.
     */
    public $hintOptions = array();

    /**
     * Initializes the widget.
     * @throws CException if the widget could not be initialized.
     */
    public function init() {
        if (!isset($this->form))
            throw new CException(__CLASS__ . ': Failed to initialize widget! Form is not set.');

        if (!isset($this->model))
            throw new CException(__CLASS__ . ': Failed to initialize widget! Model is not set.');

        if (!isset($this->widget))
            throw new CException(__CLASS__ . ': Failed to initialize widget! widget name is not set.');

        $this->processHtmlOptions();
    }

    /**
     * Runs the widget.
     */
    public function run() {
        $this->widgetOptions['model'] = $this->model;
        $this->widgetOptions['attribute'] = $this->attribute;
        $this->widgetOptions['htmlOptions'] = $this->htmlOptions;
        echo CHtml::openTag('div', array('class' => 'control-group ' . $this->getContainerCssClass()));
        echo $this->runWidget();
        parent::run();
        echo '</div>';
    }

    /**
     * Returns the input container CSS classes.
     * @return string the CSS class
     */
    protected function getAddonCssClass() {
        $classes = array();
        if (isset($this->prependText))
            $classes[] = 'input-prepend';
        if (isset($this->appendText))
            $classes[] = 'input-append';

        return implode(' ', $classes);
    }

    /**
     * Processes the html options.
     */
    protected function processHtmlOptions() {
        if (isset($this->htmlOptions['label'])) {
            $this->label = $this->htmlOptions['label'];
            unset($this->htmlOptions['label']);
        }

        if (isset($this->htmlOptions['prepend'])) {
            $this->prependText = $this->htmlOptions['prepend'];
            unset($this->htmlOptions['prepend']);
        }

        if (isset($this->htmlOptions['append'])) {
            $this->appendText = $this->htmlOptions['append'];
            unset($this->htmlOptions['append']);
        }

        if (isset($this->htmlOptions['hint'])) {
            $this->hintText = $this->htmlOptions['hint'];
            unset($this->htmlOptions['hint']);
        }

        if (isset($this->htmlOptions['labelOptions'])) {
            $this->labelOptions = $this->htmlOptions['labelOptions'];
            unset($this->htmlOptions['labelOptions']);
        }

        if (isset($this->htmlOptions['prependOptions'])) {
            $this->prependOptions = $this->htmlOptions['prependOptions'];
            unset($this->htmlOptions['prependOptions']);
        }

        if (isset($this->htmlOptions['appendOptions'])) {
            $this->appendOptions = $this->htmlOptions['appendOptions'];
            unset($this->htmlOptions['appendOptions']);
        }

        if (isset($this->htmlOptions['hintOptions'])) {
            $this->hintOptions = $this->htmlOptions['hintOptions'];
            unset($this->htmlOptions['hintOptions']);
        }

        if (isset($this->htmlOptions['errorOptions'])) {
            $this->errorOptions = $this->htmlOptions['errorOptions'];
            unset($this->htmlOptions['errorOptions']);
        }

        if (isset($this->htmlOptions['captchaOptions'])) {
            $this->captchaOptions = $this->htmlOptions['captchaOptions'];
            unset($this->htmlOptions['captchaOptions']);
        }
    }

    /**
     * Returns the prepend element for the input.
     * @return string the element
     */
    protected function getPrepend() {
        if ($this->hasAddOn()) {
            $htmlOptions = $this->prependOptions;

            if (isset($htmlOptions['class']))
                $htmlOptions['class'] .= ' add-on';
            else
                $htmlOptions['class'] = 'add-on';

            ob_start();
            echo '<div class="' . $this->getAddonCssClass() . '">';
            if (isset($this->prependText))
                echo CHtml::tag('span', $htmlOptions, $this->prependText);

            return ob_get_clean();
        } else
            return '';
    }

    /**
     * Returns the append element for the input.
     * @return string the element
     */
    protected function getAppend() {
        if ($this->hasAddOn()) {
            $htmlOptions = $this->appendOptions;

            if (isset($htmlOptions['class']))
                $htmlOptions['class'] .= ' add-on';
            else
                $htmlOptions['class'] = 'add-on';

            ob_start();
            if (isset($this->appendText))
                echo CHtml::tag('span', $htmlOptions, $this->appendText);

            echo '</div>';
            return ob_get_clean();
        } else
            return '';
    }

    /**
     * Returns whether the input has an add-on (prepend and/or append).
     * @return boolean the result
     */
    protected function hasAddOn() {
        return isset($this->prependText) || isset($this->appendText);
    }

    /**
     * Returns the hint text for the input.
     * @return string the hint text
     */
    protected function getHint() {
        if (isset($this->hintText)) {
            $htmlOptions = $this->hintOptions;

            if (isset($htmlOptions['class']))
                $htmlOptions['class'] .= ' help-block';
            else
                $htmlOptions['class'] = 'help-block';

            return CHtml::tag('p', $htmlOptions, $this->hintText);
        } else
            return '';
    }

    /**
     * Returns the label for this block.
     * @return string the label
     */
    protected function getLabel() {
        if (isset($this->labelOptions['class']))
            $this->labelOptions['class'] .= ' control-label';
        else
            $this->labelOptions['class'] = 'control-label';

        if ($this->label !== false && $this->hasModel())
            return $this->form->labelEx($this->model, $this->attribute, $this->labelOptions);
        else if ($this->label !== null)
            return $this->label;
        else
            return '';
    }

    /**
     * Returns the error text for the input.
     * @return string the error text
     */
    protected function getError() {
        return $this->form->error($this->model, $this->attribute, $this->errorOptions);
    }

    /**
     * Renders a text field.
     * @return string the rendered content
     */
    protected function runWidget() {
        echo $this->getLabel();
        echo '<div class="controls">';
        echo $this->getPrepend();
        $this->widget($this->widget, $this->widgetOptions);
        echo $this->getAppend();
        echo $this->getError() . $this->getHint();
        echo '</div>';
    }

    /**
     * Returns the container CSS class for the input.
     * @return string the CSS class
     */
    protected function getContainerCssClass() {
        $attribute = $this->attribute;
        return $this->model->hasErrors(CHtml::resolveName($this->model, $attribute)) ? CHtml::$errorCss : '';
    }

}
