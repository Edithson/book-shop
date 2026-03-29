<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];

    // -------------------------------------------------------
    // Auto-slug
    // -------------------------------------------------------
    protected static function booted(): void
    {
        static::creating(function (Category $category) {
            $category->slug = static::generateUniqueSlug($category->name);
        });

        static::updating(function (Category $category) {
            if ($category->isDirty('name')) {
                $category->slug = static::generateUniqueSlug($category->name, $category->id);
            }
        });
    }

    private static function generateUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i    = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }

    // -------------------------------------------------------
    // Relations
    // -------------------------------------------------------
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
