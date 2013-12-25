<?php

/**
 * Class View_Raw
 * @method static View_Raw create($data)
 */
class View_Raw extends Base_Class implements View_Renderer {
    public $data = '';

    public function __construct($data) {
        $this->data = $data;
    }

    public function isEmpty()
    {
        return '' == $this->data;
    }

    public function render()
    {
        echo $this->data;
    }

} 