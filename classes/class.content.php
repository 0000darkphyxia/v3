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

class MiracleCMS extends mysqli implements Configurations {
    /** OBJECT ORIENTED VARIABLES **/
    public $SITE, $GUILD, $TOP100, $TOPPPK, $TOPGUILDS, $USER, $PAYPAL, $MYSQL, $ONLINE;
    private $CACHE;

    /** MISC VARIABLES **/
    public $USERSONLINE = 0, $SERVERSONLINE = 0, $ANNOUNCEMENT = null,
        $EXPLORABLEMAPS = null, $MYGUILDS = null, $GUILDS = null, $SERVERMSG = null;

    /** CONSTRUCTOR **/
    public function MiracleCMS() {
        $this->SITE = $this->GUILD 
        = $this->TOP100 = $this->TOPPK
		= $this->TOPGUILDS = $this->USER 
        = $this->PAYPAL = $this->CACHE 
        = $this->ONLINE = new stdClass();
		
        $this->SITE->TITLE = Configurations::ServerName;
        $this->SITE->LATEST = Configurations::ServerNews;
        $this->SITE->DESCRIPTION = Configurations::ServerDescription;

        $this->SITE->DEBUG = new stdClass();
        $this->SITE->DEBUG->MODE = Configurations::DebugMode;
        $this->SITE->DEBUG->MESSAGE = null;
        $this->SITE->DEBUG->TIMESTART = $this->Initialize('MicroTime');

        $this->SITE->CURRENTFILE = null;
        $this->SITE->BACKGROUND = null;
        $this->SITE->SUBTITLE = null;
        $this->SITE->FACEBOOK = null;
        $this->SITE->SWFFILE = null;
        $this->SITE->SWFWIDTH = null;
        $this->SITE->SWFHEIGHT = null;
        $this->SITE->PROFILEPAGE = null;
        $this->SITE->PROFILEINVENTORY = null;
        $this->SITE->PROFILEACHIEVEMENTS = null;
        $this->SITE->PROFILEADDON = null;
        $this->SITE->SPONSOR = null;
        $this->SITE->JSCRIPTS = null;
        $this->SITE->CONTENT = null;		
		$this->SITE->NEWS = null;

        $this->SITE->CMS = new stdClass();
        $this->SITE->CMS->Root = $_SERVER['DOCUMENT_ROOT'] . Configurations::MainRoot;
        $this->SITE->CMS->Style = 'alpha';
        $this->SITE->CMS->Template = 'default';
        $this->SITE->CMS->StickyHeader = false;
		$this->SITE->CMS->About = false;
		$this->SITE->CMS->Features = false;
        $this->SITE->CMS->Cache = false;
        $this->SITE->CMS->PlayOptions = false;
        $this->SITE->CMS->Connection = false;
        $this->SITE->CMS->TotalQuery = 0;
        $this->SITE->CMS->Stats = false;
        $this->SITE->CMS->Slides = null;

        $this->TOP100->LIST = null;
        $this->TOP100->WINNER = null;
		$this->TOP100->ELF = null;
		$this->TOP100->HUMAN = null;
		$this->TOP100->ORC = null;
		$this->TOPPK->LIST2 = null;
		$this->TOPGUILDS->LIST3 = null;
		
		$this->SITE->ELFS = 0;
		$this->SITE->HUMANS = 0;
		$this->SITE->ORCS = 0;

        $this->GUILD->Options = null;
        $this->GUILD->OptionsTitle = null;
        $this->GUILD->Name = null;
        $this->GUILD->FounderName = null;
        $this->GUILD->Description = null;
        $this->GUILD->TotalMembers = null;
        $this->GUILD->PendingMembers = null;
        $this->GUILD->DateCreated = null;
        $this->GUILD->MemberList = null;
        $this->GUILD->PendingList = null;

        $this->USER->LOGGEDIN = null;
		$this->USER->ID = null;
        $this->USER->NAME = null;
		$this->USER->PASSWORD = null;
        $this->USER->EMAIL = null;
        $this->USER->EMAILSTATUS = null;
        $this->USER->EMAILSTATUSCOLOR = null;
        $this->USER->DATECREATED = null;
        $this->USER->LASTACCESS = null;
        $this->USER->UPGEXPIRE = null;
        $this->USER->UPGMESSAGE = null;    
        $this->USER->SESSION = null;
        $this->USER->CURRENTURL = null;
        $this->USER->Golds = 0;
        $this->USER->Coins = 0;

        $this->PAYPAL->SERVER = null;
        $this->PAYPAL->EMAIL = null;
        $this->PAYPAL->LANG = null;
        $this->PAYPAL->CCODE = null;
        $this->PAYPAL->SURL = null;
        $this->PAYPAL->CURL = null;
        $this->PAYPAL->RMETHOD = null;
        
        $this->CACHE->FILE = $_SERVER['DOCUMENT_ROOT'] . Configurations::MainRoot . 'caches/' . md5(session_id() . $_SERVER['REQUEST_URI']);
        $this->CACHE->STATUS = (file_exists($this->CACHE->FILE) AND (time() - mt_rand(40, 60) < filemtime($this->CACHE->FILE))) ? true : false;

        $this->ONLINE->LAST24 = 0;
        $this->ONLINE->TOTAL = 0;
    }

