<?php

namespace LukeTowers\GA4EventTracking\Jobs;

use LukeTowers\GA4EventTracking\Events\EventBroadcaster;
use LukeTowers\GA4EventTracking\GA4;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendEventToAnalytics implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $event;

    public ?string $clientId;

    public ?string $userId;

    public ?string $sessionId;

    public function __construct($event, ?string $clientId = null, ?string $userId = null, ?string $sessionId = null)
    {
        $this->event = $event;
        $this->clientId = $clientId;
        $this->userId = $userId;
        $this->sessionId = $sessionId;
    }

    public function handle(EventBroadcaster $broadcaster)
    {
        if ($this->clientId) {
            $broadcaster->withParameters(fn (GA4 $GA4) => $GA4->setClientId($this->clientId));
        }

        if ($this->userId) {
            $broadcaster->withParameters(fn (GA4 $GA4) => $GA4->setUserId($this->userId));
        }

        if ($this->sessionId) {
            $broadcaster->withParameters(fn (GA4 $GA4) => $GA4->setSessionId($this->sessionId));
        }

        $broadcaster->handle($this->event);
    }
}
