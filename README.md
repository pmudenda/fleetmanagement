<p align="center"><a href="https://laravel.com" target="_blank">
<img src="https://www.zesco.co.zm/images/zesco_logo.png" width="90">
</a>
</p>

## About Zesco Fleet Master

ZFM is a web based fleet & logistics management system built using laravel framework.  It brings to life Vehicle Onboarding, Fuel Requisition, Motor vehicle requisition, Workshop management & other modules:
<br/>Laravel Provides:
- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

## First Time Installation ZFM
#Scripts to run on server
- [ git clone respository]()
- [ composer install]()
- composer require yajra/laravel-oci8:^10
- Uncomment Yajra\Oci8\Oci8ServiceProvider::class in app.php providers array
-  php artisan vendor:publish --tag=oracle
- [ php artisan migrate:fresh --seed]()
- [ php artisan adldap:import ]()
- [chmod -R 755 storage]() 
- sudo chown -R apache:apache /var/www/html/project_name
  sudo chmod 2775 /var/www/html/project_name
  find /var/www/html/project_name -type d -exec sudo chmod 2775 {} \;
  find /var/www/html/project_name -type f -exec sudo chmod 0664 {} \;
- if above cmd doesnt not work use: sudo chmod -R ugo+rw storage
- [ chmod -R 755 vendor]()
- [ chmod -R 644 bootstrap/cache]()
-
## Updating Existing Installation
- [ php artisan migrate]()
- [ php artisan db:seed --class= {SeederClassName}]() - replace {SeederClassName} with actual class name e.g UserSeeder

## System Scripts
```oracle
CREATE SEQUENCE "FLEETMASTER"."FUEL_REQ_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE
    20 NOORDER NOCYCLE NOKEEP NOSCALE GLOBAL;

CREATE SEQUENCE "FLEETMASTER"."SPARES_REQ_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE
    20 NOORDER NOCYCLE NOKEEP NOSCALE GLOBAL;


CREATE SEQUENCE "FLEETMASTER"."PURCHASE_REQ_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE
    20 NOORDER NOCYCLE NOKEEP NOSCALE GLOBAL;



CREATE SEQUENCE "FLEETMASTER"."STORES_REQ_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE
    20 NOORDER NOCYCLE NOKEEP NOSCALE GLOBAL;

CREATE SEQUENCE "FLEETMASTER"."GENERAL_REQ_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE
    20 NOORDER NOCYCLE NOKEEP NOSCALE GLOBAL;

CREATE SEQUENCE "FLEETMASTER"."DRV_ONBOARDING_REQ_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE
    20 NOORDER NOCYCLE NOKEEP NOSCALE GLOBAL;

CREATE SEQUENCE "FLEETMASTER"."VEH_ONBOARDING_REQ_SEQ" MINVALUE 1 MAXVALUE 9999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE
    20 NOORDER NOCYCLE NOKEEP NOSCALE GLOBAL;


CREATE OR REPLACE FUNCTION fn_generate_reference_number (
    p_module VARCHAR2
) RETURN STRING IS
    v_prefix    VARCHAR2(7);
    v_next_num  INTEGER;
    v_reference VARCHAR2(20);
BEGIN
    IF p_module = 'FUEL_REQ' THEN
        v_prefix := 'ZFMFUE';
        v_next_num := "FLEETMASTER"."FUEL_REQ_SEQ".nextval;
    ELSIF p_module = 'SPARES_REQ' THEN
        v_prefix := 'ZFMREF';
        v_next_num := "FLEETMASTER"."SPARES_REQ_SEQ".nextval;
    ELSIF p_module = 'PUR' THEN
        v_prefix := 'ZFMPUR';
        v_next_num := "FLEETMASTER"."PURCHASE_REQ_SEQ".nextval;
    ELSIF p_module = 'STR' THEN
        v_prefix := 'ZFMSTR';
        v_next_num := "FLEETMASTER"."STORES_REQ_SEQ".nextval;
    ELSIF p_module = 'REQ' THEN
        v_prefix := 'ZFMREQ';
        v_next_num := "FLEETMASTER"."GENERAL_REQ_SEQ".nextval;
    ELSIF p_module = 'DRV_ONBOARD' THEN
        v_prefix := 'DRVONB';
        v_next_num := "FLEETMASTER"."DRV_ONBOARDING_REQ_SEQ".nextval;
    ELSIF p_module = 'VEH_ONBOARD' THEN
        v_prefix := 'VEHONB';
        v_next_num := "FLEETMASTER"."VEH_ONBOARDING_REQ_SEQ".nextval;
    END IF;

    v_reference := v_prefix
        || lpad(to_char(v_next_num), 7, '0');
    RETURN v_reference;
EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line('Error!');
END;
```
```php
$list = User::select('id', 'user_unit_id', 'con_st_code', 'positions_id', 'staff_no', 'user_unit_code', 'job_code', 'email', 'name', 'created_at', 'phone')
            ->where('email', 'LIKE', "%{$search}%")
            ->orWhere('name', 'LIKE', "%{$search}%")
            ->orWhere('nrc', 'LIKE', "%{$search}%")
            ->orWhere('staff_no', 'LIKE', "%{$search}%")
            ->orWhere('staff_no_alt', 'LIKE', "%{$search}%")
            ->orWhere('user_unit_code', 'LIKE', "%{$search}%")
            ->orWhere('contract_type', 'LIKE', "%{$search}%")
            ->orWhere('con_st_code', 'LIKE', "%{$search}%")
            ->orWhere('job_code', 'LIKE', "%{$search}%")
            ->orWhere('phone', 'LIKE', "%{$search}%")
            ->get();
```
In order to ensure that the system remains stable at all times, all features, bugs and enhance will be done using issue first approach. A pull request will then be merged by repository administrator.
## Security Vulnerabilities

To Be Advised By Cyber-Security

## License

The ZQMS is propitiatory software developed for use under strict [license]().