    public function __destructor() {
        if ($this->SITE->CMS->Connection)
            parent::close();
    }

    /** MYSQL IMPROVED EXTENSION (PARENT CLASS) **/
    public function DBase($type, $params = array()) {
        if (!$this->SITE->CMS->Connection)
            SystemExit('No available MySQLi connection', __LINE__, __FILE__);
            
        switch (strtoupper($type)) {
            case 'QUERY':
                if ($Query = parent::query($params[0])) {
                    $this->SITE->CMS->TotalQuery++;
                    return $Query;
                } else
                    SystemExit('MySQLi failed to query: ' . $params[0], __LINE__, __FILE__);
                break;
            case 'PREPARE':
                if ($Query = parent::prepare($params[0])) {
                    $this->SITE->CMS->TotalQuery++;
                    return $Query;
                } else
                    SystemExit('MySQLi failed to prepare: ' . $params[0], __LINE__, __FILE__);
                break;
            case 'ESCAPESTRING':                
                if ($Escape = parent::real_escape_string($params[0]))
                    return $Escape;
                else
                    SystemExit('MySQLi failed to escape: ' . $params[0], __LINE__, __FILE__);                
                break;
        }
    }
	
	/** TIME AGO **/
	public function TimeAgo ($oldTime, $newTime) {
		$timeCalc = strtotime($newTime) - strtotime($oldTime);
		if ($timeCalc >= (60*60*24*30*12*2)){
			$timeCalc = intval($timeCalc/60/60/24/30/12) . " years ago";
			}else if ($timeCalc >= (60*60*24*30*12)){
				$timeCalc = intval($timeCalc/60/60/24/30/12) . " year ago";
			}else if ($timeCalc >= (60*60*24*30*2)){
				$timeCalc = intval($timeCalc/60/60/24/30) . " months ago";
			}else if ($timeCalc >= (60*60*24*30)){
				$timeCalc = intval($timeCalc/60/60/24/30) . " month ago";
			}else if ($timeCalc >= (60*60*24*2)){
				$timeCalc = intval($timeCalc/60/60/24) . " days ago";
			}else if ($timeCalc >= (60*60*24)){
				$timeCalc = " Yesterday";
			}else if ($timeCalc >= (60*60*2)){
				$timeCalc = intval($timeCalc/60/60) . " hours ago";
			}else if ($timeCalc >= (60*60)){
				$timeCalc = intval($timeCalc/60/60) . " hour ago";
			}else if ($timeCalc >= 60*2){
				$timeCalc = intval($timeCalc/60) . " mins ago";
			}else if ($timeCalc >= 60){
				$timeCalc = intval($timeCalc/60) . " min ago";
			}else if ($timeCalc >= 0){
				$timeCalc .= " second(s) ago";
			}
		return $timeCalc;
	}

