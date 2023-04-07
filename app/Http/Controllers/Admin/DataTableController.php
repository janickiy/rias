<?php

namespace App\Http\Controllers\Admin;

use App\Models\{
    Catalog,
    ProductParameters,
    ProductPhotos,
    ProductVideos,
    ProductDocuments,
    User,
    Settings,
    Pages,
    News,
    Products,
    FeedBack
};
use Illuminate\Support\Facades\Auth;
use App\Helpers\VideoHelper;
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

            ->editColumn('main', function ($row) {
                return $row->main ? 'да' : 'нет';
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

           ->editColumn('image', function ($row) {
               return $row->image ? 'да' : 'нет';
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
                $title = $row->title;
                $title .= '<br><br><a href="' . URL::route('cp.product_photos.index', ['product_id' => $row->id]) . '">Фото</a>';
                $title .= '<br><a href="' . URL::route('cp.product_videos.index', ['product_id' => $row->id]) . '">Видео</a>';
                $title .= '<br><a href="' . URL::route('cp.product_documents.index', ['product_id' => $row->id]) . '">Документы</a>';
                $title .= '<br><a href="' . URL::route('cp.product_parameters.index', ['product_id' => $row->id]) . '">Характеристики</a>';

                return $title;
            })

            ->rawColumns(['actions', 'title'])->make(true);
    }

    /**
     * @param int $product_id
     * @return mixed
     */
    public function getPhotos(int $product_id)
    {
        $row = ProductPhotos::where('product_id', $product_id );

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {

                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.product_photos.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
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
    public function getVideos(int $product_id)
    {
        $row = ProductVideos::where('product_id', $product_id );

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.product_videos.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })

            ->addColumn('thumb', function ($row) {
                return '<img src="' . VideoHelper::getThumb($row->provider,$row->video). '" width="250px">';
            })

            ->rawColumns(['actions','thumb'])->make(true);
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

    /**
     * @param int $product_id
     * @return mixed
     */
    public function getDocuments(int $product_id)
    {
        $row = ProductDocuments::where('product_id', $product_id);

        return Datatables::of($row)
            ->addColumn('actions', function ($row) {
                $editBtn = '<a title="редактировать" class="btn btn-xs btn-primary"  href="' . URL::route('cp.product_documents.edit', ['id' => $row->id]) . '"><span  class="fa fa-edit"></span></a> &nbsp;';
                $deleteBtn = '<a title="удалить" class="btn btn-xs btn-danger deleteRow" id="' . $row->id . '"><span class="fa fa-remove"></span></a>';

                return '<div class="nobr"> ' . $editBtn . $deleteBtn . '</div>';
            })
            ->rawColumns(['actions'])->make(true);
    }
}
