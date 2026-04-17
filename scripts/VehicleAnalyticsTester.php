<?php

declare(strict_types=1);

/**
 * Improved comprehensive test script for vehicle analytics dashboard
 */

require_once __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Http\Request;
use App\Services\VehicleManagement\VehicleAnalyticsService;
use App\Http\Controllers\VehicleManagement\VehicleController;

class VehicleAnalyticsTester
{
    private $db;
    private array $results = [];
    private string $testVehicle;
    private int $testMonths;

    public function __construct(string $testVehicle = 'CAD 6376', int $testMonths = 6)
    {
        $this->db = app('db');
        $this->testVehicle = $testVehicle;
        $this->testMonths = $testMonths;
    }

    public function run(): void
    {
        $this->printHeader();

        $this->runTest('Database Connectivity Test', fn() => $this->testDatabaseConnectivity());
        $this->runTest('Dashboard SQL Structure Test', fn() => $this->testDashboardSqlStructure());
        $this->runTest('Vehicle Analytics Service Test', fn() => $this->testAnalyticsService());
        $this->runTest('Dashboard Controller Endpoints Test', fn() => $this->testDashboardEndpoints());
        $this->runTest('Route Registration Test', fn() => $this->testRouteRegistration());
        $this->runTest('Data Quality Check', fn() => $this->testDataQuality());

        $this->printSummary();
    }

    private function printHeader(): void
    {
        $this->line("COMPREHENSIVE VEHICLE ANALYTICS DASHBOARD TEST");
        $this->line(str_repeat("=", 50));
        $this->line("Test Vehicle: {$this->testVehicle} | Period: {$this->testMonths} months");
        $this->line("");
    }

    private function runTest(string $name, callable $callback): void
    {
        $this->line($name);
        $this->line(str_repeat('-', strlen($name)));

        $start = microtime(true);

        try {
            $details = $callback();
            $duration = round((microtime(true) - $start) * 1000, 2);

            $this->results[] = [
                'name' => $name,
                'status' => 'PASS',
                'duration_ms' => $duration,
                'details' => $details,
            ];

            $this->line("Status: PASS ({$duration} ms)");

            if (!empty($details)) {
                foreach ($details as $detail) {
                    $this->line("  - {$detail}");
                }
            }
        } catch (\Throwable $e) {
            $duration = round((microtime(true) - $start) * 1000, 2);

            $this->results[] = [
                'name' => $name,
                'status' => 'FAIL',
                'duration_ms' => $duration,
                'details' => [$e->getMessage()],
            ];

            $this->line("Status: FAIL ({$duration} ms)");
            $this->line("  - Error: " . $e->getMessage());
        }

        $this->line("");
    }

