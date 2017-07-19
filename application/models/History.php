<?php

class Application_Model_History
{
    protected $_id;
    protected $_from;
    protected $_to;
    protected $_from_amount;
    protected $_to_amount;
    protected $_created

    public function __construct(array $options = null) {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid History property');
        }
        $this->$method($value);
    }

    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid History property');
        }
        return $this->$method();
    }

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

    public function setFrom($text)
    {
        $this->_from = (string)$text;
        return $this;
    }

    public function getFrom()
    {
        return $this->_from;
    }

    public function setTo($text)
    {
        $this->_to = (string)$text;
        return $this;
    }

    public function getTo()
    {
        return $this->_to;
    }

    public function setFromAmount($float_value)
    {
        $this->_from_amount = (float)$float_value;
        return $this;
    }

    public function getFromAmount() {
        return $this->_from_amount;
    }

    public function setToAmount($float_value)
    {
        $this->_to_amount = (float)$float_value;
        return $this;
    }

    public function getToAmount() {
        return $this->_to_amount;
    }

    public function setCreated($ts)
    {
        $this->_created = $ts;
        return $this;
    }

    public function getCreated()
    {
        return $this->_created;
    }

    public function setId($id) {
        $this->_id = (int)$id;
        return $this;
    }

    public function getId()
    {
        return $this->_id;
    }

}

