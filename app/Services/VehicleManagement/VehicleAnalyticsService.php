<?php

namespace App\Services\VehicleManagement;

use App\Constants\QueryComparisonOperator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class VehicleAnalyticsService
{
    /**
     * Get top vehicles by fuel consumption
     */
    public function getTopVehiclesByFuelConsumption(int $limit = 10): array
    {
        try {
            $results = DB::table('fleetmaster.fuel_management')
                ->select(
                    'reg_no',
                    DB::raw('SUM(amount) as total_fuel_cost'),
                    DB::raw('COUNT(*) as transaction_count'),
                    DB::raw('AVG(amount) as avg_fuel_cost'),
                    DB::raw('MAX(FECH_ACT) as last_transaction_date')
                )
                ->where('FECH_ACT', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -6)'))
                ->groupBy('reg_no')
                ->orderBy('total_fuel_cost', 'desc')
                ->take($limit)
                ->get();

            return $results->toArray();
        } catch (Exception $e) {
            Log::error("Error fetching top vehicles by fuel consumption: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top vehicles by maintenance costs
     */
    public function getTopVehiclesByMaintenanceCost(int $limit = 10): array
    {
        try {
            $results = DB::table('fleetmaster.gen_material_details as d')
                ->join('fleetmaster.gen_material_headers as h', 'h.REQ_NO', '=', 'd.REQ_NO')
                ->join('fleetmaster.vm_vehicle_header as g', 'd.reg_no', '=', 'g.REGISTRATION_NUMBER')
                ->join('ZFM_ARTICLES_VIEW as a', 'd.MATERIAL_CODE', '=', 'a.code_article')
                ->join('fleetmaster.vm_engine_details as ed', 'g.REGISTRATION_NUMBER', '=', 'ed.reg_no')
                ->join('fleetmaster.vm_assignments as va', 'g.REGISTRATION_NUMBER', '=', 'VA.REG_NO')
                ->join('fleetmaster.tms_data_clean_up as td', 'g.REGISTRATION_NUMBER', '=', 'td.REGISTRATIONNUMBER')
                ->join('store_movements_header as mh', 'h.st_pur', '=', 'mh.stores_requisition_no')
                ->join('fleetmaster.gps as gps', 'g.REGISTRATION_NUMBER', '=', 'gps.REG_NUMBER')
                ->select(
                    'd.reg_no',
                    DB::raw('ed.engine_brand || \' \' || g.model_name as vehicle_type'),
                    DB::raw('SUM(d.QUANTITY * d.PRICE) as total_maintenance_cost'),
                    DB::raw('COUNT(*) as maintenance_count'),
                    DB::raw('AVG(d.QUANTITY * d.PRICE) as avg_maintenance_cost'),
                    DB::raw('MAX(h.DATE_CREATED) as last_maintenance_date'),
                    DB::raw('va.BUSINESS_UNIT_NAME || \' \' || va.COST_CENTER_NAME as vehicle_assignment'),
                    'td.ORGANIZATIONALUNIT'
                )
                ->where('h.status', 'IN', ['26', '32', '42', '46'])
                ->where('h.IS_FUEL', '=', 'N')
                ->where('h.DATE_CREATED', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -6)'))
                ->groupBy('d.reg_no', 'ed.engine_brand', 'g.model_name', 'va.BUSINESS_UNIT_NAME', 'va.COST_CENTER_NAME', 'td.ORGANIZATIONALUNIT')
                ->orderBy('total_maintenance_cost', 'desc')
                ->take($limit)
                ->get();

            return $results->toArray();
        } catch (Exception $e) {
            Log::error("Error fetching top vehicles by maintenance cost: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get vehicles with highest total operating costs
     */
    public function getTopVehiclesByOperatingCosts(int $limit = 10): array
    {
        try {
            // Get fuel costs
            $fuelData = DB::table('fleetmaster.fuel_management')
                ->select(
                    'reg_no',
                    DB::raw('SUM(amount) as fuel_cost')
                )
                ->where('FECH_ACT', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -6)'))
                ->groupBy('reg_no');

            // Get maintenance costs with comprehensive joins
            $maintenanceData = DB::table('fleetmaster.gen_material_details as d')
                ->join('fleetmaster.gen_material_headers as h', 'h.REQ_NO', '=', 'd.REQ_NO')
                ->join('fleetmaster.vm_vehicle_header as g', 'd.reg_no', '=', 'g.REGISTRATION_NUMBER')
                ->join('ZFM_ARTICLES_VIEW as a', 'd.MATERIAL_CODE', '=', 'a.code_article')
                ->join('fleetmaster.vm_engine_details as ed', 'g.REGISTRATION_NUMBER', '=', 'ed.reg_no')
                ->join('fleetmaster.vm_assignments as va', 'g.REGISTRATION_NUMBER', '=', 'VA.REG_NO')
                ->join('fleetmaster.tms_data_clean_up as td', 'g.REGISTRATION_NUMBER', '=', 'td.REGISTRATIONNUMBER')
                ->join('store_movements_header as mh', 'h.st_pur', '=', 'mh.stores_requisition_no')
                ->join('fleetmaster.gps as gps', 'g.REGISTRATION_NUMBER', '=', 'gps.REG_NUMBER')
                ->select(
                    'd.reg_no',
                    DB::raw('SUM(d.QUANTITY * d.PRICE) as maintenance_cost'),
                    DB::raw('ed.engine_brand || \' \' || g.model_name as vehicle_type'),
                    DB::raw('va.BUSINESS_UNIT_NAME || \' \' || va.COST_CENTER_NAME as vehicle_assignment'),
                    'td.ORGANIZATIONALUNIT'
                )
                ->where('h.status', 'IN', ['26', '32', '42', '46'])
                ->where('h.IS_FUEL', '=', 'N')
                ->where('h.DATE_CREATED', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -6)'))
                ->groupBy('d.reg_no', 'ed.engine_brand', 'g.model_name', 'va.BUSINESS_UNIT_NAME', 'va.COST_CENTER_NAME', 'td.ORGANIZATIONALUNIT');

            // Combine fuel and maintenance costs
            $results = DB::table('fleetmaster.vm_vehicle_header as v')
                ->select(
                    'v.REGISTRATION_NUMBER as reg_no',
                    DB::raw('COALESCE(fuel_data.fuel_cost, 0) as fuel_cost'),
                    DB::raw('COALESCE(maintenance_data.maintenance_cost, 0) as maintenance_cost'),
                    DB::raw('COALESCE(fuel_data.fuel_cost, 0) + COALESCE(maintenance_data.maintenance_cost, 0) as total_cost')
                )
                ->leftJoinSub($fuelData, 'fuel_data', function ($join) {
                    $join->on('v.REGISTRATION_NUMBER', '=', 'fuel_data.reg_no');
                })
                ->leftJoinSub($maintenanceData, 'maintenance_data', function ($join) {
                    $join->on('v.REGISTRATION_NUMBER', '=', 'maintenance_data.reg_no');
                })
                ->whereNotNull('fuel_data.reg_no')
                ->orWhereNotNull('maintenance_data.reg_no')
                ->orderBy('total_cost', 'desc')
                ->take($limit)
                ->get();

            return $results->toArray();
        } catch (Exception $e) {
            Log::error("Error fetching top vehicles by operating costs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get vehicle performance trends for a specific vehicle
     */
    public function getVehiclePerformanceTrends(string $registrationNumber, int $months = 12): array
    {
        try {
            $trends = [];

            // Get monthly fuel trends
            $fuelTrends = DB::table('fleetmaster.fuel_management')
                ->select(
                    DB::raw('TO_CHAR(FECH_ACT, \'YYYY-MM\') as period'),
                    DB::raw('SUM(amount) as fuel_cost'),
                    DB::raw('COUNT(*) as fuel_transactions')
                )
                ->where('reg_no', QueryComparisonOperator::EQUALS, $registrationNumber)
                ->where('FECH_ACT', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -' . $months . ')'))
                ->groupBy(DB::raw('TO_CHAR(FECH_ACT, \'YYYY-MM\')'))
                ->orderBy('period')
                ->get();

            // Get monthly maintenance trends with comprehensive joins
            $maintenanceTrends = DB::table('fleetmaster.gen_material_details as d')
                ->join('fleetmaster.gen_material_headers as h', 'h.REQ_NO', '=', 'd.REQ_NO')
                ->join('fleetmaster.vm_vehicle_header as g', 'd.reg_no', '=', 'g.REGISTRATION_NUMBER')
                ->join('ZFM_ARTICLES_VIEW as a', 'd.MATERIAL_CODE', '=', 'a.code_article')
                ->join('fleetmaster.vm_engine_details as ed', 'g.REGISTRATION_NUMBER', '=', 'ed.reg_no')
                ->join('fleetmaster.vm_assignments as va', 'g.REGISTRATION_NUMBER', '=', 'VA.REG_NO')
                ->join('fleetmaster.tms_data_clean_up as td', 'g.REGISTRATION_NUMBER', '=', 'td.REGISTRATIONNUMBER')
                ->join('store_movements_header as mh', 'h.st_pur', '=', 'mh.stores_requisition_no')
                ->join('fleetmaster.gps as gps', 'g.REGISTRATION_NUMBER', '=', 'gps.REG_NUMBER')
                ->select(
                    DB::raw('TO_CHAR(h.DATE_CREATED, \'YYYY-MM\') as period'),
                    DB::raw('SUM(d.QUANTITY * d.PRICE) as maintenance_cost'),
                    DB::raw('COUNT(*) as maintenance_count'),
                    DB::raw('ed.engine_brand || \' \' || g.model_name as vehicle_type'),
                    DB::raw('va.BUSINESS_UNIT_NAME || \' \' || va.COST_CENTER_NAME as vehicle_assignment'),
                    'td.ORGANIZATIONALUNIT'
                )
                ->where('d.reg_no', QueryComparisonOperator::EQUALS, $registrationNumber)
                ->where('h.status', 'IN', ['26', '32', '42', '46'])
                ->where('h.IS_FUEL', '=', 'N')
                ->where('h.DATE_CREATED', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -' . $months . ')'))
                ->groupBy(DB::raw('TO_CHAR(h.DATE_CREATED, \'YYYY-MM\')'), 'ed.engine_brand', 'g.model_name', 'va.BUSINESS_UNIT_NAME', 'va.COST_CENTER_NAME', 'td.ORGANIZATIONALUNIT')
                ->orderBy('period')
                ->get();

            return [
                'fuel_trends' => $fuelTrends->toArray(),
                'maintenance_trends' => $maintenanceTrends->toArray()
            ];
        } catch (Exception $e) {
            Log::error("Error fetching vehicle performance trends: " . $e->getMessage());
            return [
                'fuel_trends' => [],
                'maintenance_trends' => []
            ];
        }
    }

    /**
     * Get fleet-wide analytics summary
     */
    public function getFleetAnalyticsSummary(): array
    {
        try {
            $summary = [];

            // Total fuel consumption (last 30 days)
            $fuelSummary = DB::table('fleetmaster.fuel_management')
                ->select(
                    DB::raw('SUM(amount) as total_fuel_cost'),
                    DB::raw('COUNT(DISTINCT reg_no) as active_vehicles'),
                    DB::raw('AVG(amount) as avg_fuel_cost_per_transaction')
                )
                ->where('FECH_ACT', '>=', DB::raw('CURRENT_DATE - 30'))
                ->first();

            // Total maintenance costs (last 30 days)
            $maintenanceSummary = DB::table('fleetmaster.gen_material_details as d')
                ->join('fleetmaster.gen_material_headers as h', 'h.REQ_NO', '=', 'd.REQ_NO')
                ->select(
                    DB::raw('SUM(d.QUANTITY * d.PRICE) as total_maintenance_cost'),
                    DB::raw('COUNT(DISTINCT d.reg_no) as vehicles_in_maintenance'),
                    DB::raw('AVG(d.QUANTITY * d.PRICE) as avg_maintenance_cost')
                )
                ->where('h.status', 'IN', ['26', '32', '42', '46'])
                ->where('h.IS_FUEL', '=', 'N')
                ->where('h.DATE_CREATED', '>=', DB::raw('CURRENT_DATE - 30'))
                ->first();

            return [
                'fuel_summary' => $fuelSummary ? (array) $fuelSummary : [],
                'maintenance_summary' => $maintenanceSummary ? (array) $maintenanceSummary : []
            ];
        } catch (Exception $e) {
            Log::error("Error fetching fleet analytics summary: " . $e->getMessage());
            return [
                'fuel_summary' => [],
                'maintenance_summary' => []
            ];
        }
    }

    /**
     * Get vehicle behavior patterns (irregular fuel consumption, maintenance frequency, etc.)
     */
    public function getVehicleBehaviorPatterns(string $registrationNumber): array
    {
        try {
            $patterns = [];

            // Analyze fuel consumption patterns
            $fuelPatterns = DB::table('fleetmaster.fuel_management')
                ->select(
                    DB::raw('AVG(amount) as avg_fuel_cost'),
                    DB::raw('STDDEV(amount) as fuel_cost_variance'),
                    DB::raw('MAX(amount) as max_fuel_cost'),
                    DB::raw('MIN(amount) as min_fuel_cost'),
                    DB::raw('COUNT(*) as transaction_count')
                )
                ->where('reg_no', QueryComparisonOperator::EQUALS, $registrationNumber)
                ->where('FECH_ACT', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -3)'))
                ->first();

            // Analyze maintenance patterns
            $maintenancePatterns = DB::table('fleetmaster.gen_material_details as d')
                ->join('fleetmaster.gen_material_headers as h', 'h.REQ_NO', '=', 'd.REQ_NO')
                ->select(
                    DB::raw('AVG(d.QUANTITY * d.PRICE) as avg_maintenance_cost'),
                    DB::raw('COUNT(*) as maintenance_count'),
                    DB::raw('MAX(h.DATE_CREATED) as last_maintenance_date')
                )
                ->where('d.reg_no', QueryComparisonOperator::EQUALS, $registrationNumber)
                ->where('h.status', 'IN', ['26', '32', '42', '46'])
                ->where('h.IS_FUEL', '=', 'N')
                ->where('h.DATE_CREATED', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -6)'))
                ->first();

            return [
                'fuel_patterns' => $fuelPatterns ? (array) $fuelPatterns : [],
                'maintenance_patterns' => $maintenancePatterns ? (array) $maintenancePatterns : []
            ];
        } catch (Exception $e) {
            Log::error("Error analyzing vehicle behavior patterns: " . $e->getMessage());
            return [
                'fuel_patterns' => [],
                'maintenance_patterns' => []
            ];
        }
    }

    /**
     * Get comprehensive maintenance details for a vehicle
     */
    public function getMaintenanceDetails(string $registrationNumber, int $months = 12): array
    {
        try {
            // Simplified query with correct column names
            $details = DB::table('fleetmaster.gen_material_details as d')
                ->join('fleetmaster.gen_material_headers as h', 'h.req_no', '=', 'd.req_no')
                ->leftJoin('fleetmaster.vm_vehicle_header as v', 'v.registration_number', '=', 'd.reg_no')
                ->leftJoin('ZFM_ARTICLES_VIEW as a', 'a.code_article', '=', 'd.material_code')
                ->select(
                    'd.reg_no',
                    'd.material_code as article_code',
                    'd.quantity',
                    'd.price',
                    'h.req_no as requisition_number',
                    'h.document_no as job_card_no',
                    'h.date_created as document_date',
                    'h.status as document_status',
                    'v.brand_name',
                    'v.model_name',
                    DB::raw('COALESCE(a.description, d.material_code) as article_description'),
                    DB::raw('(d.quantity * d.price) as total_cost')
                )
                ->where('d.reg_no', '=', $registrationNumber)
                ->whereIn('h.status', ['26', '32', '42', '46'])
                ->where('h.is_fuel', '=', 'N')
                ->where('h.date_created', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -' . $months . ')'))
                ->orderBy('h.date_created', 'desc')
                ->take(50)
                ->get();

            return $details->toArray();
        } catch (Exception $e) {
            Log::error("Error fetching maintenance details: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get unified monthly summary for dashboard aggregation
     */
    public function getUnifiedMonthlySummary(int $months = 12): array
    {
        try {
            $sql = "
                WITH maintenance_summary AS (
                    SELECT
                        d.reg_no,
                        TO_CHAR(h.DATE_CREATED, 'YYYYMM') AS period,
                        SUM(d.QUANTITY * d.PRICE) AS maintenance_cost,
                        COUNT(DISTINCT h.document_no) AS maintenance_events
                    FROM fleetmaster.gen_material_details d
                    INNER JOIN fleetmaster.gen_material_headers h
                        ON h.REQ_NO = d.REQ_NO
                    WHERE h.status IN ('26', '32', '42', '46')
                      AND h.IS_FUEL = 'N'
                      AND h.DATE_CREATED >= ADD_MONTHS(CURRENT_DATE, -:months)
                    GROUP BY d.reg_no, TO_CHAR(h.DATE_CREATED, 'YYYYMM')
                ),
                fuel_summary AS (
                    SELECT
                        f.reg_no,
                        TO_CHAR(f.FECH_ACT, 'YYYYMM') AS period,
                        SUM(f.amount) AS fuel_cost,
                        COUNT(*) AS fuel_events
                    FROM fleetmaster.fuel_management f
                    WHERE f.FECH_ACT >= ADD_MONTHS(CURRENT_DATE, -:months)
                    GROUP BY f.reg_no, TO_CHAR(f.FECH_ACT, 'YYYYMM')
                ),
                vehicle_info AS (
                    SELECT 
                        vh.REGISTRATION_NUMBER AS reg_no,
                        vh.brand_name,
                        vh.model_name,
                        ed.engine_brand,
                        va.BUSINESS_UNIT_NAME,
                        va.COST_CENTER_NAME,
                        td.ORGANIZATIONALUNIT
                    FROM fleetmaster.vm_vehicle_header vh
                    LEFT JOIN (
                        SELECT reg_no, MAX(engine_brand) AS engine_brand
                        FROM fleetmaster.vm_engine_details
                        GROUP BY reg_no
                    ) ed ON vh.REGISTRATION_NUMBER = ed.reg_no
                    LEFT JOIN (
                        SELECT reg_no, MAX(BUSINESS_UNIT_NAME) AS BUSINESS_UNIT_NAME, 
                               MAX(COST_CENTER_NAME) AS COST_CENTER_NAME
                        FROM fleetmaster.vm_assignments
                        GROUP BY reg_no
                    ) va ON vh.REGISTRATION_NUMBER = va.reg_no
                    LEFT JOIN (
                        SELECT REGISTRATIONNUMBER, MAX(ORGANIZATIONALUNIT) AS ORGANIZATIONALUNIT
                        FROM fleetmaster.tms_data_clean_up
                        GROUP BY REGISTRATIONNUMBER
                    ) td ON vh.REGISTRATION_NUMBER = td.REGISTRATIONNUMBER
                )
                SELECT
                    COALESCE(fs.reg_no, ms.reg_no) AS reg_no,
                    COALESCE(fs.period, ms.period) AS period,
                    vi.brand_name,
                    vi.model_name,
                    vi.engine_brand,
                    vi.BUSINESS_UNIT_NAME,
                    vi.COST_CENTER_NAME,
                    vi.ORGANIZATIONALUNIT,
                    NVL(fs.fuel_cost, 0) AS fuel_cost,
                    NVL(fs.fuel_events, 0) AS fuel_events,
                    NVL(ms.maintenance_cost, 0) AS maintenance_cost,
                    NVL(ms.maintenance_events, 0) AS maintenance_events,
                    NVL(fs.fuel_cost, 0) + NVL(ms.maintenance_cost, 0) AS total_operating_cost
                FROM fuel_summary fs
                FULL OUTER JOIN maintenance_summary ms
                    ON fs.reg_no = ms.reg_no
                   AND fs.period = ms.period
                LEFT JOIN vehicle_info vi ON COALESCE(fs.reg_no, ms.reg_no) = vi.reg_no
                ORDER BY period DESC, total_operating_cost DESC
            ";

            $results = DB::select($sql, ['months' => $months]);
            return $results;
        } catch (Exception $e) {
            Log::error("Error fetching unified monthly summary: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get executive KPI summary for dashboard
     */
    public function getExecutiveKpiSummary(int $days = 30): array
    {
        try {
            // Use a broader date range to get actual data
            $sql = "
                WITH current_period AS (
                    SELECT
                        COUNT(DISTINCT fs.reg_no) AS active_vehicles,
                        NVL(SUM(fs.fuel_cost), 0) AS total_fuel_cost,
                        NVL(SUM(fs.maintenance_cost), 0) AS total_maintenance_cost,
                        NVL(SUM(fs.total_operating_cost), 0) AS total_operating_cost,
                        COUNT(DISTINCT CASE WHEN fs.maintenance_cost > 0 THEN fs.reg_no END) AS vehicles_with_maintenance,
                        COUNT(DISTINCT CASE WHEN fs.fuel_cost > 0 THEN fs.reg_no END) AS vehicles_with_fuel
                    FROM (
                        SELECT
                            f.reg_no,
                            SUM(f.amount) AS fuel_cost,
                            0 AS maintenance_cost,
                            SUM(f.amount) AS total_operating_cost
                        FROM fleetmaster.fuel_management f
                        WHERE f.FECH_ACT >= ADD_MONTHS(CURRENT_DATE, -365)
                        GROUP BY f.reg_no
                        UNION ALL
                        SELECT
                            d.reg_no,
                            0 AS fuel_cost,
                            SUM(d.QUANTITY * d.PRICE) AS maintenance_cost,
                            SUM(d.QUANTITY * d.PRICE) AS total_operating_cost
                        FROM fleetmaster.gen_material_details d
                        INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                        WHERE h.status IN ('26', '32', '42', '46')
                          AND h.IS_FUEL = 'N'
                          AND h.DATE_CREATED >= ADD_MONTHS(CURRENT_DATE, -365)
                        GROUP BY d.reg_no
                    ) fs
                ),
                previous_period AS (
                    SELECT
                        COUNT(DISTINCT fs.reg_no) AS active_vehicles,
                        NVL(SUM(fs.fuel_cost), 0) AS total_fuel_cost,
                        NVL(SUM(fs.maintenance_cost), 0) AS total_maintenance_cost,
                        NVL(SUM(fs.total_operating_cost), 0) AS total_operating_cost
                    FROM (
                        SELECT
                            f.reg_no,
                            SUM(f.amount) AS fuel_cost,
                            0 AS maintenance_cost,
                            SUM(f.amount) AS total_operating_cost
                        FROM fleetmaster.fuel_management f
                        WHERE f.FECH_ACT BETWEEN ADD_MONTHS(CURRENT_DATE, -730) AND ADD_MONTHS(CURRENT_DATE, -365)
                        GROUP BY f.reg_no
                        UNION ALL
                        SELECT
                            d.reg_no,
                            0 AS fuel_cost,
                            SUM(d.QUANTITY * d.PRICE) AS maintenance_cost,
                            SUM(d.QUANTITY * d.PRICE) AS total_operating_cost
                        FROM fleetmaster.gen_material_details d
                        INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                        WHERE h.status IN ('26', '32', '42', '46')
                          AND h.IS_FUEL = 'N'
                          AND h.DATE_CREATED BETWEEN ADD_MONTHS(CURRENT_DATE, -730) AND ADD_MONTHS(CURRENT_DATE, -365)
                        GROUP BY d.reg_no
                    ) fs
                ),
                top_vehicle AS (
                    SELECT reg_no, total_operating_cost
                    FROM (
                        SELECT
                            f.reg_no,
                            SUM(f.amount) AS total_operating_cost
                        FROM fleetmaster.fuel_management f
                        WHERE f.FECH_ACT >= ADD_MONTHS(CURRENT_DATE, -365)
                        GROUP BY f.reg_no
                        UNION ALL
                        SELECT
                            d.reg_no,
                            SUM(d.QUANTITY * d.PRICE) AS total_operating_cost
                        FROM fleetmaster.gen_material_details d
                        INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                        WHERE h.status IN ('26', '32', '42', '46')
                          AND h.IS_FUEL = 'N'
                          AND h.DATE_CREATED >= ADD_MONTHS(CURRENT_DATE, -365)
                        GROUP BY d.reg_no
                    ) combined
                    GROUP BY reg_no
                    ORDER BY total_operating_cost DESC
                    FETCH FIRST 1 ROW ONLY
                )
                SELECT
                    cp.active_vehicles,
                    cp.total_fuel_cost,
                    cp.total_maintenance_cost,
                    cp.total_operating_cost,
                    CASE WHEN cp.active_vehicles > 0 THEN cp.total_operating_cost / cp.active_vehicles ELSE 0 END AS avg_cost_per_vehicle,
                    tv.reg_no AS highest_cost_vehicle,
                    tv.total_operating_cost AS highest_cost,
                    CASE WHEN pp.active_vehicles > 0 AND pp.total_operating_cost > 0 THEN 
                        ROUND(((cp.total_operating_cost - pp.total_operating_cost) / pp.total_operating_cost) * 100, 2)
                    ELSE 0 END AS cost_trend_percentage,
                    cp.vehicles_with_maintenance,
                    cp.vehicles_with_fuel
                FROM current_period cp
                CROSS JOIN previous_period pp
                LEFT JOIN top_vehicle tv ON 1=1
            ";

            $result = DB::selectOne($sql);
            
            // Ensure we return a proper array with all required fields
            if ($result) {
                return [
                    'active_vehicles' => (int)($result->active_vehicles ?? 0),
                    'total_fuel_cost' => (float)($result->total_fuel_cost ?? 0),
                    'total_maintenance_cost' => (float)($result->total_maintenance_cost ?? 0),
                    'total_operating_cost' => (float)($result->total_operating_cost ?? 0),
                    'avg_cost_per_vehicle' => (float)($result->avg_cost_per_vehicle ?? 0),
                    'highest_cost_vehicle' => $result->highest_cost_vehicle ?? 'N/A',
                    'highest_cost' => (float)($result->highest_cost ?? 0),
                    'cost_trend_percentage' => (float)($result->cost_trend_percentage ?? 0),
                    'vehicles_with_maintenance' => (int)($result->vehicles_with_maintenance ?? 0),
                    'vehicles_with_fuel' => (int)($result->vehicles_with_fuel ?? 0)
                ];
            }
            
            // Return default values if no data found
            return [
                'active_vehicles' => 0,
                'total_fuel_cost' => 0,
                'total_maintenance_cost' => 0,
                'total_operating_cost' => 0,
                'avg_cost_per_vehicle' => 0,
                'highest_cost_vehicle' => 'N/A',
                'highest_cost' => 0,
                'cost_trend_percentage' => 0,
                'vehicles_with_maintenance' => 0,
                'vehicles_with_fuel' => 0
            ];
        } catch (Exception $e) {
            Log::error("Error fetching executive KPI summary: " . $e->getMessage());
            return [
                'active_vehicles' => 0,
                'total_fuel_cost' => 0,
                'total_maintenance_cost' => 0,
                'total_operating_cost' => 0,
                'avg_cost_per_vehicle' => 0,
                'highest_cost_vehicle' => 'N/A',
                'highest_cost' => 0,
                'cost_trend_percentage' => 0,
                'vehicles_with_maintenance' => 0,
                'vehicles_with_fuel' => 0
            ];
        }
    }

    /**
     * Get executive KPI summary - Simplified working version
     */
    public function getExecutiveKpiSummaryWorking(int $days = 30): array
    {
        try {
            // Get current fuel data
            $currentFuelData = DB::table('fleetmaster.fuel_management')
                ->where('FECH_ACT', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -' . $days . ')'))
                ->selectRaw('
                    COUNT(DISTINCT reg_no) as active_vehicles,
                    SUM(amount) as total_fuel_cost,
                    COUNT(DISTINCT CASE WHEN amount > 0 THEN reg_no END) as vehicles_with_fuel
                ')
                ->first();
            
            // Get current maintenance data
            $currentMaintenanceData = DB::table('fleetmaster.gen_material_details')
                ->join('fleetmaster.gen_material_headers', 'fleetmaster.gen_material_headers.REQ_NO', '=', 'fleetmaster.gen_material_details.REQ_NO')
                ->where('fleetmaster.gen_material_headers.status', 'IN', ['26', '32', '42', '46'])
                ->where('fleetmaster.gen_material_headers.IS_FUEL', 'N')
                ->where('fleetmaster.gen_material_headers.DATE_CREATED', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -' . $days . ')'))
                ->selectRaw('
                    SUM(fleetmaster.gen_material_details.QUANTITY * fleetmaster.gen_material_details.PRICE) as total_maintenance_cost,
                    COUNT(DISTINCT fleetmaster.gen_material_details.reg_no) as vehicles_with_maintenance
                ')
                ->first();
            
            // Get top vehicle by cost
            $topVehicle = DB::table('fleetmaster.fuel_management')
                ->where('FECH_ACT', '>=', DB::raw('ADD_MONTHS(CURRENT_DATE, -' . $days . ')'))
                ->selectRaw('reg_no, SUM(amount) as total_cost')
                ->groupBy('reg_no')
                ->orderBy('total_cost', 'desc')
                ->first();
            
            // Calculate totals
            $activeVehicles = max($currentFuelData->active_vehicles ?? 0, $currentMaintenanceData->vehicles_with_maintenance ?? 0);
            $totalFuelCost = $currentFuelData->total_fuel_cost ?? 0;
            $totalMaintenanceCost = $currentMaintenanceData->total_maintenance_cost ?? 0;
            $totalOperatingCost = $totalFuelCost + $totalMaintenanceCost;
            $avgCostPerVehicle = $activeVehicles > 0 ? $totalOperatingCost / $activeVehicles : 0;
            
            return [
                'active_vehicles' => (int)$activeVehicles,
                'total_fuel_cost' => (float)$totalFuelCost,
                'total_maintenance_cost' => (float)$totalMaintenanceCost,
                'total_operating_cost' => (float)$totalOperatingCost,
                'avg_cost_per_vehicle' => (float)$avgCostPerVehicle,
                'highest_cost_vehicle' => $topVehicle->reg_no ?? 'N/A',
                'highest_cost' => (float)($topVehicle->total_cost ?? 0),
                'cost_trend_percentage' => 0.0, // Simplified for now
                'vehicles_with_maintenance' => (int)($currentMaintenanceData->vehicles_with_maintenance ?? 0),
                'vehicles_with_fuel' => (int)($currentFuelData->vehicles_with_fuel ?? 0)
            ];
            
        } catch (Exception $e) {
            Log::error("Error fetching executive KPI summary (working): " . $e->getMessage());
            return [
                'active_vehicles' => 0,
                'total_fuel_cost' => 0,
                'total_maintenance_cost' => 0,
                'total_operating_cost' => 0,
                'avg_cost_per_vehicle' => 0,
                'highest_cost_vehicle' => 'N/A',
                'highest_cost' => 0,
                'cost_trend_percentage' => 0,
                'vehicles_with_maintenance' => 0,
                'vehicles_with_fuel' => 0
            ];
        }
    }

    /**
     * Get monthly trend data for charts
     */
    public function getMonthlyTrends(int $months = 12): array
    {
        try {
            $sql = "
                WITH monthly_data AS (
                    SELECT
                        TO_CHAR(period_month, 'YYYY-MM') AS period,
                        SUM(fuel_cost) AS fuel_cost,
                        SUM(maintenance_cost) AS maintenance_cost,
                        SUM(total_operating_cost) AS total_operating_cost,
                        COUNT(DISTINCT reg_no) AS active_vehicles
                    FROM (
                        SELECT
                            TRUNC(f.FECH_ACT, 'MM') AS period_month,
                            f.reg_no,
                            SUM(f.amount) AS fuel_cost,
                            0 AS maintenance_cost,
                            SUM(f.amount) AS total_operating_cost
                        FROM fleetmaster.fuel_management f
                        WHERE f.FECH_ACT >= ADD_MONTHS(TRUNC(CURRENT_DATE, 'MM'), -:months)
                        GROUP BY TRUNC(f.FECH_ACT, 'MM'), f.reg_no
                        UNION ALL
                        SELECT
                            TRUNC(h.DATE_CREATED, 'MM') AS period_month,
                            d.reg_no,
                            0 AS fuel_cost,
                            SUM(d.QUANTITY * d.PRICE) AS maintenance_cost,
                            SUM(d.QUANTITY * d.PRICE) AS total_operating_cost
                        FROM fleetmaster.gen_material_details d
                        INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                        WHERE h.status IN ('26', '32', '42', '46')
                          AND h.IS_FUEL = 'N'
                          AND h.DATE_CREATED >= ADD_MONTHS(TRUNC(CURRENT_DATE, 'MM'), -:months)
                        GROUP BY TRUNC(h.DATE_CREATED, 'MM'), d.reg_no
                    )
                    GROUP BY period_month
                )
                SELECT *
                FROM monthly_data
                ORDER BY period
            ";

            $results = DB::select($sql, ['months' => $months]);
            return $results;
        } catch (Exception $e) {
            Log::error("Error fetching monthly trends: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get top vehicles by various metrics
     */
    public function getTopVehiclesByMetric(string $metric = 'total_cost', int $limit = 10, int $days = 30): array
    {
        try {
            // Simplified approach that works with actual database structure
            $sql = "
                SELECT 
                    f.reg_no,
                    v.registration_number,
                    v.brand_name,
                    v.model_name,
                    v.business_unit_name,
                    SUM(f.amount) as total_cost,
                    SUM(f.amount) as fuel_cost,
                    0 as maintenance_cost,
                    COUNT(*) as fuel_events,
                    0 as maintenance_events,
                    MAX(f.FECH_ACT) as last_fuel_date
                FROM fleetmaster.fuel_management f
                LEFT JOIN fleetmaster.vm_vehicle_header v ON v.registration_number = f.reg_no
                WHERE f.FECH_ACT >= ADD_MONTHS(CURRENT_DATE, -{$days})
                GROUP BY f.reg_no, v.registration_number, v.brand_name, v.model_name, v.business_unit_name
                ORDER BY total_cost DESC
                FETCH FIRST {$limit} ROWS ONLY
            ";

            $results = DB::select($sql);
            
            return $results;
        } catch (Exception $e) {
            Log::error("Error fetching top vehicles by metric: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get cost distribution by organizational unit
     */
    public function getCostDistributionByOrgUnit(int $days = 30): array
    {
        try {
            // Use a simpler approach that works with the actual database structure
            $sql = "
                SELECT 
                    td.ORGANIZATIONALUNIT as org_unit,
                    NVL(SUM(fuel_costs.total_fuel), 0) as fuel_cost,
                    NVL(SUM(maintenance_costs.total_maintenance), 0) as maintenance_cost,
                    NVL(SUM(fuel_costs.total_fuel + maintenance_costs.total_maintenance), 0) as total_cost
                FROM fleetmaster.tms_data_clean_up td
                LEFT JOIN (
                    SELECT 
                        t.REGISTRATIONNUMBER,
                        SUM(f.amount) as total_fuel
                    FROM fleetmaster.tms_data_clean_up t
                    INNER JOIN fleetmaster.fuel_management f ON f.reg_no = t.REGISTRATIONNUMBER
                    WHERE f.FECH_ACT >= ADD_MONTHS(CURRENT_DATE, -{$days})
                    GROUP BY t.REGISTRATIONNUMBER
                ) fuel_costs ON td.REGISTRATIONNUMBER = fuel_costs.REGISTRATIONNUMBER
                LEFT JOIN (
                    SELECT 
                        t.REGISTRATIONNUMBER,
                        SUM(d.QUANTITY * d.PRICE) as total_maintenance
                    FROM fleetmaster.tms_data_clean_up t
                    INNER JOIN fleetmaster.gen_material_details d ON d.reg_no = t.REGISTRATIONNUMBER
                    INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                    WHERE h.status IN ('26', '32', '42', '46')
                      AND h.IS_FUEL = 'N'
                      AND h.DATE_CREATED >= ADD_MONTHS(CURRENT_DATE, -{$days})
                    GROUP BY t.REGISTRATIONNUMBER
                ) maintenance_costs ON td.REGISTRATIONNUMBER = maintenance_costs.REGISTRATIONNUMBER
                WHERE td.ORGANIZATIONALUNIT IS NOT NULL
                GROUP BY td.ORGANIZATIONALUNIT
                HAVING NVL(SUM(fuel_costs.total_fuel + maintenance_costs.total_maintenance), 0) > 0
                ORDER BY total_cost DESC
            ";

            $results = DB::select($sql);

            return collect($results)->map(function ($result) {
                return [
                    'org_unit' => $result->org_unit,
                    'org_unit_name' => $result->org_unit,
                    'fuel_cost' => (float) $result->fuel_cost,
                    'maintenance_cost' => (float) $result->maintenance_cost,
                    'total_cost' => (float) $result->total_cost
                ];
            })->toArray();
        } catch (Exception $e) {
            Log::error("Error fetching cost distribution by org unit: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get fleet exceptions and alerts
     */
    public function getFleetExceptions(int $days = 30): array
    {
        try {
            $exceptions = [];

            // 1. Vehicles with no maintenance in last 6 months
            $noMaintenance = DB::select("
                SELECT DISTINCT vh.REGISTRATION_NUMBER, vh.brand_name, vh.model_name
                FROM fleetmaster.vm_vehicle_header vh
                WHERE vh.REGISTRATION_NUMBER NOT IN (
                    SELECT DISTINCT d.reg_no
                    FROM fleetmaster.gen_material_details d
                    INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                    WHERE h.status IN ('26', '32', '42', '46')
                      AND h.IS_FUEL = 'N'
                      AND h.DATE_CREATED >= ADD_MONTHS(CURRENT_DATE, -6)
                )
                AND vh.REGISTRATION_NUMBER IN (
                    SELECT DISTINCT reg_no FROM fleetmaster.fuel_management 
                    WHERE FECH_ACT >= ADD_MONTHS(CURRENT_DATE, -6)
                )
                FETCH FIRST 10 ROWS ONLY
            ");

            // 2. Vehicles with unusually high maintenance spend
            $highMaintenance = DB::select("
                SELECT 
                    d.reg_no,
                    vh.brand_name,
                    vh.model_name,
                    SUM(d.QUANTITY * d.PRICE) AS maintenance_cost,
                    COUNT(DISTINCT h.document_no) AS maintenance_events
                FROM fleetmaster.gen_material_details d
                INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                INNER JOIN fleetmaster.vm_vehicle_header vh ON d.reg_no = vh.REGISTRATION_NUMBER
                WHERE h.status IN ('26', '32', '42', '46')
                  AND h.IS_FUEL = 'N'
                  AND h.DATE_CREATED >= CURRENT_DATE - :days
                GROUP BY d.reg_no, vh.brand_name, vh.model_name
                HAVING SUM(d.QUANTITY * d.PRICE) > (
                    SELECT PERCENTILE_CONT(0.75) WITHIN GROUP (ORDER BY total_cost) * 2
                    FROM (
                        SELECT SUM(d.QUANTITY * d.PRICE) AS total_cost
                        FROM fleetmaster.gen_material_details d
                        INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                        WHERE h.status IN ('26', '32', '42', '46')
                          AND h.IS_FUEL = 'N'
                          AND h.DATE_CREATED >= CURRENT_DATE - :days
                        GROUP BY d.reg_no
                    )
                )
                ORDER BY maintenance_cost DESC
                FETCH FIRST 10 ROWS ONLY
            ", ['days' => $days]);

            $exceptions = [
                'no_maintenance' => $noMaintenance,
                'high_maintenance' => $highMaintenance
            ];

            return $exceptions;
        } catch (Exception $e) {
            Log::error("Error fetching fleet exceptions: " . $e->getMessage());
            return [];
        }
    }
}
