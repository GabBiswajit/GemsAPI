<?php

namespace Biswajit\GemsAPI\Commands;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use Biswajit\GemsAPI\GemsAPI;
use Biswajit\GemsAPI\Utils\Utils;
use pocketmine\command\CommandSender;

class GemsCommand extends Command
{
    public function __construct()
    {
        parent::__construct("gems", "Gems", "/Gems <give/lbalance/take> <player> <amount>");
        $this->setPermission("GemsAPI.cmd.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): mixed
    {
        if ($sender->hasPermission("GemsAPI.cmd.use")) {
            if (!isset($args[0])) {
                $sender->sendMessage("§e/Gems <help/pay> <player> <amount>");
                return false;
            } 
            
               switch ($args[0]) {
                 case "help":
                    $sender->sendMessage("§7Gems Command : §e/addgems\n§7Gems Coammnd : §e/mygems\n§7Gems Command : §e/pay\n§7Gems Coammnd : §e/removegems\n§7Gems Coammnd : §e/gems pay\n§7Gems Command : §e/gems bal\n§7Gems Command : §e/topgems§r");
                    break;
                 case "pay":
                    if (!isset($args[1]) || !isset($args[2])) {
                        $sender->sendMessage("§e/gems §a<help/pay> §e<player> <amount>");
                        return false;
                    }
                    $amount = $args[2];
                    $playerName = $sender->getName();
                    $bal = GemsAPI::getInstance()->getGemsBalance($playerName);
                    if ($amount > $bal) {
                    $sender->sendMessage("§cYou Don't Have §r" . $amount . "§r §cGems To Pay");
                    }else{
                    GemsAPI::getInstance()->takeGemsBalance($playerName, (float) $args[2]);
                    GemsAPI::getInstance()->giveGemsBalance($args[1], (float) $args[2]);
                    $sender->sendMessage("§7Pay§e " . $args[2] . "§7 Gems To §e" . $args[1]);
                    
                    $player = Server::getInstance()->getPlayerExact($args[1]);
                    if ($player instanceof Player) {
                        $player->sendMessage("§7You Got §e " . $args[2] . " §7 Gems");
                     }
                   }
                    break;
                    case "bal":
                    if (!isset($args[1])) {
                        $sender->sendMessage("§e/Gems <bal> <player>");
                        return false;
                    }
                    $sender->sendMessage('§7The Player §e' . $args[1] . '§7 Have : §e$' . GemsAPI::getInstance()->getGemsBalance($args[1]) . "§7 Gems");
                    break;
                default:
                    $sender->sendMessage("§e/Gems <pay/help> <player> <amount>");
                    break;
            }
            return true;
        }
        $sender->sendMessage("§cYou Don't Have Permission");
        return false;
    }
}
