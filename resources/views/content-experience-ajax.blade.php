@if(@$experiences)
@foreach (@$experiences as $experience)
@include('content-experience', (array)$experience)
@endforeach
@endif