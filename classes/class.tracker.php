<?php
/**
	* Advanced Miracle Content Management System - CMS Configurations
	*
	* Copyright (c) 2016 Naufal Hardiansyah (www.augoeides.world)
	* The program is distributed under the terms of the GNU General Public License 
	*
	* This file is part of Advanced Miracle Content Management System (AdvMiracleCMS).
	* 
	* AdvMiracleCMS is free software: you can redistribute it and/or modify
	* it under the terms of the GNU General Public License as published by
	* Naufal Hardiansyah, either version 3 of the License, or any later version.
	* 
	* AdvMiracleCMS is distributed in the hope that it will be useful,
	* but WITHOUT ANY WARRANTY; without even the implied warranty of
	* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
	* GNU General Public License for more details.
	* 
	* You should have received a copy of the GNU General Public License
	* along with AdvMiracleCMS. If not, see <http://www.gnu.org/licenses/>.
**/

/**
  * class.tracker.php
  * Author: Ilir Fekaj
  * Contact: tebrino@hotmail.com
  * Last updated: July 28, 2005
  * Version: 1.1
  * Latest version & info: http://www.sim-php.info
  * Support: http://forum.sim-php.info/ (if you find bugs or you need help with installation)
  * Demo: http://www.free-midi.org
  * 
  * This very simple class enables you to track number of visitors online in
  * an easy and accurate manner. It's free for all purposes, just please don't
  * claim you wrote it. If you have any problems, please feel free to contact me.
  * Also if you like this script please put link to http://www.sim-php.info. Thanks
**/

class UserTracker {
    var $Root = null;
    var $timeout = 86400;
    var $count = 0;
    var $error;
    var $i = 0;
    
    function StartTracking() {
        $this->timestamp = time();
        $this->ip = $this->ipCheck();
        $this->new_user();
        $this->delete_user();
        $this->count_users();
    }
    
    function ipCheck() {
        /**
          * This function will try to find out if user is coming behind proxy server. Why is this important?
          * If you have high traffic web site, it might happen that you receive lot of traffic
          * from the same proxy server (like AOL). In that case, the script would count them all as 1 user.
          * This function tryes to get real IP address.
          * Note that getenv() function doesn't work when PHP is running as ISAPI module
        **/
        if (getenv('HTTP_CLIENT_IP')) 
            $ip = getenv('HTTP_CLIENT_IP');        
        elseif (getenv('HTTP_X_FORWARDED_FOR')) 
            $ip = getenv('HTTP_X_FORWARDED_FOR');        
        elseif (getenv('HTTP_X_FORWARDED'))
            $ip = getenv('HTTP_X_FORWARDED');        
        elseif (getenv('HTTP_FORWARDED_FOR'))
            $ip = getenv('HTTP_FORWARDED_FOR');        
        elseif (getenv('HTTP_FORWARDED'))
            $ip = getenv('HTTP_FORWARDED');        
        else 
            $ip = $_SERVER['REMOTE_ADDR'];
        
        return $ip;
    }
    
    function new_user() {
        $location = base64_encode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
        if (!$this->Root->MySQLi('Query', array( 0 => "INSERT INTO `cms_traffics`(Timestamp, Client, Link) VALUES ('$this->timestamp', '$this->ip', '$location')" ))) {
            $this->error[$this->i] = "Unable to record new visitor\r\n";            
            $this->i++;
        }
    }
    
    function delete_user() {
        if (!$this->Root->MySQLi('Query', array( 0 => "DELETE FROM `cms_traffics` WHERE Timestamp < ($this->timestamp - $this->timeout)" ))) {
            $this->error[$this->i] = "Unable to delete visitors";
            $this->i++;
        }
    }
    
    function count_users() {
        if (count($this->error) == 0)
            $this->Root->ONLINE->LAST24 = $this->Root->MySQLi('Query', array( 0 => "SELECT DISTINCT Client FROM cms_traffics" ))->num_rows;        
    }
}

?>