    /** HANDLES INITIALIZATIONS **/
    public function Initialize($type, $params = null) {
        switch ($type) {
            case 'Connection':
                parent::__construct($this->MYSQL->HOST, $this->MYSQL->USER, $this->MYSQL->PASS, $this->MYSQL->DATA);
                if (mysqli_connect_error())
                    $this->__parseError(mysqli_connect_errno(), mysqli_connect_error());
                else
                    $this->SITE->CMS->Connection = true;

                break;
            case 'MicroTime':
                list($usec, $sec) = explode(" ", microtime());
                return ((float) $usec + (float) $sec);
                break;
            case 'Compress':
                $params[0] = preg_replace("!/\*[^*]*\*+([^/][^*]*\*+)*/!", "", $params[0]);
                break;
            case 'MemoryUsage':
                $memory = memory_get_usage();
                return $memory < 1024 ? $memory . 'B' : ($memory < 1048576 ? round($memory / 1024,2) . 'KB' : round($memory / 1048576,2) . 'MB');
            case 'UsersOnline':
                $rs = $this->DBase('Query',  array( 0 => "SELECT SUM(Count) AS `TOTAL` FROM `servers`" ));
                if ($obj = $rs->fetch_object()) $this->USERSONLINE = $obj->TOTAL;                     
                break;
			case 'Announcement':
                $rs = $this->DBase('Query', array( 0 => "SELECT value FROM `settings_login` WHERE name = 'bNews'" ));
                if ($obj = $rs->fetch_object()) $this->ANNOUNCEMENT = $obj->value;                              
                break;
            case 'ServersOnline':
                $rs = $this->DBase('Query', array( 0 => "SELECT SUM(Online) AS `TOTAL` FROM `servers`" ));
                if ($obj = $rs->fetch_object()) $this->SERVERSONLINE = $obj->TOTAL;                              
                break;
            case 'TopCharacters':
                $rs = $this->DBase('Query', array( 0 => "SELECT character.Name, character.RaceID, character.RaceXP, character.Level, character.Country, guild.Name AS GuildName, character.KillCount, character.DeathCount FROM `users_characters` AS `character` LEFT JOIN `users_characters_guilds` AS `characterguild` ON characterguild.CharacterID = character.CharacterID LEFT JOIN `guilds` AS `guild` ON guild.id = characterguild.GuildID WHERE character.Access < '40' ORDER BY character.Level DESC LIMIT 100" ));
                $c = (int) 0;
				$orc = (int) 0;
				$elf = (int) 0;
				$human = (int) 0;
                while ($obj = $rs->fetch_object()) {
					switch($obj->RaceID){
						case 1:
							$orc++;
							$this->TOP100->ORC = trim((!empty($this->TOP100->ORC))  ? $this->TOP100->ORC : $obj->Name);
							$this->SITE->ORCS = $orc;
							break;
						case 2:
							$human++;
							$this->TOP100->HUMAN = trim((!empty($this->TOP100->HUMAN))  ? $this->TOP100->HUMAN : $obj->Name);
							$this->SITE->HUMANS = $human;
							break;
						case 3:
							$elf++;
							$this->TOP100->ELF = trim((!empty($this->TOP100->ELF))  ? $this->TOP100->ELF : $obj->Name);
							$this->SITE->ELFS = $elf;
							break;
					}
					
                    $c++; $Thropy = null;
					if($c == 1){
						$Thropy = 'rank_1';
					} else if ($c == 2){
						$Thropy = 'rank_2';
					} else if ($c == 3){
						$Thropy = 'rank_3';
					} else if ($c >= 4 && $c <= 10){
						$Thropy = 'rank_4';
					} else if ($c >= 11 && $c <= 20){
						$Thropy = 'rank_5';
					} else if ($c >= 21 && $c <= 30){
						$Thropy = 'rank_6';
					} else if ($c >= 31 && $c <= 40){
						$Thropy = 'rank_7';
					} else if ($c >= 41 && $c <= 50){
						$Thropy = 'rank_8';
					} else if ($c >= 51 && $c <= 60){
						$Thropy = 'rank_9';
					} else {
						$Thropy = 'rank_10';
					};
					$Points = ($obj->KillCount - $obj->DeathCount) * 100;
                    $this->TOP100->LIST .=
                       '<address class="top_div SoulArts">
							<span class="' . $Thropy . ' user_ranking_small SoulArts"></span>	
								<span class="rankingScore">' . $c . '		
							</span>
							<span class="rank_title">' . $obj->Name . '<img style="margin:-2px 0px 0px 3px;" src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/images/flags/' . $obj->Country . '.png"></span>
							<span class="rank_bg">
							<p class="rank_stats">
							<a href="{SITE_CMS_ROOT}?profile=' . $obj->Name . '">View Profile</a> <br><br>
								Level: ' . $obj->Level . '<br>
								Kills: ' . $obj->KillCount . '<br>
								Deaths: ' . $obj->DeathCount . '<br>
							</p>
							<span class="power_orb_ranking orb_fire"></span>
							<span class=" ranking_flag"></span>
						</address>';
                }
                break;
			case 'TopPKCharaters':
                $rs = $this->DBase('Query', array( 0 => "SELECT character.Name, character.Level, guild.Name AS GuildName, character.KillCount, character.DeathCount, characterkill.Bounty, characterkill.Kills FROM `users_characters` AS `character` LEFT JOIN `users_characters_guilds` AS `characterguild` ON characterguild.CharacterID = character.CharacterID LEFT JOIN `guilds` AS `guild` ON guild.id = characterguild.GuildID JOIN `users_characters_pks` AS `characterkill` ON characterkill.CharacterID = character.CharacterID WHERE character.access < 40 ORDER BY characterkill.Kills DESC LIMIT 10" ));
                $d = (int) 0;
                while ($obj = $rs->fetch_object()) {
                    $d++; $Thropy = null;
					$this->TOPPK->WINNER = trim((!empty($this->TOPPK->WINNER))  ? $this->TOPPK->WINNER : $obj->Name);
                    switch ($d) {
					    case 1:
						    $Thropy = '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/img/trophy-icon.png">';
							break;
					    case 2:
						    $Thropy = '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/img/trophy-silver-icon.png">';
							break;
					    case 3:
						    $Thropy = '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/img/trophy-bronze-icon.png">';
							break;
						default:
						    $Thropy = $d;
					}
                    $this->TOPPK->LIST2 .=
                       '<tr>
                            <td><b style="color: #669;">' . $Thropy . '</b></td>
                            <td><span style="color:white;"><a href="?profile=' . $obj->Name . '">' . $obj->Name . '</a></span></td>
							<td><span style="color:white;">' . $obj->GuildName . '</span></td>
							<td><span style="color:white;">' . $obj->Level . '</span></td> 
							<td><span style="color:white;">' . $obj->Kills . '</span></td>
							<td><span style="color:white;">' . $obj->Bounty . '</span></td>
                        </tr>';
                }
                break;
			case 'TopGuilds':
                $rs = $this->DBase('Query', array( 0 => "SELECT guild.id, guild.Name, guild.Level, guild.Wins, guild.Loss, character.Name AS Leader FROM `guilds` AS `guild` JOIN `users_characters_guilds` AS `characterguild` ON characterguild.GuildID = guild.id JOIN `users_characters` AS `character` ON character.CharacterID = characterguild.CharacterID WHERE characterguild.Rank = 3" ));
                $d = (int) 0;
                while ($obj = $rs->fetch_object()) {
                    $d++; $Thropy = null;
					$this->TOPGUILDS->WINNER = trim((!empty($this->TOPGUILDS->WINNER))  ? $this->TOPGUILDS->WINNER : $obj->Name);
                    switch ($d) {
					    case 1:
						    $Thropy = '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/img/trophy-icon.png">';
							break;
					    case 2:
						    $Thropy = '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/img/trophy-silver-icon.png">';
							break;
					    case 3:
						    $Thropy = '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/img/trophy-bronze-icon.png">';
							break;
						default:
						    $Thropy = $d;
					}
					$Points = ($obj->Wins - $obj->Loss) * 100;
                    $this->TOPGUILDS->LIST3 .=
                       '<tr>
                            <td><b style="color: #669;">' . $Thropy . '</b></td>
							<td><a href="?guild=' . $obj->Name . '">' . $obj->Name . '</a></td>
							<td><a href="?profile=' . $obj->Name . '">' . $obj->Leader . '</a></td>
							<td><span style="color:white;">' . $obj->Level . '</span></td>
							<td><span style="color:white;">' . $obj->Wins . '</span></td>
							<td><span style="color:white;">' . $obj->Loss . '</span></td>
							<td><span style="color:white;">' . $Points. '</span></td>
                        </tr>';
                }
                break;
            case 'AvailableAreas':
                $rs = $this->DBase('Query', array( 0 => "SELECT Name, FileName, Extra, monsters_info FROM `meh_maps` ORDER BY `Name` ASC" ));      
        
                while ($obj = $rs->fetch_object()) {
                    $monsters = null;
        
                    if (strtolower($obj->Extra) == 'bstaff') continue;
                    if (!file_exists('gamefiles/maps/' . $obj->FileName)) continue;

                    $data['line'] = explode(",", $obj->monsters_info);
                    for ($i = 0; $i < $count = count($data['line']); $i++) {
                        if (strlen($data['line'][$i]) < 1) continue;
                        $sql = $this->DBase('Query', array( 0 => "SELECT Name FROM `meh_monsters` WHERE id={$data['line'][$i]} GROUP BY `Name`" ));
                        $monster = $sql->fetch_object();
                        $monsters .= ($i != 0 ? ', ' . $monster->Name : $monster->Name);
                    } 

                    $this->EXPLORABLEMAPS .= '
                        <tr>
                            <td><b style="color: #669;">' . $obj->Name . '</b></td>
                            <td>' . ($monsters == null ? '- <font style="color: green;">Safe Zone</font> -' : $monsters) . '</td>
                        </tr>';
                }

                break;
            case 'Guilds':
                $rs = $this->DBase('Query', array( 0 => "SELECT * FROM `guilds` ORDER BY `Name` ASC" ));
                $c = (int) 0;
        
                while ($GUILD = $rs->fetch_object()) {
                    $sqlFounder = $this->DBase('Query', array( 0 => "SELECT Name FROM `users_characters` WHERE id={$GUILD->Founder}" ));
                    $objFounder = $sqlFounder->fetch_object();

                    $guild['founder'] = $objFounder->Name;
                    $guild['members'] = 0;
                    $guild['status'] = null;
                    $guild['status_temp'] = null;
            
                    $data['line'] = explode(",", $GUILD->Members);
            
                    switch ($GUILD->Type) {
                        case 0:
                            $guild['status'] = '<font style="color: red;">Closed</font>';
                            break;
                        case 1:
                            $guild['status'] = '<font style="color: green;">Open</font>';
                            break;
                    }

                    for ($i = 0; $i < $count = count($data['line']); $i++) {
                        if (strlen($data['line'][$i]) < 1) continue;
                        $guild['members']++;
                        if ($data['line'][$i] == $params[0]->UserData['id']) {
                            $guild['status_temp'] = $params[0]->UserData['guild'] == $GUILD->id ? '- <font style="color: green;">Default</font> -' : "<a href='?guildinfo&id={$GUILD->id}&join'>Set as Default</a>";
                            $temp = "<td><a href='?guildinfo&id={$GUILD->id}'><b>{$GUILD->Name}</b></a></td>";
                            $temp .= "<td><a href='?profile&u={$guild['founder']}'>{$guild['founder']}</a></td>";
                            $temp .= "<td>{$guild['members']} Members</td>";          
                            $temp .= "<td>{$guild['status_temp']}</td>";
                            $this->MYGUILDS .= "<tr>{$temp}</tr>";
                        }
                    }

                    $temp ="<td><a href='?guildinfo&id={$GUILD->id}'><b>{$GUILD->Name}</b></a></td>";
                    $temp .= "<td><a href='?profile&u={$guild['founder']}'>{$guild['founder']}</a></td>";
                    $temp .= "<td>{$guild['members']} Members</td>";
                    $temp .= "<td>{$guild['status']}</td>";
                    $this->GUILDS .= "<tr>{$temp}</tr>";
                }
                $this->MYGUILDS = $this->MYGUILDS == null ? '<tr><td colspan="4">You have no guilds.</td></tr>' : '<tr>' . $this->MYGUILDS . '</tr>';
				break;
           case 'News':
                if ($params == null OR !class_exists('BBCodes'))
                    SystemExit('No BBCodes Class Found!', __LINE__, __FILE__);

                $rs = $this->DBase('Query', array( 0 => "SELECT article.PostID, article.Image, article.Subject, article.Content, article.Date, user.Name FROM `articles` AS `article` JOIN `users` AS `user` ON user.id = article.Author ORDER BY article.PostID DESC" ));
                while ($obj = $rs->fetch_object()) {
					
					
					if($obj->Image != null){
						$Image = '<img class="img-responsive" src="styles/' . $this->SITE->CMS->Style . '/img/' . $obj->Image . '"  width="900px" height="300px"/>';
					} else {
						$Image = '';
					}
					
                    /** PARSES BBCODES **/
                    $obj->Content = $params[0]->Format($obj->Content);
					
					/** PARSES TIME **/
					$time = $this->TimeAgo($obj->Date, date("Y-m-d H:i:s"));
					
                    /** PARSES NEWS **/
                    $this->SITE->CONTENT .= '<article class="post hmboxesBGC">
												<div class="avatarContainer"><div class="avatarBorder" style="margin-bottom:4px;"><a href=""></a></div>
														<span class="publishDetails" style="display:block;text-align:left;margin-left:21px;font-size:11px;line-height:16px;">Posted by <a href="http://adventurelands.sytes.net/?profile&u="></a><br />
														<time datetime="3/15/2013 6:15 PM" style="font-size:9px;">'. $time .'</time></span>
													</div>
													<div class="postContent">
														<span class="title"><a href="http://adventurelands.sytes.net/?news&id="></a></span>
														<div style="height:18px;">&nbsp;</div><p></p>
														<div class="readmorecontainer">
														<a href="?news&id=" class="fsprite readmore"><span>Read More</span></a>
														<a href="?news&id=" class="commentLink"><span class="comments sprite"></span>  Comments</a>
													</div>
												</div>
												<div class="clear"></div>
											</article>'; 
					}
                break;
            case 'Sponsor':
                /**
                  * ADVERTISEMENT HIJACKING (ADJACKING)
                  * A method by Anselmus Ricky (th0r.info)
                  * In this version, it requires user session in order to get the last time 
                  * user visit in microtime format for sponsor to be visible or not in given seconds
                **/
                $this->SITE->SPONSOR = $this->GetCMSTemplate('sponsor');
                $seconds = 5; 
                
                if (!isset($_SESSION))
                    session_start();

                $this->SITE->SPONSOR = (Configurations::Advertisements ? ((!isset($_SESSION['adtime']) OR ($time = round($this->Initialize('MicroTime') - $_SESSION['adtime'], 3)) >= $seconds) ?  $this->SITE->SPONSOR . '<iframe src="{SITE_CMS_ROOT}templates/template.sponsor.sub.php" id="sendframe" class="sendframe" frameborder="0" scrolling="no" width="100" height="100" name="sendframe"></iframe>' : null) : null);
                    
                $_SESSION['adtime'] = $this->Initialize('MicroTime');
                break;
            case 'Cache':
                $this->SITE->CMS->Cache = true;
                /** HANDLES CONTENT CACHE **/
                if ($this->CACHE->STATUS) {
                    $SESSION = new stdClass();
                    $SESSION->ID = session_id();
                    $SESSION->TIME = $this->CACHE->STATUS ? date("F d Y H:i:s", filemtime($this->CACHE->FILE)) : 'N/A';header('Content-Type: text/css');
			
			        $EXTENSION = pathinfo(isset($_GET['path']) ? $_GET['path'] : $_SERVER['SCRIPT_NAME'], PATHINFO_EXTENSION);
                    switch (strtolower($EXTENSION)) {
                        case 'css':
                            header('Content-Type: text/css');
                            break;
                        case 'php':
                        case 'html':
                            header('Content-Type: text/html');
                            print "<!--"
                                . "\n Cached Session: {$SESSION->ID}"
                                . "\n Last Modifed: {$SESSION->TIME}"
                                . "\n Script Location: {$_SERVER['REQUEST_URI']}"
                                . "\n-->"
                                . "\n\n";
                            break;
                        default:
                            header('Content-Type: text/plain');
                            break;
                    }

                    print (gzinflate(file_get_contents($this->CACHE->FILE)));
                    exit();
                }
                break;

            case 'Stats':
			/*
                if (!$this->USER->LOGGEDIN) return;			
                $this->SITE->CMS->Stats = true;
                $USERNAME = $this->DBase('EscapeString', array( 0 => $params[0]->UserData['Username'] ));
                $MYSQL_QUERY = $this->DBase('Query', array( 0 => "SELECT * FROM meh_users WHERE Username = '$USERNAME'" ));

                if ($MYSQL_QUERY->num_rows < 1)
                    $this->SendResponse('Character not found!', 2);     
                else {

                    $USER_DATA = $MYSQL_QUERY->fetch_assoc();
                    $this->SITE->PROFILEADDON = 'start=here&intColorHair=' . $USER_DATA['ColorHair'] . '&intColorSkin=' . $USER_DATA['ColorSkin'] . '&intColorEye=' . $USER_DATA['ColorEye'] . '&intColorTrim=' . $USER_DATA['ColorTrim'] . '&intColorBase=' . $USER_DATA['ColorBase'] . '&intColorAccessory=' . $USER_DATA['ColorAccessory'] . '&ia1=8192&strGender=' . $USER_DATA['Gender'] . '&strHairFile=' . $USER_DATA['HairFile'] . '&strHairName=' . $USER_DATA['HairName'] . '&strName=' . $USER_DATA['Username'] . '&intLevel=' . $USER_DATA['Level'];
                    $params[0]->Initialize('UserInventory', array( 0 => $params[0]->Initialize('UserItems', array( 0 => $USER_DATA['id'], 1 => $this )), 1 => $this ));
					
	                $this->USER->Golds = $params[0]->UserData['Gold'];
	                $this->USER->Coins = $params[0]->UserData['Coins'];
                }
                break;*/
        }
    }

