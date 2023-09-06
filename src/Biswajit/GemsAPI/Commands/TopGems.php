<?php

namespace Biswajit\GemsAPI\Commands;

use pocketmine\Server;
use pocketmine\player\Player;
use pocketmine\command\Command;
use Biswajit\GemsAPI\GemsAPI;
use pocketmine\command\CommandSender;

class TopGems extends Command
{
    public function __construct()
    {
        parent::__construct("topgems", "/topgems");
        $this->setPermission("GemsAPI.cmd.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): mixed
    {
    	 if($sender instanceof Player)
        {
         $topPlayer = GemsAPI::getInstance()->getTopPlayerWithGems();
        $sender->sendMessage("Player with the most gems: " . $topPlayer);
      
        return true;
     }
    return false;
  }
}