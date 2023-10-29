<?php

namespace App\Models\Page;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Traits\GeneratesIds;
use App\Models\Traits\HasDataColumn;
use App\Models\Traits\BelongsToTenant;
use Illuminate\Support\Facades\View;

class Page extends Model
{
    use HasFactory;
    use HasDataColumn;
    use GeneratesIds;
    use SoftDeletes;
    use BelongsToTenant;

    protected $table = 'public.pages';

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

    protected $casts = [
        'only_auth' => 'boolean',
        'published' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'title',
            'slug',
            'view',
            'tenant_id',
            'only_auth',
            'published',
            'created_at',
            'updated_at',
            'deleted_at',
        ];
    }

    public function getView(bool $checkIfExists = true): ?string
    {
        $view = $this->view;

        if (!$view) {
            return null;
        }

        if ($checkIfExists) {
            return View::exists($view) ? $view : null;
        }

        return $view;
    }
}
