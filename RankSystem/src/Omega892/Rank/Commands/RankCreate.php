<?php

namespace Omega892\Rank\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Omega892\Rank\Main;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;
use CortexPE\DiscordWebhookAPI\Message;
use CortexPE\DiscordWebhookAPI\Webhook;
use CortexPE\DiscordWebhookAPI\Embed; 

class RankCreate extends Command {

    public function __construct() {
        parent::__construct("rankcreate", "Operateur -> Créer un rank", "/rankcreate");
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Cette commande est réservée aux joueurs !");
            return;
        }
        $this->createRank($sender);
    }
    public function createRank(Player $player){
        $form = new CustomForm(function (Player $player, $data){
            if(!isset($data)) {
                return;
            }
            $config = Main::$rank;
            $rankName = $data[0];
            $rankChat = $data[1];
            $rankGame = $data[2];
            if($config->exists($rankName)){
                $player->sendMessage("§fCe grade §eexiste déjà §f!");
                return;
            }
            $config->set($rankName);
            $config->set($rankName, [
                         "tag-tchat" => $rankChat,
                         "tag-game" => $rankGame
                         ]);
            $config->save();
            $player->sendMessage("§fVous avez bien créer le grade §e$rankName §f!");

            $configWebhook = Main::$webhook;
            $webhookLink = $configWebhook->get("webhook-link");
            $msg = new Message();
            $webHook = new Webhook($webhookLink);
            $embed = new Embed();
            $embed->setTitle("Log Rank");
            $embed->setColor(0x00FF00);
            $embed->setDescription("**{$player->getName()}** a créer le grade **{$rankPlayer}** !");
            $embed->setFooter("RankSystem by Omega892");
            $msg->addEmbed($embed);
            $webHook->send($msg);
        });
        $form->setTitle("Créer un grade");
        $form->addInput("Nom du grade", "Admin");
        $form->addInput("Tag dans le tchat", "[Admin] {player} {message}");
        $form->addInput("Tag en jeu", "[Admin] {player}");
        $player->sendForm($form);
    }
}






