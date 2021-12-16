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

class Core {
    var $UserData = null;
    var $CurrentDate;

    function Core() {
        if (strlen(session_id()) < 1) {
            session_start();
        }
		
        if (isset($_SESSION['udata'])) {
            $this->UserData = $_SESSION['udata'];
            $this->UserData['Login'] = true;
        } else {
            $this->UserData = array();
            $this->UserData['Login'] = false;
        }
		
        $this->UserData['Address'] = trim(isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : $_SERVER['REMOTE_ADDR']);
        $this->UserData['Location'] = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		
        $this->CurrentDate = date("F j, Y, g:i a");
    }

    function Validate($type, $params) {
        switch (strtoupper($type)) {
            case 'USERDATA':
                if ($params[0] != null) {
                    $sql = $params[0]->DBase('Query', array( 0 => "SELECT * FROM `users` WHERE Hash='{$this->UserData['Hash']}'" ));
                    if ($sql->num_rows > 0) {
					    $_SESSION['udata'] = $sql->fetch_assoc();
			            $this->Core();
                    } else {
                        $this->DestroySessions();
                        $this->UserData = null;						
                        return false;
                    }
                }

                return true;
                break;
            case 'USEREMAIL':
                return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $params[0]);
                break;
        }
    }
	
    function HandleUser($type, $params = array()) {
        switch (strtoupper($type)) {
            case 'LOGIN':
                $token = $this->Initialize('UserToken', array( 0 => $params[2], 1 => $params[1] ));
                $sql = $params[0]->DBase('Query', array( 0 => "SELECT * FROM `users` WHERE Name='{$params[1]}' AND Hash='{$params[2]}'" ));//{$token}
				$dtToday = date("Y-m-d H:i:s",time());
                if ($sql->num_rows > 0) {
                    $_SESSION['udata'] = $sql->fetch_assoc();
					$sql2 = $params[0]->DBase('Query', array( 0 => "UPDATE `users` SET LastLogin='{$dtToday}' WHERE Name = '{$params[1]}' AND Hash = '{$params[2]}'"));
					$SessionResponse = "true";

					return $SessionResponse;
                } else {
                    $this->DestroySessions();
                    $params[0]->SendResponse('Invalid Credentials', 2);
					$SessionResponse = "false";
					return $SessionResponse;
                }
                break;
			case 'SIGNUP':
                $sql = $params[0]->DBase('Query', array( 0 => "SELECT * FROM `users` WHERE Name='{$params[1]}'" ));//{$token}
				$dtToday = date("Y-m-d H:i:s",time());
                if ($sql->num_rows > 0) {
					$SessionResponse = "taken";
					return $SessionResponse;
                } else {
					$sql = $params[0]->DBase('Query', array( 0 => "INSERT INTO `users` (`Name`, `Hash`, `Email`, `LastLogin`, `IP`) VALUES ('{$params[1]}', '{$params[2]}', '{$params[3]}', '{$dtToday}', '{$_SERVER['REMOTE_ADDR']}')"));
					$SessionResponse = "true";
					return $SessionResponse;
                }
                break;
            case 'JOINGUILD':
                $sql = $params[1]->DBase('Query', array( 0 => "SELECT * FROM `guilds` WHERE id=" . $params[0] ));
                $params[3] = isset($params[3]) ? (int) $params[1]->DBase('EscapeString', array( 0 => $params[3] )) : $this->UserData['id'];
                if ($sql->num_rows > 0) {
                    $objGuild = $sql->fetch_object();

                    if (!$params[2])
                        $params[1]->DBase('Query', array( 0 => "UPDATE `users` SET GuildID={$objGuild->id} WHERE id=" . $params[3] ));	
				
                    $boolean[1] = false;
                    $boolean[2] = false;

                    if ($objGuild->Type <= 0 AND $params[2]) {
                        $params[1]->SendResponse('This is a special guild, you can\'t join unless you\'re invited by the guild\'s founder.', 3);
                        return;
                    }

                    $members = null;
                    $pending = null;

                    if (!$params[2])
                        $members = $params[3];
                    else 
                        $pending = $params[3];

                    $data['line'] = explode(",", $objGuild->Members);
                    for ($i = 0; $i < $count = count($data['line']); $i++) {
                        if (strlen($data['line'][$i]) < 1) continue;
                        if ($data['line'][$i] == $params[3])
                            continue;
                        $members .= $members == null ? $data['line'][$i] : ',' . $data['line'][$i];
                    }
					
                    $data['line'] = explode(",", $objGuild->Pending);
                    for ($i = 0; $i < $count = count($data['line']); $i++) {
                        if (strlen($data['line'][$i]) < 1) continue;
                        if ($data['line'][$i] == $params[3])
                            continue;
                        $pending .= $pending == null ? $data['line'][$i] : ',' . $data['line'][$i];
                    }

                    $params[1]->DBase('Query',  array( 0 => "UPDATE `guilds` SET Pending='{$pending}' WHERE id=" . $params[0] ));
                    $params[1]->DBase('Query',  array( 0 => "UPDATE `guilds` SET Members='{$members}' WHERE id=" . $params[0] ));
                }
                break;
            case 'RESIGNGUILD':
                $sql = $params[1]->DBase('Query', array( 0 => "SELECT * FROM `guilds` WHERE id=" . $params[0] ));
                $params[3] = isset($params[3]) ? (int) $params[1]->DBase('EscapeString', array( 0 => $params[3] )) : $this->UserData['id'];
                if ($sql->num_rows > 0) {
                    $objGuild = $sql->fetch_object();

                    if (!$params[2])
                        $params[1]->DBase('Query', array( 0 => "UPDATE `users` SET GuildID=0 WHERE id=" . $params[3] ));	
				
                    $boolean[1] = false;
                    $boolean[2] = false;

                    $members = null;
                    $pending = null;

                    if (!$params[2])
                        $members = $params[3];
                    else 
                        $pending = $params[3];

                    $members = $objGuild->Members == $params[3] ? null : str_replace(",{$params[3]}", null, $members);
                    $Pending = $objGuild->Members == $params[3] ? null : str_replace(",{$params[3]}", null, $pending);

                    $params[1]->DBase('Query',  array( 0 => "UPDATE `guilds` SET Pending='{$pending}' WHERE id=" . $params[0] ));
                    $params[1]->DBase('Query',  array( 0 => "UPDATE `guilds` SET Members='{$members}' WHERE id=" . $params[0] ));
                }
                break;
        }
    }

    function Initialize($type, $params = array()) {
        switch (strtoupper($type)) {
            case 'USERDATA':
                $this->UserData = $params[0];
                break;
            case 'USERTOKEN':
                $params[1] = strtolower($params[1]);
                $str = hash("sha512", $params[0] . $params[1]);
                $len = strlen($params[1]);

                return strtoupper(substr($str, $len, 17));
                break;
            case 'USERITEMS':
			
                $ii = (int) 0;
                $xx = (int) 0;
                $strItem = null;
                $yey = null;

                $result = $params[1]->DBase('Query', array( 0 => "SELECT a.id, b.id AS ItemID FROM users_characters_items AS a JOIN items AS b ON b.id = a.ItemID WHERE a.CharacterID = {$params[0]} AND b.Equipment IN ('co','ar','pe','he','ba','Weapon')" ));// AND equipment IN ('co','ar','pe','he','ba','Weapon')
                while ($data = $result->fetch_assoc()) {
                    if ($ii == 0) {
                        $strItem .= $data['ItemID'];
                        $ii++;
                    } else 
                        $strItem .= "," . $data['ItemID'];
                }
                
                $result = $params[1]->DBase('Query', array( 0 => "SELECT id, Equipment, Name, Upgrade, Coins, Type, File, Link FROM items WHERE id IN ($strItem) ORDER BY Equipment ASC" ));
                while ($data = $result->fetch_assoc()) {
                    $subresult = $params[1]->DBase('Query', array( 0 => "SELECT Equipped, Quantity FROM users_characters_items WHERE CharacterID = {$params[0]} AND ItemID = {$data['id']}" ));
                    $subdata = $subresult->fetch_assoc();
					
					
                    $yey[$xx]['id'] = $data['id'];
                    $yey[$xx]['equipment'] = $data['Equipment'];
                    $yey[$xx]['name'] = $data['Name'];
                    $yey[$xx]['rank'] = $data['Equipment'] == "ar" ? $this->Initialize('UserRank', array( 0 => $subdata['Quantity'])) : null;
                    $yey[$xx]['upgrade'] = $data['Upgrade'];
                    $yey[$xx]['coins'] = $data['Coins'];
                    $yey[$xx]['file'] = $data['File'];
                    $yey[$xx]['link'] = $data['Link'];    
                    $yey[$xx]['type'] = strtolower($data['Type']);
                    $yey[$xx]['equipped'] = $subdata['Equipped'] == 1 ? true : false;					
                    $xx++;
					
                } return $yey;
                break;
            case 'USERRANK':
                for ($a = 1; $a < 10; $a++) {
                    $rankExp = (pow(($a + 1), 3) * 100);
                    if ($a > 1)
                        $arrRanks[$a]=($rankExp + $arrRanks[($a - 1)]);
                    else
                        $arrRanks[$a]=($rankExp + 100);            
                }
            
                for ($i = 1; $i < 10; $i++) {
                    if ($arrRanks[$i] >= $params[0]) {
                        if ($params[0] == 302500) return 10;
                        return $i;
                    }
                } return -1;
                break;
            case 'USERINVENTORY':
                $items = $params[0];
				
                $weps = null;
                $armr = null;
                $class = null;

                $equipped = array();
                $equipped['helm'] = array();
                $equipped['pet'] = array();
                $equipped['cape'] = array();
                $equipped['armor'] = array();
                $equipped['class'] = array();
                $equipped['weapon'] = array();

                $equipped['cape']['name'] = null;
                $equipped['helm']['name'] = null;
                $equipped['pet']['name'] = null;
                $equipped['armor']['name'] = null;
                $equipped['class']['name'] = null;
                $equipped['weapon']['name'] = null;

                $equipped['helm']['file'] = 'none';
                $equipped['helm']['link'] = 'none';
                $equipped['pet']['file'] = 'none';
                $equipped['pet']['link'] = 'none';
                $equipped['cape']['file'] = 'none';
                $equipped['cape']['link'] = 'none';
				
                for ($ii = 0; $ii < count($items); $ii++) {
                    if ($items[$ii]['coins'] > 0)
                        $class = 'acItem';
                    else if ($items[$ii]['upgrade'] > 0)
                        $class = 'memItem';
                    else
                        $class = 'normItem';
                    
                    if ($items[$ii]['equipped'] == true) {
                        switch (strtolower($items[$ii]['equipment'])) {
                            case 'ar':
                                if (isset($equipped['class']['true'])) break;
                                $equipped['armor']['name'] = $items[$ii]['name'];
                                $equipped['class']['name'] = $items[$ii]['name'];
                                $equipped['class']['file'] = $items[$ii]['file'];
                                $equipped['class']['link'] = $items[$ii]['link'];
                                break;
                            case 'co':
                                $equipped['armor']['name'] = $items[$ii]['name'];
                                $equipped['class']['file'] = $items[$ii]['file'];
                                $equipped['class']['link'] = $items[$ii]['link'];
                                $equipped['class']['true'] = true;    
                                break;
                            case 'weapon':
								$equipped['weapon']['name'] = $items[$ii]['name'];
								$equipped['weapon']['file'] = $items[$ii]['file'];
								$equipped['weapon']['link'] = $items[$ii]['link'];
								$equipped['weapon']['type'] = $items[$ii]['type'];	
                                break;
                            case 'ba':
                                $equipped['cape']['name'] = $items[$ii]['name'];
                                $equipped['cape']['file'] = $items[$ii]['file'];
                                $equipped['cape']['link'] = $items[$ii]['link'];                    
                                break;
                            case 'pe':
                                $equipped['pet']['name'] = $items[$ii]['name'];
                                $equipped['pet']['file'] = $items[$ii]['file'];
                                $equipped['pet']['link'] = $items[$ii]['link'];                    
                                break;
                            case 'he':
                                $equipped['helm']['name'] = $items[$ii]['name'];
                                $equipped['helm']['file'] = $items[$ii]['file'];
                                $equipped['helm']['link'] = $items[$ii]['link'];                    
                                break;
                        }
                    }
				
            
                    $class .= ' ' . $items[$ii]['type'];
                    if ($items[$ii]['type'] == "class" )        
                        $armr .= "<span class=\"item-row $class\"><a href=\"#\">{$items[$ii]['name']} (Rank {$items[$ii]['rank']})</a></span>\n<br>";
                    else if ($items[$ii]['type'] == "armor" )        
                        $armr .= "<span class=\"item-row $class\"><a href=\"#\">{$items[$ii]['name']}</a></span>\n<br>";
                    else
                        $weps .= "<span class=\"item-row $class\"><a href=\"#\">{$items[$ii]['name']}</a></span>\n<br>";
                
                }
				//$Name = $_GET['profile'];
				//$params[1]->SITE->PROFILEADDON .= "&strClassName={$equipped['class']['name']}&strClassFile={$equipped['class']['file']}&strClassLink={$equipped['class']['link']}&strArmorName={$equipped['armor']['name']}&strWeaponFile={$equipped['weapon']['file']}";
                $params[1]->SITE->PROFILEADDON .= "&strClassName={$equipped['class']['name']}&strClassFile={$equipped['class']['file']}&strClassLink={$equipped['class']['link']}&strArmorName={$equipped['armor']['name']}&strWeaponFile={$equipped['weapon']['file']}&strWeaponLink={$equipped['weapon']['link']}&strWeaponType={$equipped['weapon']['type']}&strWeaponName={$equipped['weapon']['name']}&strCapeFile={$equipped['cape']['file']}&strCapeLink={$equipped['cape']['link']}&strCapeName={$equipped['cape']['name']}&strHelmFile={$equipped['helm']['file']}&strHelmLink={$equipped['helm']['link']}&strHelmName={$equipped['helm']['name']}&strPetFile={$equipped['pet']['file']}&strPetLink={$equipped['pet']['link']}&strPetName={$equipped['pet']['name']}&bgindex=0&strFaction=Evil";
                //return '<p class="left"><span class="subheaderBlack">Items</span><br /><br />' . $weps . '</p><p class="right"><span class="subheaderBlack">Armors</span><br /><br />'.$armr.'</p><div class="clear"></div>'; 
				return '<div class="row" style="min-height:300px;">
						<div class="col-sm-6">
							<div class="col-xs-3">
								<!-- required for floating -->
								<!-- Nav tabs -->
								<ul class="nav nav-tabs tabs-left">
									<li class="active"><a href="#weapon" data-toggle="tab">Weapons</a></li>
									<li><a href="#classes" data-toggle="tab">Classes</a></li>
									<li><a href="#house" data-toggle="tab">Houses</a></li>
								</ul>
							</div>
							<div class="col-xs-9">
								<!-- Tab panes -->
								<div class="tab-content">
									<div class="tab-pane active" id="weapon">' . $weps . '</div>
									<div class="tab-pane" id="classes">'.$armr.'</div>
									<div class="tab-pane" id="house"></div>
								</div>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>';
				break;
            case 'USERACHIEVEMENTS':
                $Achievements = null;
			
                $result[0] = $params[1]->DBase('Query', array( 0 => "SELECT * FROM `achievements` ORDER BY `id` ASC" ));				
                $result[1] = $params[1]->DBase('Query', array( 0 => "SELECT a.AchievementID FROM `users_characters_achievements` AS a JOIN `users_characters` AS b ON b.CharacterID = a.CharacterID WHERE b.Name='{$params[0]}'" ));              
                $data[1] = $result[1]->fetch_assoc();

                while ($data[0] = $result[0]->fetch_assoc()) {				
                    if (strpos($data[1]['AchievementID'], $data[0]['id']) !== false) {
                        $Achievements .= 
                            "<a href='#'  data-toggle='tooltip' title='{$data[0]['Name']} : {$data[0]['Description']}'><img width='98' height='89' src='styles/alpha/images/badges/{$data[0]['Name']}.png'/><span></a>";
                    }
                }

                return ($Achievements != null ?
                    "
                    <div class='achievements'>
                        {$Achievements}<br />
                    </div><br />" : null);
                break;
            default:
                return null;
                break;
        }
    }

    function DestroySessions() {
        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
    }
}

?>