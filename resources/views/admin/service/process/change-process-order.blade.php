@extends('admin.layouts.app')
@prepend('page-css')
<style>
    #serviceProcessOrder li a {
        cursor: move;
    }
</style>
@endprepend
@section('page-title', 'Change Process Order')
@section('content')
<div class="card">
    <div class="card-body">
        <h2 class="card-title">{{ $service->name }}</h2>
        <hr>
        <form action="{{ route('admin.update.process.order', $service->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <ul class="inbox-wid list-unstyled" id="serviceProcessOrder">
                @foreach ($service->process as $process)
                <li class="inbox-list-item">
                    <input type="hidden" name="service_process[]" value="{{ $process->id }}">
                    <a href="javascript:void(0)">
                        <div class="d-flex align-items-start">
                            <div class="me-3 align-self-center">
                                <i class="mdi mdi-drag-vertical" style="font-size: 22px;"></i>
                            </div>
                            <div class="flex-1 overflow-hidden text-center">
                                <h5 class="font-size-16 mt-1">{{ $process->user->fullname }}</h5>
                                <h5 class="font-size-15 mt-1">{{ $process->office->description }}</h5>
                                <p class="text-truncate mb-0 mt-1">{{ $process->user->userPosition->position_name }}</p>
                                <p class="text-truncate mb-0 mt-1">{{ $process->responsible }}</p>
                            </div>
                        </div>
                    </a>
                </li>
                @endforeach
            </ul>
            <div class="form-group">
                <div class="text-end">
                    <button onclick="history.back()" type="button" class="btn btn-danger">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@push('page-scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js" integrity="sha512-zYXldzJsDrNKV+odAwFYiDXV2Cy37cwizT+NkuiPGsa9X1dOz04eHvUWVuxaJ299GvcJT31ug2zO4itXBjFx4w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
$(document).ready(function(){
    new Sortable(serviceProcessOrder, {
        animation: 150,
        ghostClass: 'blue-background-class'
    });
});
</script>
@endpush
