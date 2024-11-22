<?php

namespace App\Console\Commands\Vehicle;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class VehicleOverIssuedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification:over-issued';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = now()->format("Ymd");

        $overIssues = DB::select("SELECT
	g.REGISTRATION_NUMBER,
	g.BRAND_NAME || ' ' || MODEL_NAME AS VEHICLE_BRAND_NAME,
	f.tank_capacity AS main_tank_capacity,
	F.SUB_TANK_CAPACITY,
	( f.tank_capacity + F.SUB_TANK_CAPACITY ) AS total_tank_capacity,
	md.quantity AS one_off_quantity_issued,
	md.quantity - ( f.tank_capacity + f.SUB_TANK_CAPACITY ) AS issued_variance,
	md.price_map * ( md.quantity - ( f.tank_capacity + f.SUB_TANK_CAPACITY ) ) AS issued_variance_cost,
	sh.document_no AS store_req_no,
	sd.quantity AS quantity_requested,
	mh.document_no AS issue_no,
	md.amount AS issued_fuel_value,
--md.price_map as fuel_cost,
	mh.date_act AS fuel_issue_date,
	s.description AS store_issuing_name,
	smt.description AS movement_type,
	a.description AS fuel_type,
	mh.CODE_COLLECTOR || ' ' || NAME_COLLECTOR AS Fuel_Collector,
--md.user_act,
	u.first_name || ' ' || u.surname AS issuing_officer_name,
	p.description AS issuing_officer_job_title 
FROM
	fleetmaster.vm_vehicle_header g,
	Fleetmaster.vm_engine_details f,
	store_requisitions_header sh,
	store_requisitions_detail sd,
	store_movements_header mh,
	store_movements_detail md,
	spms_store_movement_types smt,
	ZFM_ARTICLES_VIEW a,
	spms_users u,
	spms_positions p,
	spms_document_status ds,
	spms_stores s 
WHERE
	g.REGISTRATION_NUMBER = f.reg_no 
	AND g.REGISTRATION_NUMBER = sh.reg_no 
	AND sh.ind_fuel = 1 
	AND sh.document_no = sd.document_no 
	AND sh.document_no = mh.stores_requisition_no 
	AND mh.document_no = md.document_no 
	AND mh.code_movement = smt.code_movement 
	AND MD.CODE_ARTICLE = a.code_article 
	AND mh.VEHICLE_REG_NO = f.reg_no 
	AND f.fuel_types = a.code_article 
	AND mh.document_no = ds.document_no 
	AND md.document_no = ds.document_no 
	AND p.code_position = ds.code_position 
	AND u.USER_ID = ds.USER_ACT 
	AND TO_CHAR( sh.date_act, 'YYYYMMDDHH' ) >= '{$now}' 
	AND md.quantity > ( f.tank_capacity + f.SUB_TANK_CAPACITY ) 
	AND mh.code_store = s.code_store 
	AND ds.status <> 07 
ORDER BY
	mh.date_act ASC");
    }


}
