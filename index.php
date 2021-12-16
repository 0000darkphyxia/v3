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

/** READS CMS CONFIGURATIONS **/
require_once 'config.php';

/** DEFINES CLASSES **/
DefineClass('class.handler');
DefineClass('class.content');
DefineClass('class.bbcodes');
DefineClass('class.core');
DefineClass('class.data');

/** CREATES NEW CLASSES **/
$handler = new ErrorHandler();
$content = new MiracleCMS();
$bbcodes = new BBCodes();
$advcore = new Core();
$advdata = new DataHandler(null, 'test');

/** CONFIGURES MYSQL PARAMETERS **/
$MySQL = new stdClass();
$MySQL->HOST = Configurations::MySQLHost; 
$MySQL->USER = Configurations::MySQLUser;
$MySQL->PASS = Configurations::MySQLPass;
$MySQL->DATA = Configurations::MySQLData;
$content->MYSQL = $MySQL; 

/** BEGINS INITIALIZATIONS **/
$content->Initialize('Connection');
$content->Initialize('Settings');

/** GENERATE NEW CLIENT SESSION ID **/
if (isset($_GET['generatenewid'])) { $_SESSION['paymentid'] = session_id(); print 'success'; exit(); }

/** SETS USER LOGIN STATUS **/
$content->USER->LOGGEDIN = $advcore->UserData['Login'];
$content->USER->CURRENTURL = $advcore->UserData['Location'];

