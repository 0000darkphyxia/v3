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

$MySQL = array(
	'HOSTNAME' => 'localhost', 
	'USERNAME' => 'root', 
	'PASSWORD' => 'fWoRlDS2018@@@**IDKFWORLDS', 
	'DATABASE' => 'miraclev1' 
);
mysql_select_db($MySQL['DATABASE'], mysql_connect($MySQL['HOSTNAME'], $MySQL['USERNAME'], $MySQL['PASSWORD'])) 
	or die('Error when connecting to the database, please check your "<i><b>scripts/MySQL_Connector.php</b></i>" to fix this problem.');

// Signup Details
$masterID = mysql_real_escape_string(stripslashes($_POST["intMasterID"]));
$username = mysql_real_escape_string(stripslashes($_POST["strUsername"]));
$password = mysql_real_escape_string(stripslashes($_POST["strPassword"]));
$race = mysql_real_escape_string(stripslashes($_POST["strRace"]));
$pin = mysql_real_escape_string(stripslashes($_POST["strBankPassword"]));
$age = mysql_real_escape_string(stripslashes($_POST["intAge"]));
$dob = mysql_real_escape_string(stripslashes($_POST["strDOB"]));
$email = mysql_real_escape_string(stripslashes($_POST["strEmail"]));
$gender = mysql_real_escape_string(stripslashes($_POST["strGender"]));
$classid = mysql_real_escape_string(stripslashes($_POST["ClassID"]));

// Color Details
$eyecolor = mysql_real_escape_string(stripslashes($_POST["intColorEye"]));
$skincolor = mysql_real_escape_string(stripslashes($_POST["intColorSkin"]));
$haircolor = mysql_real_escape_string(stripslashes($_POST["intColorHair"]));
$trimcolor = "000000";
$basecolor = "000000";
$accessorycolor = "000000";

// Hair Details
$hairid = $_POST['HairID'];

// Ip and Location
$ip = $_SERVER['REMOTE_ADDR'];
$details = json_decode(file_get_contents("http://ipinfo.io/". $ip));
$country = $details -> country;

// Date Time
$dateCreated = date("Y-m-d H:i:s");
$time = date("Y-m-d");

