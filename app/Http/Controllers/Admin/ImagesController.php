<?php

namespace App\Http\Controllers\Admin;

use App\Models\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Storage;

class ImagesController extends Controller
{
    /**
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(int $id)
    {
        $row = Images::find($id);

        if (!$row) abort(404);

        return view('cp.images.edit', compact('row',))->with('title', 'Редактирование изображения');

    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required|min:6|max:200',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $row = Images::find($request->id);

        if (!$row) abort(404);

        $row->name = $request->input('name');
        $row->save();

        return redirect(URL::route('cp.photoalbums.index', ['photoalbum_id' => $row->photoalbum_id]))->with('success', 'Данные успешно обновлены');

    }

    /**
     * @param Request $request
     */
    public function destroy(Request $request)
    {
        $row = Images::find($request->id);

        if ($row) {
            if (Storage::disk('public')->exists('images/' . $row->thumbnail) === true) Storage::disk('public')->delete('images/' . $row->thumbnail);
            if (Storage::disk('public')->exists('images/' . $row->origin) === true) Storage::disk('public')->delete('images/' . $row->origin);

            $row->delete();
        }

    }
}
