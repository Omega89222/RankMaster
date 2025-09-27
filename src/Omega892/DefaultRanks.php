<?php

namespace Omega892;

class DefaultRanks {
    
    public static function getRanks(): array {
        return [
            "admin" => [
                "prefix" => "§c§lAdmin§r",
                "nametag" => "§c§lAdmin§r"
            ],
            "moderator" => [
                "prefix" => "§e§lModerator§r",
                "nametag" => "§e§lModerator§r"
            ]
        ];
    }
}