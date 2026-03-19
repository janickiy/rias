<?php

namespace App\Http\Controllers\Admin;

use App\DTO\Admin\UserData;
use App\Http\Requests\Admin\Users\EditRequest;
use App\Http\Requests\Admin\Users\StoreRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UsersController extends Controller
{
    public function __construct(private readonly UserRepository $userRepository)
    {
    }

    public function index(): View
    {
        return view('cp.users.index')->with('title', 'Пользователи');
    }

    public function create(): View
    {
        $options = [
            'admin' => 'Админ',
            'moderator' => 'Модератор',
            'editor' => 'Редактор',
        ];

        return view('cp.users.create_edit', compact('options'))->with('title', 'Добавление пользователя');
    }

    public function store(StoreRequest $request): RedirectResponse
    {
        $this->userRepository->create(UserData::fromArray($request->validated()));

        return redirect()->route('cp.users.index')->with('success', 'Информация успешно добавлена');
    }

    public function edit(int $id): View
    {
        $row = User::findOrFail($id);
        $options = [
            'admin' => 'Админ',
            'moderator' => 'Модератор',
            'editor' => 'Редактор',
        ];

        return view('cp.users.create_edit', compact('row', 'options'))->with('title', 'Редактирование пользователя');
    }

    public function update(EditRequest $request): RedirectResponse
    {
        $row = User::findOrFail($request->integer('id'));
        $this->userRepository->update($row, UserData::fromArray($request->validated()));

        return redirect()->route('cp.users.index')->with('success', 'Информация успешно обновлена');
    }

    public function destroy(Request $request): void
    {
        $row = User::find($request->id);

        if ($row && $row->id !== Auth::id()) {
            $this->userRepository->delete($row);
        }
    }
}
