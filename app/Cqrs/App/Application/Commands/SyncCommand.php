<?php

namespace App\Cqrs\App\Application\Commands;

abstract class SyncCommand extends Command
{
    abstract public function getResponse(): mixed;
}
