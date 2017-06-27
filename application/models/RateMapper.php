<?php

class Application_Model_RateMapper
{
    protected $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_Rate');
        }
        return $this->_dbTable;
    }
    
    public function save(Application_Model_Rate $rate)
    {
        $data = array(
            'code'   => $rate->getCode(),
            'rate' => $rate->getRate(),
            'created' => date('Y-m-d H:i:s'),
        );
 
        if (null === ($id = $rate->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    public function find($id, Application_Model_Rate $rate)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();
        $rate->setId($row->id)
             ->setCode($row->code)
             ->setRate($row->rate)
             ->setCreated($row->created);
    }

    public function findByCode($code) {
        $result = $this->getDbTable()->fetchRow(
            'code = "' . $code . '"'
        );

        return $result;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Application_Model_Rate();
            $entry->setId($row->id)
                  ->setCode($row->code)
                  ->setRate($row->rate)
                  ->setCreated($row->created);
            $entries[] = $entry;
        }
        return $entries;
    }

}

