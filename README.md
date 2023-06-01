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
CREATE OR REPLACE FUNCTION fn_create_stores_req (
    p_ref_no           IN VARCHAR2,
    p_reg_no           IN VARCHAR2,
    p_store_code       IN VARCHAR2,
    p_user_requesting  IN VARCHAR2,
    p_job_card         IN VARCHAR2,
    p_system_origin    IN VARCHAR2,
    p_fleet_req_code   IN VARCHAR2,
    p_req_acc_number   IN VARCHAR2,
    p_delivery_site    IN VARCHAR2,
    p_transaction_type IN VARCHAR2,
    p_current_user     IN VARCHAR2
) RETURN STRING IS

/* Declaration of variables --STORES.AREA%TYPE;  */
    i                         BINARY_INTEGER := 0;
    v_line_number             BINARY_INTEGER := 0;
    ls_program_id             VARCHAR2(20);
    ls_point                  VARCHAR2(200);
    ln_rows                   NUMBER(12);
    ls_location               VARCHAR2(20);
    ls_function               VARCHAR2(20);
    ls_ace                    VARCHAR2(20);
    v_cst_cntr_code           VARCHAR2(20);
    v_reqsnr_usr_area         VARCHAR2(20);
    ls_data                   VARCHAR2(250);
    ln_count                  NUMBER(2);
    ls_desc                   VARCHAR2(150);
    stores_rsrvn_number       NUMBER(2);
    store_rsvn_status         VARCHAR2(20);
    ll_count                  NUMBER(5);
    ls_account_tms            VARCHAR(10);
    n_ds                      NUMBER(2);
    n_cnt                     NUMBER(1);

    /* Declaration of variables  */

    v_req_matrl_code          VARCHAR2(20);
    v_req_quantity            NUMBER(10, 2);
    v_req_artcl_price         NUMBER(10, 2);
    v_req_artcl_description   VARCHAR2(100);
    v_req_artcl_specification VARCHAR2(2000);
    v_veh_type_spec           VARCHAR2(100);
    v_req_articl_unit_msr     VARCHAR2(5);
    v_article_type            VARCHAR2(2);
    v_business_unit           VARCHAR2(10);
    ls_doc_no                 VARCHAR2(20);

/*START ROK ZMES-892*/

    v_req_article_group       VARCHAR2(6);
    v_fuel_bal                NUMBER(5);
    v_fuel_req_ind            NUMBER(2);
    v_req_odometer            NUMBER(19);
    v_req_flng_type           NUMBER(2);
    v_req_cost_cntr_code      VARCHAR(10);
    v_req_project_code        VARCHAR(10) := '000000';
    v_req_cost_bearer         VARCHAR(2);
/* ROK ZMES-892 */
    typ_spare_items           pg_sending_data.gt_spare_items;
    rec_spare_trans           pg_sending_data.gr_spare_trans;

/* Error-handling Variables  */
    v_err_message             VARCHAR2(255);
    v_error_code              VARCHAR2(255);
    ls_return                 VARCHAR2(300);
    v_wkshp_code              VARCHAR2(20);--GTAWKWAC.WSHP_CODE%TYPE;
    v_check_user_unit_wk      NUMBER;
    ls_user_unit_wk           VARCHAR2(255);-- ORGANIZATIONAL_UNITS.CODE_UNIT%TYPE;
    v_sys_origin              VARCHAR2(2) := '05';
    v_fuel_req_group          VARCHAR2(6) := '300101';
---------------------------------------------------------------------

    CURSOR cur_req_detail (
        req_number VARCHAR2
        ) IS
        SELECT
            d.material_code,
            d.quantity,
            d.price,
            art.description,
            art.technical_specifications,
            u.abbreviation,
            art.type_article,
            '', -- workshop_code
            h.requisition_type AS fueling_type,
            h.odometer,
            h.cost_centre      AS code_unit, --h.code_unit,
            CASE h.cost_assigned_to
                WHEN 'CostCenter' THEN
                    '01'
                ELSE
                    '02'
                END                AS ind_project, -- ind_project '01' - cost center 02 project
            d.project_code
        FROM
            gen_material_headers h,
            spms_articles_view   art,
            units_view           u,
            gen_material_details d
        WHERE
            h.req_no = req_number
          AND art.unit_measure = u.code_unit
          AND art.code_article = d.material_code
          AND h.req_no = d.req_no;

-- End of Declarations - Function Body
BEGIN
    n_cnt := 0;
