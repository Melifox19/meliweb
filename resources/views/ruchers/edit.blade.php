@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            {{__('tables.apiary')}}
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($rucher, ['route' => ['ruchers.update', $rucher->id], 'method' => 'patch']) !!}

                        @include('ruchers.fields_edit')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection
