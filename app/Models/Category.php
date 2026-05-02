<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, Translatable;

    protected $fillable = [
        'parent_id',
        'status',
        'image_path',
    ];

    public $translatedAttributes = [
        'name',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }


    // recursively for tree-view
    public function descendants()
    {
        return $this->children()->with('descendants');
    }


    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // scopes ------

    public function scopeRoots(Builder $query)
    {
        return $query->whereNull('parent_id');
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('status', true);
    }

    public function scopeWithChildren(Builder $query)
    {
        return $query->with('children');
    }


    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    // dfs
    public function getAncestorIds(): array
    {
        $ancestors = [];
        $current = $this->parent;

        while ($current) {
            $ancestors[] = $current->id;
            $current = $current->parent;
        }

        return $ancestors;
    }



    public function isDescendantOf(int $categoryId): bool
    {
        return in_array($categoryId, $this->getAncestorIds());
    }

    // prevent self assigning
    public function canBeParentOf(int $childId): bool
    {
        if ($this->id === $childId) {
            return false;
        }

        // no loops
        $child = Category::findOrFail($childId); // exception if childId is not existing
        if ($child && $child->isDescendantOf($this->id)) {
            return false;
        }

        return true;
    }

    // dfs to full category path
    public function getPath(string $separator = ' > '): string
    {
        $path = [$this->name];
        $current = $this->parent;

        while ($current) {
            array_unshift($path, $current->name);
            $current = $current->parent;
        }

        return implode($separator, $path);
    }

    public function getAllProducts()
    {

        $categoryIds = $this->getAllCategoryIds();

        return Product::whereIn('category_id', $categoryIds);
    }

    protected function getAllCategoryIds(): array
    {
        $ids = [$this->id];

        foreach ($this->children as $child) {
            $ids = array_merge($ids, $child->getAllCategoryIds());
        }

        return $ids;
    }
}
