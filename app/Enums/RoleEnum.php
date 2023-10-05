<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static USER()
 * @method static static MANAGER()
 * @method static static WORKER()
 */
final class RoleEnum extends Enum
{
    const USER = 1;
    const WORKER = 10;
    const MANAGER = 100;
}
