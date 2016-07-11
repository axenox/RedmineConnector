<?php namespace axenox\RedmineConnector\QueryBuilders;

use exface\Core\CommonLogic\QueryBuilder\QueryPartFilter;
use exface\Core\CommonLogic\QueryBuilder\QueryPartSorter;
use exface\HttpDataConnector\QueryBuilders\JSON;

/**
 * This is a special REST query builder for Redmine (JSON API). It uses Redmines custom filtering!
 * @author aka
 *
 */
class JSONRedmine extends JSON {
	
	/**
	 * 
	 * {@inheritDoc}
	 * @see \exface\DataSources\QueryBuilders\REST_AbstractRest::build_url_filter()
	 */
	protected function build_url_filter(QueryPartFilter $qpart){
		$filter = '';
		$filter_name = '';
		
		// Determine filter name (URL parameter name)
		if ($param = $qpart->get_data_address_property('filter_query_parameter')){
			$filter_name = $param;
		} else {
			$filter_name = $qpart->get_data_address();
		}
		$filter = 'f[]=' . $filter_name;
				
		// Add the operator
		$filter .= '&op[' . $filter_name . ']=';
		// Some special cases need special treatment
		if ($filter_name == 'status_id' && $qpart->get_compare_value() == 'o'){
			$op = o;
		} else {
			// In all other cases, translate ExFace-comparators to those in Redmine
			switch ($qpart->get_comparator()){
				// TODO add other comparators
				case EXF_COMPARATOR_IS: $op = '~'; break;
				case EXF_COMPARATOR_IN:
					$op = '=';
					if (!is_array($qpart->get_compare_value())){
						$val = explode(',', $qpart->get_compare_value());
					}
					break;
				default: $op = $qpart->get_comparator();
			}
		}
		$filter .= $op;
		
		// Add the value
		$val = isset($val) ? $val : $qpart->get_compare_value();
		if (is_array($val)){
			foreach ($val as $v){
				$filter .= '&v[' . $filter_name . '][]=' . $v;
			}
		} else {
			$filter .= '&v[' . $filter_name . '][]=' . $val;
		}
		return $filter;
	}
	
	protected function build_url_sorter(QueryPartSorter $qpart){
		return ($qpart->get_data_address_property('sort_query_parameter') ? $qpart->get_data_address_property('sort_query_parameter') : $qpart->get_data_address()) . ($qpart->get_order() == 'desc' ? ':desc' : '');
	}
	  
}
?>