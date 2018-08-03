<?php

/*
*   _____      _ _ 
*  / ____|    | | |
* | (___   ___| | |
*  \___ \ / _ \ | |
*  ____) |  __/ | |
* |_____/ \___|_|_|
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU Lesser General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*/

namespace SellHand;

use pocketmine\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;
use PiggyCustomEnchants\CustomEnchants\CustomEnchantsId;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat as TF;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{

public $enchantments = [
		0 => "PROTECTION", 
		1 => "FIRE_PROTECTION", 
		2 => "FEATHER_FALLING",
		3 => "BLAST_PROTECTION", 
		4 => "PROJECTILE_PROTECTION", 
		5 => "THORNS",
		6 => "RESPIRATION", 
		7 => "DEPTH_STRIDER",
		8 => "AQUA_AFFINITY",
		9 => "SHARPNESS", 
		10 => "SMITE", 
		11 => "BANE_OF_ARTHROPODS",
		12 => "KNOCKBACK",
		13 => "FIRE_ASPECT",
		14 => "LOOTING",
		15 => "EFFICIENCY",
		16 => "SILK_TOUCH",
		17 => "UNBREAKING",
		18 => "FORTUNE",
		19 => "POWER",
		20 => "PUNCH",
		21 => "FLAME",
		22 => "INFINITY",
		23 => "LUCK_OF_THE_SEA",
		24 => "LURE",
		25 => "FROST_WALKER",
		26 => "MENDING",
	        100 => "LIFESTEAL",
    		101 => "BLIND",
                102 => "DEATHBRINGER",
    		103 => "GOOEY",
    		104 => "POISON",
    		108 => "AUTOREPAIR",
    		109 => "CRIPPLE",
    	        109 => "CRIPPLINGSTRIKE",
   		111 => "VAMPIRE",
    		113 => "CHARGE",
    		114 => "AERIAL",
    		115 => "WITHER",
    		117 => "DISARMING",
    		118 => "SOULBOUND",
    		119 => "HALLUCINATION",
                120 => "BLESSED",
    		121 => "DISARMR",
    		122 => "BACKSTAB",
    		123 => "LIGHTNING",
    		200 => "EXPLOSIVE", //Not accurate
    		201 => "SMELTING",
    		202 => "ENERGIZING",
    		203 => "QUICKENING",
    		204 => "LUMBERJACK",
    		205 => "TELEPATHY",
    		206 => "DRILLER",
    		207 => "HASTE",
    		208 => "FERTILIZER",
    		209 => "FARMER",
    		210 => "HARVEST",
    		211 => "OXYGENATE",
		212 => "JACKPOT",
		301 => "WITHERSKULL",
   	        303 => "PARALYZE",
		304 => "MOLOTOV",
		305 => "VOLLEY",
    		306 => "AUTOAIM",
    		307 => "PIERCING",
    		308 => "SHUFFLE",
    		309 => "BOUNTYHUNTER",
    		310 => "HEALING",
   		311 => "BLAZE",
    		312 => "HEADHUNTER",
    		313 => "GRAPPLING",
    		314 => "PORKIFIED",
    		315 => "MISSILE",
    		316 => "PLACEHOLDER",
    /*//ARMOR
    const MOLTEN = 400;
    const ENLIGHTED = 401;
    const HARDENED = 402;
    const POISONED = 403;
    const FROZEN = 404;
    const OBSIDIANSHIELD = 405;
    const REVULSION = 406;
    const SELFDESTRUCT = 407;
    const CURSED = 408;
    const ENDERSHIFT = 409;
    const DRUNK = 410;
    const BERSERKER = 411;
    const CLOAKING = 412;
    const REVIVE = 413;
    const SHRINK = 414;
    const GROW = 415;
    const CACTUS = 416;
    const ANTIKNOCKBACK = 417;
    const FORCEFIELD = 418;
    const OVERLOAD = 419;
    const ARMORED = 420;
    const TANK = 421;
    const HEAVY = 422;
    const SHIELDED = 423;
    const POISONOUSCLOUD = 424;
    //HELMET
    const IMPLANTS = 600;
    const GLOWING = 601;
    const MEDITATION = 602;
    const FOCUSED = 603;
    const ANTITOXIN = 604;
    //CHESTPLATE
    const PARACHUTE = 800;
    const CHICKEN = 801;
    const PROWL = 802;
    const SPIDER = 803;
    const ENRAGED = 804;
    const VACUUM = 805;
    //BOOTS
    const GEARS = 500;
    const SPRINGS = 501;
    const STOMP = 502;
    const JETPACK = 503;
    const MAGMAWALKER = 504;
    //COMPASS
    const RADAR = 700;
	*/];
	public function onLoad(){
		$this->getLogger()->info("§ePlugin Loading!");
	}

	public function onEnable(){
    	$this->getLogger()->info(TF::GREEN.TF::BOLD."
   _____      _ _ 
  / ____|    | | |
 | (___   ___| | |
  \___ \ / _ \ | |
  ____) |  __/ | |
 |_____/ \___|_|_|
 Enabled Sell by Muqsit and JackMD.
 		");
		$files = array("sell.yml", "messages.yml");
		foreach($files as $file){
			if(!file_exists($this->getDataFolder() . $file)) {
				@mkdir($this->getDataFolder());
				file_put_contents($this->getDataFolder() . $file, $this->getResource($file));
			}
		}
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->sell = new Config($this->getDataFolder() . "sell.yml", Config::YAML);
		$this->messages = new Config($this->getDataFolder() . "messages.yml", Config::YAML);
	}

	public function onDisable(){
    	$this->getLogger()->info("§cPlugin Disabled!");
  	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
		switch(strtolower($cmd->getName())){
			case "sell":
			// Checks if command is executed by console.
			// It further solves the crash problem.
			if(!($sender instanceof Player)){
				$sender->sendMessage(TF::RED . TF::BOLD ."Error: ". TF::RESET . TF::DARK_RED ."Please use this command in game!");
				return true;
				break;
			}

				/* Check if the player is permitted to use the command */
				if($sender->hasPermission("sell") || $sender->hasPermission("sell.hand") || $sender->hasPermission("sell.all")){
					/* Disallow non-survival mode abuse */
					if(!$sender->isSurvival()){
						$sender->sendMessage(TF::RED . TF::BOLD ."§2§lError: ". TF::RESET . TF::DARK_RED ."§cPlease switch back to survival mode.");
						return false;
					}
					
					/* Sell Hand */
					if(isset($args[0]) && strtolower($args[0]) == "hand"){
						if(!$sender->hasPermission("sell.hand")){
							$error_handPermission = $this->messages->get("error-nopermission-sellHand");
							$sender->sendMessage(TF::RED . TF::BOLD . "§2§lError: " . TF::RESET . TF::RED . $error_handPermission);
							return false;
						}
						$item = $sender->getInventory()->getItemInHand();
						$itemId = $item->getId();
						/* Check if the player is holding a block */
						if($item->getId() === 0){
							$sender->sendMessage(TF::DARK_GREEN . TF::BOLD ."§2§lError: ". TF::RESET . TF::DARK_RED ."§6You aren't holding any blocks/items.");
							return false;
						}
						/* Recheck if the item the player is holding is a block */
						if($this->sell->get($itemId) == null){
							$sender->sendMessage(TF::RED . TF::BOLD ."§2§lError: §r§cThe item named ". TF::RESET . TF::DARK_GREEN . $item->getName() . TF::DARK_RED ." §ccannot be sold.");
							return false;
						}
						/* Sell the item in the player's hand */
						EconomyAPI::getInstance()->addMoney($sender, $this->sell->get($itemId) * $item->getCount());
						$sender->getInventory()->removeItem($item);
						$price = $this->sell->get($item->getId()) * $item->getCount();
						$sender->sendMessage(TF::GREEN . TF::GREEN . "§5$" . $price . " §dhas been added to your account.");
						$sender->sendMessage(TF::GREEN . "§bSold for " . TF::RED . "§3$" . $price . TF::GREEN . " §bAmount: §3" . $item->getCount() . " §bName: §3" . $item->getName() . " §bat §3$" . $this->sell->get($itemId) . " §beach.");

					/* Sell All */
					}elseif(isset($args[0]) && strtolower($args[0]) == "all"){
						if(!$sender->hasPermission("sell.all")){
							$error_allPermission = $this->messages->get("error-nopermission-sellAll");
							$sender->sendMessage(TF::RED . TF::BOLD . "§2§lError " . TF::RESET . TF::RED . $error_allPermission);
							return false;
						}
						$items = $sender->getInventory()->getContents();
						foreach($items as $item){
							if($this->sell->get($item->getId()) !== null && $this->sell->get($item->getId()) > 0){
								$price = $this->sell->get($item->getId()) * $item->getCount();
								EconomyAPI::getInstance()->addMoney($sender, $price);
								$sender->sendMessage(TF::GREEN . "§bSold for " . TF::RED . "§3$" . $price . TF::GREEN . " §bAmount: §5" . $item->getCount() . " §bName: §3" . $item->getName() . " §bat §3$" . $this->sell->get($item->getId()) . " §beach.");
								$sender->getInventory()->remove($item);
							}
						}
					}
					if(isset($args[0]) && strtolower($args[0]) == "info"){
					$item = $sender->getInventory()->getItemInHand();
				   $name = $item->getName();
				   $id = $item->getId();
				if ($id === 0) {
					$sender->sendMessage("§cYou aren't holding any items.");
					return false;
				}
				$sender->sendMessage("§bName: §3$name". "\n". "§bId: §3$id". "\n". "§bEnchantments:");
				if ($item->hasEnchantments() == true) {
					foreach($item->getEnchantments() as $enchantments) {
						$enchantmentsid = $enchantments->getId();
						$enchantmentslevel = $enchantments->getLevel();
						$names = $this->enchantments[$enchantmentsid];
						$sender->sendMessage("§3 $names §5$enchantmentslevel");
					}
				} else {
					$sender->sendMessage("§cNone");
				}
			break;
		return true;
					}elseif(isset($args[0]) && strtolower($args[0]) == "about"){
						$sender->sendMessage(TF::RED . TF::RESET . TF::GRAY . "§aThis plugin is Sell Hand, based from Factions, and Prisons.");
					}else{
						$sender->sendMessage(TF::RED . TF::RESET . TF::DARK_RED . "§7[§6Sell §bHelp!§7]");
						$sender->sendMessage(TF::RED . "§5- " . TF::DARK_RED . "§b/sell hand " . TF::GRAY . "- §7Sell the item that's in your hand.");
						$sender->sendMessage(TF::RED . "§5- " . TF::DARK_RED . "§b/sell all " . TF::GRAY . "- §7Sell every possible thing in inventory.");
						$sender->sendMessage(TF::RED . "§5- " . TF::DARK_RED . "§b/sell about " . TF::GRAY . "- §7Plugin information");
						$sender->sendMessage(TF::RED . "§5- " . TF::DARK_RED . "§b/sell info " . TF::GRAY . "- §7Checks for a item information (the one you're holding.)");
						return true;
					}
				}else{
					$error_permission = $this->messages->get("error-permission");
					$sender->sendMessage(TF::RED . TF::BOLD . "§2§lError: " . TF::RESET . TF::RED . $error_permission);
				}
				break;
		}
		return true;
	}
}
