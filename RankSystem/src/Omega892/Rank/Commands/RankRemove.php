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

class RankRemove extends Command {

    public function __construct() {
        parent::__construct("rankremove", "Operateur -> Supprimer un rank", "/rankremove");
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Cette commande est réservée aux joueurs !");
            return;
        }
        if (!isset($args[0])) {
            $sender->sendMessage("§fVous devez executez la commande §e/rankremove §f!");
            return;
        }
        $config = Main::$rank; 
        if(!$config->exists($args[0])){
            $sender->sendMessage("§fCe grade §en'existe pas §f!");
            return;
        }
        $config->remove($args[0]);
        $config->save();
        $sender->sendMessage("§fVous avez bien supprimer le grade §e{$args[0]} §f!");

        $configWebhook = Main::$webhook;
        $webhookLink = $configWebhook->get("webhook-link");
        $msg = new Message();
        $webHook = new Webhook($webhookLink);
        $embed = new Embed();
        $embed->setTitle("Log Rank");
        $embed->setColor(0x00FF00);
        $embed->setDescription("**{$sender->getName()}** a supprimé le grade **{$args[0]}** !");
        $embed->setFooter("RankSystem by Omega892");
        $msg->addEmbed($embed);
        $webHook->send($msg);
    }
}






