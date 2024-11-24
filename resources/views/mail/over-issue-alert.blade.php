@component('mail::message')
# Over Issued Fuel Alert

This is to inform you that the vehicle with registration number **{{ $overIssue->registration_number }}** has been flagged for being issued one-off fuel exceeding the system-defined tank capacity.

Below are the details of the transaction:

|                       |                            |
|-----------------------|-------------------------------------|
| **Registration No.**  | {{ $overIssue->registration_number }}   |
| **Vehicle Brand Name**  | {{ $overIssue->vehicle_brand_name }}   |
| **Main Tank Capacity** | {{ $overIssue->main_tank_capacity }}  |
| **Sub Tank Capacity** | {{ $overIssue->sub_tank_capacity }}  |
| **Total Tank Capacity** | {{ $overIssue->total_tank_capacity }}  |
| **One Off Quantity issued**| {{ $overIssue->one_off_quantity_issued }}  |
| **Issued variance**| {{ $overIssue->issued_variance }}  |
| **Issued variance cost**| {{ $overIssue->issued_variance_cost }}  |
| **Stores Request No**| {{ $overIssue->store_req_no }}  |
| **Quantity Requested**| {{ $overIssue->quantity_requested }}  |
| **Issue No**| {{ $overIssue->issue_no }}  |
| **Issued Fuel Value**| {{ $overIssue->issued_fuel_value }}  |
| **Issued Fuel Date**| {{ $overIssue->fuel_issue_date }}  |
| **Store Issuing**| {{ $overIssue->store_issuing_name }}  |
| **Movement Type**| {{ $overIssue->movement_type }}  |
| **Fuel Type**| {{ $overIssue->fuel_type }}  |
| **Fuel Collector**| {{ $overIssue->fuel_collector }}  |
| **Issuing Officer Name**| {{ $overIssue->issuing_officer_name }}  |
| **Issuing Officer Title**| {{ $overIssue->issuing_officer_job_title }}  |

Please take the necessary action to address this issue.


Thanks,
{{ config('app.name') }}
@endcomponent
