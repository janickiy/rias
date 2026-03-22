<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\UserData;
use App\Http\Requests\Admin\Users\EditRequest;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Http\Requests\Admin\Users\DeleteRequest;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UsersController extends Controller
{
    public function __construct(
        private readonly UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.users.index', [
            'title' => 'Пользователи',
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        return view('cp.users.create_edit', [
            'options' => $this->getRoleOptions(),
            'title' => 'Добавление пользователя',
        ]);
    }

    /**
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $this->userRepository->create(
            UserData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.users.index')
            ->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = $this->userRepository->findOrFail($id);

        return view('cp.users.create_edit', [
            'row' => $row,
            'options' => $this->getRoleOptions(),
            'title' => 'Редактирование пользователя',
        ]);
    }

    /**
     * @param EditRequest $request
     * @return RedirectResponse
     */
    public function update(EditRequest $request): RedirectResponse
    {
        $row = $this->userRepository->findOrFail($request->integer('id'));

        $this->userRepository->update(
            $row,
            UserData::fromArray($request->validated())
        );

        return redirect()
            ->route('cp.users.index')
            ->with('success', 'Информация успешно обновлена');
    }

    /**
     * @param DeleteRequest $request
     * @return RedirectResponse
     */
    public function destroy(DeleteRequest $request): RedirectResponse
    {
        $row = $this->userRepository->find($request->integer('id'));

        if ($row === null) {
            return redirect()
                ->route('cp.users.index')
                ->with('error', 'Пользователь не найден');
        }

        if ($row->id === Auth::id()) {
            return redirect()
                ->route('cp.users.index')
                ->with('error', 'Нельзя удалить текущего пользователя');
        }

        $this->userRepository->delete($row);

        return redirect()
            ->route('cp.users.index')
            ->with('success', 'Информация успешно удалена');
    }

    /**
     * @return string[]
     */
    private function getRoleOptions(): array
    {
        return [
            'admin' => 'Админ',
            'moderator' => 'Модератор',
            'editor' => 'Редактор',
        ];
    }
}
