@if($errors->any())
<div class="alert alert-danger" role="alert">
    @foreach($errors->all() as $error)
    <li class="text-center" style="list-style-type: none; ">{{ $error }}</li>
    @endforeach
</div>
@endif
