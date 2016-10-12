<?php

namespace Project\Tasks;

use Robo\Common\TaskIO;
use Robo\Result;
use Robo\Task\BaseTask;
use Robo\Task\Bower\loadTasks as bowerTasks;
use Robo\Task\Composer\loadTasks as composerTasks;
use Robo\Task\Docker\loadTasks as dockerTasks;
use Robo\Task\Testing\loadTasks as testingTasks;

class SetupProject extends BaseTask
{
    use TaskIO;
    use composerTasks;
    use bowerTasks;
    use testingTasks;
    use dockerTasks;

    const COMPOSER_BINARY = '/usr/local/bin/composer';

    protected $steps = 5;
    protected $exitCode = 0;
    protected $errorMessage = '';

    public function progressIndicatorSteps()
    {
        return $this->steps;
    }

    public function composer($composerPath = self::COMPOSER_BINARY)
    {
        if (file_exists(__DIR__.'/vendor')) {
            $this->printTaskInfo('Running Composer Update');
            $this->taskComposerUpdate($composerPath)
                ->optimizeAutoloader()
                ->run();
        } else {
            $this->printTaskInfo('Running Composer Install');
            $this->taskComposerInstall($composerPath)
                ->optimizeAutoloader()
                ->run();
        }

        return $this;
    }

    public function bower()
    {
        $this->taskBowerInstall('bower.json')
            ->noDev()
            ->run();
    }

    public function tests()
    {
        $this->taskPHPUnit()
            ->bootstrap('phpunit.xml.dist')
            ->json()
            ->xml()
            ->run();
    }

    public function run()
    {
        $this->say('Getting ready to setup your wonderfup project');

        $this->composer();
        $this->bower();
        $this->tests();

        $this->say('Setup Complete');

        return new Result(
            $this,
            $this->exitCode,
            $this->errorMessage,
            []
        );
    }
}
