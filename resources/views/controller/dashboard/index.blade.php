@extends('frontApp')

@section('meta-header')
   Main page
@endsection
@section('content')
    <div style="text-align: center">
        <img src="/images/logo.png" alt="" style="">
        <h4>Schnellsuche</h4>

        <form class="form-inline">
            <select class="form-control">
                <option value="">Type</option>
                <option value="rent">Rent</option>
                <option value="sale">Sale</option>
            </select>
            <input type="text"  class="form-control" placeholder="Zip, City">
            <select class="form-control" >
                <option value="">Radius</option>
            </select>
            <input type="text" class="form-control" placeholder="Area from">
            <select class="form-control" >
                <option value="">Rooms</option>
            </select>
            <input type="text" class="form-control" placeholder="Price from">
            <button type="button" class="btn  btn-info">Search</button>
        </form>
        <hr>
    </div>
@endsection