<?php

declare(strict_types=1);

namespace Biswajit\GemsAPI\CommandHandler;

use pocketmine\Server;
use Biswajit\GemsAPI\Commands\GemsCommand;
use Biswajit\GemsAPI\Commands\RemoveGems;
use Biswajit\GemsAPI\Commands\AddGems;
use Biswajit\GemsAPI\Commands\MyGems;
use Biswajit\GemsAPI\Commands\SetGems;
use Biswajit\GemsAPI\Commands\TopGems;

final class CommandHandler
{
    public static function initialize(): void
    {
        foreach (self::getCommands() as $key => $value) {
            Server::getInstance()->getCommandMap()->register($key, $value);
        }
    }


    public static function getCommands(): array
    {
        return [
            "gems" => new GemsCommand(),
            "removegems" => new RemoveGems(),
            "addgems" => new AddGems(),
            "mygems" => new MyGems(),
            "setgems" => new SetGems(),
            "topgems" => new TopGems(),
        ];
    }
}
