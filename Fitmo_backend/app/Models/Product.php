<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

      public function orders()
    {
        return $this->belongsToMany(Order::class)->withTimestamps();
        // Use withPivot(['column1', 'column2']) pokud máte další sloupce ve vazební tabulce
    }

     public function categories()
{
    return $this->belongsToMany(Category::class, 'product_categories')->withTimestamps();
    // If you have additional columns in the pivot table, you can use withPivot(['column1', 'column2'])
}
      public function color()
    {
        return $this->belongsTo(Color::class);
    }
 public function children()
    {
        return $this->hasMany(Product::class, 'parent_id');
    }
}
