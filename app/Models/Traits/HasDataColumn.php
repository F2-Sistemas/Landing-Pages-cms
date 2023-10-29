<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Stancl\VirtualColumn\VirtualColumn;

/**
 * Extends VirtualColumn for backwards compatibility. This trait will be removed in v4.
 */
trait HasDataColumn
{
    use VirtualColumn;

    protected $primaryKey = 'id';

    /**
     * Get the guarded attributes for the model.
     *
     * @return array<string>
     */
    public function getGuarded()
    {
        // Because has 'data' column, any key can be stored
        return [];
    }

    public static function getCustomColumns(): array
    {
        return [
            'id',
        ];
    }

    protected static $modelsShouldPreventAccessingMissingAttributes = false;
}
