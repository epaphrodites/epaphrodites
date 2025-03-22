<?php

namespace Epaphrodites\epaphrodites\api\email\ini;

trait constant{

    private const MAX_RETRIES = 3;
    private const RETRY_DELAY_MS = 1000;
    private const BATCH_SIZE = 100;
    private const RATE_LIMIT_EMAILS_PER_HOUR = 1000;
    private const RATE_LIMIT_WINDOW_SECONDS = 3600;
    private array $sentEmails = [];
    private float $lastSendTime = 0;
}