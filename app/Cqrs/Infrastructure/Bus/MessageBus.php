<?php

namespace App\Cqrs\Infrastructure\Bus;

use App\Cqrs\App\Application\Commands\AsyncCommand;
use App\Cqrs\App\Application\Commands\Command;
use App\Cqrs\App\Application\Commands\SyncCommand;
use Illuminate\Support\Facades\Bus;

final class MessageBus
{
    public function dispatch(Command $command): mixed
    {
        if ($command instanceof AsyncCommand) {
            Bus::dispatch($command);

            return null;
        }

        Bus::dispatchSync($command);

        if ($command instanceof SyncCommand) {
            return $command->getResponse();
        }

        return null;
    }
}
