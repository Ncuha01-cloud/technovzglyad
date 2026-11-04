<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug'])]
class Category extends Model
{
    use HasFactory;

    /**
     * Статьи, относящиеся к этой категории.
     */
    public function texts(): HasMany
    {
        return $this->hasMany(Text::class);
    }

    /**
     * В URL категория идентифицируется по slug, а не по числовому id.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