/** HANDLES PAGES **/
switch (key($_GET)) {
	case 'news':
        //** INITIALIZES OUTPUT SETTINGS FOR: 404 NEWS **/
		if ($advcore->UserData['Login']) {
			$content->SITE->DESCRIPTION = 'News Board';
			$content->SITE->CMS->About = false;
			$content->SITE->CMS->Features = false;
			$content->SITE->CONTENT = $content->GetCMSTemplate('default.news');
			$content->Initialize('News', array( 0 => $bbcodes ));
		} else {
			header('Location: ?login');
		}
        break;
	case 'ladder':
        /** INITIALIZES OUTPUT SETTINGS FOR: RANKING **/
		if ($advcore->UserData['Login']) {
			$content->Initialize('TopCharacters');
			$content->SITE->DESCRIPTION = 'Ladder';
			$content->SITE->CMS->About = false;
			$content->SITE->CMS->Features = false;
			$content->SITE->CONTENT = $content->GetCMSTemplate('default.ladder');
			$content->Initialize('Announcement');
		} else {
			header('Location: ?login');
		}
        break;
	case 'profile':
        /** CHARACTER PROFIlE **/
        $content->SITE->DESCRIPTION = $_GET['profile']. '&#39;s Profile';
        $content->SITE->CMS->PlayOptions = false;
        $content->SITE->CONTENT = $content->GetCMSTemplate('default.profile');
		$content->Initialize('Announcement');
        /** RETRIEVES AND ESCAPES CHARACTER NAME STRING **/
        $USERNAME = $content->DBase('EscapeString', array( 0 => $_GET['profile'] ));
        $MYSQL_QUERY = $content->DBase('Query', array( 0 => "SELECT * FROM users_characters WHERE Name = '$USERNAME'" ));

        /** HANDLES CHARACTER DATA **/
        if ($MYSQL_QUERY->num_rows < 1)
            $content->SendResponse('Character not found!', 2);     
        else {
            /** INITIALIZES CHARACTER DATA **/
            $USER_DATA = $MYSQL_QUERY->fetch_assoc();
			$HAIR_QUERY = $content->DBase('Query', array( 0 => "SELECT * FROM hairs WHERE id = ". $USER_DATA['HairID'] . ""));
			$HAIR_DATA = $HAIR_QUERY->fetch_assoc();
            $content->SITE->PROFILEADDON = '&intColorHair=' . $USER_DATA['ColorHair'] . '&intColorSkin=' . $USER_DATA['ColorSkin'] . '&intColorEye=' . $USER_DATA['ColorEye'] . '&intColorTrim=' . $USER_DATA['ColorTrim'] . '&intColorBase=' . $USER_DATA['ColorBase'] . '&intColorAccessory=' . $USER_DATA['ColorAccessory'] . '&ia1=25174450&strGender=' . $USER_DATA['Gender'] . '&strHairFile=' . $HAIR_DATA ['File'] .'&strHairName=' . $HAIR_DATA ['Name'] .'&strName=' . $USER_DATA['Name'] . '&intLevel=' . $USER_DATA['Level'];
			
            /** INITIALIZES CHARACTER PROFILE **/
            $content->SITE->PROFILEINVENTORY = $advcore->Initialize('UserInventory', array( 0 => $advcore->Initialize('UserItems', array( 0 => $USER_DATA['CharacterID'], 1 => $content )), 1 => $content ));
            $content->SITE->PROFILEACHIEVEMENTS = $advcore->Initialize('UserAchievements', array( 0 => $USER_DATA['Name'], 1 => $content ));
		}
        break;
	case 'play':
        /** INITIALIZES OUTPUT SETTINGS FOR: GAMEPAGE **/
		if ($advcore->UserData['Login']) {
			$content->SITE->DESCRIPTION = 'Game';
			$content->SITE->CMS->About = false;
			$content->SITE->CMS->Features = false;
			$content->SITE->CMS->Template = 'game';
			$content->Initialize('Announcement');
		} else {
			header('Location: ?login');
		}
        break;
	case 'MiracleVersion':
		/** INITIALIZES OUTPUT SETTINGS FOR: GAME VERSION **/
		if ($advcore->UserData['Login'] )  {
			$id = $advcore->UserData['id'];
			$content->SITE->DESCRIPTION = 'Version';
			$query = $content->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'sNews'"));
			$news = $query->fetch_object();
			$query = $content->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'sFile'"));
			$client = $query->fetch_object();
			$query = $content->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'sTitle'"));
			$title = $query->fetch_object();
			$query = $content->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'sBG'"));
			$background = $query->fetch_object();
			$query = $content->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'sBGM'"));
			$music = $query->fetch_object();
			$query = $content->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'sNewUser'"));
			$signup = $query->fetch_object();
			$query = $content->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'sCharacter'"));
			$character = $query->fetch_object();
			$query = $content->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'sEmoji'"));
			$emoji = $query->fetch_object();
			
			$advdata->addData('status', 'Stacia');
			$advdata->addData('sFile', $client->value);
			$advdata->addData('sTitle', $title->value);
			$advdata->addData('sBG', $background->value);
			$advdata->addData('sBGM', $music->value);
			$advdata->addData('sNews', $news->value);
			$advdata->addData('sNewUser', $signup->value);
			$advdata->addData('sCharacter', $character->value);
			$advdata->addData('sEmoji', $emoji->value);
			$advdata->addData('sUsername', $advcore->UserData['Name']);
			$advdata->addData('sPassword', $advcore->UserData['Hash']);
			$content->SITE->CONTENT = $advdata->ParseData();
			$content->FlushContent(false);
		} else {
			header('Location: ?dashboard');
		}
		break;
	case 'login':
        /** INITIALIZES OUTPUT SETTINGS FOR: LOGIN **/
        if (!$advcore->UserData['Login']) {
            if (isset($_POST['Username']) AND isset($_POST['Password'])) {
                $content->SITE->CMS->Template = 'success';
                $result = array();
                $result[0] = $advcore->HandleUser
                ('Login', array(
                    0 => $content, 
                    1 => $_POST['Username'], 
                    2 => $_POST['Password'] 
                ));    
                echo $result[0];
            } else {
                $content->SITE->DESCRIPTION = 'Login';
                $content->SITE->CMS->Template = 'default.login';
            }
        } else {
            header('Location: ?dashboard');
        };
        break;
	case 'signup':
        /** INITIALIZES OUTPUT SETTINGS FOR: SIGNUP **/
		if (isset($_POST['Username']) AND isset($_POST['Password']) AND isset($_POST['Email'])) {
			$content->SITE->CMS->Template = 'success';
			$result = array();
			$result[0] = $advcore->HandleUser
			('Signup', array( 
				0 => $content, 
				1 => $_POST['Username'], 
				2 => $_POST['Password'], 
				3 => $_POST['Email'] 
			));	
			echo $result[0];
		} else {
			header('Location: ?login');
		};
		break;
	case 'seedbox':
		/** INITIALIZES SEEDBOX **/
		$content->SITE->CMS->Template = 'default.files';
		echo "Here are our files <br>";
		$path = "./seedbox";
		$dh = opendir($path);
		$i=1;
		while (($file = readdir($dh)) !== false) {
			if($file != "." && $file != ".." && $file != "index.php" && $file != ".htaccess" && $file != "error_log" && $file != "cgi-bin") {
				echo "<a href='$path/$file'>$file</a><br /><br />";
				$i++;
			}
		}
		closedir($dh);
		break;
	case 'logout':
		/** INITIALIZES OUTPUT SETTINGS FOR: LOGOUT PAGE **/
		$advcore->DestroySessions();
		header('Location: ?login');
        break;
	case 'dashboard':
    default:
        /** INITIALIZES OUTPUT SETTINGS FOR: HOME PAGE **/
		if ($advcore->UserData['Login']) {
			$content->SITE->DESCRIPTION = 'Home';
			$content->SITE->CMS->About = false;
			$content->SITE->CMS->Features = false;
			$content->SITE->CMS->Template = 'default';
			$content->Initialize('Announcement');
		} else {
			$content->SITE->DESCRIPTION = 'Login';
			$content->SITE->CMS->Template = 'default.login';
		}
        break;
};
$content->FlushContent();
?>