<?php
declare(strict_types=1);

namespace Biswajit\GemsAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use Biswajit\GemsAPI\GemsAPI;
use pocketmine\world\World;

class RemoveGems extends Command
{
    public function __construct()
    {
        parent::__construct("removegems", "§eremove Gems For A Player.");
        $this->setPermission("GemsAPI.remove.cmd");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("GemsAPI.remove.cmd")) {
            if (!isset($args[0])) {
                $sender->sendMessage("§e/removegems <player> <amount>");
                return false;
            }
            if($sender instanceof Player)
        {
            if (!isset($args[1])) {
                        $sender->sendMessage("§e/removegems <player> <amount>");
                        return false;
                    }
                    GemsAPI::getInstance()->takeGemsBalance($args[0], (float) $args[1]);
                    $sender->sendMessage("§7Removed §e" . $args[1] . "§7 Gems From §e" . $args[0]);
            }
            return true;
        }
    }
}