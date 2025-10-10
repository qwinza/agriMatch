@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Produk Hasil Tani</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($products as $product)
        <div class="col-md-3 mb-3">
            <div class="card h-100">
                <img src="{{ asset('storage/'.$product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                <div class="card-body">
                    <h5>{{ $product->name }}</h5>
                    <p>{{ Str::limit($product->description, 60) }}</p>
                    <p><strong>Rp {{ number_format($product->price) }}</strong></p>
                    <a href="{{ route('products.show', $product) }}" class="btn btn-success btn-sm">Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
