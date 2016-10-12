<?php

namespace Project\Tasks;

trait loadTasks
{
    protected function loadSetupProject()
    {
        return new SetupProject();
    }
}
