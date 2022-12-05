<?php

namespace App\Helpers;

use URL;

class CatalogHelper
{

    /**
     * @param $catalog
     * @param $parent_id
     * @param false $only_parent
     * @return string
     */
    public static function build_tree($catalog, $parent_id, $only_parent = false): string
    {
        if (is_array($catalog) && isset($catalog[$parent_id])) {
            $tree = '<ul>';
            if ($only_parent == false) {
                foreach ($catalog[$parent_id] as $row) {
                    $tree .= '<li>' . $row['name'] . ' <a title="Добавить подкатегорию" href="' . URL::route('cp.catalog.create', ['parent_id' => $row['id']]) . '"> <span class="fa fa-plus"></span> </a> <a title="Редактировать" href="' . URL::route('cp.catalog.edit', ['id' => $row['id']]) . '"> <span class="fa fa-pencil"></span> </a> <a title="Удалить" href="' . URL::route('cp.catalog.delete', $row['id']) . '"> <span class="fa fa-trash-o"></span> </a>';
                    $tree .= self::build_tree($catalog, $row['id']);
                    $tree .= '</li>';
                }
            } elseif (is_numeric($only_parent)) {
                $row = $catalog[$parent_id][$only_parent];
                $tree .= '<li>' . $row['name'] . ' #' . $row['id'];
                $tree .= self::build_tree($catalog, $row['id']);
                $tree .= '</li>';
            }
            $tree .= '</ul>';
            return $tree;
        } else return '';
    }
}
