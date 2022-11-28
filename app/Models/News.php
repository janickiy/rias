<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StringHelpers;

class News extends Model
{

    protected $table = 'news';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'text',
        'preview',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
    ];

    /**
     * @param string $lang
     * @return mixed
     */
    public function excerpt()
    {
        $content = $this->text;
        $content = preg_replace("/<img(.*?)>/si", "", $content);
        $content = preg_replace('/(<.*?>)|(&.*?;)/', '', $content)  ;

        return StringHelpers::shortText($content,500);
    }

}
