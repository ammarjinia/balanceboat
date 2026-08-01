@php $tierLabel = \App\Http\Helpers\CommonHelper::commissionTierLabel(isset($experience->commission) ? (float) $experience->commission : null); @endphp
@if($tierLabel)
<div class="tier-badge">{{ $tierLabel }}</div>
@endif
