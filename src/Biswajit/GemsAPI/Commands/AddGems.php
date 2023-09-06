<?php
declare(strict_types=1);

namespace Biswajit\GemsAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use Biswajit\GemsAPI\GemsAPI;
use pocketmine\world\World;

class AddGems extends Command
{
    public function __construct()
    {
        parent::__construct("addgems", "§eAdd Gems For A Player.");
        $this->setPermission("GemsAPI.add.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("GemsAPI.add.cmd")) {
            if (!isset($args[0])) {
                $sender->sendMessage("§e/addgems <player> <amount>");
                return false;
            }
            if($sender instanceof Player)
        {
            if (!isset($args[1])) {
                        $sender->sendMessage("§e/addgems §e<player> <amount>");
                        return false;
                    }
                    GemsAPI::getInstance()->giveGemsBalance($args[0], (float) $args[1]);
                    $sender->sendMessage("§7Added §e" . $args[1] . "§7 Gems In §e" . $args[0]);
                    
                    $player = Server::getInstance()->getPlayerExact($args[0]);
                    if ($player instanceof Player) {
                        $player->sendMessage("§7You Got §e" . $args[1] . "§7 Gems");
                     }
                   }
            return true;
        }
    }
}
