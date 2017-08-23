<?php

class Application_Model_HistoryMapper
{
    protected $_dbTable;

    // Decorator for setting the table class
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

    // Decorator for table class
    public function getDbTable()
    {
        if (null === $this->_dbTable) {
            $this->setDbTable('Application_Model_DbTable_History');
        }
        return $this->_dbTable;
    }

    // Add a history element to the DB
    public function save(Application_Model_History $history)
    {
        // Set the new values
        $data = array(
            'from'   => $history->getFrom(),
            'to' => $history->getTo(),
            'from_amount' => $history->getFromAmount(),
            'to_amount' => $history->getToAmount(),
            'created' => date('Y-m-d H:i:s'),
        );
 
        // If not already existing, insert
        if (null === ($id = $history->getId())) {
            unset($data['id']);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
    }

    // Return a history element based on ID
    public function find($id, Application_Model_History $history)
    {
        // Find using id
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current();

        // Map the result
        $history->setId($row->id)
             ->setFrom($row->from)
             ->setTo($row->to)
             ->setFromAmount($row->from_amount)
             ->setToAmount($row->to_amount)
             ->setCreated($row->created);

        return $history;
    }

    // Return all the history elements in the DB
    public function fetchAll()
    {
        // Find all
        $selection = $this->getDbTable()->select()->order('created DESC')->limit(5,0);
        $resultSet = $this->getDbTable()->fetchAll($selection);

        // return in reverse order to have the latest entry at the bottom
        return array_reverse($resultSet->toArray());
    }

}

