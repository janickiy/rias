<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Menu\AddCustomMenuRequest;
use App\Http\Requests\Admin\Menu\CreateMenuRequest;
use App\Http\Requests\Admin\Menu\DeleteMenuItemRequest;
use App\Http\Requests\Admin\Menu\DeleteMenuRequest;
use App\Http\Requests\Admin\Menu\GenerateMenuControlRequest;
use App\Http\Requests\Admin\Menu\UpdateMenuItemRequest;
use App\Repositories\MenuRepository;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    public function __construct(
        private readonly MenuRepository $menuRepository,
    ) {
    }

    public function index(): View
    {
        return view('cp.menu.index', [
            'title' => 'Меню',
        ]);
    }

    public function createNewMenu(CreateMenuRequest $request): JsonResponse
    {
        $menu = $this->menuRepository->createMenu(
            $request->string('menuname')->toString()
        );

        return response()->json([
            'resp' => $menu->id,
        ]);
    }

    public function deleteItemMenu(DeleteMenuItemRequest $request): JsonResponse
    {
        $deleted = $this->menuRepository->deleteMenuItem(
            $request->integer('id')
        );

        return response()->json([
            'resp' => $deleted ? 1 : 0,
        ]);
    }

    public function deleteMenuGroup(DeleteMenuRequest $request): JsonResponse
    {
        $menuId = $request->integer('id');

        if ($this->menuRepository->menuHasItems($menuId)) {
            return response()->json([
                'resp' => 'You have to delete all items first',
                'error' => 1,
            ], 422);
        }

        $this->menuRepository->deleteMenu($menuId);

        return response()->json([
            'resp' => 'you delete this item',
        ]);
    }

    public function updateItem(UpdateMenuItemRequest $request): JsonResponse
    {
        $arrayData = $request->input('arraydata');

        if (is_array($arrayData)) {
            $this->menuRepository->bulkUpdateItems($arrayData);
        } else {
            $this->menuRepository->updateItem(
                id: $request->integer('id'),
                label: $request->string('label')->toString(),
                link: $request->string('url')->toString(),
                class: $request->input('clases'),
                roleId: config('menu.use_roles')
                    ? (int) ($request->input('role_id') ?: 0)
                    : null,
            );
        }

        return response()->json([
            'resp' => 1,
        ]);
    }

    public function addCustomMenu(AddCustomMenuRequest $request): JsonResponse
    {
        $this->menuRepository->addCustomMenuItem(
            menuId: $request->integer('idmenu'),
            label: $request->string('labelmenu')->toString(),
            link: $request->string('linkmenu')->toString(),
            roleId: config('menu.use_roles')
                ? (int) ($request->input('rolemenu') ?: 0)
                : null,
        );

        return response()->json([
            'resp' => 1,
        ]);
    }

    public function generateMenuControl(GenerateMenuControlRequest $request): JsonResponse
    {
        $this->menuRepository->updateMenuStructure(
            menuId: $request->integer('idmenu'),
            menuName: $request->string('menuname')->toString(),
            items: $request->input('arraydata', []),
            roleId: config('menu.use_roles')
                ? (int) ($request->input('role_id') ?: 0)
                : null,
        );

        return response()->json([
            'resp' => 1,
        ]);
    }
}