//Checks If Username has been Taken
$sql = mysql_query("SELECT * FROM users_characters WHERE Name = '$username'") or die("status=Error&strReason=" . mysql_error());
if (mysql_num_rows($sql) !=0) {
	die("status=Taken&strReason=The username is already in use by another character.");
} else {
	switch ($hairid) {
		//MALE HAIR
		case 52:
			$hairname = 'Default';
			$hairfile = 'hair/M/Default.swf';
			break;
		case 396:
			$hairname = 'MQElegant';
			$hairfile = 'hair/M/MQElegant.swf';
			break;
		case 55:
			$hairname = 'Goku1';
			$hairfile = 'hair/M/Goku1.swf';
			break;
		case 58:
			$hairname = 'Goku2';
			$hairfile = 'hair/M/Goku2.swf';
			break;
		case 92:
			$hairname = 'Ponytail8';
			$hairfile = 'hair/M/Ponytail8.swf';
			break;
		case 64:
			$hairname = 'Normal2';
			$hairfile = 'hair/M/Normal2.swf';
			break;
		case 349:
			$hairname = 'SuggestedHair';
			$hairfile = 'hair/M/SuggestedHair.swf';
			break;
		case 284:
			$hairname = 'SandBoy';
			$hairfile = 'hair/M/SandBoy.swf';
			break;
		case 383:
			$hairname = 'AQDemonHunter1';
			$hairfile = 'hair/M/AQDemonHunter1.swf';
			break;
		case 390:
			$hairname = 'DFWarStyle';
			$hairfile = 'hair/M/DFWarStyle.swf';
			break;
		case 275:
			$hairname = 'FauxHawk';
			$hairfile = 'hair/M/FauxHawk3.swf';
			break;
		case 398:
			$hairname = 'MQSwift';
			$hairfile = 'hair/M/MQSwift.swf';
			break;
		case 324:
			$hairname = 'Troll3Human';
			$hairfile = 'hair/M/Troll3Human.swf';
			break;	
		//FEMALE HAIR
		case 14:
			$hairname = 'Pig1Bangs1';
			$hairfile = 'hair/F/Pig1Bangs1.swf';
			break;
		case 18:
			$hairname = 'Pig2Bangs2';
			$hairfile = 'hair/F/Pig2Bangs2.swf';
			break;
		case 26:
			$hairname = 'Pony2Bangs2';
			$hairfile = 'hair/F/Pony2Bangs2.swf';
			break;
		case 83:
			$hairname = 'Bangs2Long';
			$hairfile = 'hair/F/Bangs2Long.swf';
			break;
		case 84:
			$hairname = 'Bangs3Long';
			$hairfile = 'hair/F/Bangs3Long.swf';
			break;
		case 285:
			$hairname = 'SandHairGirl';
			$hairfile = 'hair/F/SandHairGirl.swf';
			break;
		case 379:
			$hairname = 'MQGalaxyBuns';
			$hairfile = 'hair/F/MQGalaxyBuns.swf';
			break;
		case 375:
			$hairname = 'AQCasual';
			$hairfile = 'hair/F/AQCasual.swf';
			break;
		case 380:
			$hairname = 'MQRibbon';
			$hairfile = 'hair/F/MQRibbon.swf';
			break;
		case 277:
			$hairname = 'Dragonhawk';
			$hairfile = 'hair/F/Dragonhawk.swf';
			break;
		case 328:
			$hairname = 'TrollFem1HumanFix';
			$hairfile = 'hair/F/TrollFem1HumanFix.swf';
			break;
	}	
	
	//Inserts Character Info into DB
	$sql2 = mysql_query("INSERT INTO `users_characters` (`UserId`, `Name`, `Hash`, `HairID`, `Access`, `Stage`, `SkillPoints`, `RaceID`, `RaceXP`, `ActivationFlag`, `PermamuteFlag`, `Country`, `Age`, `Gender`, `Email`, `Level`, `Copper`, `Silver`, `Gold`, `Coins`, `Exp`, `ColorHair`, `ColorSkin`, `ColorEye`, `ColorBase`, `ColorTrim`, `ColorAccessory`, `SlotsBag`, `SlotsBank`, `SlotsHouse`, `DateCreated`, `LastLogin`, `CpBoostExpire`, `RepBoostExpire`, `GoldBoostExpire`, `ExpBoostExpire`, `UpgradeExpire`, `UpgradeDays`, `Upgraded`, `Achievement`, `Settings`, `Quests`, `Quests2`, `DailyQuests0`, `DailyQuests1`, `DailyQuests2`, `MonthlyQuests0`, `LastArea`, `SpawnPoint`, `CurrentServer`, `HouseInfo`, `KillCount`, `DeathCount`, `Address`, `Language`, `Rebirth`, `Bounty`, `Coordinates`, `Fly`) 
	VALUES ('$masterID', '$username', '$password', '$hairid', '1', 'Adult', '0', '$race', 0, 5, 0, '$country', '$age', '$gender', '$email', 1, 0, 0, 0, 0, 0, '$haircolor', '$skincolor', '$eyecolor', '$basecolor', '$trimcolor', '$accessorycolor', 40, 0, 20, '$dateCreated', '$dateCreated', '$dateCreated', '$dateCreated', '$dateCreated', '$dateCreated', '$dateCreated', '0', '0', '0', '0', '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', '0000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000', 0, 0, 0, 0, 'asylum-1|Enter|Spawn', 'asylum-1|Enter|Spawn', 'Offline', '', 0, 0, '$ip', '', 0, 0, '421|428', '0')")	or die ("status=Error&strReason=Cannot Insert: " . mysql_error());
	
	//Selects New User ID
	$sql3 = mysql_query("SELECT * FROM users_characters WHERE Name = '$username'") or die("status=Error&strReason= Cannot Select Character" . mysql_error());
	$user = mysql_fetch_assoc($sql3) or die("status=Error&strReason=Cannot Fetch" . mysql_error());
	$CharacterID = $user['CharacterID'];
	$Level = $user['Level'];

	//Add's Starting Armor
	switch ($classid) {
		case 2:
			mysql_query("INSERT INTO users_characters_items (CharacterID, ItemID, ElementID, RefineID, Equipped, Quantity, Bank, DatePurchased) VALUES ('". $CharacterID ."', '1', '0', '0', '1', '1', '0', '$dateCreated')");//btnWarrior
			break;
		case 3:
			mysql_query("INSERT INTO users_characters_items (CharacterID, ItemID, ElementID, RefineID, Equipped, Quantity, Bank, DatePurchased) VALUES ('". $CharacterID ."', '1', '0', '0', '1', '1', '0', '$dateCreated')");//btnMage
			break;
		case 4:
			mysql_query("INSERT INTO users_characters_items (CharacterID, ItemID, ElementID, RefineID, Equipped, Quantity, Bank, DatePurchased) VALUES ('". $CharacterID ."', '1', '0', '0', '1', '1', '0', '$dateCreated')");//btnRogue
			break;
		case 5:
			mysql_query("INSERT INTO users_characters_items (CharacterID, ItemID, ElementID, RefineID, Equipped, Quantity, Bank, DatePurchased) VALUES ('". $CharacterID ."', '1', '0', '0', '1', '1', '0', '$dateCreated')");//btnHealer
			break;
	} 

// ADDS DEFAULT SKILL 
$addskill = mysql_query("INSERT INTO users_characters_job_skills (CharacterID, ClassID, SkillID, Slot, Level) VALUES ($CharacterID, '1', '1', '1', '1')");
	
// ADDS USERS FRIEND LIST 
$addfriends = mysql_query("INSERT INTO users_characters_friends (CharacterID, FriendID) VALUES ($CharacterID, '')");

// ADDS USERS BANK
$addbank = mysql_query("INSERT INTO users_characters_banks (CharacterID, Pin, Copper, Silver, Gold, Coins) VALUES ($CharacterID, '$pin', '0', '0', '0', '0')");

// ADDS USERS STATS
$addstats = mysql_query("INSERT INTO users_characters_stats (CharacterID, Points, Strength, Intellect, Endurance, Dexterity, Wisdom, Luck) VALUES ($CharacterID, '3', '0', '0', '0', '0', '0', '0')");

// ADDS USERS VENDING
$addvending = mysql_query("INSERT INTO users_characters_vending (CharacterID, Copper, Silver, Gold, Coins) VALUES ($CharacterID, '0', '0', '0', '0')");
}
//SUCCESS	
echo "status=Success";
?>