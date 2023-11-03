<?php
/*
    Autor: benhur alencar azevedo
    utilidade: gera o entity manager do doctrine
 */
namespace lib\services;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;

class EntityManagerFactory
{
    public static function getEntityManager(): EntityManager
    {
        $conf = \lib\config\Config::getConfig();

        $rootDir = __DIR__. DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "..";

        $paths = array($rootDir . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . "models");
        $isDevMode = true;
        // Create a simple "default" Doctrine ORM configuration for Attributes
        # $config = ORMSetup::createAttributeMetadataConfiguration(
        #     $paths,
        #     $isDevMode,
        # );
        $config = ORMSetup::createAnnotationMetadataConfiguration(
            $paths,
            $isDevMode,
        );
        switch($conf['db']['driver'])
        {
            case 'pdo_sqlite':
                $connection = DriverManager::getConnection([
                    'driver' => 'pdo_sqlite',
                    'path' => $rootDir . DIRECTORY_SEPARATOR . 'var' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'db.sqlite',
                ], $config);
                break;
        }
        

        return new EntityManager($connection, $config);
    }
}