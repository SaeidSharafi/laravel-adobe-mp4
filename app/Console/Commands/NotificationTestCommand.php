<?php

namespace App\Console\Commands;

use App\Models\Auth\User;
use App\Notifications\SimpleDBNotification;
use Illuminate\Console\Command;

class NotificationTestCommand extends Command
{
    protected $signature = 'test:notification';

    protected $description = 'Command description';

    public function handle(): void
    {
        $user = User::query()->where('email', 'admin@easy.ir')->first();
        $user->notify(new SimpleDBNotification(['title' => 'test','description' => 'test description']));
    }
}
