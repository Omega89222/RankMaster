<?php

namespace Omega892\Rank\Events;

use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\Listener;
use pocketmine\player\Player;
use Omega892\Rank\Main;
use pocketmine\Server;

class RankEvents implements Listener {

    public function onChat(PlayerChatEvent $event): void {
        $config = Main::getInstance()->getConfig(); 
        $configRank = Main::$rank;

        $player = $event->getPlayer();
        $name = $player->getName();
        $rank = $config->getNested("{$name}.rank", "Joueur");
        $rankFormat = $configRank->getNested("{$rank}.tag-tchat", "§f[§7Joueur§f]§7 {player} : {message}");

        $message = $event->getMessage();

            $formattedRank = str_replace(
                ["{player}", "{message}"],
                [$name, $message],
                $rankFormat
            );

        $event->cancel();
        Server::getInstance()->broadcastMessage($formattedRank);
    }
}