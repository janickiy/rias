<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StringHelper;

class News extends Model
{

    protected $table = 'news';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'text',
        'preview',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
        'seo_h1',
        'seo_url_canonical'
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

        return StringHelper::shortText($content,500);
    }

}
