<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = File::exists(storage_path("products.json")) ? json_decode(File::get(storage_path("products.json"))) : [];

        $html = $this->getProductsHtml($products);
        return view('welcome')->with('html', $html);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string',
            'quantity_in_stock' => 'required|integer',
            'price_per_item' => 'required|integer',
        ]);

        $products = File::exists(storage_path("products.json")) ? json_decode(File::get(storage_path("products.json"))) : [];

        $json = $request->except('_token');
        $json['total_value_number'] = $request->quantity_in_stock * $request->price_per_item;
        $json['datetime_submitted'] = Carbon::now()->format("Y-m-d H:i:s");

        array_push($products, $json);

        // sort array descending on datetime_submitted
        $datetime_submitted = array_column($products, 'datetime_submitted');
        array_multisort($datetime_submitted, SORT_DESC, $products);

        File::put(storage_path("products.json"), json_encode($products));

        $html = $this->getProductsHtml($products);
        $response = Response::make($html);
        $response->header('Content-Type', 'text/plain');
        return $response;
    }

    public function getProductsHtml($products)
    {
        if (!count($products)) {
            return '';
        }

        $html = '';
        $total = 0;
        foreach ($products as $product) {
            $product = (array) $product;
            $total+=$product['total_value_number'];
            $html .= '<tr>
                        <td>'.$product['product_name'].'</td>
                        <td>'.$product['quantity_in_stock'].'</td>
                        <td>'.$product['price_per_item'].'</td>
                        <td>'.$product['total_value_number'].'</td>
                        <td>'.$product['datetime_submitted'].'</td>
                        <td align="center">
                            <a href="" class="text-primary"><i class="fa fa-fw fa-edit"></i> Edit</a> |
                            <a href="" class="text-danger" onClick="return confirm(\'Are you sure to delete this user?\');"><i class="fa fa-fw fa-trash"></i> Delete</a>
                        </td>
                    </tr>';
        }
        $html .= '<tr>
                        <td></td>
                        <td></td>
                        <td><strong>Total</strong></td>
                        <td>'.$total.'</td>
                        <td></td>
                        <td></td>
                    </tr>';

        return $html;
    }
}
