@php use App\Enums\RepairTypes; @endphp
@php @endphp
<div class="container-fluid">
    <input type="hidden"
           id="suppliersList"
           value="{{route('suppliers.list')}}"/>
    <ul class="nav nav-tabs" role="tablist">

        @if(!empty($details))
            @if(RepairTypes::ContractedService->value != $details->repair_type ?? '')
                <li class="nav-item" style="list-style: none; width: 178px;">
                    <a class="nav-link active" data-toggle="tab" href="#spares" role="tab">Spares</a>
                </li>
            @endif

            @if(in_array($details->repair_type ?? '',[
                        RepairTypes::ContractedService->value,
                        RepairTypes::AccidentRepair->value,
                        RepairTypes::GeneralService->value,
                        RepairTypes::GeneralRepair->value
                        ]))
                <li class="nav-item" style="list-style: none; width: 178px;">
                    <a class="nav-link @if(RepairTypes::ContractedService->value == $details->repair_type ?? '') active @endif"
                       data-toggle="tab" href="#services" role="tab">Services</a>
                </li>
            @endif
        @endif

        <li class="nav-item" style="list-style: none; width: 178px; display: none;">
            <a class="nav-link" data-toggle="tab" href="#imprest" role="tab">Imprest Buys</a>
        </li>

        <li class="nav-item" style="list-style: none; width: 178px; display: none;">
            <a class="nav-link" data-toggle="tab" href="#labour" role="tab">Labour</a>
        </li>

    </ul><!-- Tab panes -->
    <div class="tab-content">
        @if(!empty($details))

            @if(RepairTypes::ContractedService->value != $details->repair_type ?? '')
                <div class="tab-pane active" id="spares" role="tabpanel">
                    @include('modules.workshopManagement.jobCard.tabs.materials')
                </div>
            @endif

            @if(in_array($details->repair_type ?? '',[
                    RepairTypes::ContractedService->value,
                    RepairTypes::AccidentRepair->value,
                    RepairTypes::GeneralService->value,
                    RepairTypes::GeneralRepair->value
                ]))
                <div
                    class="tab-pane @if(RepairTypes::ContractedService->value == $details->repair_type ?? '') active @endif"
                    id="services" role="tabpanel">
                    @include('modules.workshopManagement.jobCard.tabs.services')
                </div>
            @endif
        @endif

        <div class="tab-pane" id="imprest" role="tabpanel">
            @include('modules.workshopManagement.jobCard.tabs.imprest_buy')
        </div>
        <div class="tab-pane" id="labour" role="tabpanel">
            @include('modules.workshopManagement.jobCard.tabs.labour')
        </div>
    </div>
</div>
