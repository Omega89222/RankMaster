<?php

namespace Omega892\Rank\Commands;

use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Omega892\Rank\Main;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;

class Rank extends Command {

    public function __construct() {
        parent::__construct("rank", "Operateur -> Ouvrir le menu des ranks", "/rank", ["ranksystem", "systemrank"]);
        $this->setPermission("rank.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void {
        if (!$sender instanceof Player) {
            $sender->sendMessage("Cette commande est réservée aux joueurs !");
            return;
        }
        $form = new SimpleForm(function (Player $player, $data){
            if(!isset($data)){
                return;
            }
            switch ($data){
                case 0:
                    $this->createRank($player);
                    break;
                case 1:
                    $this->removeRank($player);
                    break;
                case 2:
                    $this->editRank($player);
                    break;
                case 3:
                    $this->helpRank($player);
                    break;
            }
        });
        $form->setTitle("Menu des grades");
        $form->addButton("Créer un grade");
        $form->addButton("Supprimer un grade");
        $form->addButton("Modifier un grade");
        $form->addButton("Aide");
        $sender->sendForm($form);
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
                $player->sendMessage("§fCe grade §en'existe pas §f!");
                return;
            }
            $config->set($rankName);
            $config->set($rankName, [
                         "tag-tchat" => $rankChat,
                         "tag-game" => $rankGame
                         ]);
            $config->save();
            $player->sendMessage("§fVous avez bien créer le grade §e$rankName §f!");
        });
        $form->setTitle("Créer un grade");
        $form->addInput("Nom du grade", "Admin");
        $form->addInput("Tag dans le tchat", "[Admin] {player} {message}");
        $form->addInput("Tag en jeu", "[Admin] {player}");
        $player->sendForm($form);
    }
    public function removeRank(Player $player){
        $form = new CustomForm(function (Player $player, $data){
            if(!isset($data)){
                return;
            }
            $config = Main::$rank; 
            if(!$config->exists($data[0])){
                $player->sendMessage("§fCe grade §en'existe pas §f!");
                return;
            }
            $config->remove($data[0]);
            $config->save();
        });
        $form->setTitle("Supprimer un grade");
        $form->addInput("Nom du grade", "Admin");
        $player->sendForm($form);
    }
    public function helpRank(Player $player){
        $form = new SimpleForm(function (Player $player, $data){
            if(!isset($data)) {
                return;
            }
        });
        $form->setTitle("Aide");
        $form->setContent("Voici toutes les commandes de §eRankSystem §7:\n- §e/rankcreate§f\n- §e/rankremove§f\n- §e/rankedit§f\n- §e/ranksetformat§f\n- §e/ranklist§f\n- §e/rankset§f\n- §e/rank§f\n- §e/rankdebug§f\n\nVous pouvez égalements §emodifier §fles grades dans le fichier config §erank.yml §f.");
        $player->sendForm($form);
    }
    public function editRank(Player $player){
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
            $config->setNested("$rankName.tag-tchat", $rankChat);
            $config->setNested("$rankName.tag-game", $rankGame);
            $config->save();
            $player->sendMessage("§fVous avez bien modifier le grade §e$rankName §f!");
        });
        $form->setTitle("Modifier un grade");
        $form->addInput("Nom du grade", "Admin");
        $form->addInput("Tag dans le tchat", "[Admin] {player} {message}");
        $form->addInput("Tag en jeu", "[Admin] {player}");
        $player->sendForm($form);
    }
}