--------------------------------------------------------------------------------  
    dbms_output.enable(100000000000000000000);
    dbms_output.put_line('Entering Procedure');
--------------------------------------------------------------------------------
    SELECT
        va.cost_center,
        va.business_unit,
        vh.body_type_name
            || ' '
            || vh.brand_name
            || ' '
            || vh.model_name
            || ' '
            || vh.model_code
    INTO
        v_cst_cntr_code,
        v_business_unit,
        v_veh_type_spec --, N_CNT
    FROM
        vm_vehicle_header vh,
        vm_assignments    va
    WHERE
        vh.id = va.vehicle_header_id
      AND va.assignment_state = '01'
      AND vh.registration_number = p_reg_no;

    OPEN cur_req_detail(p_ref_no);
    LOOP
        FETCH cur_req_detail INTO
            v_req_matrl_code,
            v_req_quantity,
            v_req_artcl_price,
            v_req_artcl_description,
            v_req_artcl_specification,
            v_req_articl_unit_msr,
            v_article_type,
            v_wkshp_code,
            v_req_flng_type,
            v_req_odometer,
            v_req_cost_cntr_code,
            v_req_cost_bearer,
            v_req_project_code;

        EXIT WHEN cur_req_detail%notfound;
        v_line_number := v_line_number + 1;

        /*ROK ZMES-892*/
        SELECT
                code_group
                || code_subgroup
                || code_class
        INTO v_req_article_group
        FROM
            spms_articles_view
        WHERE
            code_article = v_req_matrl_code;

        /*ROK ZMES-892*/
        /** determine account to post requition to based on entity bearing cost **/
        IF v_req_article_group = '300101' THEN
            IF v_req_cost_bearer = '01' THEN  -- 01 means cost center
                SELECT
                    bu_code,
                    cc_code
                INTO
                    v_business_unit,
                    v_cst_cntr_code
                FROM
                    organizational_units_view
                WHERE
                    code_unit = v_req_cost_cntr_code;

                ls_account_tms := p_req_acc_number;
            ELSE
                /*ls_project_code:=ls_code_unit;*/
                SELECT
                    code_bu,
                    code_cost_center
                INTO
                    v_business_unit,
                    v_cst_cntr_code
                FROM
                    spms_projects_view
                WHERE
                    code_project = v_req_project_code;

                v_req_cost_cntr_code := '';
                ls_account_tms := '1196000';
            END IF;
        END IF;

        /*END ROK*/

        rec_spare_trans.date_requirement := sysdate;
        ls_point := '1.0 - SYSDATE ';
        rec_spare_trans.store_code := p_store_code;
        ls_point := '1.0 - ls_store_code ';
        rec_spare_trans.cost_centre := v_cst_cntr_code;
        ls_point := '1.0 - ls_cost_centre ';
        rec_spare_trans.work_order_no := '';
        ls_point := '1.0 - work_order_no ';
        rec_spare_trans.stf_number := '';
        ls_point := '1.0 - stf_number ';
        rec_spare_trans.user_requesting := p_user_requesting;
        ls_point := '1.0 - user_requesting ';
        rec_spare_trans.system_origin := p_system_origin;
        ls_point := '1.0 - system_origin ';
        rec_spare_trans.store_res_no := '';
        ls_point := '1.0 - store_res_no ';
        rec_spare_trans.delivery_site := p_delivery_site;
        ls_point := '1.0 - delivery_site ';
        rec_spare_trans.transaction_type := p_transaction_type;
        ls_point := '1.0 - transaction_type ';

        /* MATERIAL OTHER THAN FUEL */
        IF v_req_article_group <> '300101' THEN
            /*ROK ZMES-892*/
            rec_spare_trans.subject := 'ZFMS STF GENERATED FOR MATERIAL MOVT. No.'
                || p_fleet_req_code
                || ' FOR '
                || p_reg_no
                || ' '
                || v_veh_type_spec;
        ELSE
            /* MATERIAL MVT. FOR FUEL */
            rec_spare_trans.subject := 'ZFMS STF GENERATED FOR FUEL MOVT. No.'
                || p_fleet_req_code
                || ' FOR '
                || p_reg_no
                || ' '
                || v_veh_type_spec;
        END IF;
        /*END ROK ZMES-892*/

        --lr_spare_trans.subject := 'TMS STF FOR MATERIAL MOVT. No.' ||
        --ls_tms_code||' FOR '||LS_REG_NO||''||LS_VEHICLE_TYPE  ;
        rec_spare_trans.dcs_code := p_fleet_req_code;
        ls_point := '1.0 - dcs_code ';
        rec_spare_trans.business_unit := v_business_unit;
        ls_point := '1.0 - business_unit ';
        rec_spare_trans.job_no := p_job_card;
        ls_point := '1.0 - job_no ';
        rec_spare_trans.project_no := '';
        ls_point := '1.0 - project_no ';
        rec_spare_trans.req_number := '';
        ls_point := '1.0 - req_number ';
        --

        dbms_output.put_line(v_error_code);
        --Get the detail data into the detail package
        --
        typ_spare_items(v_line_number).material_code := v_req_matrl_code;
        ls_point := '1.0 - material_code ';
        typ_spare_items(v_line_number).quantity := v_req_quantity;
        ls_point := '1.0 - quantity ';
        typ_spare_items(v_line_number).price := v_req_artcl_price;
        ls_point := '1.0 - price ';
        typ_spare_items(v_line_number).account_no := ls_account_tms;
        ls_point := '1.0 - account_no ';
        typ_spare_items(v_line_number).description := v_req_artcl_description;
        ls_point := '1.0 - Description ';
        typ_spare_items(v_line_number).specification := v_req_artcl_specification;
        ls_point := '1.0 - specification ';
        typ_spare_items(v_line_number).unit_measure := v_req_articl_unit_msr;
        ls_point := '1.0 - unit_measure ';
        typ_spare_items(v_line_number).article_type := v_article_type;
        ls_point := '1.0 - article_type ' || v_req_matrl_code;
        typ_spare_items(v_line_number).project_no := v_req_project_code;
        ls_point := '1.0 - ls_project_code ' || v_req_project_code;
    END LOOP;

    CLOSE cur_req_detail;
