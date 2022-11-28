<?php

namespace App\Http\Controllers\Admin;

use App\Models\{User,Settings, Pages};
use Illuminate\Support\Facades\Auth;
use DataTables;
use URL;

class DataTableController extends Controller
{
    /**
     * @return mixed
     */
    public function getUsers()
    {
        $row = User::query();

        return Datatables::of($row)
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.users.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';

                if ($row->id != Auth::id())
                    $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';
                else
                    $deleteBtn = '';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getPages()
    {
        $row = Pages::query();

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.pages.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })

            ->editColumn('pages.parent', function ($row) {
                return $row->parent->title ?? '';
            })

            ->editColumn('page_path', function ($row) {
                return $row->PagePathType ?? '';
            })

            ->rawColumns(['actions'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        $row = Settings::query();

        return Datatables::of($row)
            ->addColumn('action', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.settings.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['action'])->make(true);
    }
}
