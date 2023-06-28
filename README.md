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
- sudo chmod 2775 /var/www/html/project_name
- find /var/www/html/project_name -type d -exec sudo chmod 2775 {} \;
- find /var/www/html/project_name -type f -exec sudo chmod 0664 {} \;
- if above cmd doesnt not work use: sudo chmod -R ugo+rw storage
- [ chmod -R 755 vendor]()
- [ chmod -R 644 bootstrap/cache]()
-
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



