@php use App\Enums\RequisitionItemTypes;use App\Helpers\StatusHelper;use App\Models\reference\PurchaseOffice;use Carbon\Carbon;
@endphp
<div class="container-fluid">
    <input type="hidden"
           id="suppliersList"
           value="{{route('suppliers.list')}}"/>
    <div class="container"><h2>Example 3 </h2></div>
    <div id="exTab3" class="container">
        <ul  class="nav nav-pills">
            <li class="active">
                <a  href="#1b" data-toggle="tab">Materials</a>
            </li>
            <li><a href="#2b" data-toggle="tab">Services</a>
            </li>
            <li><a href="#3b" data-toggle="tab">Applying clearfix</a>
            </li>
            <li><a href="#4a" data-toggle="tab">Background color</a>
            </li>
        </ul>

        <div class="tab-content clearfix">
            <div class="tab-pane active" id="1b">
                @include('modules.requisitions.maintenance.tabs.materials')
            </div>
            <div class="tab-pane" id="2b">
                <h3>We use the class nav-pills instead of nav-tabs which automatically creates a background color for the tab</h3>
            </div>
            <div class="tab-pane" id="3b">
                <h3>We applied clearfix to the tab-content to rid of the gap between the tab and the content</h3>
            </div>
            <div class="tab-pane" id="4b">
                <h3>We use css to change the background color of the content to be equal to the tab</h3>
            </div>
        </div>
    </div>

</div>
