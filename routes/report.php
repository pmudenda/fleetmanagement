<?php

use App\Http\Controllers\ReportsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Jimmyjs\ReportGenerator\Facades\PdfReportFacade;
use Jimmyjs\ReportGenerator\ReportMedia\PdfReport;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'is.active', 'change.password'], 'prefix' => 'reports'], function () {

    Route::get('fuel/cost', \App\Livewire\Reports\Fuel\FuelIndex::class)
        ->name('reports.fuel.requisitions');

    Route::get('fuel/status', \App\Livewire\Reports\Fuel\Status\FuelStatusIndex::class)
        ->name('reports.fuel.status');

    Route::get('fuel', \App\Livewire\Reports\Fuel\FuelPeriodReport::class)
        ->name('reports.fuel');

    Route::get('spares', \App\Livewire\Reports\Sprares\SparesPeriodReport::class)
        ->name('reports.spares');

    Route::get('data/fuel/cost', [ReportsController::class, 'getFuelCost'])->name('reports.fuel.data');

    Route::get('vehicle/status', [ReportsController::class, 'vehicleByStatus'])
        ->name('reports.vehicle.status');

    Route::get('overdue-in-workshop', \App\Livewire\Reports\Workshop\OverdueInWorkshopReport::class)
        ->name('reports.workshop.overdue_in_workshop');

    Route::get('audit/document', \App\Livewire\Reports\Audit\DocumentAudit::class)
        ->name('reports.audit.document');

    Route::get('/', \App\Livewire\Report\ReportIndex::class)->name('report.index');
    Route::get('/{report}/show', \App\Livewire\Report\ReportView::class)->name('report.view');
//    Route::get('/{report}/stream', function (Request $request, Report $report) {
//        $report = (object)config('report')[$name];
//        $query = DB::query()->selectRaw($report->query);
//        $columns = $report->columns;
//        return PdfReportFacade::of($report->title, [], $query, $columns)
//            ->setOrientation('landscape')
//            ->limit(20)
//            ->stream();
//    })->name('report.stream');
});