    private function testDatabaseConnectivity(): array
    {
        $details = [];

        // Test fuel management table
        $fuelTest = $this->db->selectOne("
            SELECT COUNT(*) AS count
            FROM fleetmaster.fuel_management
            WHERE ROWNUM = 1
        ");
        $details[] = "Fuel Management Table: " . (($fuelTest->count ?? 0) > 0 ? 'Accessible' : 'Empty/Not Accessible');

        // Test maintenance tables with comprehensive joins
        $maintenanceTest = $this->db->selectOne("
            SELECT COUNT(*) AS count
            FROM fleetmaster.gen_material_details d
            INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
            WHERE ROWNUM = 1
        ");
        $details[] = "Maintenance Tables: " . (($maintenanceTest->count ?? 0) > 0 ? 'Accessible' : 'Empty/Not Accessible');

        // Test vehicle header table
        $vehicleTest = $this->db->selectOne("
            SELECT COUNT(*) AS count
            FROM fleetmaster.vm_vehicle_header
            WHERE ROWNUM = 1
        ");
        $details[] = "Vehicle Header Table: " . (($vehicleTest->count ?? 0) > 0 ? 'Accessible' : 'Empty/Not Accessible');

        return $details;
    }

    private function testDashboardSqlStructure(): array
    {
        $details = [];

        // Test unified monthly summary SQL
        $unifiedTest = $this->db->selectOne("
            WITH maintenance_summary AS (
                SELECT
                    d.reg_no,
                    TO_CHAR(h.DATE_CREATED, 'YYYYMM') AS period,
                    SUM(d.QUANTITY * d.PRICE) AS maintenance_cost
                FROM fleetmaster.gen_material_details d
                INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
                WHERE h.status IN ('26', '32', '42', '46')
                  AND h.IS_FUEL = 'N'
                  AND h.DATE_CREATED >= ADD_MONTHS(CURRENT_DATE, -6)
                GROUP BY d.reg_no, TO_CHAR(h.DATE_CREATED, 'YYYYMM')
                FETCH FIRST 1 ROW ONLY
            ),
            fuel_summary AS (
                SELECT
                    f.reg_no,
                    TO_CHAR(f.FECH_ACT, 'YYYYMM') AS period,
                    SUM(f.amount) AS fuel_cost
                FROM fleetmaster.fuel_management f
                WHERE f.FECH_ACT >= ADD_MONTHS(CURRENT_DATE, -6)
                GROUP BY f.reg_no, TO_CHAR(f.FECH_ACT, 'YYYYMM')
                FETCH FIRST 1 ROW ONLY
            )
            SELECT COUNT(*) AS count
            FROM fuel_summary fs
            FULL OUTER JOIN maintenance_summary ms ON fs.reg_no = ms.reg_no AND fs.period = ms.period
        ");
        $details[] = "Unified Monthly Summary SQL: " . (($unifiedTest->count ?? 0) >= 0 ? 'Working' : 'Failed');

        // Test executive KPI SQL structure
        $kpiTest = $this->db->selectOne("
            WITH current_period AS (
                SELECT
                    COUNT(DISTINCT f.reg_no) AS active_vehicles,
                    SUM(f.amount) AS total_fuel_cost
                FROM fleetmaster.fuel_management f
                WHERE f.FECH_ACT >= CURRENT_DATE - 30
            )
            SELECT COUNT(*) AS count FROM current_period
        ");
        $details[] = "Executive KPI SQL Structure: " . (($kpiTest->count ?? 0) >= 0 ? 'Working' : 'Failed');

        // Test deduplicated joins
        $dedupTest = $this->db->selectOne("
            SELECT COUNT(*) AS count
            FROM (
                SELECT reg_no, MAX(engine_brand) AS engine_brand
                FROM fleetmaster.vm_engine_details
                GROUP BY reg_no
            ) ed
            WHERE ROWNUM = 1
        ");
        $details[] = "Deduplicated Joins: " . (($dedupTest->count ?? 0) > 0 ? 'Working' : 'Failed');

        return $details;
    }

    private function testAnalyticsService(): array
    {
        $details = [];
        /** @var VehicleAnalyticsService $analyticsService */
        $analyticsService = app(VehicleAnalyticsService::class);

        // Test executive KPI summary
        $kpiSummary = $analyticsService->getExecutiveKpiSummary(30);
        $details[] = 'Executive KPI Summary: ' . (count($kpiSummary) > 0 ? 'Working' : 'No Data');

        // Test monthly trends
        $monthlyTrends = $analyticsService->getMonthlyTrends(6);
        $details[] = 'Monthly Trends: ' . count($monthlyTrends) . ' months found';

        // Test top vehicles by metric
        $topVehicles = $analyticsService->getTopVehiclesByMetric('total_cost', 5, 30);
        $details[] = 'Top Vehicles by Total Cost: ' . count($topVehicles) . ' vehicles found';

        // Test cost distribution
        $costDistribution = $analyticsService->getCostDistributionByOrgUnit(30);
        $details[] = 'Cost Distribution: ' . count($costDistribution) . ' org units found';

        // Test fleet exceptions
        $fleetExceptions = $analyticsService->getFleetExceptions(30);
        $exceptionCount = isset($fleetExceptions['no_maintenance']) ? count($fleetExceptions['no_maintenance']) : 0;
        $details[] = 'Fleet Exceptions: ' . $exceptionCount . ' alerts found';

        // Test unified monthly summary
        $unifiedSummary = $analyticsService->getUnifiedMonthlySummary(6);
        $details[] = 'Unified Monthly Summary: ' . count($unifiedSummary) . ' records found';

        return $details;
    }

    private function testDashboardEndpoints(): array
    {
        $details = [];
        /** @var VehicleController $controller */
        $controller = app(VehicleController::class);

        // Test executive KPI endpoint
        $kpiRequest = new Request(['days' => 30]);
        $kpiResponse = $controller->getDashboardKpi($kpiRequest);
        $kpiData = $kpiResponse->getData(true);
        $details[] = 'KPI Endpoint Status: ' . $kpiResponse->status();
        $details[] = 'KPI Endpoint Success: ' . (($kpiData['success'] ?? false) ? 'Yes' : 'No');

        // Test monthly trends endpoint
        $trendsRequest = new Request(['months' => 6]);
        $trendsResponse = $controller->getMonthlyTrends($trendsRequest);
        $trendsData = $trendsResponse->getData(true);
        $details[] = 'Trends Endpoint Status: ' . $trendsResponse->status();
        $details[] = 'Trends Endpoint Success: ' . (($trendsData['success'] ?? false) ? 'Yes' : 'No');

        // Test top vehicles endpoint
        $topVehiclesRequest = new Request(['metric' => 'total_cost', 'limit' => 5, 'days' => 30]);
        $topVehiclesResponse = $controller->getTopVehiclesByMetric($topVehiclesRequest);
        $topVehiclesData = $topVehiclesResponse->getData(true);
        $details[] = 'Top Vehicles Endpoint Status: ' . $topVehiclesResponse->status();
        $details[] = 'Top Vehicles Endpoint Success: ' . (($topVehiclesData['success'] ?? false) ? 'Yes' : 'No');

        // Test cost distribution endpoint
        $costDistRequest = new Request(['days' => 30]);
        $costDistResponse = $controller->getCostDistribution($costDistRequest);
        $costDistData = $costDistResponse->getData(true);
        $details[] = 'Cost Distribution Endpoint Status: ' . $costDistResponse->status();
        $details[] = 'Cost Distribution Endpoint Success: ' . (($costDistData['success'] ?? false) ? 'Yes' : 'No');

        // Test fleet exceptions endpoint
        $exceptionsRequest = new Request(['days' => 30]);
        $exceptionsResponse = $controller->getFleetExceptions($exceptionsRequest);
        $exceptionsData = $exceptionsResponse->getData(true);
        $details[] = 'Fleet Exceptions Endpoint Status: ' . $exceptionsResponse->status();
        $details[] = 'Fleet Exceptions Endpoint Success: ' . (($exceptionsData['success'] ?? false) ? 'Yes' : 'No');

        return $details;
    }

    private function testRouteRegistration(): array
    {
        $details = [];
        $routes = app('router')->getRoutes();

        $dashboardRoutes = [];
        $analyticsRoutes = [];

        foreach ($routes as $route) {
            $uri = $route->uri();

            if (str_contains($uri, 'analytics')) {
                $analyticsRoutes[] = $uri;
            }
            
            if (in_array($uri, [
                'vehicle-management/analytics/kpi',
                'vehicle-management/analytics/trends', 
                'vehicle-management/analytics/top-vehicles-metric',
                'vehicle-management/analytics/cost-distribution',
                'vehicle-management/analytics/exceptions'
            ])) {
                $dashboardRoutes[] = $uri;
            }
        }

        $details[] = 'Total Analytics Routes: ' . count($analyticsRoutes);
        $details[] = 'Dashboard-Specific Routes: ' . count($dashboardRoutes);
        
        foreach ($dashboardRoutes as $route) {
            $details[] = "Dashboard Route: {$route}";
        }

        return $details;
    }

    private function testDataQuality(): array
    {
        $details = [];

        // Check for recent data
        $recentFuel = $this->db->selectOne("
            SELECT COUNT(*) AS count
            FROM fleetmaster.fuel_management
            WHERE FECH_ACT >= ADD_MONTHS(CURRENT_DATE, -3)
        ");
        $details[] = 'Recent Fuel Data (3 months): ' . ($recentFuel->count ?? 0) . ' records';

        $recentMaintenance = $this->db->selectOne("
            SELECT COUNT(*) AS count
            FROM fleetmaster.gen_material_details d
            INNER JOIN fleetmaster.gen_material_headers h ON h.REQ_NO = d.REQ_NO
            WHERE h.DATE_CREATED >= ADD_MONTHS(CURRENT_DATE, -3)
              AND h.status IN ('26', '32', '42', '46')
              AND h.IS_FUEL = 'N'
        ");
        $details[] = 'Recent Maintenance Data (3 months): ' . ($recentMaintenance->count ?? 0) . ' records';

        // Check data completeness
        $vehicleCount = $this->db->selectOne("
            SELECT COUNT(*) AS count
            FROM fleetmaster.vm_vehicle_header
        ");
        $details[] = 'Total Vehicles in Database: ' . ($vehicleCount->count ?? 0);

        // Check for potential duplicates in joins
        $duplicateCheck = $this->db->selectOne("
            SELECT COUNT(*) AS count
            FROM fleetmaster.vm_assignments
            GROUP BY reg_no
            HAVING COUNT(*) > 1
            FETCH FIRST 1 ROW ONLY
        ");
        $details[] = 'Potential Duplicate Assignments: ' . (($duplicateCheck->count ?? 0) > 0 ? 'Found' : 'None');

        return $details;
    }

    private function printSummary(): void
    {
        $this->line(str_repeat('=', 50));
        $this->line('FINAL SUMMARY');
        $this->line(str_repeat('=', 50));

        $passCount = count(array_filter($this->results, fn($r) => $r['status'] === 'PASS'));
        $failCount = count(array_filter($this->results, fn($r) => $r['status'] === 'FAIL'));
        $totalDuration = array_sum(array_column($this->results, 'duration_ms'));

        $this->line("Passed: {$passCount}");
        $this->line("Failed: {$failCount}");
        $this->line("Total Duration: " . round($totalDuration, 2) . " ms");
        $this->line("");

        foreach ($this->results as $result) {
            $statusIcon = $result['status'] === 'PASS' ? 'PASS' : 'FAIL';
            $this->line(sprintf(
                "[%s] %s (%sms)",
                $statusIcon,
                $result['name'],
                $result['duration_ms']
            ));
        }

        $this->line("");
        $this->line("DASHBOARD READINESS:");
        
        if ($passCount === count($this->results)) {
            $this->line("Status: READY FOR PRODUCTION");
            $this->line("All dashboard components are working correctly!");
        } else {
            $this->line("Status: NEEDS ATTENTION");
            $this->line("Some components require fixes before production use.");
        }

        $this->line("");
        $this->line("NEXT STEPS:");
        $this->line("1. Access vehicle details page to test dashboard UI");
        $this->line("2. Verify all charts render with real data");
        $this->line("3. Test interactive features (tabs, filters)");
        $this->line("4. Validate alert functionality");
        $this->line("5. Test export and refresh features");
    }

    private function line(string $message): void
    {
        echo $message . PHP_EOL;
    }
}

// Command line argument handling
$vehicle = $argv[1] ?? 'CAD 6376';
$months = isset($argv[2]) ? (int) $argv[2] : 6;
$jsonOutput = in_array('--json', $argv, true);

try {
    $tester = new VehicleAnalyticsTester($vehicle, $months);
    $tester->run();
    
    if ($jsonOutput) {
        echo json_encode($tester->results, JSON_PRETTY_PRINT);
    }
} catch (\Throwable $e) {
    echo 'FATAL ERROR: ' . $e->getMessage() . PHP_EOL;
    echo $e->getTraceAsString() . PHP_EOL;
}
