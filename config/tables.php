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
        'projects' => 'ZFM_PROJECTS_VIEW',
        'areas' => 'ZFM_AREAS_VIEW',
        'articles' => 'ZFM_ARTICLES_VIEW',
        'units' => 'ZFM_UNITS_VIEW',
        'stockManagement' => 'ZFM_STOCK_MANAGEMENT_VIEW',
        'organizationalView' => 'ZFM_ORGANIZATIONAL_UNITS_VIEW',
        'purchaseOffices' => 'ZFM_PURCHASE_OFFICES',
        'purchaseOrders' => 'P_ORDERS_ONBOARDING_VIEW',
        'stores' => 'ZFM_STORES_VIEW',
        'generalTables' => 'ZFM_GENERAL_TABLES',
        'documentStatus'=> 'ZFM_DOCUMENT_STATUS_VIEW',
        'employee' => 'ipa_phris_view'
    ],

    'column_names' => [
        'team_foreign_key' => 'team_id',
    ],

];
