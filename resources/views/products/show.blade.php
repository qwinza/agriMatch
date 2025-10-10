@extends('layouts.app')

@section('content')
<div class="container">
    <h3>{{ $product->name }}</h3>
    <img src="{{ asset('storage/'.$product->image) }}" width="300">
    <p>{{ $product->description }}</p>
    <p>Harga: <strong>Rp {{ number_format($product->price) }}</strong></p>

    @auth
        @if(auth()->user()->isPembeli())
        <form method="POST" action="{{ route('order.store', $product->id) }}">
            @csrf
            <label>Jumlah:</label>
            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}">
            <button class="btn btn-primary">Beli Sekarang</button>
        </form>
        @endif

        <hr>

        <form method="POST" action="{{ route('review.store', $product->id) }}">
            @csrf
            <label>Rating:</label>
            <select name="rating">
                @for($i=1;$i<=5;$i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                @endfor
            </select>
            <input type="text" name="comment" placeholder="Tulis ulasan...">
            <button class="btn btn-outline-success btn-sm">Kirim Ulasan</button>
        </form>
    @endauth

    <h5 class="mt-4">Ulasan:</h5>
    @foreach($product->reviews as $review)
        <p>⭐ {{ $review->rating }} - {{ $review->comment }}</p>
    @endforeach
</div>
@endsection