    /** COMPRESS DATA **/
    public function Compress($type, $data) {
        switch (strtoupper($type)) {
            case 'STRING':
                $data = preg_replace(base64_decode("IS9cKlteKl0qXCorKFteL11bXipdKlwqKykqLyE="), "", $data);
                $data = str_replace(array("\r\n", "\r", "\n", "\t", "  ", "    ", "    "), "", $data);
                $data = str_replace(base64_decode("Ly8tLT4="), "\n" . base64_decode("Ly8tLT4="), str_replace(base64_decode("PCEtLQ=="), base64_decode("PCEtLQ==") . "\r", $data));
                return trim($data);
                break;
        }
    }

    /** RETRIEVE DEFINED CONTENT TEMPLATE **/
    public function GetCMSTemplate($temp) {
        $template = "{$this->SITE->CMS->Root}styles/{$this->SITE->CMS->Style}/template.{$temp}.html";

        if (!file_exists($template))
            SystemExit('Template not found: ' . $template, __LINE__, __FILE__);

        $data[0][0] = fopen($template, "r");
        $data[0][1] = fread($data[0][0], filesize($template));
        fclose($data[0][0]);

        return $data[0][1];
    }

    /** SEND RESPONSE TO THE CLIENT **/
    public function SendResponse($message, $type = 1, $bbcodes = null, $width = null, $return = false) {
        $width = $width == null ? null : ' style="width:' . $width . 'px"';
        $message = $bbcodes == null ? $message : $bbcodes->Format($message);
        switch ($type) {
            case 1:
                //success
                if ($return) return '<p class="success"' . $width . '>' . $message . '</p>';
                else $this->SERVERMSG .= '<p class="success"' . $width . '>' . $message . '</p>';
                break;
            case 2:
                //error
                if ($return) return '<p class="error"' . $width . '>' . $message . '</p>';
                else $this->SERVERMSG .= '<p class="error"' . $width . '>' . $message . '</p>';
                break;
            case 3:
                //info
                if ($return) return '<p class="info"' . $width . '>' . $message . '</p>';                    
                else $this->SERVERMSG .= '<p class="info"' . $width . '>' . $message . '</p>';                    
                break;
            case 4:
                //notice
                if ($return) return '<p class="notice"' . $width . '>' . $message . '</p>';                    
                else $this->SERVERMSG .= '<p class="notice"' . $width . '>' . $message . '</p>';   
                break;
            default:
                //undefined
                $this->SERVERMSG .= $message;
                break;
        }
    }
	
