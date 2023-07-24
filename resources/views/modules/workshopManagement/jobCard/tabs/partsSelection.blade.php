@php use App\Enums\RequisitionItemTypes;use App\Helpers\StatusHelper;use App\Models\reference\PurchaseOffice;use Carbon\Carbon;
@endphp
<div class="container-fluid">
    <input type="hidden"
           id="suppliersList"
           value="{{route('suppliers.list')}}"/>
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item" style="list-style: none; width: 178px;">
            <a class="nav-link active" data-toggle="tab" href="#spares" role="tab">Spares</a>
        </li>
        <li class="nav-item" style="list-style: none; width: 178px;">
            <a class="nav-link" data-toggle="tab" href="#services" role="tab">Services</a>
        </li>
        <li class="nav-item" style="list-style: none; width: 178px;">
            <a class="nav-link" data-toggle="tab" href="#imprest" role="tab">Imprest Buys</a>
        </li>
        <li class="nav-item" style="list-style: none; width: 178px; display: none;">
            <a class="nav-link" data-toggle="tab" href="#labour" role="tab">Labour</a>
        </li>
    </ul><!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="spares" role="tabpanel">
            @include('modules.workshopManagement.jobCard.tabs.materials')
        </div>
        <div class="tab-pane" id="services" role="tabpanel">
            @include('modules.workshopManagement.jobCard.tabs.services')
        </div>
        <div class="tab-pane" id="imprest" role="tabpanel">
            @include('modules.workshopManagement.jobCard.tabs.imprest_buy')
        </div>
        <div class="tab-pane" id="labour" role="tabpanel">
            @include('modules.workshopManagement.jobCard.tabs.labour')
        </div>
    </div>
</div>
