<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\MenuItems;
use App\Models\Menus;

class MenuRepository
{
    public function createMenu(string $name): Menus
    {
        $menu = new Menus();
        $menu->name = $name;
        $menu->save();

        return $menu;
    }

    public function deleteMenuItem(int $id): bool
    {
        $menuItem = MenuItems::find($id);

        if ($menuItem === null) {
            return false;
        }

        return (bool)$menuItem->delete();
    }

    public function menuHasItems(int $menuId): bool
    {
        $menuItems = new MenuItems();

        return count($menuItems->getall($menuId)) > 0;
    }

    public function deleteMenu(int $id): bool
    {
        $menu = Menus::find($id);

        if ($menu === null) {
            return false;
        }

        return (bool)$menu->delete();
    }

    public function updateItem(
        int     $id,
        string  $label,
        string  $link,
        ?string $class = null,
        ?int    $roleId = null,
    ): bool
    {
        $menuItem = MenuItems::find($id);

        if ($menuItem === null) {
            return false;
        }

        $menuItem->label = $label;
        $menuItem->link = $link;
        $menuItem->class = $class;

        if (config('menu.use_roles')) {
            $menuItem->role_id = $roleId ?? 0;
        }

        return $menuItem->save();
    }

    public function bulkUpdateItems(array $items): void
    {
        foreach ($items as $item) {
            $menuItem = MenuItems::find((int)($item['id'] ?? 0));

            if ($menuItem === null) {
                continue;
            }

            $menuItem->label = $item['label'] ?? '';
            $menuItem->link = $item['link'] ?? '';
            $menuItem->class = $item['class'] ?? null;

            if (config('menu.use_roles')) {
                $menuItem->role_id = !empty($item['role_id']) ? (int)$item['role_id'] : 0;
            }

            $menuItem->save();
        }
    }

    public function addCustomMenuItem(
        int    $menuId,
        string $label,
        string $link,
        ?int   $roleId = null,
    ): MenuItems
    {
        $menuItem = new MenuItems();
        $menuItem->label = $label;
        $menuItem->link = $link;
        $menuItem->menu = $menuId;
        $menuItem->sort = MenuItems::getNextSortRoot($menuId);

        if (config('menu.use_roles')) {
            $menuItem->role_id = $roleId ?? 0;
        }

        $menuItem->save();

        return $menuItem;
    }

    public function updateMenuStructure(
        int    $menuId,
        string $menuName,
        array  $items = [],
        ?int   $roleId = null,
    ): void
    {
        $menu = Menus::findOrFail($menuId);
        $menu->name = $menuName;
        $menu->save();

        foreach ($items as $item) {
            $menuItem = MenuItems::find((int)($item['id'] ?? 0));

            if ($menuItem === null) {
                continue;
            }

            $menuItem->parent = (int)($item['parent'] ?? 0);
            $menuItem->sort = (int)($item['sort'] ?? 0);
            $menuItem->depth = (int)($item['depth'] ?? 0);

            if (config('menu.use_roles')) {
                $menuItem->role_id = $roleId ?? 0;
            }

            $menuItem->save();
        }
    }
}
