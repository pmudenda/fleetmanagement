<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('CONFIG_CHARGE_OUT_RATE', function (Blueprint $table) {
            $table->id();
            $table->string('created_by', 255);
            $table->string('modified_by', 255)->nullable();
            $table->string('vehicle_specification', 20);
            $table->string('vehicle_description');
            $table->decimal('charge', 19, 2);
            $table->string('currency', 3);
            $table->timestamps();
        });
        DB::unprepared("CREATE OR REPLACE TRIGGER tr_insert_vehicle_rates AFTER
                        INSERT ON config_charge_out_rate
                        FOR EACH ROW
                    DECLARE
                        no_update EXCEPTION;
                        error_parametrizacion EXCEPTION;
                        ls_dcs_category_code VARCHAR(20);
                    BEGIN
                            SELECT
                                'T'
                                || TRIM(to_char(MAX(TO_NUMBER(substr(dcs_category_code,
                                                                     2,
                                                                     length(dcs_category_code)))) + 1,
                                                '0000'))
                            INTO ls_dcs_category_code
                            FROM
                                dcsgt04.dcs_tms_categories@distdevfleet;

                            INSERT INTO dcsgt04.dcs_tms_categories@distdevfleet (
                                dcs_category_code,
                                tms_category_code,
                                category_description,
                                unit_of_measure,
                                transport_cost,
                                date_of_change,
                                user_act,
                                effective_date
                            ) VALUES (
                                ls_dcs_category_code,
                                :new.vehicle_specification,
                                :new.vehicle_description,
                                :new.charge,
                                :new.currency,
                                sysdate,
                                user,
                                sysdate
                            );
                    EXCEPTION
                        WHEN OTHERS THEN
                            raise_application_error(-20010, 'System error. .Trigger:TR_INSERT_VEHICLE_RATES. :  Oracle code:  '
                                                            || to_char(sqlcode)
                                                            || ' Error description : '
                                                            || sqlerrm);
                    END;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('CONFIG_CHARGE_OUT_RATE');
    }
};
