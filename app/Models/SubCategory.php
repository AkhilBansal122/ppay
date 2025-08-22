<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    protected $guarded=[];
    public function fetchData($request, $columns)
    {
        $query = SubCategory::where('id', '!=', '');

        // Filter subcategories where the related category has status = 1
   // Filter subcategories where both the category and the main category have status = 1
        $query->whereHas('category', function ($q) {
            $q->where('status', 1)   // Category status = 1
            ->whereHas('mainCategory', function ($q) {
                $q->where('status', 1);  // Main category status = 1
            });
        });
        // Filter by date range
        if (isset($request->from_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") >= ?', [date("Y-m-d", strtotime($request->from_date))]);
        }
        if (isset($request->end_date)) {
            $query->whereRaw('DATE_FORMAT(created_at, "%Y-%m-%d") <= ?', [date("Y-m-d", strtotime($request->end_date))]);
        }

        // Search by subcategory name or category name
        if (isset($request['search']['value']) && !empty($request['search']['value'])) {
            $searchValue = $request['search']['value'];

            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'like', '%' . $searchValue . '%')
                  ->orWhereHas('category', function ($q) use ($searchValue) {
                      $q->where('status', 1);  // Ensure status is 1 when searching category name
                      $q->where('name', 'like', '%' . $searchValue . '%');
                  });
            });
        }

        // Order by specified column or default to created_at
        if (isset($request->order_column)) {
            $subCategories = $query->orderBy($columns[$request->order_column], $request->order_dir);
        } else {
            $subCategories = $query->orderBy('created_at', 'desc');
        }

        return $subCategories;
    }


    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id')->where('status', 1);    }
}
