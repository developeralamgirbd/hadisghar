<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sitemap\Contracts\Sitemapable;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class Post extends Model implements Sitemapable
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['title', 'meta_description', 'description', 'feature_img', 'category_id', 'user_id', 'slug', 'status'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comment():HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function toSitemapTag(): Url|string|array
    {
        return route('post.view', $this);
    }


}