    public function InitializeVariables($data = null) {
        $replace = array
           (
		    '{SITE_TEMPLATE_HEADER}' => $this->GetCMSTemplate('default.header'),
			'{SITE_TEMPLATE_FOOTER}' => $this->GetCMSTemplate('default.footer'),
			//'{SITE_TEMPLATE_SCRIPTS}' => $this->GetCMSTemplate('default.scripts'),
			'{SITE_TEMPLATE_NAVIGATION}' => $this->GetCMSTemplate('default.navigation.bar'),
			//'{SITE_TEMPLATE_ABOUT}' => $this->SITE->CMS->About ? $this->GetCMSTemplate('default.about') : null,
			//'{SITE_TEMPLATE_FEATURES}' => $this->SITE->CMS->Features ? $this->GetCMSTemplate('default.features') : null,
			//'{SITE_TEMPLATE_DIVIDER1}' => $this->GetCMSTemplate('default.divider1'),
		    '{SITE_TITLE}' => $this->SITE->TITLE,
            '{SITE_BACKGROUND}' => $this->SITE->BACKGROUND,
            '{SITE_CONTENT}' => $this->SITE->CONTENT,
            '{SITE_SUBTITLE}' => $this->SITE->SUBTITLE,
            '{SITE_FACEBOOK}' => $this->SITE->FACEBOOK,
            '{SITE_STICKY_HEADER}' => $this->SITE->CMS->StickyHeader ? $this->GetCMSTemplate('stickyheader') : null,
			'{SITE_CUSTOM_SCRIPTS}' => $this->SITE->JSCRIPTS,
            '{SITE_SWF_FILE}' => $this->SITE->SWFFILE,
            '{SITE_SWF_WIDTH}' => $this->SITE->SWFWIDTH,
            '{SITE_SWF_HEIGHT}' => $this->SITE->SWFHEIGHT,
            '{SITE_SPONSOR}' => $this->SITE->SPONSOR,
            '{SERVER_NAME}' => Configurations::ServerName,
            '{SERVER_NEWS}' => Configurations::ServerNews,
            '{SERVER_DESCRIPTION}' => Configurations::ServerDescription,
            '{SERVER_COMPANY}' => Configurations::ServerCompany,
            '{SERVER_FACEBOOK}' => Configurations::FacebookId,
            '{SERVER_USERSONLINE}' => $this->USERSONLINE,
            '{SERVER_SERVERSONLINE}' => $this->SERVERSONLINE,
			'{SERVER_ANNOUNCEMENT}' => $this->ANNOUNCEMENT,
			'{SERVER_RACE_ELFS}' => $this->SITE->ELFS,
			'{SERVER_RACE_HUMANS}' => $this->SITE->HUMANS,
			'{SERVER_RACE_ORCS}' => $this->SITE->ORCS,
            '{SERVER_EXPLORABLE_MAPS}' => $this->EXPLORABLEMAPS,
            '{SERVER_TOP100_LIST}' => $this->TOP100->LIST,
            '{SERVER_TOP100_WINNER}' => $this->TOP100->WINNER,
			'{SERVER_TOP_HUMAN_WINNER}' => $this->TOP100->HUMAN,
			'{SERVER_TOP_ELF_WINNER}' => $this->TOP100->ELF,
			'{SERVER_TOP_ORC_WINNER}' => $this->TOP100->ORC,
			'{SERVER_TOPPK_LIST}' => $this->TOPPK->LIST2,
			'{SERVER_TOPGUILDS_LIST}' => $this->TOPGUILDS->LIST3,
            '{SERVER_MYGUILDS}' => $this->MYGUILDS,
            '{SERVER_GUILDS}' => $this->GUILDS,
            '{SERVER_GUILD_OPTIONS}' => $this->GUILD->Options == null ? null : $this->GUILD->Options,
            '{SERVER_GUILD_OPTIONSTITLE}' => $this->GUILD->OptionsTitle == null ? null : '
                <span class="PageHeader2">
                        ' . $this->GUILD->OptionsTitle . '<br />
                </span> ',
            '{SERVER_GUILD_NAME}' => $this->GUILD->Name,
            '{SERVER_GUILD_FOUNDER}' => $this->GUILD->FounderName,
            '{SERVER_GUILD_DESCRIPTION}' => $this->GUILD->Description,
            '{SERVER_GUILD_TOTALMEMBERS}' => $this->GUILD->TotalMembers . ' Members',
            '{SERVER_GUILD_TOTALPENDINGMEMBERS}' => $this->GUILD->PendingMembers . ' Users',
            '{SERVER_GUILD_DATECREATED}' => $this->GUILD->DateCreated,
            '{SERVER_MEMBER_LIST}' => $this->GUILD->MemberList,
            '{SERVER_PENDING_LIST}' => $this->GUILD->PendingList,
            '{SERVER_MESSAGE}' => $this->SERVERMSG,
            '{SERVER_OWNER_NAME}' => Configurations::OwnerName,
            '{SERVER_OWNER_EMAIL}' => Configurations::OwnerEmail,
            '{SERVER_PAYPAL_URL}' => $this->PAYPAL->SERVER,
            '{SERVER_PAYPAL_BUSINESS}' => Configurations::PayPalEmail,
            '{SERVER_PAYPAL_LANGUAGE}' => Configurations::PayPalLanguage,
            '{SERVER_PAYPAL_CURRENCY_CODE}' => Configurations::PayPalCCode,
            '{SERVER_PAYPAL_URL_SUCCESS}' => Configurations::PayPalSuccessURL,
            '{SERVER_PAYPAL_URL_CANCEL}' => Configurations::PayPalFailureURL,
            '{SERVER_PAYPAL_RETURN_METHOD}' => Configurations::PayPalRMethod,
            '{SERVER_ONLINE_LAST24}' => $this->ONLINE->LAST24,
            '{SERVER_ONLINE_TOTAL}' =>  null,
            '{SERVER_NOSCRIPT}' => $this->SendResponse('Your browser does not support JavaScript or JavaScript is disabled. You must enable JavaScript or use a JavaScript supported browser for this site to function correctly.', 4, null, null, true),
            '{UDATA_NAME}' => $this->USER->NAME,
            '{UDATA_EMAIL}' => $this->USER->EMAIL,
            '{UDATA_EMAIL_STATUS}' => $this->USER->EMAILSTATUS,
            '{UDATA_EMAIL_STATUS_COLOR}' => $this->USER->EMAILSTATUSCOLOR,
            '{UDATA_DATECREATED}' => $this->USER->DATECREATED,
            '{UDATA_LASTACCESS}' => $this->USER->LASTACCESS,
            '{UDATA_UPGEXPIRE}' => $this->USER->UPGEXPIRE,
            '{UDATA_UPGMSG}' => $this->USER->UPGMESSAGE,
            '{UDATA_SESSION}' => $this->USER->SESSION,
            '{UDATA_CURRENTURL}' => $this->USER->CURRENTURL,
            '{UDATA_STATS}' => ($this->USER->LOGGEDIN AND $this->SITE->CMS->Stats) ? $this->GetCMSTemplate('default.stats') : null,
            '{UDATA_GOLDS}' => $this->USER->Golds,
            '{UDATA_COINS}' => $this->USER->Coins,
            '{SITE_CMS_SLIDES}' => $this->SITE->CMS->Slides,
            '{SITE_CMS_STYLE}' => $this->SITE->CMS->Style,
            '{SITE_CMS_ROOT}' => Configurations::MainRoot,
            '{SITE_CMS_FILE}' => $_SERVER['REQUEST_URI'],
            '{SITE_CMS_TEMPLATE}' => $this->SITE->CMS->Template,
            '{SITE_PROFILE_INVENTORY}' => $this->SITE->PROFILEINVENTORY,
            '{SITE_PROFILE_ACHIEVEMENTS}' => $this->SITE->PROFILEACHIEVEMENTS,
            '{SITE_PROFILE_ADDON}' => $this->SITE->PROFILEADDON,
			'{SITE_NEWS}' => $this->SITE->NEWS,
			'{SITE_DESCRIPTION}' => $this->SITE->DESCRIPTION
			); 
 
        return str_replace(array_keys($replace), array_values($replace), $data);
    }
   
