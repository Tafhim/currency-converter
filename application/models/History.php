<?php

class Application_Model_History
{
    // Columns
    protected $_id;
    protected $_from;
    protected $_to;
    protected $_from_amount;
    protected $_to_amount;
    protected $_created;

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    // Magic method for setting any attribute
    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid History property');
        }
        $this->$method($value);
    }

    // Magic method for getting any attribute
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid History property');
        }
        return $this->$method();
    }

    // Method for setting attributes in bulk using an array
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    // Set the 'from' code
    public function setFrom($text)
    {
        $this->_from = (string)$text;
        return $this;
    }

    // Get the 'from' code
    public function getFrom()
    {
        return $this->_from;
    }

    // Set the 'to' code
    public function setTo($text)
    {
        $this->_to = (string)$text;
        return $this;
    }

    // Get the 'to' code
    public function getTo()
    {
        return $this->_to;
    }

    // Set the amount to convert from
    public function setFromAmount($float_value)
    {
        $this->_from_amount = (float)$float_value;
        return $this;
    }

    // Get the amount to covnert from
    public function getFromAmount() {
        return $this->_from_amount;
    }

    // Set the converted amount
    public function setToAmount($float_value)
    {
        $this->_to_amount = (float)$float_value;
        return $this;
    }

    // Get the converted amount
    public function getToAmount() {
        return $this->_to_amount;
    }

    // Set the timestamp
    public function setCreated($ts)
    {
        $this->_created = $ts;
        return $this;
    }

    // Get the timestamp
    public function getCreated()
    {
        return $this->_created;
    }

    // Set the id
    public function setId($id) {
        $this->_id = (int)$id;
        return $this;
    }

    // Get the id
    public function getId()
    {
        return $this->_id;
    }

}

