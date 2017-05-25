<?php
namespace axenox\RedmineConnector\DataConnectors;

use exface\UrlDataConnector\DataConnectors\HttpConnector;

class RedmineApiConnector extends HttpConnector
{

    public function getUserId()
    {
        return $this->user_id;
    }

    public function setUserId($value)
    {
        $this->user_id = $value;
        return $this;
    }
}

?>