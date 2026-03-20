<?php

declare(strict_types=1);

namespace App\Services;

use Harimayco\Menu\Models\Menus;

class FrontendMenuService
{
    public function getTopMenu(): array
    {
        $menu = Menus::query()
            ->where('name', 'top')
            ->with('items')
            ->first();

        return $menu?->items?->toArray() ?? [];
    }
}
