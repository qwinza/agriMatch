@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Pilih Peran Anda</h3>
    <form action="/choose-role" method="POST">
        @csrf
        <div>
            <label>
                <input type="radio" name="role" value="petani" required> Petani
            </label>
        </div>
        <div>
            <label>
                <input type="radio" name="role" value="pembeli" required> Pembeli
            </label>
        </div>
        <button type="submit" class="btn btn-primary mt-2">Lanjutkan</button>
    </form>
</div>
@endsection
