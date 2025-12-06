<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [
        'id'
    ];
    protected $fillable = [
        'category',
        'subcategory',
        'type',
        'variant',
        'brand',
        'size',
        'unit',
        'name',
        'stock',
        'price',
        'img',
        'description',
        'is_active',
    ];
    public $timestamps = false;

    public static function searchBySimilarity($columnName, $searchParam, $limit = null)
    {
        // Check if the column exists in the table
        if (!DB::getSchemaBuilder()->hasColumn('products', $columnName)) {
            throw new \InvalidArgumentException("Column {$columnName} does not exist in the products table.");
        }
        $wordCount = str_word_count($searchParam);
        $threshold = match ($wordCount) {
            1 => 0.1,
            2 => 0.275,
            3 => 0.375,
            default => 0.45,
        };
        $result = self::query()
            ->selectRaw("
                *,
                ts_rank_cd(search_vector, websearch_to_tsquery('indonesian', ?) ||
                                        websearch_to_tsquery('english', ?)) AS text_rank,
                similarity({$columnName}, ?) AS fuzzy_rank
            ", [$searchParam, $searchParam, $searchParam])
            ->where('is_active', true)
            ->whereRaw("
                search_vector @@ (websearch_to_tsquery('indonesian', ?) ||
                                  websearch_to_tsquery('english', ?))
            ", [$searchParam, $searchParam])
            ->orWhereRaw("similarity({$columnName}, ?) >= {$threshold}", [$searchParam])
            ->orderByDesc('text_rank')
            ->orderByDesc('fuzzy_rank')
            ->when($limit, fn($query) => $query->limit($limit))
            ->get();
        // Remove the text_rank and fuzzy_rank columns from the result
        $result->makeHidden(['text_rank', 'fuzzy_rank', 'search_vector']);
        // dd($result->toArray());
        return $result;
    }

    public static function searchBySimilarityPaginated($columnName, $searchParam, $page = 15, $filter = []): LengthAwarePaginator
    {
        // Check if the column exists in the table
        if (!DB::getSchemaBuilder()->hasColumn('products', $columnName)) {
            throw new \InvalidArgumentException("Column {$columnName} does not exist in the products table.");
        }
        $wordCount = str_word_count($searchParam);
        $threshold = match ($wordCount) {
            1 => 0.1,
            2 => 0.275,
            3 => 0.375,
            default => 0.45,
        };
        $result = self::query()
            ->selectRaw("
                *,
                ts_rank_cd(search_vector, websearch_to_tsquery('indonesian', ?) ||
                                        websearch_to_tsquery('english', ?)) AS text_rank,
                similarity({$columnName}, ?) AS fuzzy_rank
            ", [$searchParam, $searchParam, $searchParam])
            ->where('is_active', true)
            ->whereRaw("
                search_vector @@ (websearch_to_tsquery('indonesian', ?) ||
                                  websearch_to_tsquery('english', ?))
            ", [$searchParam, $searchParam])
            ->orWhereRaw("similarity({$columnName}, ?) >= {$threshold}", [$searchParam])
            // Apply filters
            ->when(isset($filter['category']) && !empty($filter['category']), function ($query) use ($filter) {
                $query->where('category', $filter['category']);
            })
            ->when(isset($filter['subcategory']) && !empty($filter['subcategory']), function ($query) use ($filter) {
                $query->where('subcategory', $filter['subcategory']);
            })
            ->when(isset($filter['brand']) && !empty($filter['brand']), function ($query) use ($filter) {
                $query->where('brand', $filter['brand']);
            })
            ->orderByDesc('text_rank')
            ->orderByDesc('fuzzy_rank')
            ->paginate($page);
        // Remove the text_rank and fuzzy_rank columns from the result
        $result->makeHidden(['text_rank', 'fuzzy_rank', 'search_vector']);
        // dd($result->toArray());
        return $result;
    }
    protected $appends = ['img_path'];

    public function getImgPathAttribute()
    {
        return asset('images/products/' . $this->img);
    }
}
