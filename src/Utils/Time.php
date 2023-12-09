<?php

namespace Pubq\Utils;

use Carbon\Carbon;

class Time
{
    public static function getRemainingSeconds(int $timestamp)
    {
        // Create Carbon instances for the current time and the target timestamp
        $currentTime = Carbon::now();
        $targetTime = Carbon::createFromTimestamp($timestamp);

        // Calculate the remaining seconds
        $remainingSeconds = $targetTime->diffInSeconds($currentTime);

        return $remainingSeconds;
    }
}
