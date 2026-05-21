@if (count($errors) > 0)
<div class="container">      
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
        <em>{{ $error }}</em>
        @endforeach
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span> </button>
    </div>
</div>
@endif