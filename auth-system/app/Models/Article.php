<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class Article extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'image',
        'user_id',
        'category_id',
        'status',
        'featured',
        'views_count'
    ];

    protected $casts = [
        'featured' => 'boolean',
        'views_count' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Updated to use Purifier
    public function getCleanContentAttribute()
    {
        $content = $this->content;
        
        // First clean the content with Purifier
        $content = Purifier::clean($content, [
            'HTML.Allowed' => 'h1,h2,h3,h4,h5,h6,b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span,img[width|height|alt|src|class],table[class|border|cellpadding|cellspacing],thead,tbody,tr,td,th',
            'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty' => true,
        ]);
    
        return $content;
    }

    // Updated excerpt generator
    public function getExcerptAttribute()
    {
        $cleanContent = strip_tags($this->content);
        return Str::limit($cleanContent, 150);
    }
}