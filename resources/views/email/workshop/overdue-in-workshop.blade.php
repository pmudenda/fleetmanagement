@component('mail::message')
# Overdue Workshop Alert

The following vehicles have exceeded their expected service completion date in the workshop. Please review the details below:
<x-mail::table>
| **Reg No** | **Workshop Name**     | **Date In** | **Date Out**          | **Days Overdue** |
|------------|:-----------------------:|:-------------:|:-----------------------:|------------------:|
@foreach ($vehicles as $vehicle)
| {{ $vehicle->reg_no }} | {{ $vehicle->workshop_name }} | {{ $vehicle->date_in }} | {{ $vehicle->expected_date_out }}  | {{ $vehicle->days_overdue }} |
@endforeach
</x-mail::table>
@component('mail::button', ['url' => $actionUrl])
View Details
@endcomponent

Thanks,
{{ config('app.name') }}
@endcomponent
