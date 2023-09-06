<?php
declare(strict_types=1);

namespace Biswajit\GemsAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use Biswajit\GemsAPI\GemsAPI;
use pocketmine\world\World;

class SetGems extends Command
{
    public function __construct()
    {
        parent::__construct("setgems", "§eSet Gems For A Player.");
        $this->setPermission("GemsAPI.Set.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("GemsAPI.set.cmd")) {
            if (!isset($args[0])) {
                $sender->sendMessage("§e/setgems <player> <amount>");
                return false;
            }
            if($sender instanceof Player)
        {
            if (!isset($args[1])) {
                        $sender->sendMessage("§e/setgems §e<player> <amount>");
                        return false;
                    }
                    GemsAPI::getInstance()->setGems($args[0], (float) $args[1]);
                    $sender->sendMessage("§7Set §e" . $args[1] . "§7 Gems For §e" . $args[0]);
                    
                   }
            return true;
        }
    }
}
