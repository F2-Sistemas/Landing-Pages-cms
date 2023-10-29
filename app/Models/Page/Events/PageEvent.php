<?php

declare(strict_types=1);

namespace App\Models\Page\Events;

use Illuminate\Queue\SerializesModels;
use App\Models\Page\Page;

abstract class PageEvent
{
    use SerializesModels;

    /** @var Page */
    public $page;

    public function __construct(Page $page)
    {
        $this->page = $page;
    }
}