--------------------------------------------------------------------------------
    v_error_code := '3-';
    dbms_output.put_line(v_error_code);
    dbms_output.put_line('No. of Rows entered in lt_spare_items ' || to_char(v_line_number));
--------------------------------------------------------------------------------
    IF rec_spare_trans.transaction_type = '01' THEN
        dbms_output.put_line('transaction type ' || rec_spare_trans.transaction_type);
--------------------------------------------------------------------------------
        ls_program_id := 'SPMS 1.04';
        ls_point := '1.0';
        ln_rows := typ_spare_items.last;

        /* check system of origin */
        IF rec_spare_trans.system_origin <> v_sys_origin THEN
            ls_return := '1 '
                || ls_program_id
                || ls_point
                || ': System origin not determined. 
                         Please contact support';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        ls_point := '1.2';
        /* check the number of items added in collections*/
        IF typ_spare_items.last < 1 THEN
            ls_return := '1'
                || ls_program_id
                || ls_point
                || ': No item detail has been SET FOR the request';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        ls_point := '1.3';
        -- Get area info

        ls_point := '1.4';

        /* FUEL REQUISTION  */
        IF v_req_article_group = v_fuel_req_group THEN
            v_fuel_req_ind := 1;
            SELECT
                COUNT(*),
                area_code,
                nvl(area_code, 01)
            INTO
                ln_count,
                v_reqsnr_usr_area,
                ls_data
            FROM
                sec_users
            WHERE
                username = p_user_requesting
            GROUP BY
                area_code,
                nvl(area_code, 01);

        ELSE
            SELECT
                COUNT(*),
                area,
                head_store
            INTO
                ln_count,
                v_reqsnr_usr_area,
                ls_data
            FROM
                spms_stores_view
            WHERE
                code_store = rec_spare_trans.store_code
            GROUP BY
                area,
                head_store;

            IF ls_data = '01' THEN
                ls_return := '1' || 'Materials cannot be selected from a stock room. Please
                SELECT materials FROM the parent STORE';
                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            END IF;

        END IF;

        --

        IF ln_count < 1 THEN
            ls_return := '1'
                || 'The store '
                || rec_spare_trans.store_code
                || ' was not FOUND';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        rec_spare_trans.business_area := v_reqsnr_usr_area;
        -- If the store is a store with stockrooms then error

        FOR i IN typ_spare_items.first..typ_spare_items.last LOOP
                -- if the quantity is negative then the entry is not valid
                IF typ_spare_items(i).quantity <= 0 THEN
                    ls_return := '1'
                        || ls_program_id
                        || ls_point
                        || ': A new requisition is being created FOR the request. '
                        || 'However the detail cannot include a reduction IN quantities. Item '
                        || typ_spare_items(i).material_code
                        || ' is affected';

                    dbms_output.put_line(ls_return);
                    RETURN ( ls_return );
                END IF;

                IF rec_spare_trans.system_origin = v_sys_origin THEN
                    -- confirm that the quantities required are in the store for DCS only

                    IF v_req_article_group <> '300101' THEN   /*ROK ZMES-892*/
                        ls_return := f_check_quantities(rec_spare_trans.store_code, typ_spare_items(i).material_code, typ_spare_items(i).quantity
                            );
                    END IF;

                    dbms_output.put_line(ls_return);
                END IF;

            END LOOP;

        ls_point := '1.5';
        --
        -- check if the reservation no exists, if not replace with 'XXX'
        ls_data := nvl(rec_spare_trans.store_res_no, 'XXX');
        SELECT
            COUNT(*)
        INTO ln_count
        FROM
            store_reservations_header
        WHERE
            document_no = ls_data;

        dbms_output.put_line('STORE_RES_NO COUNT ' || to_char(ln_count));

        -- no reservation found
        IF ln_count < 1 THEN
            -- create stores reservation in authorised state
            IF v_req_article_group <> '300101' THEN  /*ROK ZMES-892*/
                rec_spare_trans.store_res_no := f_create_stores_reservation(rec_spare_trans, typ_spare_items);
                /* remove the preceeding 0 */
                rec_spare_trans.store_res_no := substr(rec_spare_trans.store_res_no, 2, length(rec_spare_trans.store_res_no) - 1);

            END IF;

            dbms_output.put_line(rec_spare_trans.store_res_no);
            --
        END IF;

        --
        ls_point := '1.6';
        --

        SELECT
            COUNT(*)
        INTO stores_rsrvn_number
        FROM
            store_reservations_header
        WHERE
            document_no = rec_spare_trans.store_res_no;
