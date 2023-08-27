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
        Schema::create('FUEL_MANAGEMENT', function (Blueprint $table) {
            //$table->id();
            $table->string('user_act', 20)->nullable();;
            $table->date('fech_act')->nullable();
            $table->string('voucher_no', 20)->nullable();
            $table->string('document_no', 20)->nullable();
            $table->string('reg_no', 20);
            $table->string('cost_center', 12)->nullable();
            $table->date('voucher_date')->nullable();
            $table->string('bus_area', 2)->nullable();
            $table->string('authorisor', 50)->nullable();
            $table->string('receiver', 50)->nullable();
            $table->string('issue_office', 50)->nullable();
            $table->string('unit_measure', 6)->nullable();
            $table->string('fuel_code', 12)->nullable();
            $table->decimal('quantity', 19, 4)->nullable();
            $table->decimal('price', 19, 4)->nullable();
            $table->decimal('amount', 19, 4)->nullable();
            $table->decimal('odometer', 19, 0)->nullable();
            $table->decimal('pump_start', 19, 0)->nullable();
            $table->decimal('pump_end', 19, 0)->nullable();
            $table->string('status', 15)->nullable();
            $table->date('voucher_time')->nullable();
            $table->string('business_unit', 8)->nullable();
            $table->string('user_unit', 15)->nullable();
            $table->string('sys_origin', 10)->nullable();
            $table->string('fueling_type', 2)->nullable();
            $table->string('comments', 2000)->nullable();
            $table->unique(["VOUCHER_NO", "REG_NO", "STATUS", "VOUCHER_DATE"]);
            $table->timestamps();
        });

        DB::unprepared('CREATE INDEX "IDX_FUELMNGT" ON "FUEL_MANAGEMENT" ("REG_NO", "FUELING_TYPE", "VOUCHER_DATE")');
        DB::unprepared('CREATE INDEX "INDFMBA" ON "FUEL_MANAGEMENT" ("BUS_AREA")');
        DB::unprepared('CREATE INDEX "INDFMVD" ON "FUEL_MANAGEMENT" ("FECH_ACT")');
        DB::unprepared('CREATE UNIQUE INDEX "IND_FM_DOC_NO" ON "FUEL_MANAGEMENT" ("DOCUMENT_NO")');
        DB::unprepared('CREATE INDEX "IND_VD" ON "FUEL_MANAGEMENT" ("VOUCHER_DATE")');
        DB::unprepared('CREATE OR REPLACE TRIGGER "TR_FUEL_MANAGEMENT_INSERT"
                                AFTER INSERT
                                ON FUEL_MANAGEMENT
                                REFERENCING NEW AS NEW OLD AS OLD
                                FOR EACH ROW
                                DECLARE
                                LN_COUNT NUMBER;
                                LN_OLD_MILLEAGE NUMBER;

                                vdt fuel_management.voucher_date%type;
                                BEGIN

                                    UPDATE vm_vehicle_header
                                    SET MILEAGE = :NEW.ODOMETER
                                    WHERE TRIM(REGISTRATION_NUMBER)=TRIM(:NEW.REG_NO);
                                END;
                                /
                                ALTER TRIGGER "TR_FUEL_MANAGEMENT_INSERT" ENABLE;

                                CREATE OR REPLACE TRIGGER "TR_FUEL_ODOMETER_UPDATE"
                                AFTER UPDATE
                                OF ODOMETER
                                ON FUEL_MANAGEMENT
                                REFERENCING NEW AS NEW OLD AS OLD
                                FOR EACH ROW
                                DECLARE
                                LN_COUNT NUMBER;
                                BEGIN
                                UPDATE vm_vehicle_header
                                SET MILEAGE = :NEW.ODOMETER
                                WHERE trim(REGISTRATION_NUMBER)= trim(:OLD.REG_NO);
                                END;');

        /*DB::unprepared('ALTER TRIGGER "TR_FUEL_ODOMETER_UPDATE" ENABLE;

                                GRANT UPDATE ON "FUEL_MANAGEMENT" TO "ORAFINANCE";

                                GRANT SELECT ON "FUEL_MANAGEMENT" TO "ORAFINANCE";

                                GRANT INSERT ON "FUEL_MANAGEMENT" TO "ORAFINANCE";

                                GRANT UPDATE ON "FUEL_MANAGEMENT" TO "END_USER_TMS";

                                GRANT SELECT ON "FUEL_MANAGEMENT" TO "END_USER_TMS";

                                GRANT INSERT ON "FUEL_MANAGEMENT" TO "END_USER_TMS";

                                GRANT DELETE ON "FUEL_MANAGEMENT" TO "END_USER_TMS";

                                GRANT UPDATE ON "FUEL_MANAGEMENT" TO "SPMS";

                                GRANT SELECT ON "FUEL_MANAGEMENT" TO "SPMS";

                                GRANT INSERT ON "FUEL_MANAGEMENT" TO "SPMS";');*/
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('FUEL_MANAGEMENT');
    }
};
