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

error_reporting(0);

/** Content Type is XML **/
header("Content-Type: text/xml");

$sql['host'] = 'localhost';
$sql['user'] = 'root';
$sql['pass'] = 'fWoRlDS2018@@@**IDKFWORLDS';
$sql['name'] = 'miraclev1';
	
$con = mysqli_connect($sql['host'], $sql['user'], $sql['pass'], $sql['name']) or die($msg['erro']);
if(isset($_POST['unm'])) {
	$username = $con->real_escape_string(stripslashes($_POST['unm']));
	$password = $con->real_escape_string(stripslashes($_POST['pwd']));
	$ip = $_SERVER['REMOTE_ADDR'];	
	$user_quer = $con->query('SELECT id FROM users WHERE Name="'.$username.'" AND Hash="'.$password.'" LIMIT 1');
	$user_info = $user_quer->fetch_assoc();
	$user_id = $user_info['id'];
		
	$date = date("Y-m-d H:i:s");
	$updateUser = $con->query('UPDATE users SET LastLogin = "'. $date .'" AND IP = "' . $ip . '" WHERE id = "' . $user_id . '"');
	
	if ($user_quer->num_rows === 0) {
		print '<login bSuccess="0" sMsg="BARDY IS BAYOT!"/>';
	} else {
		/** Login Data **/
		print '<login bSuccess="1" userid="'.$user_id.'" sToken="'.$password.'" bCCOnly="0" strCountryCode="PH">';
		
		/** List Servers **/
		$server_info_list = $con->query("SELECT * FROM servers");
		while ($server_info = $server_info_list->fetch_assoc()) {
			print '<servers sName="'. $server_info['Name'] .'" sIP="'. $server_info['IP'] .'" iCount="'. $server_info['Count'] .'" iMax="'. $server_info['Max'] .'" bOnline="'. $server_info['Online'] .'" iChat="'. $server_info['Chat'] .'" bUpg="'. $server_info['Upgrade'] . '" sLang="it" sPort="'. $server_info['Port'] . '"/>';
		}	

		$server_news = $con->query("SELECT * FROM settings_login WHERE name='bNews'");
		while ($server_news_info = $server_news->fetch_assoc()) {
			print '<news bNews="'. $server_news_info['value'] .'" />';
		}				
		
		$getchar = $con->query('SELECT * FROM users_characters WHERE UserID="'.$user_id.'" ORDER BY CharacterID ASC LIMIT 5');
		while ($char = $getchar->fetch_assoc()) {
			$gethair = $con->query('SELECT * FROM hairs WHERE id="'. $char['HairID'] .'"');
			$hair = $gethair->fetch_assoc();
			$getRace = $con->query('SELECT * FROM races WHERE id="'. $char['RaceID'] .'"');
			$race = $getRace->fetch_assoc();
			
			echo '<chars charID="'. $char["CharacterID"] .'" sName="'. $char["Name"] .'" Stage="'. $race["Stage"]. '" Race="'. $race["Name"] .'" Copper="'. $char["Copper"] .'" Silver="'. $char["Silver"] .'" Gold="'. $char["Gold"] .'" Coins="'. $char["Coins"] .'" iLevel="'. $char["Level"] .'" sGender="'. $char["Gender"] .'" intColorSkin="'. $char["ColorSkin"] .'" intColorEye="'. $char["ColorEye"] .'" intColorHair="'. $char["ColorHair"] .'" intColorBase="'. $char["ColorBase"] .'" intColorTrim="'. $char["ColorTrim"] .'" intColorAccessory="'. $char["ColorAccessory"] .'" strHairName="'. $hair["Name"] .'" strHairFilename="'. $hair["File"] .'" LastLogin="'. $char["LastLogin"] .'" DateCreated="'. $char["DateCreated"] .'">';
			
				/** Retrieve currently equipped items info **/
				$getitem = $con->query('SELECT * FROM users_characters_items WHERE Equipped = "1" AND CharacterID="'.$char["CharacterID"].'"');
				while ($item = $getitem->fetch_assoc()) {
					$queryitem = $con->query('SELECT * FROM items WHERE id="' .$item["ItemID"] . '"');//mysql_query(");
					$getinfo = $queryitem->fetch_assoc();
					$check = $queryitem->num_rows;
					
					if($check > 0) {
						echo '<items sES="'. $getinfo["Equipment"] .'" sType="'. $getinfo["Type"] .'" sFile="'. $getinfo["File"] .'" sLink="'. $getinfo["Link"] .'"/>';
					}
				}
				
			echo '</chars>';
		}
		print '</login>';
	}		
} else {
	print '<login bSuccess="0" sMsg="Invalid Input"/>';
}
?>