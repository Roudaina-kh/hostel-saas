@extends('layouts.app')
@section('title', 'Modifier espace tente')
@section('content')
<div style="max-width:600px;">
    <div style="margin-bottom:1.5rem;">
        <h1 style="font-size:1.5rem; font-weight:700; color:#1A2B3C; margin:0;">Modifier : {{ $tentSpace->name }}</h1>
    </div>
    <div style="background:white; border-radius:1rem; padding:2rem; border:1px solid #E8EEF2;">
        <form method="POST" action="{{ route('tent-spaces.update', $tentSpace) }}">
            @csrf @method('PUT')
            @include('tent-spaces._form', ['tentSpace' => $tentSpace])
            <div style="display:flex; gap:0.75rem; margin-top:1.5rem;">
                <button type="submit" style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:700; color:white; border:none; cursor:pointer; background:linear-gradient(135deg,#1A4A6B,#2C6E8A); box-shadow:0 4px 15px rgba(44,110,138,0.3);">Enregistrer</button>
                <a href="{{ route('tent-spaces.index') }}" style="padding:0.75rem 1.5rem; border-radius:0.75rem; font-size:0.875rem; font-weight:500; color:#5A6B7A; text-decoration:none; background:#F8FBFD; border:1px solid #E8EEF2;">Annuler</a>
            </div>
        </form>
    </div>
</div>
@endsection