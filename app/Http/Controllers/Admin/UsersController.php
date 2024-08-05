<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UsersController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('cp.users.index')->with('title', 'Пользователи');
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $options = [
            'admin' => 'Админ',
            'moderator' => 'Модератор',
            'editor' => 'Редактор',
        ];

        return view('cp.users.create_edit', compact('options'))->with('title', 'Добавление пользователя');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $rules = [
            'login' => 'required|unique:users|max:255',
            'name' => 'required',
            'password' => 'required|min:6',
            'password_again' => 'required|min:6|same:password',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create($request->all());

        return redirect()->route('cp.users.index')->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param int $id
     * @return View
     */
    public function edit(int $id): View
    {
        $row = User::find($id);

        if (!$row) abort(404);

        $options = [
            'admin' => 'Админ',
            'moderator' => 'Модератор',
            'editor' => 'Редактор',
        ];

        return view('cp.users.create_edit', compact('row', 'options'))->with('title', 'Редактирование пользователя');
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $rules = [
            'login' => 'required|max:255|unique:users,login,' . $request->id,
            'name' => 'required',
            'password' => 'min:6|nullable',
            'password_again' => 'min:6|same:password|nullable',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $row = User::find($request->id);

        if (!$row) abort(404);

        $row->name = $request->input('name');
        $row->login = $request->input('login');

        if (!empty($request->role)) $row->role = $request->input('role');

        if (!empty($request->password)) $row->password = $request->password;

        $row->save();

        return redirect()->route('cp.users.index')->with('success', 'Информация успешно обновлена');
    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request): void
    {
        if ($request->id != \Auth::id()) User::where('id', $request->id)->delete();
    }
}
