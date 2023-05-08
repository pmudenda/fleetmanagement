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
- [ chmod -R 644 bootstrap/caches]()
-
## Updating Existing Installation
- [ php artisan migrate]()
- [ php artisan db:seed --class= {SeederClassName}]() - replace {SeederClassName} with actual class name e.g UserSeeder

## Importing Users from Active Directory
To import users from AD, run php artisan import
## Contributing
To contribute to this project, create an issue and subsequently a pull request
## Code of Conduct

In order to ensure that the system remains stable at all times, all features, bugs and enhance will be done using issue first approach. A pull request will then be merged by repository administrator.
## Security Vulnerabilities

To Be Advised By Cyber-Security

## License

The ZQMS is propitiatory software developed for use under strict [license]().



