<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Address;
use App\Models\Order;
use App\Models\Map_table;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Models\Order_product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
public function index()
{
    // Retrieve orders with products, pivot data, delivery type, product colors, billing address, and delivery address
    $orders = Order::with([
        'products' => function ($query) {
            // Include pivot data from the order_product table
            $query->withPivot('product_price');
            $query->withPivot('product_discounted');
            $query->withPivot('product_discountedPercent');
            $query->withPivot('product_count');
            // Include color information for each product
            $query->with('color');
              $query->with('categories');
        },
        'deliveryType',
        'paymentType',
        'billingAddress',
        'deliveryAddress',
        'user'
    ])->get();

    // Loop through each order
    foreach ($orders as $order) {
        // Loop through each product in the order
        foreach ($order->products as $product) {
            // Map the price to the product from pivot data
            $product->price = $product->pivot->product_price;
            $product->discounted = $product->pivot->product_discounted;
            $product->discountedPercent = $product->pivot->product_discountedPercent;
        }
    }

    return $orders;
}


    public function getOrderById($hash)
    {
         
         $orders = Order::with([
        'products' => function ($query) {
            // Include pivot data from the order_product table
            $query->withPivot('product_price');
            $query->withPivot('product_discounted');
            $query->withPivot('product_discountedPercent');
            // Include color information for each product
            $query->with('color');
              $query->with('categories');
        },
        'deliveryType',
        'paymentType',
        'billingAddress',
        'deliveryAddress',
        'user'
    ])->where("hash", $hash)->get();

    // Loop through each order
    foreach ($orders as $order) {
        // Loop through each product in the order
        foreach ($order->products as $product) {
            // Map the price to the product from pivot data
            $product->price = $product->pivot->product_price;
            $product->discounted = $product->pivot->product_discounted;
            $product->discountedPercent = $product->pivot->product_discountedPercent;
        }
    }
    return $orders;
    }

   


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }



    function guidv4($data = null)
    {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);

        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    public function store(Request $request)
    {
        $totalPrice = 0;
        $cart = $request->cart;
        $overallInfo = $request->customerInfo;
        if (!$cart || !$overallInfo || !isset($overallInfo["billingInfo"]) || !$overallInfo["deliveryInfo"] || !$overallInfo["customerInfo"]) {
            return response()->json([
                'message' => 'Nastala chyba',
                'status' => 400
            ]);
        }
        $billingInfo = $overallInfo["billingInfo"];
        $deliveryInfo = $overallInfo["deliveryInfo"];
        $customerInfo = $overallInfo["customerInfo"];

        // Kontrola jestli jsou vsechny udaje vyplnene
        foreach ($customerInfo as $key => $value) {
            if (empty($value)) {
                return response()->json([
                    'message' => 'Vyplnte vsechny udaje',
                    'status' => 400
                ]);
            }
        }
        foreach ($billingInfo as $key => $value) {
            if (empty($value)) {
                return response()->json([
                    'message' => 'Vyplnte vsechny udaje',
                    'status' => 400
                ]);
            }
        }
        foreach ($deliveryInfo as $key => $value) {
            if (empty($value)) {
                return response()->json([
                    'message' => 'Vyplnte vsechny udaje',
                    'status' => 400
                ]);
            }
        }
        // Kontrola jestli je v kosiku neco
        if (empty($cart)) {
            return response()->json([
                'message' => 'Kosik je prazdny',
                'status' => 400
            ]);
        }

            $productsToSave = [];

        // Check if items in cart exist in the database
        foreach ($cart as $item) {
            $product = Product::select('prices.*', 'product_states.*', 'categories.name as category_name', 'map_table.path as category_path', 'colors.*', 'products.*')
                ->selectRaw('(SELECT GROUP_CONCAT(image_path) FROM images WHERE images.product_id = products.id AND images.is_main = 1) AS image_urls')
                ->leftJoin('prices', 'products.id', '=', 'prices.product_id')
                ->join('product_states', 'products.id', '=', 'product_states.product_id')
              
        ->join('product_categories', 'products.id', '=', 'product_categories.product_id')
        ->join('categories', 'categories.id', '=', 'product_categories.category_id') 
                ->join('map_table', 'product_categories.category_id', '=', 'map_table.category_id')
                ->leftJoin('colors', 'products.color_id', '=', 'colors.id')
                ->orderBy('products.created_at', "desc")
                ->where("products.id", $item["id"])
                ->get()[0];
            if (!$product) {
                return response()->json([
                    'message' => 'Produkt neexistuje',
                    'status' => 400
                ]);
            }
            if ($item["count"] < 0) {
                return response()->json([
                    'message' => 'Nastala chyba',
                    'status' => 400
                ]);
            }
            // Assign the count value from $item to the product
            $product['count'] = $item['count'];
            if($product->discounted){
                $totalPrice += $product->discounted * $product['count'];
            }
            else{
                $totalPrice += $product->price * $product['count'];
            }

            // Add the modified product to the $productsToSave array
            $productsToSave[] = $product;
        }
        //Save address
        $newBillingAddress = new Address();
        $newBillingAddress->city = $billingInfo["city"];
        $newBillingAddress->street = $billingInfo["street"];
        $newBillingAddress->zip = $billingInfo["zip"];
        $newBillingAddress->country = $billingInfo["country"];
        $newBillingAddress->type = $billingInfo["type"] ?? "fakturacni";
        $newBillingAddress->save();

        $newBillingAddressId = $newBillingAddress->id;

        $newDeliveryAddress = new Address();
        $newDeliveryAddress->city = $deliveryInfo["city"];
        $newDeliveryAddress->street = $deliveryInfo["street"];
        $newDeliveryAddress->zip = $deliveryInfo["zip"];
        $newDeliveryAddress->country = $deliveryInfo["country"];
        $newDeliveryAddress->type = $deliveryInfo["type"];
        $newDeliveryAddress->save();

        $newDeliveryAddressId = $newDeliveryAddress->id;

        $userId = $request->userId;
        if(!$userId){
            $foundUser = User::where('email', $customerInfo['email'])->first();
            if($foundUser){
                $userId = $foundUser->id;
                //doplnit úprava adres?
            }
            else{
                $newUser = new User();
                $newUser->name = $customerInfo["nameSurname"];
                $newUser->phone = $customerInfo["phone"];
                $newUser->prePhone = $customerInfo["prePhone"];
                $newUser->email = $customerInfo["email"];
                $newUser->address_delivery = $newDeliveryAddressId;
                $newUser->address_billing = $newBillingAddressId;
                $newUser->save();
                $userId = $newUser->id;
            }
        }
        //Save order
        $newOrder = new Order();
        $newOrder->hash = $this->guidv4();
        $newOrder->status = "vytvořena";
        $newOrder->user_id = $userId;
        $newOrder->delivery_id = $overallInfo["deliveryType"]["id"];
        $newOrder->delivery_price = $overallInfo["deliveryType"]["priceNumber"];
        $newOrder->payment_id = $overallInfo["paymentType"]["id"];
        $newOrder->payment_price = $overallInfo["paymentType"]["priceNumber"];
        $totalPrice += $overallInfo["deliveryType"]["priceNumber"];
        $totalPrice += $overallInfo["paymentType"]["priceNumber"];
        $newOrder->billing_address = $newBillingAddressId;
        $newOrder->delivery_address = $newDeliveryAddressId;
        $newOrder->total_price = $totalPrice;
        $newOrder->save();


        //Save order_products
        foreach ($productsToSave as $product) {
            $newOrderProduct = new Order_product();
            $newOrderProduct->order_id = $newOrder->id;
            $newOrderProduct->product_id = $product->id;
            $newOrderProduct->product_count = $product->count;
            $newOrderProduct->product_price = $product->price;
            $newOrderProduct->product_discounted = $product->discounted;
            $newOrderProduct->product_discountedPercent = $product->discountedPercent;
            $newOrderProduct->save();
        }
        //Send email
        $to_name = $customerInfo["nameSurname"];
        $to_email = $customerInfo["email"];
        $email_data = array('name' => $to_name, 'email' => $to_email, "order_id" => $newOrder->hash, 'order_hash' => $newOrder->hash, "total_price" => $totalPrice);

         Mail::send('shipped', $email_data, function ($message) use ($email_data) {
            $message->to($email_data['email'], $email_data['name'])
                ->subject('Potvrzení objednávky na Fitmo.cz č.' . $email_data["order_hash"])
                ->from('obchod@fitmo.cz', 'Fitmo');
        });
        return response()->json([
            'message' => 'Objednavka byla uspesne vytvorena',
            'status' => 200,
            "order_id" => $newOrder->hash,
        ]);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
