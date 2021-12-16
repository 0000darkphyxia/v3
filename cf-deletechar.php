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
		
$sql['host'] = 'localhost';
$sql['user'] = 'root';
$sql['pass'] = 'fWoRlDS2018@@@**IDKFWORLDS';
$sql['name'] = 'miraclev1';
	
$con = mysqli_connect($sql['host'], $sql['user'], $sql['pass'], $sql['name']) or die($msg['erro']);
	
/** Retrieve POST data **/
$Username = $_POST['Username'];			//Username
$Password = $_POST['Password'];			//Password
$CharacterID = $_POST['CharacterID'];	//Character ID

/** Check user and pass to ensure no tampering happened **/
$user_quer = $con->query('SELECT * FROM users WHERE Name="'. $Username .'" AND Hash="'. $Password .'" LIMIT 1');
$user_info = $user_quer->fetch_assoc();
$UserID = $user_info['id'];
$Rows = $user_quer -> num_rows;

/** If character exists being deletion **/
if($Rows > 0) {
	$selectCharacter = $con->query('SELECT * FROM users_characters WHERE UserId = "'. $UserID .'" AND CharacterID = "'. $CharacterID .'"');
	$user_data = $selectCharacter->fetch_assoc();
	$CharacterID = $user_data['CharacterID'];
	$UserID = $user_data['UserId'];
	$Name = $user_data['Name'];
	$Hash = $user_data['Hash'];
	$HairID = $user_data['HairID'];
	$Access = $user_data['Access'];
	$Stage = $user_data['Stage'];
	$SkillPoints = $user_data['SkillPoints'];
	$RaceID = $user_data['RaceID'];
	$RaceXP = $user_data['RaceXP'];
	$ActivationFlag = $user_data['ActivationFlag'];
	$PermamuteFlag = $user_data['PermamuteFlag'];
	$Country = $user_data['Country'];
	$Age = $user_data['Age'];
	$Gender = $user_data['Gender'];
	$Email = $user_data['Email'];
	$Level = $user_data['Level'];
	$Copper = $user_data['Copper'];
	$Silver = $user_data['Silver'];
	$Gold = $user_data['Gold'];
	$Coins = $user_data['Coins'];
	$Exp = $user_data['Exp'];
	$ColorHair = $user_data['ColorHair'];
	$ColorSkin = $user_data['ColorSkin'];
	$ColorEye = $user_data['ColorEye'];
	$ColorBase = $user_data['ColorBase'];
	$ColorTrim = $user_data['ColorTrim'];
	$ColorAccessory = $user_data['ColorAccessory'];
	$SlotsBag = $user_data['SlotsBag'];
	$SlotsBank = $user_data['SlotsBank'];
	$SlotsHouse = $user_data['SlotsHouse'];
	$DateCreated = $user_data['DateCreated'];
	$LastLogin = $user_data['LastLogin'];
	$CpBoostExpire = $user_data['CpBoostExpire'];
	$RepBoostExpire = $user_data['RepBoostExpire'];
	$GoldBoostExpire = $user_data['GoldBoostExpire'];
	$ExpBoostExpire = $user_data['ExpBoostExpire'];
	$UpgradeExpire = $user_data['UpgradeExpire'];
	$UpgradeDays = $user_data['UpgradeDays'];
	$Upgraded = $user_data['Upgraded'];
	$Achievement = $user_data['Achievement'];
	$Settings = $user_data['Settings'];
	$Quests = $user_data['Quests'];
	$Quests2 = $user_data['Quests2'];
	$DailyQuests0 = $user_data['DailyQuests0'];
	$DailyQuests1 = $user_data['DailyQuests1'];
	$DailyQuests2 = $user_data['DailyQuests2'];
	$MonthlyQuests0 = $user_data['MonthlyQuests0'];
	$LastArea = $user_data['LastArea'];
	$SpawnPoint = $user_data['SpawnPoint'];
	$CurrentServer = $user_data['CurrentServer'];
	$HouseInfo = $user_data['HouseInfo'];
	$KillCount = $user_data['KillCount'];
	$DeathCount = $user_data['DeathCount'];
	$Address = $user_data['Address'];
	$Language = $user_data['Language'];
	$Rebirth = $user_data['Rebirth'];
	$Bounty = $user_data['Bounty'];
	$Coordinates = $user_data['Coordinates'];
	$Fly = $user_data['Fly'];
	
	$backupCharacter = $con->query("INSERT INTO `users_characters_history` (`CharacterID`, `UserId`, `Name`, `Hash`, `HairID`, `Access`, `Stage`, `SkillPoints`, `RaceID`, `RaceXP`, `ActivationFlag`, `PermamuteFlag`, `Country`, `Age`, `Gender`, `Email`, `Level`, `Copper`, `Silver`, `Gold`, `Coins`, `Exp`, `ColorHair`, `ColorSkin`, `ColorEye`, `ColorBase`, `ColorTrim`, `ColorAccessory`, `SlotsBag`, `SlotsBank`, `SlotsHouse`, `DateCreated`, `LastLogin`, `CpBoostExpire`, `RepBoostExpire`, `GoldBoostExpire`, `ExpBoostExpire`, `UpgradeExpire`, `UpgradeDays`, `Upgraded`, `Achievement`, `Settings`, `Quests`, `Quests2`, `DailyQuests0`, `DailyQuests1`, `DailyQuests2`, `MonthlyQuests0`, `LastArea`, `SpawnPoint`, `CurrentServer`, `HouseInfo`, `KillCount`, `DeathCount`, `Address`, `Language`, `Rebirth`, `Bounty`, `Coordinates`, `Fly`) 
	VALUES ('" . $CharacterID . "', '" . $UserID . "', '" . $Name ."', '" . $Hash . "', '" . $HairID . "', '" . $Access . "', '" . $Stage . "', '" . $SkillPoints . "', '" . $RaceID . "', '" . $RaceXP . "', '" . $ActivationFlag . "', '" . $PermamuteFlag . "', '" . $Country . "', '" . $Age . "', '" . $Gender ."', '" . $Email . "', '" . $Level . "', '" . $Copper . "', '" . $Silver . "', '" . $Gold . "', '" . $Coins . "', '" . $Exp . "', '" . $ColorHair . "', '" . $ColorSkin . "',  '" . $ColorEye . "', '" . $ColorBase . "', '" . $ColorTrim . "', '" . $ColorAccessory . "', '" . $SlotsBag . "', '" . $SlotsBank . "','" . $SlotsHouse . "', '" . $DateCreated . "', '" . $LastLogin . "', '" . $CpBoostExpire . "', '" . $RepBoostExpire . "', '" . $GoldBoostExpire . "', '" . $ExpBoostExpire . "', '" . $UpgradeExpire . "', '" . $UpgradeDays . "', '" . $Upgraded ."', '" . $Achievement . "', '" . $Settings . "', '" . $Quests . "', '" . $Quests2 . "', '" . $DailyQuests0 . "', '" . $DailyQuests1 . "', '" . $DailyQuests2 . "', '" . $MonthlyQuests0 . "', '" . $LastArea . "', '" . $SpawnPoint . "', '" . $CurrentServer . "', '" . $HouseInfo . "', '" . $KillCount . "', '" . $DeathCount . "', '" . $Address . "', '" . $Language . "', '" . $Rebirth . "', '" . $Bounty ."', '" . $Coordinates ."', '" . $Fly ."')") or die($con->error);
	if ($selectCharacter->num_rows != 0) {
		//$deleteFactions = $con->query('DELETE FROM userss_characters_factions WHERE CharacterID = "' . $CharacterID .'"');
		//$deleteItems = $con->query('DELETE FROM users_characters_items WHERE CharacterID = "' . $CharacterID .'"');
		//$deleteFriends = $con->query('DELETE FROM users_characters_friends WHERE CharacterID = "' . $CharacterID .'"');
		$deleteCharacter = $con->query('DELETE FROM users_characters WHERE CharacterID = "' . $CharacterID . '"');
		//$deleteGuild = $con->query('DELETE FROM users_characters_guilds WHERE CharacterID = "' . $CharacterID .'"');
		//$deleteStats = $con->query('DELETE FROM users_characters_stats WHERE CharacterID = "' . $CharacterID .'"');
		//$deleteBank = $con->query('DELETE FROM users_characters_banks WHERE CharacterID = "' . $CharacterID .'"');
		//$deleteVending = $con->query('DELETE FROM users_characters_vending WHERE CharacterID = "' . $CharacterID .'"');
	} else {
		echo 'Invalid Username and Password.';
	}
}	
?>