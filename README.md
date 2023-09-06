## GemsAPI
A Gems Economy Plugin For Pocketmine 5.0.0...

## Commands
| Name | Description | Usage | Permission |
| ------- | ----------- | ----- | ---------- |
| mygems | Show your gems | `/mygems` | `GemsAPI.cmd.use` |
| pay | Pay others with your balance | `/pay <player: string> <amount: number>`  | `GemsAPI.cmd.use` |
| topgems | View the top player | `/topgems` | `GemsAPI.cmd.use` |
| addgems | Add gems to others balance | `/addgems <player: string> <amount: number>`  | `GemsAPI.add.cmd` |
| removegems | Remove gems from others balance | `/removegems <player: string> <amount: number>`  | `GemsAPI.remove.cmd` |

## tag
- ScoreHud Tag `gems.bal`

## Usage

## Get the gems of a player

```php
use Biswajit\GemsAPI\GemsAPI;
GemsAPI::getInstance()->getGemsBalance($player->getName())
```

## Add the gems of a player

```php
use Biswajit\GemsAPI\GemsAPI;
GemsAPI::getInstance()->giveGemsBalance($player->getName(), (float) $amount);
```

## Reduce the gems of a player

```php
use Biswajit\GemsAPI\GemsAPI;
GemsAPI::getInstance()->takeGemsBalance($player->getName(), (float) $amount);
```

## top gems 

```php
use Biswajit\GemsAPI\GemsAPI;
GemsAPI::getInstance()->getTopPlayerWithGems();
```
