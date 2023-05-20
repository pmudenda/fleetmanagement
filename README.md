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


create or replace FUNCTION fn_generate_reference_number (
    p_module VARCHAR2
) RETURN STRING IS
    v_prefix    VARCHAR2(7);
    v_next_num  INTEGER;
    v_reference VARCHAR2(20);
BEGIN
    IF p_module = 'FUEL_REQ' THEN
        v_prefix := 'ZFMFUEL';
        v_next_num := "FLEETMASTER"."FUEL_REQ_SEQ".nextval;
    ELSIF p_module = 'SPARES_REQ' THEN
        v_prefix := 'ZFMREF';
        v_next_num := "FLEETMASTER"."SPARES_REQ_SEQ".nextval;
    END IF;

    v_reference := v_prefix || to_char(v_next_num);
    RETURN v_reference;

EXCEPTION
    WHEN  OTHERS THEN
        dbms_output.put_line('Error!');

END;
```

In order to ensure that the system remains stable at all times, all features, bugs and enhance will be done using issue first approach. A pull request will then be merged by repository administrator.
## Security Vulnerabilities

To Be Advised By Cyber-Security

## License

The ZQMS is propitiatory software developed for use under strict [license]().



