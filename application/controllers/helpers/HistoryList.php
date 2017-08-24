<?php

/*
 * History List class to help generate history list output
 *
 */
class Application_Controller_Helper_HistoryList extends Zend_Controller_Action_Helper_Abstract
{
    
    /*
     * ->historyList() calls will execute this method
     */
    public function direct() 
    {
        // Create view instance
        $view = new Zend_View();
        
        // History model
        $history_mapper = new Application_Model_HistoryMapper();
        $latest_history = $history_mapper->fetchAll();
        
        // Assign latest history to view
        $view->history = $latest_history;

        // Set view to be used
        $view->setScriptPath(APPLICATION_PATH . '/views/scripts/history/');

        // Render
        return $view->render('latest.phtml');
    }
}