--
        dbms_output.put_line('LL_RES ' || to_char(stores_rsrvn_number));
        --
        IF stores_rsrvn_number > 0 THEN
            SELECT
                status
            INTO store_rsvn_status
            FROM
                store_reservations_header
            WHERE
                document_no = rec_spare_trans.store_res_no;
--
            dbms_output.put_line('LS_STATUS ' || store_rsvn_status);
            --
            -- If the status is not 'authorised' or 'partially issued' then it cannot be referenced
            IF
                        store_rsvn_status <> '01'
                    AND store_rsvn_status <> '02'
                    AND store_rsvn_status <> '26'
            THEN
                SELECT
                    description
                INTO ls_desc
                FROM
                    spms_general_view --general_tables
                WHERE
                    table_code = 'STA'
                  AND element_code = store_rsvn_status;

                ls_return := '1'
                    || ls_program_id
                    || ls_point
                    || ': The status of the reservation being referenced IS '
                    || lower(ls_desc)
                    || ' AND a requisition cannot be made REFERENCING it';

                dbms_output.put_line(ls_return);
                RETURN ls_return;
            END IF;
--
            UPDATE store_reservations_header
            SET
                status = '39'
            WHERE
                document_no = rec_spare_trans.store_res_no;
--
            ls_point := '1.7 UPDATE STORE RES.';
            dbms_output.put_line(ls_point);
--
        END IF;
