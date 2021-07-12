<?php

namespace App\Jobs;

use App\Entities\Users\User;
use App\Exceptions\Gateways\ClientApiException;
use App\Exceptions\Gateways\NotificationException;
use App\Services\Gateways\Notification\NotifyClientApi;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TransactionNotifierJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 3;

    private $user;

    private $message;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(User $user, string $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws NotificationException If notification was not sent
     * @throws ClientApiException If notification resource is not found
     */
    public function handle(NotifyClientApi $notifyClientApi)
    {
        try {
            if (!$notifyClientApi->notify($this->user, $this->message)) {
                throw NotificationException::unavailable();
            }
            Log::info("Notification to user '{$this->user->getName()}' with message '{$this->message}' sent successfully;");
        } catch (ConnectionException | NotificationException | ClientApiException $ex) {
            Log::debug($ex->getMessage());
            throw $ex;
        }
    }
}
