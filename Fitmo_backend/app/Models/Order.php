<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    // Definice relace Many-To-Many s Product
    public function products()
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
        // Use withPivot(['column1', 'column2']) pokud máte další sloupce ve vazební tabulce
    }
    public function deliveryType()
    {
        return $this->belongsTo(DeliveryType::class, 'delivery_id');
    }
    
    public function paymentType()
    {
        return $this->belongsTo(PaymentType::class, 'payment_id');
    }
      public function billingAddress()
    {
        return $this->belongsTo(Address::class, 'billing_address');
    }

    public function deliveryAddress()
    {
        return $this->belongsTo(Address::class, 'delivery_address');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
 
}
