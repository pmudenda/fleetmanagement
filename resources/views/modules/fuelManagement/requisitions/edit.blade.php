@php use App\Enums\RequisitionTypes;use Carbon\Carbon; @endphp
@extends('layouts.app')
@push('styles')
    <link href="{{asset("assets/plugins/select2/css/select2.min.css")}}" rel="stylesheet" type="text/css"/>
    <link href="{{asset("assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css")}}" rel="stylesheet"
          type="text/css"/>
    <style>
        .card .card-body {
            padding: 2rem 1rem !important;
        }
    </style>
@endpush
@section('content')
    <x-content-header/>
    <section class="content">
        <div class="card">
            <div class="card-header">
                <div class="card-title">
                    <h4>APPROVE STORES REQUISITION</h4>
                </div>
                <div class="card-toolbar justify-content-end">
                    @if(!empty($requestDetails))
                        <span class="badge pl-2 {{$requestDetails->color_code ?? ''}}">
                       {{$requestDetails->status_name ?? ''}}
                   </span>
                    @endif
                </div>
            </div>

            <div class="card-body pb-4 min-h-600px pt-0">

                <x-error-view/>


                <input type="hidden"
                       value="{{route('update.fuel.requisition')}}"
                       data-url="{{ route('workflow.approve') }}"
                       id="approvalUrl">
                <input type="hidden" value="{{ $requestDetails->req_no }}" id="taskReference">
            </div>
        </div>

        <x-fuel-workflow-approvers :task="$workflowTask" :request="$requestDetails"/>

        <x-workflow-approval-history :approvals="$approvalHistory" :request="$requestDetails"/>
    </section>
@endsection
@push('scripts')
    <script>
        window.citiesMap = {!! json_encode($cities) !!};
        window.citiesFrom = {!! json_encode($citiesFrom) !!};
        window.tripPeriodLimit = {!! config('maxTripPeriod') ?? 7 !!};
    </script>
    <script src="{{asset('assets/plugins/select2/js/select2.min.js')}}"></script>
    <script src="{{asset('modules/fuelManagement/requisitions/workflow.js')}}"></script>
    <script src="{{asset('modules/fuelManagement/requisitions/create.js')}}"></script>
@endpush
