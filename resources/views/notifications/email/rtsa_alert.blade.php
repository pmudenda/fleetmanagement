<x-mail::message>
# 🚗 Daily Vehicle Compliance Report

### 📊 Daily Snapshot
**Total Active Vehicles:** {{number_format($totalVehicles)}}\
**✅ Compliant Vehicles:** {{number_format($compliantCount)}} ({{ round(($compliantCount/$totalVehicles)*100,2) }}%)\
**❌ Non-Compliant:** {{number_format($nonCompliantCount)}} ({{ round(($nonCompliantCount/$totalVehicles)*100,2) }}%)\
**🔄 Awaiting Sync:** {{number_format($unsyncedCount)}} ({{ round(($unsyncedCount/$totalVehicles)*100,2) }}%)

### ⚠️ Critical Issues
**🚗 Expired Road Tax:** {{number_format($expiredTaxCount)}} vehicles\
**🔧 Expired Fitness:** {{number_format($expiredFitnessCount)}} vehicles\
**🚫 Suspended Vehicles:** {{number_format($suspendedCount)}} vehicles

### 📈 Top Compliance Issues
@foreach($statusBreakdown->take(3) as $status => $count)
• **{{ $status }}:** {{ $count }} vehicles ({{ round(($count/$nonCompliantCount)*100) }}% of non-compliant)\
@endforeach

### 🎯 Today's Priority Actions
1. **Address** {{ $statusBreakdown->first() }} issues ({{ $statusBreakdown->values()->first() }} vehicles)
2. **Renew** road tax for {{ min(10, $expiredTaxCount) }} most urgent cases
3. **Sync** {{ number_format($unsyncedCount) }} unsynced vehicles

## 📎 Attachment
- **`non-compliant-vehicles-{{date('Y-m-d')}}.csv`** - Complete list for action planning

---
*Daily automated report • {{now()->format('Y-m-d H:i')}}*
*Next update: Tomorrow 7:00 AM*

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>