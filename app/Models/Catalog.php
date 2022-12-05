<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use URL;

class Catalog extends Model
{

    const PER_PAGE = 1000;

    protected $table = 'catalog';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'keywords',
        'parent_id'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(){
        return $this->hasMany($this, 'parent_id', 'id');
    }

    /**
     * @param $option
     * @param $parent_id
     * @param int $lvl
     * @return mixed
     */
    public static function showTree(&$option, $parent_id, &$lvl = 0)
    {
        $lvl++;
        $rows = self::where('parent_id', $parent_id)->orderBy('name')->get();

        foreach ($rows as $row) {
            $indent = '';
            for ($i = 1; $i < $lvl; $i++) $indent .= '-';

            $option[$row->id] = $indent . " " . $row->name;
            self::showTree($option, $row->id, $lvl);
            $lvl--;
        }

        return $option;
    }

    /**
     * @param $id
     * @return string
     */
    public static function ShowSubCat($id)
    {
        $catalogs = self::selectRaw('catalog.id, catalog.name, COUNT(links.status) AS number_links')
            ->leftJoin('links', 'links.catalog_id', '=', 'catalog.id')
            ->groupBy('catalog.id')
            ->groupBy('catalog.name')
            ->orderBy('catalog.name')
            ->where('catalog.parent_id', $id)
            ->get();

        $sub_category_list = [];

        if ($catalogs) {
            foreach ($catalogs as $catalog) {
                $sub_category_list[] = '<a href="' . URL::route('catalog', ['id' => $catalog->id]) . '">' . $catalog->name . '</a> <span>(' . $catalog->number_links . ')</span>';
            }
        }

        return implode(', ', $sub_category_list);
    }

    /**
     * @param $topbar
     * @param $parent_id
     * @return array
     */
    public static function topbarMenu(&$topbar, $parent_id)
    {
        if (is_numeric($parent_id)) {
            $result = self::where('id', $parent_id);

            if ($result->count() > 0) {
                $catalog = $result->first();
                $topbar[] = [$catalog->id, $catalog->name];

                self::topbarMenu($topbar, $catalog->parent_id);
            }
        }

        sort($topbar);

        return $topbar;
    }
}
