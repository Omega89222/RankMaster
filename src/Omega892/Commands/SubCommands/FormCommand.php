<?php

declare(strict_types=1);

namespace Omega892\Commands\SubCommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseCommand;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Omega892\Main;
use Omega892\Form\RankForm;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use Omega892\Form\GlobalForm;

final class FormCommand extends BaseSubCommand {

    public function __construct(private Main $plugin) {
        parent::__construct("menu", "Commandes de RankMaster");
        $this->setPermission("rank.use");
    }

    protected function prepare() : void {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args) : void {
        GlobalForm::globalForm($sender);
    }
}
