<?php

namespace Accompli\Console;

use Accompli\Accompli;
use Accompli\Console\Command\DeployReleaseCommand;
use Accompli\Console\Command\InitCommand;
use Accompli\Console\Command\InstallReleaseCommand;
use Symfony\Component\Console\Application as BaseApplication;

/**
 * Application.
 *
 * @author Niels Nijens <nijens.niels@gmail.com>
 */
class Application extends BaseApplication
{
    /**
     * Constructs a new Application instance.
     */
    public function __construct()
    {
        parent::__construct('Accompli', Accompli::VERSION);
    }

    /**
     * Returns the help message.
     *
     * @return string
     */
    public function getHelp()
    {
        return Accompli::LOGO.parent::getHelp();
    }

    /**
     * Returns the array with default commands.
     *
     * @return array
     */
    protected function getDefaultCommands()
    {
        $commands = parent::getDefaultCommands();
        $commands[] = new InitCommand();
        $commands[] = new InstallReleaseCommand();
        $commands[] = new DeployReleaseCommand();

        return $commands;
    }
}
