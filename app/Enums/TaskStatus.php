<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self PENDING()
 * @method static self IN_PROGRESS()
 * @method static self COMPLETED()
 * @value string PENDING
 * @value string IN_PROGRESS
 * @value string COMPLETED
 */
final class TaskStatus extends Enum
{
    public static function all(): array
    {
        return [
            'PENDING',
            'IN_PROGRESS',
            'COMPLETED',
        ];
    }
}
