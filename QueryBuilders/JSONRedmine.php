<?php
namespace axenox\RedmineConnector\QueryBuilders;

use exface\Core\CommonLogic\QueryBuilder\QueryPartFilter;
use exface\Core\CommonLogic\QueryBuilder\QueryPartSorter;
use exface\UrlDataConnector\QueryBuilders\JsonUrlBuilder;

/**
 * This is a special REST query builder for Redmine (JSON API).
 * It uses Redmines custom filtering!
 *
 * @author Andrej Kabachnik
 *        
 */
class JSONRedmine extends JsonUrlBuilder
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \exface\DataSources\QueryBuilders\REST_AbstractRest::buildUrlFilter()
     */
    protected function buildUrlFilter(QueryPartFilter $qpart)
    {
        $filter = '';
        $filter_name = '';
        
        // Determine filter name (URL parameter name)
        if ($param = $qpart->getDataAddressProperty('filter_remote_url_param')) {
            $filter_name = $param;
        } else {
            $filter_name = $qpart->getDataAddress();
        }
        $filter = 'f[]=' . $filter_name;
        
        // Add the operator
        $filter .= '&op[' . $filter_name . ']=';
        // Some special cases need special treatment
        if ($filter_name == 'status_id' && $qpart->getCompareValue() == 'o') {
            $op = o;
        } else {
            // In all other cases, translate ExFace-comparators to those in Redmine
            switch ($qpart->getComparator()) {
                // TODO add other comparators
                case EXF_COMPARATOR_IS:
                    $op = '~';
                    break;
                case EXF_COMPARATOR_IN:
                    $op = '=';
                    if (! is_array($qpart->getCompareValue())) {
                        $val = explode(EXF_LIST_SEPARATORXF_LIST_SEPARATOR, $qpart->getCompareValue());
                    }
                    break;
                default:
                    $op = $qpart->getComparator();
            }
        }
        $filter .= $op;
        
        // Add the value
        $val = isset($val) ? $val : $qpart->getCompareValue();
        if (is_array($val)) {
            foreach ($val as $v) {
                $filter .= '&v[' . $filter_name . '][]=' . $v;
            }
        } else {
            $filter .= '&v[' . $filter_name . '][]=' . $val;
        }
        return $filter;
    }

    protected function buildUrlSorter(QueryPartSorter $qpart)
    {
        return ($qpart->getDataAddressProperty('sort_remote_url_param') ? $qpart->getDataAddressProperty('sort_remote_url_param') : $qpart->getDataAddress()) . ($qpart->getOrder() == 'desc' ? ':desc' : '');
    }
}
?>