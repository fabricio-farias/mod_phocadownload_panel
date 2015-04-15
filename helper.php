<?php
/**
 * @package		Joomla.Site
 * @subpackage	mod_articles_archive
 * @copyright	Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;
require_once JPATH_SITE . DS.'components'.DS.'com_phocadownload'.DS.'models'.DS.'category.php';
jimport('joomla.database.database');jimport( 'joomla.database.table' );jimport('joomla.html.parameter');
jimport('joomla.application.component.model');

class modPhocadownloadPanelHelper
{
    
    var $_locations             = null;
    var $_locations_ordering    = null;
    var $_location_ordering     = null;
    var $_params                = null;
    var $_db                    = null;
    
    function __construct($params = array()){
        
        $app = JFactory::getApplication();
        $this->_params = $params;

        $option = array();
        $option['driver']   = $this->_params->get('dbtype');
        $option['host']     = $this->_params->get('host');
        $option['user']     = $this->_params->get('user');
        $option['password'] = $this->_params->get('password');
        $option['database'] = $this->_params->get('db');
        $option['prefix']   = $this->_params->get('dbprefix');
        $option['debug']    = '0';
        
        $this->_db =& JDatabase::getInstance( $option );
        $this->_db->setDebug($option['debug']);

    }
    
    function _getList($query, $limitstart = 0, $limit = 0)
    {
            $this->_db->setQuery($query, $limitstart, $limit);
            $result = $this->_db->loadObjectList();

            return $result;
    }
    
    function getLocationsList() {
        
        if (empty($this->_locations)) {
            $query = $this->_getLocationsListQuery();
            $this->_locations = $this->_getList($query);

            if (!empty($this->_locations)) {
                
                $locations_params = '';
                foreach ($this->_locations as $key => $value) {
                    $registry = new JRegistry();
                    $registry->loadString($value->params);
                    $locations_params = $registry->toArray();

                    $query = $this->_getUnitsListQuery($locations_params);
                    $this->_locations[$key]->units = $this->_getList($query);
                }
            }
        }

        return $this->_locations;
    }
    
    
    function _getLocationsListQuery(){
        
        $wheres = array();
        $app = JFactory::getApplication();
        $params = $this->_params;
        $user = JFactory::getUser();
        $userLevels = implode(',', $user->authorisedLevels());
        
        $pQ = $params->get('enable_plugin_query', 0);

        $wheres[] = " lc.published = 1";
        $wheres[] = " lc.access IN (" . $userLevels . ")";

        if ($pQ == 1) {
            // GWE MOD - to allow for access restrictions
            JPluginHelper::importPlugin("phoca");
            $dispatcher = & JDispatcher::getInstance();
            $joins = array();
            $results = $dispatcher->trigger('onGetCategoriesList', array(&$wheres, &$joins, $params));
            // END GWE MOD
        }
        
        $query = " SELECT lc.id, lc.title, lc.params"
                . " FROM #__phocadownload_locations as lc"
                . " WHERE " . implode(" AND ", $wheres)
                . " ORDER BY lc.ordering"
                ;
        
        return $query;
    }
    
    
    function _getUnitsListQuery($uid = array()){
        
        $wheres = array();
        $app = JFactory::getApplication();
        $params = $this->_params;
        $user = JFactory::getUser();
        $userLevels = implode(',', $user->authorisedLevels());

        $pQ = $params->get('enable_plugin_query', 0);
               
        if($params->get('category_type')){
            $wheres[] = 'c.`type` = "'.$params->get('category_type').'"';
        }
        
        $wheres[] = " c.published = 1";


        if($uid){
            if(is_array($uid)){
                $wheres[] = ' c.`unit_id` in('.implode(",", $uid).') ';
            }else{
                $wheres[] = ' c.`unit_id` in('.$uid.') ';
            }
        }
        
        $n = count($wheres);
        if($n > 0){
            $wheres = 'WHERE (' . implode( ' AND ', $wheres) . ')';
        }
        
        $query = "SELECT DISTINCT CAT_ID, CAT_TITLE, LAST_FILE, CAT_UNIT_ID, CAT_UNIT_TITLE, STATUS_FILE, CAT_TYPE, DATE_FILE, YEAR_FILE
                    FROM (
                            select 
                            c.`id` AS CAT_ID,
                            c.`title` AS CAT_TITLE,
                            c.`unit_id` AS CAT_UNIT_ID,
                            c.`type` AS CAT_TYPE,
                            IF(f.title_id <> 0,CONCAT(en.`title`,' ',f.`title_seq`),f.`title`) AS LAST_FILE,
                            f.`status` AS STATUS_FILE,
                            f.`date` AS DATE_FILE,
                            un.`short_title` AS CAT_UNIT_TITLE,
                            YEAR (f.`date`) AS YEAR_FILE
                            from `#__phocadownload` as f
                            JOIN `#__phocadownload_categories` as c on f.`catid` = c.`id`
                            JOIN `#__phocadownload_units` as un on c.`unit_id` = un.`id`
                            LEFT JOIN `prsel_phocadownload_entitle` as en on f.`title_id` = en.`id`
                            {$wheres}
                            ORDER BY f.`ordering` DESC
                        )t1
                GROUP BY CAT_ID ORDER BY DATE_FILE DESC";
  
        return $query;
    }
}