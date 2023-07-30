<?php

return [

    'models' => [

        /*
         * When using the "HasPermissions" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your permissions. Of course, it
         * is often just the "Permission" model but you may use whatever you like.
         *
         * The model you want to use as a Permission model needs to implement the
         * `Spatie\Permission\Contracts\Permission` contract.
         */

        'permission' => Spatie\Permission\Models\Permission::class,

        /*
         * When using the "HasRoles" trait from this package, we need to know which
         * Eloquent model should be used to retrieve your roles. Of course, it
         * is often just the "Role" model but you may use whatever you like.
         *
         * The model you want to use as a Role model needs to implement the
         * `Spatie\Permission\Contracts\Role` contract.
         */

        'role' => Spatie\Permission\Models\Role::class,

    ],

    'table_names' => [
        'projects'=> 'ZFM_SPMS_PROJECTS_VIEW',
        'areas'=> 'ZFM_SPMS_AREAS_VIEW',
        'articles' => 'ZFM_SPMS_ARTICLES_VIEW',
        'units' => 'ZFM_UNITS_VIEW',
        'stockManagement' => 'ZFM_STOCK_MANAGEMENT_VIEW',
        ''=> 'ZFM_ORGANIZATIONAL_UNITS_VIEW'


    ],

    'column_names' => [
        'team_foreign_key' => 'team_id',
    ],

];
