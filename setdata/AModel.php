<?php
/**
 * Created by PhpStorm.
 * User: abu
 * Date: 16/7/24
 * Time: 下午4:49
 */

class AModel
{

    public function __construct($option = null)
    {
        if($option){
            $this->setData($option);
        }
    }

    public function setData($options) {
        if (is_object($options)) {
            if (method_exists($options, 'toArray')) {
                $options = $options->toArray();
            }
            else if (!($options instanceof \Traversable)) {
                return $this;
            }
        }
        else if (!is_array($options)) {
            return $this;
        }
        foreach ($options as $key => $value) {
            $property = lcfirst(str_replace(' ','',ucwords(str_replace('_',' ',$key))));
            if(property_exists($this,$property)){
                $this->{$property} = $value;
            }
        }

        return $this;
    }
}