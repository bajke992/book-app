<?php

namespace Bajke\BookBundle\Composer;

use Composer\Script\CommandEvent;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

class ScriptHandler extends \Sensio\Bundle\DistributionBundle\Composer\ScriptHandler {

    public static function createDB(CommandEvent $event){
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];

        $args = array();

        static::executeCommand($event, $appDir, 'doctrine:database:create' . implode(' ', $args));
    }

    public static function updateSchema(CommandEvent $event){
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];

        $args = array();

        if($options['schema-update-force']){
            $args[] = '--force';
        }

        static::executeCommand($event, $appDir, 'doctrine:schema:update' . implode(' ', $args));
    }

    public static function checkEnv(CommandEvent $event){
        $options = self::getOptions($event);
        $appDir = $options['symfony-app-dir'];

        static::executeCheck($event, $appDir);
    }

    protected static function executeCommand(CommandEvent $event, $appDir, $cmd){
        $phpFinder = new PhpExecutableFinder();
        $php = escapeshellarg($phpFinder->find());
        $console = escapeshellarg($appDir.'/console');

        $process = new Process($php.' '.$console.' '.$cmd);

        $process->run(function ($type, $buffer) use($event) { $event->getIO()->write($buffer, false); });
    }

    protected static function executeCheck(CommandEvent $event, $appDir){
        $phpFinder = new PhpExecutableFinder();
        $php = escapeshellarg($phpFinder->find());
        $check = escapeshellarg($appDir.'/check.php');

        $process = new Process($php.' '.$check);

        $process->run(function ($type, $buffer) use($event) { $event->getIO()->write($buffer, false); });
    }

    protected static function getOptions(CommandEvent $event) {
        $options = array_merge(array(
            'schema-update-force' => true,
        ), $event->getComposer()->getPackage()->getExtra());
        return $options;
    }

}