@extends('layouts.app')
@section('page-title', 'services')
@prepend('page-css')
<style>
.list:hover{
    opacity: 0.9;
}
</style>
@endprepend
@section('content')
@foreach($officesWithService as $office)
@if ($office->services[0]->process != '[]')
<div class="accordion" id="accordion">
     <div class="card">
          <div class="accordion-item">
               <h2 class="accordion-header" id="heading-{{ $loop->index }}">
                    <button style="background-color: #283D92;" class="list accordion-button text-white" type="button" data-bs-toggle="collapse" data-bs-target="#item-{{ $loop->index }}" aria-expanded="true" aria-controls="item-{{ $loop->index }}">
                         {{ $office->description }} - {{ $office->services_count }}
                    </button>
               </h2>
               <div id="item-{{ $loop->index }}" class="accordion-collapse collapse" aria-labelledby="heading-{{ $loop->index }}" data-bs-parent="#accordion">
                    <div class="accordion-body">
                        <div class="list-group">
                        @foreach($office->services as $service)
                        <p><a style="background-color: #283D92;" class="list text-white list-group-item list-group-item-action" href="{{ route('service.show', $service->id) }}"><i class="mdi mdi-arrow-right"></i> {{ $service->name }} - {{ $service->description }}</a></p>
                        @endforeach
                    </div>
                    </div>
               </div>
          </div>
     </div>
</div>
@endif
@endforeach
@endsection
