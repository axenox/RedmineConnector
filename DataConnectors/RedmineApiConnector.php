<?php namespace axenox\RedmineConnector\DataConnectors;

use exface\UrlDataConnector\DataConnectors\HttpConnector;

class RedmineApiConnector extends HttpConnector {
	
	public function get_user_id() {
		return $this->user_id;
	}
	
	public function set_user_id($value) {
		$this->user_id = $value;
		return $this;
	}
	
}

?>