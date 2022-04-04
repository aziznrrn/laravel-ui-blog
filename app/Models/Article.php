<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /*
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'title',
        'content',
        'category_id',
        'user_id',
    ];

    /**
     * Get the category for the article.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the user for the article.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
