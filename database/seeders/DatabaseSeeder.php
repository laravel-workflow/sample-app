<?php

namespace Database\Seeders;

use App\Domain\Accounts\Commands\CreateAccount;
use App\Domain\Accounts\Commands\DepositCurrency;
use App\Models\Account;
use App\Models\User;
use App\Workflows\Transfer\TransferWorkflow;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Spatie\EventSourcing\Commands\CommandBus;
use Workflow\WorkflowStub;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(CommandBus $bus): void
    {
        $userId1 = (string) Str::orderedUuid();
        $accountId1 = (string) Str::orderedUuid();
        $userId2 = (string) Str::orderedUuid();
        $accountId2 = (string) Str::orderedUuid();

        $user1 = User::factory()->create([
            'id' => $userId1,
            'name' => 'Test User 1',
            'email' => 'test1@example.com',
        ]);

        $user2 = User::factory()->create([
            'id' => $userId2,
            'name' => 'Test User 2',
            'email' => 'test2@example.com',
        ]);

        $bus = app(CommandBus::class);
        $bus->dispatch(new CreateAccount($accountId1, $userId1));
        $bus->dispatch(new CreateAccount($accountId2, $userId2));
        $bus->dispatch(new DepositCurrency($accountId2, 100));

        $workflow = WorkflowStub::make(TransferWorkflow::class);
        $workflow->start($accountId2, $accountId1, 10);
        while ($workflow->running());

        assert(Account::whereId($accountId1)->first()->balance === 10);
        assert(Account::whereId($accountId2)->first()->balance === 90);
    }
}
