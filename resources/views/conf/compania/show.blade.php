@extends('layouts.app')
@section('title-section', $conf['title-section'])

@section('btn')
    <x-btns :back="$conf['back']" :group="$conf['group']" :edit="$conf['edit']" />
@endsection

@section('content')



<div class="row">
    <x-cards size="12">
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>nombre:</strong>
            {{ $user->name }}
        </div>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-12">
        <div class="form-group">
            <strong>rut:</strong>
            {{ $user->rut }}
        </div>
    </div>
    
    </x-cards>
</div>

@endsection