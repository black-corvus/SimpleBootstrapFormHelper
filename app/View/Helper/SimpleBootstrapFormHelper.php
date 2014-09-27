<?php
App::uses('FormHelper', 'View/Helper');

class SimpleBootstrapFormHelper extends FormHelper {
    public $helpers = array('Html');

    public function create($model = null, $options = array()) {
        $default = array(
            'inputDefaults' => array(
                'div' => 'form-group',
                'class' => 'form-control'
            ),
        );
        $options = Hash::merge($default, $options);
        return parent::create($model, $options);
    }

    /**
     * Returns a series of rendered checkboxes
     * Checks $this->request->data for existing data.
     * To be used with HABTM relationships.
     *
     * @param array $checkboxData Array of key-value pairs where the array key is the checkbox value and the array value is the checkbox label
     *                            This should be the output to a call to Model->find('list')
     * @param string $associatedModel Associated model for the checkboxes. Used in the field name and to check $this->request->data
     * @return string Checkboxes
     */
    public function checkboxes($checkboxData, $associatedModel) {
        $output = '';
        if (is_array($checkboxData)) {
            foreach($checkboxData as $id => $label) {
                $output .= $this->_checkbox("$associatedModel.$associatedModel.", $id, $label, $this->_joinDataExistsForId($id, $associatedModel));
            }
        }
        return $output;
    }

    /**
     * Generates a single checkbox using Bootstrap's format for checkboxes
     *
     * @param string $name Checkbox name attribute
     * @param string $value Checkbox value attribute
     * @param string $label Label to display after the checkbox
     * @param bool $checked Whether the checkbox should be checked
     * @return string A checkbox wrapped in a label wrapped in a div
     */
    protected function _checkbox($name, $value, $label, $checked = false) {
        return $this->Html->tag(
            'div',
            $this->Html->tag('label', $this->checkbox($name, array('value' => $value,'checked' => $checked)) . ' ' . $label),
            array('class' => 'checkbox')
        );
    }

    protected function _joinDataExistsForId($id, $associatedModel) {
        $match = false;
        if (isset($this->request->data[$associatedModel])) {
            foreach ((array)$this->request->data[$associatedModel] as $selected) {
                if (isset($selected['id']) && ($selected['id'] == $id)) {
                    $match = true;
                }
            }
        }
        return $match;
    }
}