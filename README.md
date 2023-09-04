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
## Folder Permissions
- change directory to application root directory
1. sudo chown -R $USER:apache . 	
2. sudo find . -type f -exec chmod 664 {} \;    	
3. sudo find . -type d -exec chmod 775 {} \;
4. sudo chown -R apache storage bootstrap/cache 
5. sudo chmod -R ug+rwx storage bootstrap/cache

## Updating Existing Installation
- [ php artisan migrate]()
- [ php artisan db:seed --class= {SeederClassName}]() - replace {SeederClassName} with actual class name e.g UserSeeder

## System Scripts

## Create Stores Requisition Function
```oracle

```

## Create Stores Reservation Function
```oracle

```

## Function Generate Reference
```oracle

```

## Reference Number Sequences
```oracle

```
## Synchronize Requisitions
```oracle

```

## Cancel Requisitions
```oracle

```

## Example Search Query
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
021109558968407878
The ZFMS is propitiatory software developed for use under strict [license]().

# run the command to install redis server
redis-server

sqp_57d8e7be7b187776c46345a97b4b9cd792ee3593

sonar-scanner \
-Dsonar.projectKey=FleetMaster \
-Dsonar.sources=. \
-Dsonar.host.url=http://localhost:9000 \
-Dsonar.token=sqp_57d8e7be7b187776c46345a97b4b9cd792ee3593


select t2.* from ( select rownum AS "rn", t1.* from (select "V_HEADER"."ON_BOARDING_STATUS", "V_HEADER"."HAS_TOM_CARD", "V_HEADER"."CREATED_AT", "V_HEADER"."REGISTRATION_NUMBER", "V_HEADER"."BODY_TYPE_NAME", "V_HEADER"."MODEL_NAME", "V_HEADER"."MODEL_CODE", "V_HEADER"."BRAND_NAME", "V_HEADER"."STATUS", "V_HEADER"."ID" as "HEADER_ID", "ENG_DET"."FUEL_ALLOCATION", "ENG_DET"."FUEL_TYPES", "CONFIG_STATUSES"."NAME" as "STATUS_NAME", "V_HEADER"."CREATED_NAME" as "ONBOARDED_BY" from "VM_VEHICLE_HEADER" v_header left join "CONFIG_STATUSES" on "V_HEADER"."STATUS" = "CONFIG_STATUSES"."CODE" left join "VM_ASSIGNMENTS" v_asgnment on "V_HEADER"."ID" = "V_ASGNMENT"."VEHICLE_HEADER_ID" left join "VM_CHASSIS_DETAILS" on "V_HEADER"."ID" = "VM_CHASSIS_DETAILS"."VEHICLE_HEADER_ID" left join "VM_ENGINE_DETAILS" eng_det on "V_HEADER"."ID" = "ENG_DET"."VEHICLE_HEADER_ID" where "CONFIG_STATUSES"."MODULE" = 'VEH' order by "V_HEADER"."STATUS" asc) t1 where rownum <= 40) t2 where t2."rn" >= 21