    /** FLUSHES FINAL OUTPUT **/
    public function FlushContent($EnableTemplate = true) {
        /** INITIALIZES CMS TEMPLATE **/
        $data = $EnableTemplate ? $this->GetCMSTemplate($this->SITE->CMS->Template) : '{SITE_CONTENT}';

        //simple image slider mod, you may remove this
        $images = array
            ('<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/images/banner/banner1.jpg" alt="Banner1" width="950" height="328" />',
             '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/images/banner/banner2.png" alt="Banner2" width="950" height="328" />', 
             '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/images/banner/banner3.png" alt="Banner3" width="950" height="328" />', 
             '<img src="{SITE_CMS_ROOT}styles/{SITE_CMS_STYLE}/images/banner/banner4.png" alt="Banner4" width="950" height="328" />');

        shuffle($images);
        $this->SITE->CMS->Slides = 
            '<a href="#" class="show">' . $images[0] . '</a>' .
            '<a href="#">' . $images[1] . '</a>' .
            '<a href="#">' . $images[2] . '</a>';

        /** INITIALIZES TEMPLATE VARIABLES **/
        $data = $this->InitializeVariables($data);
				
        /** DEBUG COMPONENTS **/
        $this->SITE->DEBUG->TIMEEND = $this->Initialize('MicroTime');
        $this->SITE->DEBUG->MESSAGE = $this->SITE->DESCRIPTION . ' loaded in ' . round($this->SITE->DEBUG->TIMEEND - $this->SITE->DEBUG->TIMESTART, 3) . ' seconds';
        $this->SITE->DEBUG->MESSAGE .= ' with ' . $this->SITE->CMS->TotalQuery . ' queries';
        $this->SITE->DEBUG->MESSAGE .= ' using ' . $this->Initialize('MemoryUsage') . ' of Memory';        
        $data = str_replace("{SITE_DEBUG_MESSAGE}", $this->SITE->DEBUG->MODE ? $this->SITE->DEBUG->MESSAGE : null, $data);

        /** INITIALIZES OUTPUT **/
		$OUTPUT = $this->Compress('String', $data);
        /*$OUTPUT = ($EnableTemplate ? "<!--"
            . "\n Advanced " . Configurations::ServerName ." Content Management System (AdvMirEngiCMS)"
            . "\n Build " . Configurations::Build
            . "\n-->"
            . "\n\n" : null) . $this->Compress('String', $data);
           
        /** BEGINS CONTENT CACHING **/
        if ($this->SITE->CMS->Cache) {		   
            $fp = fopen($this->CACHE->FILE, 'w');
            fwrite($fp, gzdeflate($this->Compress('String', $data)));
            fclose($fp);
        }

        /** PRINTS OUTPUT **/
        print $OUTPUT;
        exit();
    }
}
?>