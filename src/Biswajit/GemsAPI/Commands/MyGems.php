<?php

declare(strict_types=1);

namespace Biswajit\GemsAPI\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;
use Biswajit\GemsAPI\GemsAPI;
use pocketmine\world\World;

class MyGems extends Command
{
    public function __construct()
    {
        parent::__construct("mygems", "§eCheck Your Gems");
        $this->setPermission("GemsAPI.cmd.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player)
        {
            $playerName = $sender->getName();
            $sender->sendMessage('§7You Have : §e' . GemsAPI::getInstance()->getGemsBalance($playerName) . "§7 Gems");
            return true;
        }
    }
}
