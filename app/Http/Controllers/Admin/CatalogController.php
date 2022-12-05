<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\{Catalog};
use Validator;
use Image;
use URL;

class CatalogController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $catalog = [];

        foreach (Catalog::get()->toArray() as $row) {
            $cats[$row['parent_id']][$row['id']] = $row;
        }


        return view('cp.catalog.index', compact('catalog'))->with('title', 'Катеории');
    }

    /**
     * @param int $parent_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($parent_id = 0)
    {
        $options[0] = 'Выберите';
        $options = Catalog::ShowTree($options, 0);

        return view('cp.catalog.create_edit', compact('parent_id', 'options'))->with('title', 'Добавление категории');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'parent_id' => 'integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        Catalog::create($request->all());

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Информация успешно добавлена');
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $row = Catalog::where('id', $id)->first();

        if (!$row) abort(404);

        $options[0] = 'Выберите';
        Catalog::ShowTree($options, 0);
        $parent_id = $row->parent_id;

        unset($options[$id]);

        return view('cp.catalog.create_edit', compact('row', 'parent_id', 'options'))->with('title', 'Редактирование категории');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $rules = [
            'name' => 'required',
            'parent_id' => 'integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) return back()->withErrors($validator)->withInput();

        $catalog = Catalog::find($request->id);

        if (!$catalog) abort(404);

        $catalog->name = $request->name;
        $catalog->description = $request->description;
        $catalog->keywords = $request->keywords;
        $catalog->parent_id = $request->parent_id;
        $catalog->save();

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Данные обновлены');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request)
    {
        $parent = Catalog::findOrFail($request->id);
        $array_of_ids = $this->getChildren($parent);
        array_push($array_of_ids, $request->id);

        Catalog::destroy($array_of_ids);

        return redirect(URL::route('cp.catalog.index'))->with('success', 'Данные удалены');
    }

    /**
     * @param $category
     * @return array
     */
    private function getChildren($category)
    {
        $ids = [];
        foreach ($category->children as $row) {
            $ids[] = $row->id;
            $ids = array_merge($ids, $this->getChildren($row));
        }
        return $ids;
    }
}
