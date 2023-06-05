<?php

declare(strict_types=1);

namespace NhanAZ\QueryServer;

use libpmquery\PMQuery;
use libpmquery\PmQueryException;
use pocketmine\player\Player;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\Server;

class Main extends PluginBase implements Listener {

	public array $players = [];

	public static function qrs($sender, $query): void {
		$keys = [
			"GameName" => "§e>§f GameName:§a ",
			"HostName" => "§e>§f HostName:§r ",
			"Protocol" => "§e>§f Protocol:§a ",
			"Version" => "§e>§f Version:§a ",
			"Players" => "§e>§f Players:§a ",
			"MaxPlayers" => "§e>§f MaxPlayers:§a ",
			"ServerId" => "§e>§f ServerId:§a ",
			"Map" => "§e>§f Map:§a ",
			"GameMode" => "§e>§f GameMode:§a ",
			"NintendoLimited" => "§e>§f NintendoLimited:§a ",
			"IPv4Port" => "§e>§f IPv4Port:§a ",
			"IPv6Port" => "§e>§f IPv6Port:§a ",
			"Extra" => "§e>§f Extra:§a "
		];

		foreach ($keys as $key => $message) {
			$value = $query[$key];
			$output = ($value == null) ? "§cNull!" : $value;
			$sender->sendMessage($message . $output);
		}
	}

	public static function logInfo($status): void {
		$sender = new ConsoleCommandSender(Server::getInstance(), Server::getInstance()->getLanguage());
		$serverInfo = $status->ip . ":" . $status->port;
		if ($status->online) {
			$sender->sendMessage("§e>§f Domain:§a " . str_replace(":", "§f:§a", $serverInfo));

			$fields = [
				"ip" => "IP/Port",
				"debug->ping" => "Ping",
				"debug->query" => "Query",
				"debug->srv" => "SRV",
				"debug->querymismatch" => "QueryMisMatch",
				"debug->ipinsrv" => "IPInSRV",
				"debug->cnameinsrv" => "CNameInSRV",
				"debug->animatedmotd" => "AnimatedMotd",
				"debug->cachetime" => "CacheTime",
				"motd->clean" => "Motd",
				"players->online" => "Online",
				"players->max" => "Max",
				"players->list" => "Players",
				"players->uuid" => "UUIDS",
				"version" => "Version",
				"protocol" => "Protocol",
				"hostname" => "HostName",
				"icon" => "Icon",
				"software" => "SoftWare",
				"map" => "Map",
				"plugins->raw" => "Plugins",
				"mods->raw" => "Mods",
				"info->clean" => "Info"
			];

			foreach ($fields as $field => $label) {
				try {
					$value = $status;
					foreach (explode("->", $field) as $key) {
						$value = $value->$key;
					}
					$output = ($value == null) ? "§cError or has blocked queries!" : $value;
					if (is_array($output)) {
						$output = implode("§f,§a ", $output);
					}
					$sender->sendMessage("§e>§f $label:§a " . $output);
				} catch (\Exception $e) {
					$sender->sendMessage("§e>§f $label:§c Error or has blocked queries!");
				}
			}

			if (strrchr($serverInfo, ":")) {
				$sender->sendMessage("§e>§f Below is the fallback query method:");

				try {
					$address = explode(":", $serverInfo);
					$query = PMQuery::query(host: $address[0], port: (int) $address[1]);
					Main::qrs($sender, $query);
				} catch (PmQueryException $e) {
					$sender->sendMessage("§e>§c The server is offline or has blocked queries!");
					$sender->sendMessage("§e>§f Possible error:§c Your IP does not open the port or the device does not match!");
				}
			}
		} else {
			$sender->sendMessage("§e>§c The server is offline or has blocked queries!");
			$sender->sendMessage("§e>§c Try another query method using §b/querys");
		}
	}

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool {
		if ($cmd->getName() == "query") {
			if (!isset($args[0])) {
				$sender->sendMessage("§e>§c Usage: /query §b<domain/ip:port>§c to query certain server information");
				$sender->sendMessage("§e>§c Example: /query §b0.0.0.0:19132 §cor §b/query goole.com:19132");
				return true;
			}
			if (!$sender instanceof Player) {
				$this->getServer()->getAsyncPool()->submitTask(new QueryTask($args[0]));
			} else {
				$sender->sendMessage("§e>§c Please use the command on the console!");
			}
			return true;
		}
		if ($cmd->getName() == "querys") {
			if ($sender instanceof Player) {
				$sender->sendMessage("§e>§c Please use the command on the console!");
				return true;
			}
			if (!isset($args[0])) {
				$sender->sendMessage("§e>§f Error:§c You have not entered the IP/Domain of the server you want to query!");
				$sender->sendMessage("§e>§c Usage: /querys §b<domain/ip> <port>§c to query certain server information");
				$sender->sendMessage("§e>§c Example: /querys §b0.0.0.0 19132 §cor §b/query goole.com 19132");
				return true;
			}
			if (!isset($args[1])) {
				$sender->sendMessage("§e>§f Error:§c You have not entered the Port of the server you want to query!");
				$sender->sendMessage("§e>§c Usage: /querys §b<domain/ip> <port>§c to query certain server information");
				$sender->sendMessage("§e>§c Example: /querys §b0.0.0.0 19132 §cor §b/query goole.com 19132");
				return true;
			}
			try {
				$query = PMQuery::query($args[0], (int)$args[1]);
				Main::qrs($sender, $query);
			} catch (PmQueryException $e) {
				$sender->sendMessage("§e>§c The server is offline or has blocked queries!");
				$sender->sendMessage("§e>§f Possible error:§c Your IP does not open the port or the device does not match!");
			}
			return true;
		}
		return true;
	}
}
