<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\StringHelper;
use Storage;

class News extends Model
{

    protected $table = 'news';

    protected $fillable = [
        'title',
        'text',
        'preview',
        'image',
        'image_title',
        'image_alt',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
        'seo_h1',
        'seo_url_canonical'
    ];

    public function excerpt(): string
    {
        $content = $this->text;
        $content = preg_replace("/<img(.*?)>/si", "", $content);
        $content = preg_replace('/(<.*?>)|(&.*?;)/', '', $content)  ;

        return StringHelper::shortText($content,500);
    }


    public function getImage(): string
    {
        return Storage::disk('public')->url('news/' . $this->image);
    }

}
