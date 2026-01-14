<?php

namespace App\Core\ORM;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

final class EntityFactory {
    public function create() {
        $config = ORMSetup::createAttributeMetadataConfiguration(paths: [__DIR__ . '/../../models'], isDevMode: $_ENV['DEVMODE']);
        return new EntityManager(DriverManager::getConnection([
            "driver" => "pdo_pgsql",
            "host"     => $_ENV["DB_HOST"],
            "port"     => $_ENV["DB_PORT"],
            "dbname"   => $_ENV["DB_NAME"],
            "user"     => $_ENV["DB_USER"],
            "password" => $_ENV["DB_PASSWORD"],
        ], $config), $config);
    }
}