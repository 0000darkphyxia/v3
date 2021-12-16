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

class ErrorHandler {	
    /** CLASS CONSTRUCTOR **/
    function ErrorHandler($absorb = true) {
        error_reporting(E_ALL);
		
        /** PHP INI PROPERTIES **/
        ini_set('display_errors', 0);
        ini_set('memory_limit', '700M');
		
        if ($absorb)
            set_error_handler(array(new ErrorHandler(false), 'HandleError'));
    }

    /** HANDLES ERRORS **/
    function HandleError($ErrorNo, $ErrorMessage, $ErrorFile, $ErrorLine, $ErrorCustom = false) {
        if (!(error_reporting() & $ErrorNo) AND !$ErrorCustom)
            return;

        /** DEFINE MAIN CLASS **/
        if (!class_exists('HiddenProjectCMS')) 
            require Configurations::MainRoot . 'classes/class.content.php';

        /** INITIALIZE CONTENT **/
        $error = new HiddenProjectCMS();

        /** PARSE ERROR TYPE **/
        switch ($ErrorNo) {
            case E_USER_ERROR:
                $ErrorType = 'Fatal Error';
                break;
            case E_USER_WARNING:
                $ErrorType = 'Warning';
                break;
            case E_USER_NOTICE:
                $ErrorType = 'Notice';
                break;
            default:
                $ErrorType = 'Unknown Error';
                break;
        }
    
        $error->SHOWOPTIONS = false;
        $error->SITE->TITLE = 'Warning';
        $error->SITE->DESCRIPTION = $ErrorType;
        $error->SITE->CONTENT = "<span class='PageHeader2'>Error No. #{$ErrorNo} - {$ErrorType}</span><br>";
        $error->SITE->CONTENT .= "<i>{$ErrorMessage}</i><br /><br />Error on line {$ErrorLine} in file {$ErrorFile}";
        $error->SITE->CONTENT .= '<br />Please notify the server administrator or webmaster: <b>' . Configurations::OwnerEmail . '</b>';

        print ($error->FlushContent());
        exit(1);
        return true;        
    }
}

?>