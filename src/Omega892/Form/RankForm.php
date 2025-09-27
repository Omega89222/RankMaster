<?php

namespace Omega892\Form;

use pocketmine\player\Player;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use Omega892\Main;
use Omega892\RankManager;

class RankForm {
    public static function createRankForm(Player $player): void {
        $name = strtolower($player->getName());
        $config = Main::$config;

        $form = new CustomForm(function (Player $player, ?array $data) use ($config, $name): void {
            if ($data === null) return;
            $rankMaster = RankManager::getInstance(); 
            $rankMaster->createRank($data[0],$data[1], $data[2]);
            $player->sendMessage("§aVous avez créer le grade §e{$data[0]} §a!");
        });
        $form->setTitle("Créer un grade");
        $form->addInput("Nom", "Staff");
        $form->addInput("Nametag (au dessus de la tête", "§a§lStaff");
        $form->addInput("Prefix (dans le chat)", "§a§lStaff");
        $player->sendForm($form);
    }

    public static function deleteRankForm(Player $player): void {}
    public static function setRankForm(Player $player): void {}
    public static function listRankForm(Player $player): void {}

}