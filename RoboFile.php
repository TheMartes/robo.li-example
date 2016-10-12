<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
class RoboFile extends \Robo\Tasks
{
    use \Project\Tasks\loadTasks;

    public function projectPrepare()
    {
        $this->loadSetupProject()
            ->run();
    }
}
