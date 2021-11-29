<?php

declare(strict_types=1);

namespace NhanAZ\QueryServer;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use NhanAZ\QueryServer\libs\libpmquery\PMQuery;
use NhanAZ\QueryServer\libs\libpmquery\PmQueryException;

class Main extends PluginBase implements Listener
{

	public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool
	{
		if ($cmd->getName() == "query") {
			if (!isset($args[0])) {
				$sender->sendMessage("§e>§c Usage: /query §b<domain/ip:port>§c to query certain server information");
				$sender->sendMessage("§e>§c Example: /query §b0.0.0.0:19132 §cor §b/query goole.com:19132");
				return true;
			}
			if (!$sender instanceof Player) {
				try {
					$status = json_decode(file_get_contents("https://api.mcsrvstat.us/2/" . $args[0]));
				} catch (\Exception $e) {
					$sender->sendMessage("§e>§f Error:§c Your IP does not open the port or the device does not match!");
					$sender->sendMessage("§e>§c Try another query method using §b/querys");
					return true;
				}
				if ($status->online == true) {
					$sender->sendMessage("§e>§f Domain:§a " . str_replace(":", "§f:§a",$args[0]));
					try {
						$sender->sendMessage("§e>§f IP/Port:§a " . $status->ip . "§f:§a" . $status->port);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f IP/Port:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f Ping:§a " . ($status->debug->ping ? "true" : "§cfalse"));
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Ping:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f Query:§a " . ($status->debug->query ? "true" : "§cfalse"));
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Query:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f SRV:§a " . ($status->debug->srv ? "true" : "§cfalse"));
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f SRV:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f QueryMisMatch:§a " . ($status->debug->querymismatch ? "true" : "§cfalse"));
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f QueryMisMatch:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f IPInSRV:§a " . ($status->debug->ipinsrv ? "true" : "§cfalse"));
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f IPInSRV:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f CNameInSRV:§a " . ($status->debug->cnameinsrv ? "true" : "§cfalse"));
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f CNameInSRV:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f AnimatedMotd:§a " . ($status->debug->animatedmotd ? "true" : "§cfalse"));
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f AnimatedMotd:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f CacheTime:§a " . $status->debug->cachetime);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f CacheTime:§c Error or has blocked queries!");
					}
					try {
						foreach ($status->motd->clean as $clean) {
							$sender->sendMessage("§e>§f Motd:§r ". $clean);
						}
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Motd:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f Online/Max:§a " . $status->players->online . "§f/§a" . $status->players->max);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Online/Max:§c Error or has blocked queries!");
					}
					try {
						$list = "§e>§f Players (".count($players->list)."): §a";
						foreach ($players->list as $lists) {
							$list .=  $lists . "§f,§a ";
						}
						$sender->sendMessage($list);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Players:§c Error or has blocked queries!");
					}
					try {
						$uuid = "§e>§f UUIDS (".count($players->uuid)."): §a";
						foreach ($players->uuid as $uuids) {
							$uuid .=  $uuids . "§f,§a ";
						}
						$sender->sendMessage($uuid);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f UUIDS:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f Version:§a " . (($status->version == null) ? "§cNull!" : $status->version));
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Version:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f protocol:§a " . $status->protocol);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Protocol:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f HostName:§a " . $status->hostname);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f HostName:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f Icon:§a " . $status->icon);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Icon:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f SoftWare:§a " . $status->software);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f SoftWare:§c Error or has blocked queries!");
					}
					try {
					$sender->sendMessage("§e>§f Map:§a " . $status->map);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Map:§c Error or has blocked queries!");
					}
					try {
						$plugin = "§e>§f Plugins (".count($status->plugins->raw)."): §a";
						foreach ($status->plugins->raw as $raw) {
							$plugin .= str_replace(" ", " v", $raw) . "§f,§a ";
						}
						$sender->sendMessage($plugin);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Plugins:§c Error or has blocked queries!");
					}
					try {
						$mod = "§e>§f Mods (".count($status->mods->raw)."): §a";
						foreach ($status->mods->raw as $raw) {
							$mod .= $raw . "§f,§a ";
						}
						$sender->sendMessage($mod);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Mods:§c Error or has blocked queries!");
					}
					try {
						$info = "§e>§f Info (".count($status->info->clean)."): §a";
						foreach ($status->info->clean as $clean) {
							$info .= $clean . "§f,§a ";
						}
						$sender->sendMessage($info);
					} catch (\Exception $e) {
						$sender->sendMessage("§e>§f Info:§c Error or has blocked queries!");
					}
					if(strrchr($args[0], ":") == true) {
						$sender->sendMessage("§e>§f Below is the fallback query method:");
						try {
							$IP_Or_Domain_And_Port = explode(":", $args[0]);
							$IP_Or_Domain = $IP_Or_Domain_And_Port[0];
							$Port = $IP_Or_Domain_And_Port[1];
							$query = PMQuery::query($IP_Or_Domain, (int)$Port);
							$sender->sendMessage("§e>§f GameName:§a " . (($query["GameName"] == null) ? "§cNull!" : $query["GameName"]));
							$sender->sendMessage("§e>§f HostName:§r " . (($query["HostName"] == null) ? "§cNull!" : $query["HostName"]));
							$sender->sendMessage("§e>§f Protocol:§a " . (($query["Protocol"] == null) ? "§cNull!" : $query["Protocol"]));
							$sender->sendMessage("§e>§f Version:§a " . (($query["Version"] == null) ? "§cNull!" : $query["Version"]));
							$sender->sendMessage("§e>§f Players:§a " .(($query["Players"] == null) ? "§cNull!" : $query["Players"]));
							$sender->sendMessage("§e>§f MaxPlayers:§a " . (($query["MaxPlayers"] == null) ? "§cNull!" : $query["MaxPlayers"]));
							$sender->sendMessage("§e>§f ServerId:§a " . (($query["ServerId"] == null) ? "§cNull!" : $query["ServerId"]));
							$sender->sendMessage("§e>§f Map:§a " . (($query["Map"] == null) ? "§cNull!" : $query["Map"]));
							$sender->sendMessage("§e>§f GameMode:§a " . (($query["GameMode"] == null) ? "§cNull!" : $query["GameMode"]));
							$sender->sendMessage("§e>§f NintendoLimited:§a " . (($query["NintendoLimited"] == null) ? "§cNull!" : $query["NintendoLimited"]));
							$sender->sendMessage("§e>§f IPv4Port:§a " . (($query["IPv4Port"] == null) ? "§cNull!" : $query["IPv4Port"]));
							$sender->sendMessage("§e>§f IPv6Port:§a " . (($query["IPv6Port"] == null) ? "§cNull!" : $query["IPv6Port"]));
							$sender->sendMessage("§e>§f Extra:§a " . (($query["Extra"] == null) ? "§cNull!" : $query["Extra"]));
						} catch(PmQueryException $e) {
							$sender->sendMessage("§e>§c The server is offline or has blocked queries!");
							$sender->sendMessage("§e>§f Possible error:§c Your IP does not open the port or the device does not match!");
						}
					}
				} else {
					$sender->sendMessage("§e>§c The server is offline or has blocked queries!");
					$sender->sendMessage("§e>§c Try another query method using §b/querys");
				}
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
				$sender->sendMessage("§e>§c Usage: /query §b<domain/ip> <port>§c to query certain server information");
				$sender->sendMessage("§e>§c Example: /query §b0.0.0.0 19132 §cor §b/query goole.com 19132");
				return true;
			}
			if (!isset($args[1])) {
				$sender->sendMessage("§e>§f Error:§c You have not entered the Port of the server you want to query!");
				$sender->sendMessage("§e>§c Usage: /query §b<domain/ip> <port>§c to query certain server information");
				$sender->sendMessage("§e>§c Example: /query §b0.0.0.0 19132 §cor §b/query goole.com 19132");
				return true;
			}
			try {
				$query = PMQuery::query($args[0], (int)$args[1]);
				$sender->sendMessage("§e>§f GameName:§a " . (($query["GameName"] == null) ? "§cNull!" : $query["GameName"]));
				$sender->sendMessage("§e>§f HostName:§r " . (($query["HostName"] == null) ? "§cNull!" : $query["HostName"]));
				$sender->sendMessage("§e>§f Protocol:§a " . (($query["Protocol"] == null) ? "§cNull!" : $query["Protocol"]));
				$sender->sendMessage("§e>§f Version:§a " . (($query["Version"] == null) ? "§cNull!" : $query["Version"]));
				$sender->sendMessage("§e>§f Players:§a " .(($query["Players"] == null) ? "§cNull!" : $query["Players"]));
				$sender->sendMessage("§e>§f MaxPlayers:§a " . (($query["MaxPlayers"] == null) ? "§cNull!" : $query["MaxPlayers"]));
				$sender->sendMessage("§e>§f ServerId:§a " . (($query["ServerId"] == null) ? "§cNull!" : $query["ServerId"]));
				$sender->sendMessage("§e>§f Map:§a " . (($query["Map"] == null) ? "§cNull!" : $query["Map"]));
				$sender->sendMessage("§e>§f GameMode:§a " . (($query["GameMode"] == null) ? "§cNull!" : $query["GameMode"]));
				$sender->sendMessage("§e>§f NintendoLimited:§a " . (($query["NintendoLimited"] == null) ? "§cNull!" : $query["NintendoLimited"]));
				$sender->sendMessage("§e>§f IPv4Port:§a " . (($query["IPv4Port"] == null) ? "§cNull!" : $query["IPv4Port"]));
				$sender->sendMessage("§e>§f IPv6Port:§a " . (($query["IPv6Port"] == null) ? "§cNull!" : $query["IPv6Port"]));
				$sender->sendMessage("§e>§f Extra:§a " . (($query["Extra"] == null) ? "§cNull!" : $query["Extra"]));
			} catch(PmQueryException $e) {
				$sender->sendMessage("§e>§c The server is offline or has blocked queries!");
				$sender->sendMessage("§e>§f Possible error:§c Your IP does not open the port or the device does not match!");
			}
			return true;
		}
	}
}
