<?php
namespace axenox\RedmineConnector\Actions;

use exface\Core\Actions\CreateData;
use exface\Core\Exceptions\Actions\ActionInputInvalidObjectError;
use exface\Core\Interfaces\Tasks\TaskInterface;
use exface\Core\Interfaces\DataSources\DataTransactionInterface;
use exface\Core\Interfaces\Tasks\ResultInterface;

/**
 * Creates a new issue showing the issue id with a link to it in the result message.
 *
 * @author Andrej Kabachnik
 *        
 */
class CreateIssue extends CreateData
{
    protected function init()
    {
        parent::init();
        
        $this->setInputObjectAlias('axenox.RedmineConnector.ISSUE');
    }
    
    protected function perform(TaskInterface $task, DataTransactionInterface $transaction) : ResultInterface
    {
        $input = $this->getInputDataSheet($task);
        
        $result = parent::perform($task, $transaction);
        $result->setUndoable(false);
        
        $new_ticket_id = $result->getData()->getUidColumn()->getCellValue(0);
        $result->setMessage($this->translate('RESULT', array(
            '%url%' => $input->getMetaObject()->getDataConnection()->getUrl(),
            '%issue_id%' => $new_ticket_id
        )));
        
        return $result;
    }
}