@extends('layouts.app')
@section('title-section', $conf['title-section'])

@section('btn')
<x-btns :group="$conf['group']" :create="$conf['create']" />
@endsection


@section('content')
<div class="row">
    @if ($message = Session::get('message'))
        <x-cards :message="$message" />
    @endif
</div>
    <div class="row">
        
              
        <x-cards>
            <table class="table table-sm table-bordered table-hover mb-0">
                <thead class="text-white bg-gray-900">
                    <tr>
                        <th width="3%" class="mb-0 text-center align-middle text-uppercase">#</th>
                        <th width="40%" class="mb-0 text-uppercase">Nombre</th>
                        <th width="40%" class="mb-0 text-uppercase">Rut</th>
                    </tr>
                </thead>
                @foreach ($table['data'] as $key => $user)
                <tr onclick="window.location='{{route('compania.show', $user->id)}}';">
                    <td class="text-center align-middle mb-0" >{{ ++$i }}</td>
                    <td class="align-middle mb-0">{{ $user->name }}</td>
                    <td class="align-middle mb-0">{{ $user->rut }}</td>
                    
                </tr>
                @endforeach
            </table>          
            
            
            
            
            
            
            
            {!! $table['data']->render() !!}
        </x-cards>
    </div>
@endsection

