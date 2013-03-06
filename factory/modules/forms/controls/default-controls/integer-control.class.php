<?php

class FactoryFormFR100FR100IntegerFormControl extends FactoryFormFR100FR100TextboxFormControl 
{
    public $type = 'integer';
        
    public function getValue($name) {
        $value = intval( parent::getValue($name) );
        return $value != 0 ? $value : null;
    }
}