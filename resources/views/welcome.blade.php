@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Product Form</div>
                    <h5 id="success-msg" class="text-success pl-2 pt-2"></h5>
                    <div class="card-body">

                        <form id="product_form">
                            <div class="form-group">
                                <label>Product name</label>
                                <input required type="text" class="form-control" id="product_name" placeholder="Enter product name">
                                <small id="productHelp" class="form-text text-muted">This field is required.</small>
                            </div>
                            <div class="form-group">
                                <label>Quantity in stock</label>
                                <input required type="number" class="form-control" id="quantity_in_stock" placeholder="Enter quantity in stock">
                                <small id="quantityHelp" class="form-text text-muted">This field is required.</small>
                            </div>
                            <div class="form-group">
                                <label>Price per item</label>
                                <input required type="number" class="form-control" id="price_per_item" placeholder="Enter price per item">
                                <small id="quantityHelp" class="form-text text-muted">This field is required.</small>
                            </div>

                            <button type="submit" id="submit_btn" class="btn btn-primary">Submit</button>
                        </form>

                    </div>
                </div>
                <br>
            </div>
            <div class="col-md-12">
                <table class="table table-striped table-bordered">
                    <thead>
                    <tr class="bg-primary text-white">
                        <th>Product name</th>
                        <th>Quantity in stock</th>
                        <th>Price per item</th>
                        <th>Total value number</th>
                        <th class="text-center">Datetime Submitted</th>
                        <th class="text-center">Action</th>
                    </tr>
                    </thead>
                    <tbody id="products_html">
                        {!! $html !!}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script>
        $('#product_form').on('submit', function (e) {
            e.preventDefault();

            let product_name = $('#product_name').val();
            let quantity_in_stock = $('#quantity_in_stock').val();
            let price_per_item = $('#price_per_item').val();

            $.ajax({
                url: "{{ url('/') }}",
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    product_name: product_name,
                    quantity_in_stock: quantity_in_stock,
                    price_per_item: price_per_item,
                },
                success: function (response) {
                    if (response) {
                        console.log(response);
                        $('#success-msg').text("Product saved successfully!");
                        $('#product_form')[0].reset();
                        // populate listing grid
                        $('#products_html').html(response);
                    }
                },
                error: function () {

                }
            });
        });
    </script>
@endsection