--
        ls_point := '1.9';
        IF rec_spare_trans.system_origin = v_sys_origin THEN --dcs
            ls_function := '';
            /* get first four characters of cost center */
            ls_location := substr(rec_spare_trans.cost_centre, 1, 4);
            ls_ace := substr(rec_spare_trans.cost_centre, 5, 4);
            v_cst_cntr_code := ls_location || ls_ace;
            IF length(ls_location) < 1 THEN
                ls_return := '1'
                    || ls_program_id
                    || ls_point
                    || ': The location for the cost centre IS invalid';
                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            ELSIF length(ls_ace) < 1 THEN
                ls_return := '1'
                    || ls_program_id
                    || ls_point
                    || ': The ACE no. for the cost centre IS invalid';
                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            END IF;

            dbms_output.put_line('LR_SPARE_TRANS.SYSTEM_ORIGIN' || rec_spare_trans.system_origin);
        END IF;

        ls_point := '2.0';
        -- validate the company
        IF length(rec_spare_trans.business_area) < 1 THEN
            ls_return := '1'
                || ls_program_id
                || ls_point
                || ': The company for the financial information IS invalid';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        dbms_output.put_line('Insert into REQUISITIONS ' || rec_spare_trans.system_origin);
        --Insert into REQUISITIONS
        --Check if the header info has already been inserted
        --and if its status is still Authorised
        SELECT
            COUNT(*)
        INTO ll_count
        FROM
            store_requisitions_header
        WHERE
            user_doc_no = p_fleet_req_code
          AND status = '02';

        dbms_output.put_line('LL_COUNT'
            || ' '
            || p_fleet_req_code
            || ' '
            || to_char(ll_count));

        IF ll_count < 1 THEN
            -- generate stores requisition number
            ls_return := storesdocumentnumbergenerator('SEQ_STORE_REQ', v_reqsnr_usr_area);
            dbms_output.put_line('SEQ_STORE_REQ' || ls_return);
            /* if ls_return has no preceeding zero */
            IF substr(ls_return, 1, 1) <> '0' THEN
                -- add the word 'IPMS' to the error and return it
                ls_return := substr(ls_return, 1, 1)
                    || ls_program_id
                    || ls_point
                    || ' '
                    || substr(ls_return, 2, length(ls_return) - 1);

                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            END IF;

            ls_point := '1.8';
            /* Get the sequence from the return value */
            ls_doc_no := substr(ls_return, 2, length(ls_return) - 1);
            dbms_output.put_line('LS_DOC_NO ' || ls_doc_no);

            /* added by CS to check the workshop code */
            IF v_req_article_group = '300101' THEN
                /*ROK ZMES-892*/
                ls_user_unit_wk := v_req_cost_cntr_code;
            ELSE
                SELECT
                    COUNT(*)
                INTO v_check_user_unit_wk
                FROM
                    config_workshop
                WHERE
                    workshop_code = v_wkshp_code;

                IF v_check_user_unit_wk > 0 THEN
                    SELECT
                        cost_center
                    INTO ls_user_unit_wk
                    FROM
                        config_workshop
                    WHERE
                        workshop_code = v_wkshp_code;

                END IF;

            END IF;

            --dbms_output.put_line('ROK '||LR_SPARE_TRANS.BUSINESS_AREA);
            ls_point := '1.80A';
            --
            INSERT INTO store_requisitions_header (
                user_act,
                date_act,
                document_no,
                user_doc_no,
                date_document,
                company,
                code_store,
                status,
                subject,
                justification,
                store_reservation_no,
                cost_centre,
                business_area,
                job_no, --Job Card No
                work_order,
                user_requesting,
                user_authorizer,
                system_origin,
                code_unit,
                account,
                reg_no,
                ind_project,
                ind_fuel,
                fueling_type,
                odometer
            ) VALUES (
                         p_current_user,
                         sysdate,
                         ls_doc_no,
                         rec_spare_trans.dcs_code,
                         sysdate,
                         rec_spare_trans.business_area,
                         rec_spare_trans.store_code,
                         '02',
                         rec_spare_trans.subject,
                         rec_spare_trans.subject,
                         rec_spare_trans.store_res_no,
                         rec_spare_trans.cost_centre,
                         rec_spare_trans.business_unit,
                         rec_spare_trans.job_no,
                         rec_spare_trans.work_order_no,
                         rec_spare_trans.user_requesting,
                         p_current_user,
                         rec_spare_trans.system_origin,
                         ls_user_unit_wk,
                         typ_spare_items(v_line_number).account_no,
                         p_reg_no,/*ROK ZMES-892*/
                         v_req_cost_bearer,
                         v_fuel_req_ind,
                         v_req_flng_type,
                         v_req_odometer
                     );

            /*ROK ZMES-892*/

            dbms_output.put_line('LS_DOC_NO FIRST ROW ' || ls_doc_no);
            ls_point := '1.81';
            --Insert a Movement in ZFMS
            INSERT INTO sm_movement_header (
                created_by,
                created_date,
                document_number,
                expense_type,
                transaction_type,
                veh_reg_no,
                movement_date,
                store_code,
                business_area,
                business_unit,
                cost_centre,
                work_order_no,
                stf_number,
                requested_by,
                system_of_origin,
                requisition_no,
                stores_resrv_no,
                delivery_site,
                subject
            ) VALUES (
                         p_current_user,
                         sysdate,
                         rec_spare_trans.dcs_code,
                         '01', --Spares
                         '01', --Requisitions
                         p_reg_no,
                         sysdate,
                         rec_spare_trans.store_code,
                         v_reqsnr_usr_area,
                         rec_spare_trans.business_unit,
                         rec_spare_trans.cost_centre,
                         rec_spare_trans.job_no,
                         '',
                         rec_spare_trans.user_requesting,
                         rec_spare_trans.system_origin,
                         ls_doc_no,
                         rec_spare_trans.store_res_no,
                         p_delivery_site,
                         rec_spare_trans.subject
                     );

        ELSE
            SELECT
                document_no
            INTO ls_doc_no
            FROM
                store_requisitions_header
            WHERE
                user_doc_no = p_fleet_req_code
              AND status = '02';

            dbms_output.put_line('LS_DOC_NO ADDITION ' || ls_doc_no);
            --
        END IF;
        -- Insert details
        FOR i IN typ_spare_items.first..typ_spare_items.last LOOP
                dbms_output.put_line('Article being inserted in Detail ' || typ_spare_items(i).material_code);
                dbms_output.put_line('DocumentNo. being inserted in Detail ' || ls_doc_no);
                ls_point := '2.01';
                --Check if the dcs code sent has another requisition
                SELECT
                    COUNT(*)
                INTO ll_count
                FROM
                    store_requisitions_detail
                WHERE
                    document_no = ls_doc_no
                  AND code_article = typ_spare_items(i).material_code;

                dbms_output.put_line('Document No. count in Detail ' || to_char(ll_count));
                ls_point := '2.02';
                INSERT INTO store_requisitions_detail (
                    user_act,
                    date_act,
                    document_no,
                    code_article,
                    quantity,
                    account,
                    price_map,
                    amount,
                    project_no,
                    task_no,
                    expenditure_type,
                    reg_no
                ) VALUES (
                             p_current_user,
                             sysdate,
                             ls_doc_no,
                             typ_spare_items(i).material_code,
                             typ_spare_items(i).quantity,
                             typ_spare_items(i).account_no,
                             typ_spare_items(i).price,
                             typ_spare_items(i).quantity * typ_spare_items(i).price,
                             typ_spare_items(i).project_no,
                             '01',
                             'Store Item',
                             p_reg_no
                         );

                ls_point := '2.021';
                INSERT INTO sm_movement_details (
                    created_by,
                    created_date,
                    document_number,
                    material_code,
                    quantity,
                    price,
                    description,
                    specification,
                    unit_of_measure,
                    article_type,
                    transaction_type,
                    veh_reg_no,
                    authorised_by
                ) VALUES (
                             p_current_user,
                             sysdate,
                             rec_spare_trans.dcs_code,
                             typ_spare_items(i).material_code,
                             typ_spare_items(i).quantity,
                             typ_spare_items(i).price,
                             typ_spare_items(i).description,
                             typ_spare_items(i).specification,
                             typ_spare_items(i).unit_measure,
                             typ_spare_items(i).article_type,
                             '01',
                             p_reg_no,
                             p_current_user
                         );

                ls_point := '2.03';
                UPDATE gen_material_headers
                SET
                    status = '02', --TODO create column in material header
                    st_pur = ls_doc_no,
                    proc_ref = ls_doc_no,
                    form_order = p_fleet_req_code,
                    authorised_by = p_current_user,
                    --requested_by = p_current_user,
                    updated_at = sysdate
                WHERE
                    req_no = p_ref_no
                  AND status IN ( '01', '21' );
                -- 

                n_ds := 0;
                SELECT
                    COUNT(*)
                INTO n_ds
                FROM
                    document_status
                WHERE
                    document_no = ls_doc_no
                  AND status = '02';

                IF n_ds = 0 THEN
                    INSERT INTO document_status (
                        user_act,
                        date_act,
                        type_document,
                        document_no,
                        status,
                        amount,
                        code_position
                    ) VALUES (
                                 p_current_user,
                                 sysdate,
                                 '08',
                                 ls_doc_no,
                                 '02',
                                 typ_spare_items(i).quantity * typ_spare_items(i).price,
                                 ''
                             );

                END IF;
                --
            END LOOP;

--------------------------------------------------------------------------------------
        dbms_output.put_line(ls_doc_no);
        v_error_code := '4-'
            || ls_program_id
            || ls_point;
        dbms_output.put_line(v_error_code);
    END IF;

    v_error_code := '5-'
        || ls_program_id
        || ls_point;
    dbms_output.put_line(v_error_code);
    dbms_output.put_line('Exiting Procedure');
    COMMIT;
    dbms_output.put_line('Commiting');
--success
    ls_return := '0' || ls_doc_no;

--Return new document
    RETURN ls_return;
EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line('Error encountered during Execution' || sqlerrm);
        ls_return := 'Error encountered during Execution '
            || ls_program_id
            || ls_point
            || ': '
            || sqlerrm;
        RETURN ( ls_return );
END;
    /
```



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



