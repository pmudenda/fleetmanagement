@php use App\Enums\RequisitionItemTypes;use App\Helpers\StatusHelper;use App\Models\reference\PurchaseOffice;use Carbon\Carbon;
@endphp
<div class="container-fluid">
    <input type="hidden"
           id="suppliersList"
           value="{{route('suppliers.list')}}"/>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" style="list-style: none;">
            <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Materials</a>
        </li>
        <li class="nav-item" style="list-style: none;">
            <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Services</a>
        </li>
        <li class="nav-item" style="list-style: none;">
            <a class="nav-link" data-toggle="tab" href="#tabs-3" role="tab">Labour</a>
        </li>
    </ul><!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="tabs-1" role="tabpanel">
            <p>First Panel</p>
            @include('modules.requisitions.maintenance.tabs.materials')
        </div>
        <div class="tab-pane" id="tabs-2" role="tabpanel">
            <p>Second Panel</p>
        </div>
        <div class="tab-pane" id="tabs-3" role="tabpanel">
            <p>Third Panel</p>
        </div>
    </div>
</div>
