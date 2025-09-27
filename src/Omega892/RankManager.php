<?php

namespace Omega892;

use pocketmine\player\Player;
use pocketmine\utils\Config;
use pocketmine\Server;
use Omega892\DefaultRanks;
use Omega892\Main;

class RankManager {

    private Config $rankConfig;
    private Config $playerConfig;
    private static ?RankManager $instance = null;

    public function __construct(string $dataFolder) {
        @mkdir($dataFolder);
        self::$instance = $this;
        $this->rankConfig = new Config($dataFolder . "ranks.yml", Config::YAML);
        $this->playerConfig = new Config($dataFolder . "players.yml", Config::YAML);

        foreach (DefaultRanks::getRanks() as $name => $data) {
            if (!$this->rankConfig->exists($name)) {
                $this->rankConfig->set($name, $data);
            }
        }

        if (!$this->rankConfig->exists("default_rank")) {
            $this->createRank("default_rank", "§l§7Player", "§l§7Player");
        }

        $this->rankConfig->save();
    }

    public static function getInstance(): ?RankManager {
        return self::$instance;
    }

    public function createRank(string $name, string $nametag, string $prefix): void {
        $config = Main::$config;
        if ($this->rankConfig->exists($name)) {
            return;
        }

        $this->rankConfig->set($name, [
            "nametag" => $nametag,
            "prefix" => $prefix
                               ]);
        $this->rankConfig->save();
        return;
    }

    public function deleteRank(string $name): void {
        $config = Main::$config;
        if (!$this->rankConfig->exists($name)) {
            return;
        }

        $this->rankConfig->remove($name);

        foreach ($this->playerConfig->getAll() as $player => $rank) {
            if ($rank === $name) {
                $this->playerConfig->remove($player);
            }
        }

        $this->playerConfig->save();
        $this->rankConfig->save();
        return;
    }

    public function addRank(Player $player, string $rank): void {
        $config = Main::$config;
        if (!$this->rankConfig->exists($rank)) {
            $player->sendMessage($config->get("rank-no-exist"));
            return;
        }

        $this->playerConfig->set(strtolower($player->getName()), $rank);
        $this->playerConfig->save();

        $rankData = $this->rankConfig->get($rank);
        $nameTag = $rankData["nametag"] ?? "§f" . $player->getName(); 

        $player->setNameTag("{$nameTag} {$player->getName()}");

        return;
    }

    public function removeRank(Player $player): void {
        $this->playerConfig->remove(strtolower($player->getName()));
        $this->playerConfig->save();

        $player->setNameTag($player->getName()); 
    }

    public function getRank(Player $player): ?string {
        $name = strtolower($player->getName());
        return $this->playerConfig->get($name) ?? $this->getDefaultRank();
    }

    public function getRankData(string $rank): ?array {
        return $this->rankConfig->get($rank, null);
    }


    public function getPrefix(Player $player): ?string {
        $rank = $this->getRank($player);
        if ($rank === null) return null;

        $data = $this->getRankData($rank);
        return $data["prefix"] ?? null;
    }

    public function getNametag(Player $player): ?string {
        $rank = $this->getRank($player);
        if ($rank === null) return null;

        $data = $this->getRankData($rank);
        return $data["nametag"] ?? null;
    }

    public function getAllRanks(): array {
        return array_keys($this->rankConfig->getAll());
    }

    public function getPlayersByRank(string $rank): array {
        $players = [];
        foreach ($this->playerConfig->getAll() as $name => $r) {
            if ($r === $rank) {
                $players[] = $name;
            }
        }
        return $players;
    }

    public function setDefaultRank(string $rank): bool {
        if (!$this->rankConfig->exists($rank)) return false;
        $rankData = $this->rankConfig->get($rank);
        if (!is_array($rankData)) return false;
        $this->rankConfig->set("default_rank", $rankData);
        $this->rankConfig->save();
        return true;
    }


    public function getDefaultRank(): ?array {
        $data = $this->rankConfig->get("default_rank");
        return is_array($data) ? $data : null;
    }

    public function addPermissionAtRank(string $rank, string $permission): void {
        if (!$this->rankConfig->exists($rank)) return;
        $permissions = $this->rankConfig->getNested("$rank.permissions") ?? [];
        if (!is_array($permissions)) {
            $permissions = [];
        }
        if (!in_array($permission, $permissions)) {
            $permissions[] = $permission;
        }
        $this->rankConfig->setNested("$rank.permissions", $permissions);
        $this->rankConfig->save();
    }

    public function removePermissionAtRank(string $rank, string $permission): void {
        if (!$this->rankConfig->exists($rank)) return;
        $permissions = $this->rankConfig->getNested("$rank.permissions") ?? [];
        if (!is_array($permissions)) return;
        $permissions = array_filter($permissions, fn($perm) => $perm !== $permission);
        $this->rankConfig->setNested("$rank.permissions", array_values($permissions));
        $this->rankConfig->save();
    }



    public function hasPermission(Player $player, string $permission): bool {
        $rank = strtolower($this->playerConfig->get(strtolower($player->getName())));
        $rankData = $this->rankConfig->get($rank);

        if (!isset($rankData["permissions"]) || !is_array($rankData["permissions"])) {
            return false;
        }

        return in_array($permission, $rankData["permissions"]);
    }
    public function requirePermission(Player $player, string $permission): bool {
        if (!$this->hasPermission($player, $permission)) {
            $player->sendMessage("§cPermission manquante : §e$permission");
            return false;
        }
        return true;
    }
    public function updatePlayerPermissions(Player $player): void {
        static $attachments = [];

        $name = strtolower($player->getName());

        if (isset($attachments[$name])) {
            $player->removeAttachment($attachments[$name]);
            unset($attachments[$name]);
        }

        $attachment = $player->addAttachment(Server::getInstance()->getPluginManager()->getPlugin("RankMaster"));

        $rank = $this->getRank($player);
        $rankData = $this->getRankData($rank);

        if (isset($rankData["permissions"]) && is_array($rankData["permissions"])) {
            foreach ($rankData["permissions"] as $permission) {
                $attachment->setPermission($permission, true);
            }
        }

        $attachments[$name] = $attachment;
    }
}