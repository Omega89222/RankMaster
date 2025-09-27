<?php

namespace Omega892\Events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\player\Player;
use Omega892\RankManager;

class RankEvents implements Listener {

    public function onJoin(PlayerJoinEvent $event): void {
        $player = $event->getPlayer();
        $name = $player->getName();

        $rankPlayer = RankManager::getInstance()->getRank($player);
        $defaultRank = RankManager::getInstance()->getDefaultRank();
        if ($rankPlayer == null) {
            RankManager::getInstance()->addRank($player, "default_rank");
        }
        $prefix = RankManager::getInstance()->getNametag($player) ?? "";
        if ($prefix !== "") {
            $prefix .= " ";
        }
        $player->setNameTag("{$prefix}{$name}");
        RankManager::getInstance()->updatePlayerPermissions($player);
    }

    public function onPlayerChat(PlayerChatEvent $event): void {
        $player = $event->getPlayer();
        $message = $event->getMessage();

        $rankManager = RankManager::getInstance();
        $prefix = $rankManager->getPrefix($player) ?? "";
        if ($prefix !== "") {
            $prefix .= " ";
        }
        $formattedMessage = "§7{$prefix}{$player->getName()} §7» §r{$message}";

        $event->cancel();
        $recipients = $event->getRecipients();
        foreach ($recipients as $recipient) {
            $recipient->sendMessage($formattedMessage);
        }
    }
}