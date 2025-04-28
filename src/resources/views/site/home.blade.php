@extends('layouts.home')

@section('title', 'Início')

@section('content')
    <h1>Olá, {{ Auth()->user()->username }}! Bem-vindo ao sistema de hotelaria!</h1>
    <p>Escolha uma opção no menu lateral.</p>
@endsection
