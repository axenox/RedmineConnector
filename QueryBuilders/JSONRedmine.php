<?php
namespace axenox\RedmineConnector\QueryBuilders;

use exface\Core\CommonLogic\QueryBuilder\QueryPartFilter;
use exface\Core\CommonLogic\QueryBuilder\QueryPartSorter;
use exface\UrlDataConnector\QueryBuilders\JsonUrlBuilder;
use exface\Core\DataTypes\SortingDirectionsDataType;
use exface\UrlDataConnector\QueryBuilders\AbstractUrlBuilder;

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
    * {@inheritDoc}
    * @see \exface\UrlDataConnector\QueryBuilders\AbstractUrlBuilder::buildUrlFilter($qpart)
    */
    protected function buildUrlFilter(QueryPartFilter $qpart)
    {
        $filter = '';
        $filter_name = '';
        
        // Determine filter name (URL parameter name)
        if ($param = $qpart->getDataAddressProperty(AbstractUrlBuilder::DAP_FILTER_REMOTE_URL_PARAM)) {
            $filter_name = $param;
        } else {
            $filter_name = $qpart->getDataAddress();
        }
        $filter = '&f[]=' . $filter_name;
        
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
                        $val = explode($qpart->getValueListDelimiter(), $qpart->getCompareValue());
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

    /**
     * 
     * {@inheritDoc}
     * @see \exface\UrlDataConnector\QueryBuilders\AbstractUrlBuilder::buildUrlParamSorter($qpart)
     */
    protected function buildUrlParamSorter(QueryPartSorter $qpart)
    {
        return ($qpart->getDataAddressProperty(AbstractUrlBuilder::DAP_SORT_REMOTE_URL_PARAM) ? $qpart->getDataAddressProperty(AbstractUrlBuilder::DAP_SORT_REMOTE_URL_PARAM) : $qpart->getDataAddress()) . ($qpart->getOrder() == SortingDirectionsDataType::DESC ? ':desc' : ':asc');
    }
}
?>