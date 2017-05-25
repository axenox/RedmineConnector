<?php
namespace axenox\RedmineConnector\Actions;

use exface\Core\Actions\CreateData;
use exface\Core\Exceptions\Actions\ActionInputInvalidObjectError;

/**
 * Creates a new issue showing the issue id with a link to it in the result message.
 *
 * @author Andrej Kabachnik
 *        
 */
class CreateIssue extends CreateData
{

    protected function perform()
    {
        if (! $this->getInputDataSheet()
            ->getMetaObject()
            ->is('axenox.RedmineConnector.ISSUE')) {
            throw new ActionInputInvalidObjectError($this, $this->getAliasWithNamespace() . ' can only be called on axenox.RedmineConnector.ISSUE objects!');
        }
        parent::perform();
        $this->setUndoable(false);
        $new_ticket_id = $this->getResultDataSheet()
            ->getUidColumn()
            ->getCellValue(0);
        $this->setResultMessage($this->translate('RESULT', array(
            '%url%' => $this->getInputDataSheet()
                ->getMetaObject()
                ->getDataConnection()
                ->getUrl(),
            '%issue_id%' => $new_ticket_id
        )));
    }
}