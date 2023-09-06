<?php

declare(strict_types=1);

namespace Biswajit\GemsAPI\Events;

use pocketmine\event\Event;
use pocketmine\player\Player;

class PlayerGemsBalChangeEvent extends Event{

    public function __construct(
        protected Player $player,
        protected string $balance
    ){}

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function getBalance(): string
    {
        return $this->balance;
    }
}
