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
jimport('joomla.database.database');
jimport( 'joomla.database.table' );

class modPhocadownloadPanelHelper
{
    
    private function getNewDatabase()
    {
    
           $option = array(); //prevent problems

           $option['driver']   = 'mysql';            // Database driver name
           $option['host']     = 'localhost';    // Database host name
           $option['user']     = 'root';       // User for database authentication
           $option['password'] = '';   // Password for database authentication
           $option['database'] = 'processos_seletivos';      // Database name
           $option['prefix']   = 'prsel_';             // Database prefix (may be empty)
           $option['debug']    = '0';

           $db =& JDatabase::getInstance( $option );
           $db->setDebug($option['debug']);
           
           return $db;
    }
    
    
    static function getList(&$params, $units=array()){
       
        $db = self::getNewDatabase();

        $wheres = array();
        
        if($params->get('category_type')){
            $wheres[] = 'c.`type` = "'.$params->get('category_type').'"';
        }
        
        if($units){
            if(is_array($units)){
                $wheres[] = 'c.`unit_id` in('.implode(",", $units).') ';
            }else{
                $wheres[] = 'c.`unit_id` in('.$units.') ';
            }
        }
        
        $wheres[] = 'c.`published` = 1';
        
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
                        f.`title` AS LAST_FILE,
                        f.`status` AS STATUS_FILE,
                        f.`date` AS DATE_FILE,
                        un.`short_title` AS CAT_UNIT_TITLE,
                        YEAR (f.`date`) AS YEAR_FILE
                        from `#__phocadownload` as f
                        JOIN `#__phocadownload_categories` as c on f.`catid` = c.`id`
                        JOIN `#__phocadownload_units` as un on c.`unit_id` = un.`id`
                        {$wheres}
                            ORDER BY f.`date` DESC
                    )t1
                    GROUP BY CAT_TITLE";
        $db->setQuery($query);
	$rows = $db->loadObjectList();
        
        return $rows;
    }
    
    static function getLastFileByCategory($categoryId){
        
        $model = new PhocaDownloadModelCategory();
        $query = $model->_getFileListQuery($categoryId,'','ordering DESC');
        $last_file = $model->getList($query);

        return $last_file;
    }
    
    static function getLocations(){
        
        $db = self::getNewDatabase();
        
        $query = "SELECT id, title, params FROM #__phocadownload_locations WHERE published = 1";
        $db->setQuery($query);
	$rows = $db->loadObjectList();
        
        return $rows;
    }
}