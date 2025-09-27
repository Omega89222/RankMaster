<?php

namespace Omega892\Form;

use pocketmine\player\Player;
use jojoe77777\FormAPI\SimpleForm;
use jojoe77777\FormAPI\CustomForm;
use jojoe77777\FormAPI\ModalForm;
use Omega892\Main;
use Omega892\RankManager;
use Omega892\Form\RankForm;

class GlobalForm
{
    public static function globalForm(Player $player): void
    {
        $name = strtolower($player->getName());
        $config = Main::$config;

        $form = new SimpleForm(function (Player $player, ?int $data) use ($config, $name): void {
            if ($data === null) return;

            switch ($data) {
                case 0:
                    RankForm::createRankForm($player);
                    break;
                case 1:
                    RankForm::deleteRankForm($player);
                    break;
                case 2:
                    RankForm::setRankForm($player);
                    break;
                case 3:
                    RankForm::listRankForm($player);
                    break;
            }
        });

        $form->setTitle("Global Form");
        $form->addButton("Create rank");
        $form->addButton("Delete rank");
        $form->addButton("Set rank");
        $form->addButton("List rank");

        $player->sendForm($form);
    }
}