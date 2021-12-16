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

/** DEFAULT TIME ZONE **/
date_default_timezone_set('Asia/Manila');

interface Configurations {
    /** CMS BUILD INFO **/
    const Build = '0.0.0.1 Development';
	
    /** SERVER INFORMATION **/
    const ServerName = 'NeverLand';
    const ServerNews = '';
    const ServerDescription = null;
    const ServerCompany = 'Classified';	

    const OwnerName = 'Vitor Ricardo';
    const OwnerEmail = 'vitor@gmail.com';
    const FacebookId = '';
	
    /** MYSQL DATA **/
    const MySQLHost = 'localhost';
    const MySQLUser = 'root';
    const MySQLPass = 'fWoRlDS2018@@@**IDKFWORLDS';
    const MySQLData = 'miraclev1';
	
    const MainRoot = '/';	
    const DebugMode = true;	
    const Advertisements = false;

    /** PAYPAL STUFF **/	
    const PayPalEmail = null;
    const PayPalSuccessURL = '?shop.php?gen';
    const PayPalFailureURL = '?shop.php?error';
    const PayPalRMethod = 2;
    const PayPalPMethod = 'fso';
    const PayPalCCode = 'USD';
    const PayPalLanguage = 'US';
    const PayPalServer = 'https://www.paypal.com/cgi-bin/webscr';
}

/** CHECKS CMS COMPONENTS **/
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'] . Configurations::MainRoot . 'classes/')) as $file) {
    switch ($file->getFilename()) {
        case 'class.content.php':
	    //if ($file->getSize() == 31216) continue; else SystemExit('FILE HAS BEEN MODIFED (' . $file->getSize() . '): ' . $file->getFilename());
	    break;
    }
}

function DefineClass($ClassName = null) {
    if (class_exists($ClassName))
        SystemExit("{$ClassName} is already defined");
    else {
        $ClassName = $_SERVER['DOCUMENT_ROOT'] . Configurations::MainRoot . "classes/{$ClassName}.php";
        ((file_exists($ClassName)) ? require $ClassName : SystemExit("{$ClassName} does not exist"));
    }
}

function SystemExit($text = 'No given message', $line = __LINE__, $file = __FILE__) {
    header('Content-Type: text/plain');
    print ("$text - " . date("F j, Y, g:i a"));
    print ("\nLocation: $file ($line)");
    exit(1);
}

/** ATTENTION!
  * For some reason, the CMS looks really bad on Internet Explorer. 
  * So to prevent users see the bad side of our CMS, we do this!
**/
if (preg_match('~MSIE|Internet Explorer~i', $_SERVER['HTTP_USER_AGENT'])) {
    //SystemExit("Sorry for the inconvenience, this page can't be viewed on Internet Explorer.\nWe recommend you to use Firefox or Google Chrome instead.");
}
?>