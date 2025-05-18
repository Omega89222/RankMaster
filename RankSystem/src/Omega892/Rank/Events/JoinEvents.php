<?php

namespace Omega892\Rank\Events;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use Omega892\Rank\Main;

class JoinEvents implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();
        $config = Main::getInstance()->getConfig(); 
        if(!$config->exists($player->getName())){
            $config->set($player->getName());
            $config->set($name, [
                         "rank" => "Joueur",
                         ]);
            $config->save();
        }
        $playerName = $player->getName();
        $rankName = $config->getNested("$playerName.rank", "Joueur");
        $configRank = Main::$rank;
        $rankTag = $configRank->getNested("$rankName.tag-game", "§7[Joueur] ");

        $player->setNameTag("$rankTag $playerName");
    }
}