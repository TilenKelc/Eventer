@extends('layouts.app')

@section('content')

    <div class="container product_add">
        @include('common.errors')

        @if ($product == null)
            <div class="header">
                <div class="content">Dodajanje novih miz</div>
            </div>
            <form action="{{ url('product/save') }}" method="POST" enctype="multipart/form-data" id="data-form">
                {{ csrf_field() }}
                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Naziv</label>
                    <input type="text" class="form-control" name="name">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Slike:</label>
                    <input type="file" class="form-control-file" name="product_image" required>
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Restavracija:</label>
                    <select name="category_id">
                        <option disabled selected>Izberite restavracijo:</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group submit-btn">
                    <button type="button" id="submitBtn" class="btn">Dodaj</button>
                </div>
            </form>
        @else
            <div class="header">
                <div class="content">Posodobitev miz</div>
            </div>
            <form action='{{ url("product/save/$product->id") }}' method="POST" enctype="multipart/form-data" id="data-form">
                {{ csrf_field() }}

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Naziv</label>
                    <input type="text" class="form-control" name="name" value="{{ $product->name }}">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Slika:</label>
                    <input type="file" class="form-control-file" name="product_image">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Trenutna slika:</label>
                    <img src="{{ url($product->image) }}" class="form-control" alt="Slika produkta">
                </div>

                <div class="form-group col-md-4">
                    <label class="col-form-label text-md">Restavracija:</label>
                    <select name="category_id">
                        <option disabled selected>Izberite restavracijo:</option>
                        @foreach ($categories as $category)
                            @if ($product->category_id == $category->id)
                                <option value="{{ $category->id }}" selected>{{ $category->name }}</option>
                            @else
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>  

                <div class="form-group submit-btn">
                    <button type="button" id="submitBtn" class="btn">Shrani</button>
                </div>
            </form>
            <form action='{{ url("product/delete/$product->id") }}' method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="form-group submit-btn">
                    <button type="submit" class="btn">Izbriši</button>
                </div>
            </form>
        @endif
    </div>


    <style>
        .hide{
            display: none;
        }
    </style>

    <script>
        $(document).ready(function () {
            function getIds(){
                let allProducts = $('.product-item').map((_,el) => el.value).get();
                let product_ids = [];

                for(let product of allProducts){
                    if(product == ''){
                        continue;
                    }
                    try{
                        let product_id = product.split('(')[1].toString();
                        product_id = product_id.replace(')', '');
                        product_id = product_id.replaceAll('\'', '');
                        product_id.trim();
                        product_ids.push(parseInt(product_id));
                    }catch (error) {
                        // invalid
                    }
                }
                return product_ids;
            }

            $(document).on('keyup', '.product-item', function(){
                var query = $(this).val();
                if(query != ''){
                    var _token = $('input[name="_token"]').val();
                    $.ajax({
                        url:"{{ route('product.fetch') }}",
                        method:"POST",
                        data:{
                            query: query, 
                            already: getIds(), 
                            _token:_token},
                        success:function(response){
                            $('#productList').fadeIn();
                            $('#productList').html(response);
                        }
                    });
                }else{
                    $('#productList').fadeOut();
                }
            });
            
            $(document).on('click', '.najdenrezultat', function(){
                let product = $(this).text();
                product = product.replace(/\s\s+/g, ' ');
                let product_name = product.split('|||')[0];

                let item = $('#active-item');
                item.val(product_name);

                let addBtn = $('#add-item');
                addBtn.show();

                $('#productList').fadeOut();  
            });
            
            $(document).on('click', '#add-item', function(){
                let new_input = "<br><input type='text' class='product-item form-control custom-search-form' id='active-item'>";
                let current_input = $('#active-item');
                current_input.removeAttr('id');
                current_input.prop("disabled", true);

                let removeBtn = "<i class='fa fa-minus-circle remove-item custom-search-btn' aria-hidden='true'></i>";
                removeBtn = $(removeBtn).insertAfter(current_input);

                $(new_input).insertAfter(removeBtn);
                $('#add-item').hide();
            });

            $(document).on('click', '.remove-item', function(){
                let input = this.previousSibling;
                let line_break = this.nextSibling;
                input.parentElement.removeChild(input);
                this.parentElement.removeChild(this);
                line_break.parentElement.removeChild(line_break);
            });

            $('#submitBtn').click(function(){
                $("#recommended_items").val('[' + getIds().toString() + ']');
                $("#data-form").submit();
            });
        });
    </script>
 @endsection
