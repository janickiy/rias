<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\StringHelper;
use App\Models\{Catalog, ProductParameters, User, Settings, Pages, News, Products, FeedBack, Photoalbums, Images};
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
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.users.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';

                if ($row->id != Auth::id())
                    $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';
                else
                    $deleteBtn = '';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['actions'])->make(true);
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
    public function getNews()
    {
        $row = News::query();

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.news.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['actions'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getFeedback()
    {
        $row = Feedback::query();

        return Datatables::of($row)
            ->make(true);
    }

    /**
     * @return mixed
     */
    public function getSettings()
    {
        $row = Settings::query();

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.settings.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['actions'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getCatalog()
    {
        $row = Catalog::query();

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.catalog.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['actions'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getProducts()
    {
        $row = Products::selectRaw('products.id,products.title,products.catalog_id,products.slug,products.created_at,products.description,catalog.name AS catalog')
            ->leftJoin('catalog', 'catalog.id', '=', 'products.catalog_id')
            ->groupBy('catalog.name')
            ->groupBy('products.id')
            ->groupBy('products.title')
            ->groupBy('products.catalog_id')
            ->groupBy('products.slug')
            ->groupBy('products.description')
            ->groupBy('products.created_at')
            ->groupBy('products.description');

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary" href="' . URL::route('cp.products.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })

            ->editColumn('title', function ($row) {
                return '<a href="' . URL::route('cp.product_parameters.index', ['product_id' => $row->id]) . '">' . $row->title . '</a>';
            })

            ->editColumn('description', function ($row) {
                return StringHelper::shortText(strip_tags($row->description), 1000);
            })

            ->rawColumns(['actions', 'title'])->make(true);
    }

    /**
     * @return mixed
     */
    public function getPhotoalbums()
    {
        $row = Photoalbums::query();

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.photoalbums.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })

            ->editColumn('title', function ($row) {
                return '<a class="text-indigo-500 hover:text-indigo-700 font-semibold" href="' . route('cp.photoalbums.show', $row->id) . '">
              ' . $row->title . '
              </a>';
            })

            ->rawColumns(['actions','title'])->make(true);
    }

    /**
     * @param int $photoalbum_id
     * @return mixed
     */
    public function getImages(int $photoalbum_id)
    {
        $row = Images::where('photoalbum_id', $photoalbum_id);

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.images.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })

            ->editColumn('thumbnail', function ($row) {
                return '<img  height="150" src="' . $row->getThumbnailUrl() .'" alt="">';
            })

            ->rawColumns(['actions', 'thumbnail'])->make(true);
    }

    /**
     * @param int $product_id
     * @return mixed
     */
    public function getProductParameters(int $product_id)
    {
        $row = ProductParameters::where('product_id', $product_id);

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.product_parameters.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['actions'])->make(true);
    }
}
