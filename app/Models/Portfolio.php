<?php

namespace App\Models;

use App\Models\PortfolioImage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'short_description',
        'full_content',
        'main_image_url',
        'project_date'
    ];

    public function images()
    {
        return $this->hasMany(PortfolioImage::class);
    }
}
