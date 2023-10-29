<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\GeneratesIds;
use App\Models\Traits\HasDataColumn;
use App\Models\Traits\BelongsToTenant;

class Page extends Model
{
    use HasFactory;
    use HasDataColumn;
    use GeneratesIds;
    use SoftDeletes;
    use BelongsToTenant;

    protected $table = 'public.pages';
    protected $primaryKey = 'id';
    protected $guarded = []; // Because has 'data' column, any key can be stored

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'slug',
            'tenant_id',
        ];
    }

    protected static $modelsShouldPreventAccessingMissingAttributes = false;

    protected $dispatchesEvents = [
        'saving' => Events\SavingPage::class,
        'saved' => Events\PageSaved::class,
        'creating' => Events\CreatingPage::class,
        'created' => Events\PageCreated::class,
        'updating' => Events\UpdatingPage::class,
        'updated' => Events\PageUpdated::class,
        'deleting' => Events\DeletingPage::class,
        'deleted' => Events\PageDeleted::class,
    ];
}
