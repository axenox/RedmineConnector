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
class CreateIssue extends CreateData {
	
	protected function perform(){
		if (!$this->get_input_data_sheet()->get_meta_object()->is('axenox.RedmineConnector.ISSUE')){
			throw new ActionInputInvalidObjectError($this, $this->get_alias_with_namespace() . ' can only be called on axenox.RedmineConnector.ISSUE objects!');
		}
		parent::perform();
		$this->set_undoable(false);
		$new_ticket_id = $this->get_result_data_sheet()->get_uid_column()->get_cell_value(0);
		$this->set_result_message($this->translate('RESULT', array('%url%' => $this->get_input_data_sheet()->get_meta_object()->get_data_connection()->get_url(), '%issue_id%'=> $new_ticket_id)));
	}
	
}