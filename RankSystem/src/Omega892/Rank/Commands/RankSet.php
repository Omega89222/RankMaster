<?php

namespace Omega892\Rank\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Omega892\Rank\Main;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed; 

class RankSet extends Command {

    public function __construct() {
        parent::__construct("rankset", "Operateur -> Mettre un rank à un joueur", "/rankset");
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Cette commande est réservée aux joueurs !");
            return;
        }
        if (!isset($args[0]) || !isset($args[1])) {
            $sender->sendMessage("§fVous devez executez la commande §e/rankset <pseudo> <rank> §f!");
            return;
        }
        $config = Main::$rank; 
        if(!$config->exists($args[1])){
            $sender->sendMessage("§fCe grade §en'existe pas §f!");
            return;
        }
        $config = Main::getInstance()->getConfig(); 
        $rankPlayer = $config->getNested("{$args[0]}.rank", 0);
        $name = $args[0];
        if ($rankPlayer === $args[1]){
            $sender->sendMessage("§fVous avez déjà le grade §e$rankPlayer §f!");
            return;
        }
        $config->setNested("{$args[0]}.rank", $args[1]);
        $config->save();
        $name = $args[0];
        $rankPlayer = $args[1];
        $configRank = Main::$rank;
        $rankTag = $configRank->getNested("{$rankPlayer}.tag-game", "§f[§7Joueur§f]§7");

        $target = $sender->getServer()->getPlayerExact($args[0]);
        $target->setNameTag("$rankTag $name");

        $configWebhook = Main::$webhook;
        $webhookLink = $configWebhook->get("webhook-link");
        $msg = new Message();
        $webHook = new Webhook($webhookLink);
        $embed = new Embed();
        $embed->setTitle("Log Rank");
        $embed->setColor(0x00FF00);
        $embed->setDescription("**{$sender->getName()}** a mit le grade **{$rankPlayer}** à **{$name}** !");
        $embed->setFooter("RankSystem by Omega892");
        $msg->addEmbed($embed);
        $webHook->send($msg);

        $sender->sendMessage("§fVous avez bien mit le grade §e{$args[1]} §f au joueur §e$name §f!");
    }
}






