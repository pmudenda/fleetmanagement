# PACKAGES
```oracle
create or replace PACKAGE          pkg_sending_data AS
    TYPE rec_spare_trans IS RECORD (
        date_requirement DATE,
        store_code       VARCHAR2(100),
        cost_centre      VARCHAR2(100),
        work_order_no    VARCHAR2(100),
        stf_number       VARCHAR2(100),
        user_requesting  VARCHAR2(100),
        system_origin    VARCHAR2(100),
        business_area    VARCHAR2(100),
        store_res_no     VARCHAR2(100),
        delivery_site    VARCHAR2(100),
        transaction_type VARCHAR2(100),
        subject          VARCHAR2(200),
        dcs_code         VARCHAR2(100),
        business_unit    VARCHAR2(100),
        job_no           VARCHAR2(100),
        project_no       VARCHAR2(100),
        req_number       VARCHAR2(100),
        task_no          VARCHAR2(100)
    );



    TYPE rec_spare_item IS RECORD (
        material_code VARCHAR2(100),
        quantity      NUMBER(15, 3),
        price         NUMBER(19, 4),
        account_no    VARCHAR2(100),
        description   VARCHAR2(120),
        specification VARCHAR2(2000),
        unit_measure  VARCHAR2(3),
        article_type  VARCHAR2(2),
        project_no    VARCHAR2(100)
    );

    TYPE type_spare_items IS
        TABLE OF rec_spare_item INDEX BY BINARY_INTEGER;
END pkg_sending_data;
```

# FUNCTIONS
```oracle
create or replace FUNCTION fn_cancel_stores_req (
    p_ref_no       IN VARCHAR2,
    p_current_user IN VARCHAR2,
    p_system_origin IN VARCHAR2
) RETURN VARCHAR2 AS
    v_return_message VARCHAR2(300);
    v_count          PLS_INTEGER;
BEGIN

    --set 45 as tms cancelled
    IF substr(p_ref_no, 0,3) = 'J02' THEN
        SELECT
            COUNT(document_no)
        INTO v_count
        FROM
            store_reservations_header
        WHERE document_no = p_ref_no;

        IF v_count = 0 THEN
            v_return_message := 'Reservation with doc no. '
                || p_ref_no
                || ' not found';
        ELSE
            UPDATE store_reservations_header
            SET
                status = '03'
            WHERE
                document_no = p_ref_no;

            v_return_message := 'Reservation '
                || p_ref_no
                || ' cancelled';
        END IF;
    ELSIF substr(p_ref_no, 0,3) = 'J01' THEN
        SELECT
            COUNT(document_no)
        INTO v_count
        FROM
            store_requisitions_header
        WHERE document_no = p_ref_no;

        IF v_count = 0 THEN
            v_return_message := 'Requistion with doc no. '
                || p_ref_no
                || ' not found';
        ELSE
            UPDATE store_requisitions_header
            SET
                status = '45'
            WHERE
                document_no = p_ref_no;

            v_return_message := 'Requistion '
                || p_ref_no
                || ' cancelled';
        END IF;
    END IF;


    SELECT
        COUNT(document_no)
    INTO v_count
    FROM
        store_requisitions_header
    WHERE document_no = p_ref_no;

    IF v_count = 0 THEN
        v_return_message := 'Requistion with doc no. '
            || p_ref_no
            || ' not found';
    ELSE
        UPDATE store_requisitions_header
        SET
            status = '45'
        WHERE
            document_no = p_ref_no;

        v_return_message := 'Requistion '
            || p_ref_no
            || ' cancelled';
    END IF;
    COMMIT;
    RETURN v_return_message;
EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line('Error encountered during Execution Of Routine' || sqlerrm);
        v_return_message := 'Error encountered during Execution ' || sqlerrm;
        RETURN ( v_return_message );
END fn_cancel_stores_req;
```

```oracle
create or replace FUNCTION fn_create_pur_process (
    p_reference        IN VARCHAR2,
    p_reg_no           IN VARCHAR2,
    p_store_code       IN VARCHAR2,
    p_user_requesting  IN VARCHAR2,
    p_job_card_no      IN VARCHAR2,
    p_system_origin    IN VARCHAR2,
    p_form_order       IN VARCHAR2,
    p_req_account      IN VARCHAR2,
    p_delivery_site    IN VARCHAR2,
    p_transaction_type IN VARCHAR2,
    p_current_user     IN VARCHAR2
) RETURN STRING IS
    /*
        Dependences from SPMS
        DOCUMENT_STATUS
    */
-- Declaration of variables
    i                      BINARY_INTEGER := 0;
    v_line_number          BINARY_INTEGER := 0;
    v_req_check            NUMBER(2);
    v_program_id           VARCHAR2(20);
    v_execution_point      VARCHAR2(200);
    ln_rows                NUMBER(6);
    ls_location            VARCHAR2(20);
    ls_function            VARCHAR2(20);
    ls_ace                 VARCHAR2(20);
    ls_cost_centre         VARCHAR2(20);
    ls_code_unit           VARCHAR2(20);
    ls_area                VARCHAR2(2);
    ls_data                VARCHAR2(255);
    ln_count               NUMBER(1);
    ls_desc                VARCHAR2(2000);
    ll_res                 NUMBER(1);
    ls_status              VARCHAR2(20);
    ll_count               NUMBER(5);
    n_check_ds             NUMBER;
    v_article_code         VARCHAR2(20);
    v_req_quantity         NUMBER(10);
    ll_price               NUMBER(10);
    ls_description         VARCHAR2(2000);
    ls_specification       VARCHAR2(2000);
    ls_unit_measure        VARCHAR2(5);
    ls_article_type        VARCHAR2(2);
    ls_business_unit       VARCHAR2(10);
    ls_doc_no              VARCHAR2(20);
    ls_delivery_place      VARCHAR(100);
    v_subject              VARCHAR2(2000);
    ls_code_unit_wks       VARCHAR(6);
    n_check_code_unit      NUMBER;
    ls_delivery_place_code VARCHAR2(2);
    type_spare_items       pkg_sending_data.type_spare_items;
    rec_spare_trans        pkg_sending_data.rec_spare_trans;
    v_err_msg              VARCHAR2(255);
    v_error_code           VARCHAR2(255);
    ls_return              VARCHAR2(255);
    v_issuing_system       VARCHAR(2) := '01';

--End of Declarations - start of Main Function Body
---------------------------------------------------------------------

    CURSOR detail_cursor (
        p_reference VARCHAR2
    ) IS
    SELECT
        d.material_code,
        d.quantity,
        d.amount,
        'SERVICE',
        specifications,
        '001',
        decode(substr(d.material_code, 1, 2),
               '40',
               '02',
               '41',
               '03',
               '01',
               '01',
               '03')
    FROM
        gen_material_headers h,
        gen_material_details d
    WHERE
            h.req_no = p_reference
        AND h.req_no = d.req_no;

    /*SELECT
        serv.material_code,
        serv.quantity,
        serv.amount_est,
        wkt.description,
        --'PURCH. REQ. FOR SUB-CONTRACTED REPAIRS FOR '||LS_REG_NO ,'001','03'
        serv.specification,
        '001',
        decode(substr(serv.material_code, 1, 2),
               '40',
               '02',
               '41',
               '03',
               '01',
               '01',
               '03')
    FROM
        wm_workshop_services serv,
        wm_workshop_tables   wkt,
        wm_vehicle_defects   vdef
    WHERE
            serv.workshop_reference = p_reference
        AND serv.workshop_reference = vdef.workshop_reference --ACT_CODE
        AND serv.req_evaluation = 'Y'
        AND serv.ind IS NULL
        AND wkt.type_code = 'WDF'
        AND wkt.code = serv.def_no
        AND parent = vdef.defect_category_code
        AND serv.def_no = vdef.defect_code;*/

BEGIN
    dbms_output.enable(100000000000000000000);
    v_execution_point := '1.0';
    SELECT
        wkshops.workshop_code,
        wkshops.workshop_name
    INTO
        ls_delivery_place_code,
        ls_delivery_place
    FROM
        gen_material_headers mat_header,
        config_workshop      wkshops
    WHERE
            mat_header.req_no = p_reference
        AND wkshops.workshop_code = mat_header.workshop_no;

    v_execution_point := '1.0A';
    SELECT
        va.cost_center,
        va.business_unit,
        vh.business_unit_code
    INTO
        ls_cost_centre,
        ls_business_unit,
        ls_code_unit
    FROM
        vm_vehicle_header vh,
        vm_assignments    va
    WHERE
            vh.id = va.vehicle_header_id
        AND vh.registration_number = p_reg_no;

    v_execution_point := '1.0B';
    SELECT
        COUNT(*)
    INTO v_req_check
    FROM
        gen_material_headers h
    WHERE
        h.req_no = p_reference
    GROUP BY
        h.req_no;

    v_execution_point := '1.0C';
    IF v_req_check > 1 THEN
        v_subject := 'SUB-CONTRACTED REPAIRS FOR ' || p_reg_no;
    ELSE

        /*SELECT
            description
            || ' FOR '
            || reg_no
        INTO v_subject
        FROM
            spms_articles_view   artcl,
            wm_job_card_header   wkjch,
            wm_workshop_services wkser
        WHERE
                wkjch.wshp_act_code = wkser.workshop_reference
            AND wkser.workshop_reference = p_reference
            AND wkser.material_code = artcl.code_article;
            */

        SELECT
            description
            || ' FOR '
            || header.reg_no
        INTO v_subject
        FROM
            ZFM_ARTICLES_VIEW   articles,
            gen_material_details header,
            gen_material_details detail
        WHERE
                header.req_no = detail.req_no
            AND detail.req_no = p_reference
            AND detail.material_code = articles.code_article;

    END IF;

    v_execution_point := '1.0D';
    OPEN detail_cursor(p_reference);
    LOOP
        FETCH detail_cursor INTO
            v_article_code,
            v_req_quantity,
            ll_price,
            ls_description,
            ls_specification,
            ls_unit_measure,
            ls_article_type;
        EXIT WHEN detail_cursor%notfound;
        -- 1. Get the data from the cursor and put it into the header package
        v_line_number := v_line_number + 1;
        rec_spare_trans.date_requirement := sysdate;
        rec_spare_trans.store_code := p_store_code;
        rec_spare_trans.cost_centre := ls_cost_centre;

        -- check if there is a code unit assined to the workshop
        n_check_code_unit := 0;
        SELECT
            COUNT(*)
        INTO n_check_code_unit
        FROM
            config_workshop
        WHERE
            workshop_code = ls_delivery_place_code;

        IF n_check_code_unit > 0 THEN
            SELECT
                user_unit
            INTO ls_code_unit_wks
            FROM
                config_workshop
            WHERE
                workshop_code = ls_delivery_place_code;

        END IF;

        rec_spare_trans.work_order_no := ls_code_unit_wks;
        rec_spare_trans.stf_number := '';
        rec_spare_trans.user_requesting := p_user_requesting;
        rec_spare_trans.system_origin := p_system_origin;
        rec_spare_trans.store_res_no := '';
        rec_spare_trans.delivery_site := ls_delivery_place;
        rec_spare_trans.transaction_type := p_transaction_type;
        rec_spare_trans.subject := v_subject;
        rec_spare_trans.dcs_code := p_form_order;
        rec_spare_trans.business_unit := ls_business_unit;
        rec_spare_trans.job_no := p_job_card_no;
        rec_spare_trans.project_no := p_reg_no;
        rec_spare_trans.req_number := '';
        v_error_code := '2-';
        type_spare_items(v_line_number).material_code := v_article_code;
        type_spare_items(v_line_number).quantity := v_req_quantity;
        type_spare_items(v_line_number).price := ll_price;
        type_spare_items(v_line_number).account_no := '6120013';
        type_spare_items(v_line_number).description := ls_description;
        type_spare_items(v_line_number).specification := ls_specification;
        type_spare_items(v_line_number).unit_measure := ls_unit_measure;
        type_spare_items(v_line_number).article_type := ls_article_type;
    END LOOP;

    CLOSE detail_cursor;
    -- v_error_code := '3-';

    dbms_output.put_line('No. of Rows entered in type_spare_items ' || to_char(v_line_number));
    v_execution_point := '1.0E'; --
    IF rec_spare_trans.transaction_type = '04' THEN
        dbms_output.put_line(rec_spare_trans.transaction_type);
        -----------------------------------------------------------------------------------
        v_program_id := 'ZFM 1.0';
        v_execution_point := '1.1';
        ln_rows := type_spare_items.last;
        IF rec_spare_trans.system_origin <> '04' THEN
            ls_return := '1'
                         || v_program_id
                         || v_execution_point
                         || ': System origin not determined. Please contact support';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        v_execution_point := '1.2';
        IF type_spare_items.last < 1 THEN
            ls_return := '1'
                         || v_program_id
                         || v_execution_point
                         || ': No item detail has been SET FOR the request';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        v_execution_point := '1.3';

        -- Get area info
        SELECT
            COUNT(*),
            area
        INTO
            ln_count,
            ls_area
        FROM
            zfm_purchase_offices
        WHERE
            code_office = rec_spare_trans.store_code
        GROUP BY
            area;

        rec_spare_trans.business_area := ls_area;
        v_execution_point := '1.4';
        IF ln_count < 1 THEN
            ls_return := '1'
                         || 'The Area for the Office '
                         || rec_spare_trans.store_code
                         || ' was not found';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        FOR i IN type_spare_items.first..type_spare_items.last LOOP
                -- if the quantity is negative then the entry is not valid
            IF type_spare_items(i).quantity <= 0 THEN
                ls_return := '1'
                             || v_program_id
                             || v_execution_point
                             || ': Creating new Req for the request. '
                             || 'However the detail cannot include a'
                             || 'reduction IN quantities. Item '
                             || type_spare_items(i).material_code
                             || ' is affected';

                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            END IF;
        END LOOP;

        v_execution_point := '1.5';
        ls_return := fn_create_pur_req(rec_spare_trans, type_spare_items);
        v_execution_point := '1.501';
        IF substr(ls_return, 1, 1) <> '0' THEN
            ls_return := substr(ls_return, 1, 1)
                         || v_program_id
                         || v_execution_point
                         || ' Error '
                         || substr(ls_return, 2, length(ls_return) - 1);

            RETURN ( ls_return );
        END IF;

        v_execution_point := '1.502';
        ls_doc_no := ls_return;
        /* remove  */
        ls_doc_no := substr(ls_doc_no, 2, length(ls_doc_no) - 1);
        dbms_output.put_line(ls_doc_no);
        v_execution_point := '1.6';

        -- validate the company
        IF length(rec_spare_trans.business_area) < 1 THEN
            ls_return := '1'
                         || v_program_id
                         || v_execution_point
                         || ': Invalid company financial Info ';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        v_execution_point := '1.81';

        /* insert a Movement in ZFMS */
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
            --business_unit,
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
            '02', --01-Spares - 02 services
            '04', --01-Stores Requisitions -04 Purchase Requisitions
            p_reg_no,
            sysdate,
            rec_spare_trans.store_code,
            ls_area,
            rec_spare_trans.cost_centre,
            rec_spare_trans.job_no,
            ls_doc_no,
            rec_spare_trans.user_requesting,
            v_issuing_system,
            '',
            '',
            p_delivery_site,
            rec_spare_trans.subject
        );

        FOR i IN type_spare_items.first..type_spare_items.last LOOP
            dbms_output.put_line('Article Code ' || type_spare_items(i).material_code);
            dbms_output.put_line('DocumentNo.' || ls_doc_no);
            v_execution_point := '2.01';
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
                type_spare_items(i).material_code,
                type_spare_items(i).quantity,
                type_spare_items(i).price,
                type_spare_items(i).description,
                ls_specification,
                'EA', --typ_spare_items(i).unit_measure,
                type_spare_items(i).article_type,
                p_transaction_type,
                p_reg_no,
                p_current_user
            );

            v_execution_point := '2.03';

                -- Update the columns inserted
            UPDATE wm_workshop_services
            SET
                ind = 'Y',
                movt_no = p_form_order,
                stf_number = ls_doc_no,
                status = '02',
                updated_at = sysdate,
                originator = user,
                authorised_by = user
            WHERE
                    wshp_act_code = p_reference
                AND req_evaluation = 'Y'
                AND mat_code = type_spare_items(i).material_code
                AND ind IS NULL;

            SELECT
                COUNT(*)
            INTO n_check_ds
            FROM
                document_status
            WHERE
                    document_no = ls_doc_no
                AND status = '02';

            IF n_check_ds > 0 THEN
                INSERT INTO document_status (
                    user_act,
                    date_act,
                    type_document,
                    document_no,
                    status,
                    amount,
                    code_position
                ) VALUES (
                    user,
                    sysdate,
                    '11',
                    ls_doc_no,
                    '02',
                    type_spare_items(i).price,
                    ''
                );

            END IF;

        END LOOP;

--------------------------------------------------------------------------------------
        dbms_output.put_line(ls_doc_no);
        v_error_code := '4-'
                        || v_program_id
                        || v_execution_point;
        dbms_output.put_line(v_error_code);
    END IF;
    --
    v_error_code := '5-'
                    || v_program_id
                    || v_execution_point;
    dbms_output.put_line(v_error_code);
    dbms_output.put_line('Exiting Procedure');
    COMMIT;
    dbms_output.put_line('Commiting');
    ls_return := '0' || ls_doc_no;

--Return new document
    RETURN ( ls_return );
EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line('Error In Sub Program' || sqlerrm);
        ls_return := 'Error Info '
                     || v_program_id
                     || v_execution_point
                     || ': '
                     || sqlerrm;
        RETURN ( ls_return );
END;
```

```oracle
create or replace FUNCTION fn_create_pur_req (
    rec_header IN pkg_sending_data.rec_spare_trans,
    type_items IN pkg_sending_data.type_spare_items
) RETURN STRING IS
/*
SPMS Dependancies
purchase_process_header
purchase_process_detail
purchase_requisition_header
purchase_offices via zfm_purchase_offices
spms articles table via spms_articles_view 
*/
--Variables
    i                  BINARY_INTEGER := 0;
    v_doc_no           VARCHAR2(20);
    ls_pr_doc_no       VARCHAR2(20);
    v_store            VARCHAR2(4);
    v_req_quantity     NUMBER(20, 3);
    ls_job_no          VARCHAR2(50);
    ls_error           VARCHAR2(255);
    ls_purchase_office VARCHAR2(255);
    v_response         VARCHAR2(255);
    ls_area            VARCHAR2(2);
    ls_data            VARCHAR2(255);
    v_execution_point  VARCHAR2(10);
    ln_count           NUMBER(3);
    ls_location        VARCHAR2(20);
    ls_function        VARCHAR2(20);
    ls_ace             VARCHAR2(20);
    ls_cost_centre     VARCHAR2(20);
    v_program_id       VARCHAR2(20);
    ls_office          VARCHAR2(200);
    ls_description     VARCHAR2(2000);
    ls_user_unit       VARCHAR(200);
    v_reg_no           VARCHAR2(10);
    ld_amount_total    NUMBER(19, 4);
    ld_amount          NUMBER(19, 4);
    n_check            NUMBER;
    v_non_stock_code   VARCHAR(2) := '03';
    v_dcs_system       VARCHAR(2) := '02';
    v_mms_system       VARCHAR(2) := '03';
    v_zfms_system      VARCHAR(2) := '04';
    v_default_veh_req  VARCHAR(7) := '6120013';
    e_invalid_system_of_origin EXCEPTION;
    PRAGMA exception_init ( e_invalid_system_of_origin, -20001 );
------------------------------------------------------------------------
BEGIN
    v_program_id := 'ZFMS 1.0';
    v_execution_point := '1.0';
    BEGIN
        IF rec_header.system_origin = v_zfms_system THEN
            ls_office := ' purchase office: ' || rec_header.store_code;
            SELECT
                purch_off.area,
                purch_off.code_office
            INTO
                ls_area,
                ls_purchase_office
            FROM
                zfm_purchase_offices purch_off
            WHERE
                purch_off.code_office = rec_header.store_code;

        ELSE
            -- Q1
            RAISE e_invalid_system_of_origin;
        END IF;
    EXCEPTION
        WHEN no_data_found THEN
            v_response := '1'
                          || v_program_id
                          || '  '
                          || v_execution_point
                          || ': The '
                          || ls_office
                          || ' was not identified';

            RETURN ( v_response );
        WHEN e_invalid_system_of_origin THEN
            v_response := 'ZFMS'
                          || v_program_id
                          || '  '
                          || v_execution_point
                          || ' Invalid System Of Origin';
            RETURN ( v_response );
        WHEN OTHERS THEN
            v_response := 'ZFMS'
                          || v_program_id
                          || '  '
                          || v_execution_point
                          || '  '
                          || sqlerrm;

            RETURN ( v_response );
    END;

    v_execution_point := '1.1';
    -- Q2

    v_execution_point := '1.3';

    /* validate the company */
    IF nvl(rec_header.business_area, '(NONE)') = '(NONE)' OR length(rec_header.business_area) < 1 THEN
        RETURN '1'
               || v_program_id
               || v_execution_point
               || ': The company for the financial information is invalid';
    END IF;

    v_execution_point := '1.4';

      /* v_response := F_VERIFY_ACCOUNTS(rec_nonstock_header.BUSINESS_AREA, LS_COST_CENTRE, type_nonstock_items );
        IF SUBSTR(v_response,1,1) <> '0' THEN
            --RETURN(v_response);
        END IF;
      */

    v_execution_point := '1.5';

    /* Generate Purchase Process Number */
    v_response := sequence_generator('SEQ_PURCHASE_PROCESS', ls_area);
    IF substr(v_response, 1, 1) <> '0' THEN
        v_response := substr(v_response, 1, 1)
                      || v_program_id
                      || v_execution_point
                      || ' Error '
                      || substr(v_response, 2, length(v_response) - 1);

        RETURN ( v_response );
    END IF;

    v_execution_point := '1.6';

    /* Remove the umber prefix */
    v_doc_no := substr(v_response, 2, length(v_response) - 1);
    SELECT
        user_unit
    INTO ls_user_unit
    FROM
        config_workshop
    WHERE
        workshop_name = rec_header.delivery_site;

    INSERT INTO purchase_process_header (
        user_act,
        date_act,
        code_office,
        code_store,
        document_no,
        date_document,
        date_delivery,
        status,
        subject,
        code_unit,
        company,
        business_area,
        cost_centre,
        work_order,
        account,
        job_no,
        delivery_place,
        system_origin,
        user_document_no,
        process_type,
        justification
    ) VALUES (
        user,
        sysdate,
        ls_purchase_office,
        NULL,
        v_doc_no,
        sysdate,
        sysdate,
        '01',
        rec_header.subject,
        ls_user_unit,
        ls_area,
        rec_header.business_unit,
        rec_header.cost_centre,
        ls_function,
        v_default_veh_req,
        NULL,
        rec_header.delivery_site,
        '04',
        rec_header.dcs_code,
        '02',
        type_items(1).specification
    );

    /* generate Purchase Requisition Header */
    v_execution_point := '1.7';
    IF sqlcode = 0 THEN
   
     /* create document number */
        v_response := fn_stores_doc_no_generator('seq_pr_non_stock', ls_area);
        IF substr(v_response, 1, 1) <> '0' THEN
        
            v_response := substr(v_response, 1, 1)
                          || v_program_id
                          || v_execution_point
                          || ' Error '
                          || substr(v_response, 2, length(v_response) - 1);

            RETURN ( v_response );
        END IF;

        v_execution_point := '1.8.0';

        /* remove document number prefix */
        ls_pr_doc_no := substr(v_response, 2, length(v_response) - 1);
         
    END IF;

    v_execution_point := '1.9';
    FOR i IN type_items.first..type_items.last LOOP
        -- confirm that the quantities are valid
        IF type_items(i).quantity <= 0 THEN
            v_response := '1'
                          || v_program_id
                          || v_execution_point
                          || ': A new requisition is being created for the request. '
                          || 'However the detail cannot include a reduction in quantities. Item '
                          || type_items(i).material_code
                          || 'IS affected';

            RETURN ( v_response );
        END IF;

        SELECT
            description
        INTO ls_description
        FROM
            ZFM_ARTICLES_VIEW articles
        WHERE
            code_article = type_items(i).material_code;

        INSERT INTO purchase_process_detail (
            user_act,
            date_act,
            document_no,
            code_article,
            quantity,
            unit_measure,
            price_reference,
            amount,
            account,
            unit_document,
            quantity_document,
            price_unit_document,
            description,
            article_type,
            specifications,
            project_no
        ) VALUES (
            user,
            sysdate,
            v_doc_no,
            type_items(i).material_code,
            type_items(i).quantity,
            type_items(i).unit_measure,
            type_items(i).price,
            type_items(i).quantity * type_items(i).price,
            type_items(i).account_no,
            type_items(i).unit_measure,
            type_items(i).quantity,
            type_items(i).price,
            ls_description,
            decode(substr(type_items(i).material_code,
                          1,
                          2),
                   '40',
                   '02',
                   '41',
                   '03',
                   '01',
                   '01',
                   '03'),
            type_items(i).specification,
            rec_header.project_no
        ); 
        -- NONSTOCK

        v_execution_point := '2.0';
       -- Create PR Details
        IF sqlcode = 0 THEN
            v_execution_point := '2.1';

            ld_amount := type_items(i).quantity * type_items(i).price;
            ld_amount_total := ld_amount + ld_amount_total;

        END IF;

    END LOOP;

    v_execution_point := '2.2';
    UPDATE purchase_requisition_header
    SET
        amount_total = ld_amount_total
    WHERE
        document_no = ls_pr_doc_no;

    v_execution_point := '2.3';
    RETURN ( '0'
             || v_doc_no );
EXCEPTION
    WHEN OTHERS THEN
        v_response := '2'
                      || v_program_id
                      || ' ' 
                      || ' Execution Point '
                      || ' ' 
                      || v_execution_point
                      || sqlerrm
                      || ' OFFICE CODE: '
                      || rec_header.store_code
                      || ' DOCUMENT NO: '
                      || v_doc_no
                      || ' PR NO: '
                      || ls_pr_doc_no;

        RETURN ( v_response );
        -- Q1
        /*IF rec_nonstock_header.system_origin = v_mms_system THEN
                ls_office := ' store code: ' || rec_nonstock_header.store_code; 
                -- Get the purchase office from the area of the store
                n_check := 0;
                SELECT
                    stores.area,
                    stores.head_store,
                    areas.purchase_office_responsible,
                    COUNT(*)
                INTO
                    ls_area,
                    ls_data,
                    ls_purchase_office,
                    n_check
                FROM
                    stores_view     stores,
                    spms_areas_view areas
                WHERE
                        areas.area = stores.area
                    AND stores.code_store = rec_nonstock_header.store_code
                    AND head_store = '03'
                GROUP BY
                    stores.area,
                    stores.head_store,
                    areas.purchase_office_responsible;

            ELSIF rec_nonstock_header.system_origin = v_dcs_system THEN
                ls_office := ' purchase office: ' || rec_nonstock_header.store_code;
                SELECT
                    pur_off.area,
                    pur_off.code_office
                INTO
                    ls_area,
                    ls_purchase_office
                FROM
                    zfm_purchase_offices pur_off
                WHERE
                    pur_off.code_office = rec_nonstock_header.store_code
                GROUP BY
                    pur_off.area,
                    pur_off.code_office;

            END IF;*/
            --Q2
                /*IF rec_nonstock_header.system_origin = v_dcs_system THEN
        ls_function := '';
        ls_location := substr(rec_nonstock_header.cost_centre, 1, 4);
        ls_ace := substr(rec_nonstock_header.cost_centre, 5, 4);
        IF length(ls_location) < 1 THEN
            RETURN '1'
                   || v_program_id
                   || v_execution_point
                   || ': Inalid location for the cost centre';
        ELSIF length(ls_ace) < 1 THEN
            RETURN '1'
                   || v_program_id
                   || v_execution_point
                   || ': The ACE no. for the cost centre is invalid';
        END IF;

        ls_cost_centre := ls_location || ls_ace;
    ELSIF rec_nonstock_header.system_origin = v_mms_system THEN
        ls_function := substr(rec_nonstock_header.cost_centre, 1, 4);
        ls_location := substr(rec_nonstock_header.cost_centre, 5, length(rec_nonstock_header.cost_centre) - 3);

        ls_ace := '';
        IF length(ls_function) < 1 THEN
            RETURN '1'
                   || v_program_id
                   || v_execution_point
                   || ': The function for the cost centre is invalid';
        ELSIF length(ls_location) < 1 THEN
            RETURN '1'
                   || v_program_id
                   || v_execution_point
                   || ': The location for the cost centre is invalid';
        END IF;

        ls_cost_centre := ls_function || ls_location;
    END IF;
    */
END;
```


```oracle
create or replace FUNCTION             fn_create_reservation (
    p_req_ref_no       IN VARCHAR2,
    p_veh_reg_no       IN VARCHAR2,
    p_store_code       IN VARCHAR2,
    p_user_requesting  IN VARCHAR2,
    p_job_card_no      IN VARCHAR2,
    p_system_origin    IN VARCHAR2,
    p_fleet_req_code   IN VARCHAR2,
    p_req_acc_number   IN VARCHAR2,
    p_delivery_site    IN VARCHAR2,
    p_transaction_type IN VARCHAR2,
    p_current_user     IN VARCHAR2
) RETURN STRING IS

    i                       BINARY_INTEGER := 0;
    v_line_number           BINARY_INTEGER := 0;
    ls_program_id           VARCHAR2(20);
    v_exe_point             VARCHAR2(200); -- used as a debug point to indicate portion where exception was encountered
    v_num_of_rows           NUMBER(12);
    v_cst_cntr_code         VARCHAR2(20);
    ls_area                 VARCHAR2(20);
    ls_data                 VARCHAR2(250);
    v_data_count_check      NUMBER(2); -- used to check if data exits to avoid no data found exception
    ls_desc                 VARCHAR2(150);
    ll_res                  NUMBER(2);
    v_user_unit_check       NUMBER(2);
    ls_status               VARCHAR2(20);
    ll_count                NUMBER(5);
    v_req_matrl_code        VARCHAR2(20);
    v_req_quantity          NUMBER(10);
    v_req_artcl_price       NUMBER(10);
    v_req_artcl_description VARCHAR2(100);
    ls_specification        VARCHAR2(2000);
    v_veh_type_spec         VARCHAR2(100);
    ls_unit_measure         VARCHAR2(5);
    ls_article_type         VARCHAR2(2);
    v_business_unit_code    VARCHAR2(10);
    v_code_unit             VARCHAR2(10);
    v_reservation_doc_no    VARCHAR2(20);
    ls_location             VARCHAR2(20);
    ls_function             VARCHAR2(20);
    ls_ace                  VARCHAR2(20);
    rec_spare_trans         pg_sending_data.gr_spare_trans;
    typ_spare_items         pg_sending_data.gt_spare_items;
    
--Error-handling Variables
    v_err_message           VARCHAR2(255);
    v_error_code            VARCHAR2(255);
    ls_return               VARCHAR2(255);
    v_workshop_code         VARCHAR2(20);
    v_usr_unit_count_check  NUMBER;
    ls_user_unit_wk         VARCHAR2(255);

--End of Declarations - start of Main Function Body
---------------------------------------------------------------------

    CURSOR detail_cursor (
        p_req_ref_no VARCHAR2
    ) IS
    SELECT
        d.material_code,
        d.quantity,
        d.price,
        art.description,
        art.technical_specifications,
        u.abbreviation,
        art.type_article,
        '' -- workshop_code
    FROM
        gen_material_headers h,
        zfm_articles_view   art,
        zfm_units_view           u,
        gen_material_details d
    WHERE
            h.req_no = p_req_ref_no
        AND d.material_code = art.code_article
        AND art.unit_measure = u.code_unit
        AND h.req_no = d.req_no;

BEGIN
    dbms_output.enable(100000000000000000000);
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
        v_business_unit_code,
        v_veh_type_spec --, N_CNT
    FROM
        vm_vehicle_header vh,
        vm_assignments    va
    WHERE
            vh.id = va.vehicle_header_id
        AND va.assignment_state = '01'
        AND vh.registration_number = p_veh_reg_no;

    /* go over data */

    OPEN detail_cursor(p_req_ref_no);
    LOOP
        FETCH detail_cursor INTO
            v_req_matrl_code,
            v_req_quantity,
            v_req_artcl_price,
            v_req_artcl_description,
            ls_specification,
            ls_unit_measure,
            ls_article_type,
            v_workshop_code;

        EXIT WHEN detail_cursor%notfound;
        v_line_number := v_line_number + 1;
        rec_spare_trans.date_requirement := sysdate;
        rec_spare_trans.store_code := p_store_code;
        rec_spare_trans.cost_centre := v_cst_cntr_code;
        rec_spare_trans.work_order_no := '';
        rec_spare_trans.stf_number := '';
        rec_spare_trans.user_requesting := p_user_requesting;
        rec_spare_trans.system_origin := p_system_origin;
        rec_spare_trans.store_res_no := '';
        rec_spare_trans.delivery_site := p_delivery_site;
        rec_spare_trans.transaction_type := p_transaction_type;
        rec_spare_trans.subject := 'ZFMS STF GENERATED FOR MATERIAL MOVT. No.'
                                   || p_fleet_req_code
                                   || ' FOR '
                                   || p_veh_reg_no
                                   || ' '
                                   || v_veh_type_spec;

        rec_spare_trans.dcs_code := p_fleet_req_code;
        rec_spare_trans.business_unit := v_business_unit_code;
        rec_spare_trans.job_no := p_job_card_no;
        rec_spare_trans.project_no := '';
        rec_spare_trans.req_number := '';
        typ_spare_items(v_line_number).material_code := v_req_matrl_code;
        typ_spare_items(v_line_number).quantity := v_req_quantity;
        typ_spare_items(v_line_number).price := v_req_artcl_price;
        typ_spare_items(v_line_number).account_no := p_req_acc_number;
        typ_spare_items(v_line_number).description := v_req_artcl_description;
        typ_spare_items(v_line_number).specification := ls_specification;
        typ_spare_items(v_line_number).unit_measure := ls_unit_measure;
        typ_spare_items(v_line_number).article_type := ls_article_type;
    END LOOP;

    CLOSE detail_cursor;
    v_error_code := '3-';
    dbms_output.put_line(v_error_code);
    dbms_output.put_line('No. of Rows entered in lt_spare_items ' || to_char(v_line_number));
    IF rec_spare_trans.transaction_type = '01' THEN
        dbms_output.put_line(rec_spare_trans.transaction_type);
             -----------------------------------------------------------------------------------
        ls_program_id := 'ZFMS 0.01';
        v_exe_point := '1.0';
        --
        v_num_of_rows := typ_spare_items.last;
        IF rec_spare_trans.system_origin <> '04' THEN
            ls_return := '1'
                         || ls_program_id
                         || v_exe_point
                         || ': Unknown System of origin. Please contact support';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        v_exe_point := '1.2';
        IF typ_spare_items.last < 1 THEN
            ls_return := '1'
                         || ls_program_id
                         || v_exe_point
                         || ': No item detail has been
SET FOR the request';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        v_exe_point := '1.3';
                -- Get area info
        SELECT
            COUNT(*),
            area,
            head_store
        INTO
            v_data_count_check,
            ls_area,
            ls_data
        FROM
            zfm_stores_view
        WHERE
            code_store = rec_spare_trans.store_code
        GROUP BY
            area,
            head_store;

        rec_spare_trans.business_area := ls_area;

        --
        v_exe_point := '1.4';
        IF v_data_count_check < 1 THEN
            ls_return := '1'
                         || 'The store '
                         || rec_spare_trans.store_code
                         || ' was not FOUND';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;
                -- If the store is a store with stockrooms then error
        IF ls_data = '01' THEN
            ls_return := '1' || 'Materials cannot be selected from a stock room.
            PleaseSELECT materials FROM the parent STORE';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        FOR i IN typ_spare_items.first..typ_spare_items.last LOOP
                      -- if the quantity is negative then the entry is not valid
            IF typ_spare_items(i).quantity <= 0 THEN
                ls_return := '1'
                             || ls_program_id
                             || v_exe_point
                             || ': A new requisition is being created FOR the request. '
                             || 'However the detail cannot include a reduction IN quantities. Item '
                             || typ_spare_items(i).material_code
                             || ' is affected';

                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            END IF;

            IF rec_spare_trans.system_origin = '04' THEN
                ls_return := f_check_quantities(rec_spare_trans.store_code, typ_spare_items(i).material_code, typ_spare_items(i).quantity
                );

                dbms_output.put_line(ls_return);
            END IF;

        END LOOP;

        v_exe_point := '1.5';

        --check if the reservation no exists
        ls_data := nvl(rec_spare_trans.store_res_no, 'XXX');
        SELECT
            COUNT(*)
        INTO v_data_count_check
        FROM
            store_reservations_header
        WHERE
            document_no = ls_data;

        dbms_output.put_line('STORE_RES_NO COUNT ' || to_char(v_data_count_check));
        --
        IF v_data_count_check < 1 THEN
             -- Create stores reservation in Authorised State
            rec_spare_trans.store_res_no := f_create_stores_reservation(rec_spare_trans, typ_spare_items);
            rec_spare_trans.store_res_no := substr(rec_spare_trans.store_res_no, 2, length(rec_spare_trans.store_res_no) - 1);

            dbms_output.put_line(rec_spare_trans.store_res_no);
--
        END IF;
            --
        v_exe_point := '1.6';

            --
        SELECT
            COUNT(*)
        INTO ll_res
        FROM
            store_reservations_header
        WHERE
            document_no = rec_spare_trans.store_res_no;
            --
        v_reservation_doc_no := rec_spare_trans.store_res_no;
        dbms_output.put_line('LL_RES ' || to_char(ll_res));
        IF ll_res > 0 THEN
            v_exe_point := '1.7.1';
            UPDATE gen_material_headers
            SET
                status = '02',
                st_pur = v_reservation_doc_no,
                proc_ref = v_reservation_doc_no,
                form_order = p_fleet_req_code,
                updated_at = sysdate,
                --requested_by = p_current_user,
                authorised_by = p_current_user
            WHERE
                    req_no = p_req_ref_no
                AND status = '01';
                
                
                --select count(*) from store_reservations_header where document_no ='J02LR26640377';
            v_exe_point := '1.7.2 ' || v_reservation_doc_no;
            SELECT
                COUNT(*)
            INTO v_user_unit_check
            FROM
                organizational_units_view org
            WHERE
                    org.cc_code = v_cst_cntr_code
                AND org.bu_code = v_business_unit_code;

            IF v_user_unit_check > 0 THEN
                SELECT
                    code_unit
                INTO v_code_unit
                FROM
                    organizational_units_view org
                WHERE
                        org.cc_code = v_cst_cntr_code
                    AND org.bu_code = v_business_unit_code;

                UPDATE store_reservations_header
                SET
                    subject = rec_spare_trans.subject,
                    justification = rec_spare_trans.subject,
                    code_unit = v_code_unit
                WHERE
                    document_no = v_reservation_doc_no;

            END IF;

            v_exe_point := '1.7.3';
            UPDATE store_reservations_detail
            SET
                reg_no = p_veh_reg_no
            WHERE
                document_no = v_reservation_doc_no;

        END IF;

        dbms_output.put_line(v_reservation_doc_no);
-----------------------------------------------------------------------------------

        dbms_output.put_line(v_reservation_doc_no);
        v_error_code := '4-'
                        || ls_program_id
                        || v_exe_point;
        dbms_output.put_line(v_error_code);
    END IF;
    --
    v_error_code := '5-'
                    || ls_program_id
                    || v_exe_point;
    dbms_output.put_line(v_error_code);
    dbms_output.put_line('Exiting Procedure');
    COMMIT;
    dbms_output.put_line('Commiting');
--success
    ls_return := '0' || v_reservation_doc_no;

--Return new document number
    RETURN ( ls_return );
EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line('Error encountered during Execution' || sqlerrm);
        ls_return := 'Error encountered during Execution '
                     || ls_program_id
                     || ': '
                     || v_exe_point
                     || ': '
                     || sqlerrm;

        RETURN ( ls_return );
END;
```

```oracle
create or replace FUNCTION fn_create_stores_req (
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

    i                         BINARY_INTEGER := 0;
    v_line_number             BINARY_INTEGER := 0;
    ls_program_id             VARCHAR2(20);
    exe_point                 VARCHAR2(200);
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
    v_req_account             VARCHAR(10);
    n_ds                      NUMBER(2);
    n_cnt                     NUMBER(1);
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
    v_req_article_group       VARCHAR2(6);
    v_fuel_bal                NUMBER(5);
    v_fuel_req_ind            NUMBER(2);
    v_req_odometer            NUMBER(19);
    v_req_flng_type           NUMBER(2);
    v_req_cost_cntr_code      VARCHAR(10);
    v_req_project_code        VARCHAR(10) := '000000';
    v_req_cost_bearer         VARCHAR(2);
    typ_spare_items           pg_sending_data.gt_spare_items;
    rec_spare_trans           pg_sending_data.gr_spare_trans;
    v_err_message             VARCHAR2(255);
    v_error_code              VARCHAR2(255);
    ls_return                 VARCHAR2(300);
    v_wkshp_code              VARCHAR2(20);
    v_wrkshp_user_unit        NUMBER;
    ls_user_unit_wk           VARCHAR2(255);
    v_system_of_origin        VARCHAR2(2) := '04';
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
        zfm_articles_view    art,
        zfm_units_view       u,
        gen_material_details d
    WHERE
            h.req_no = req_number
        AND art.unit_measure = u.code_unit
        AND art.code_article = d.material_code
        AND h.req_no = d.req_no;

BEGIN
    n_cnt := 0;
--------------------------------------------------------------------------------  
    dbms_output.enable(100000000000000000000);
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
        SELECT
            code_group
            || code_subgroup
            || code_class
        INTO v_req_article_group
        FROM
            zfm_articles_view
        WHERE
            code_article = v_req_matrl_code;

        /** determine account to post requition to based on entity bearing cost **/

        IF v_req_article_group = v_fuel_req_group THEN
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

                v_req_account := p_req_acc_number;
            ELSE
                        /*ls_project_code:=ls_code_unit;*/
                SELECT
                    code_bu,
                    code_cost_center
                INTO
                    v_business_unit,
                    v_cst_cntr_code
                FROM
                    zfm_projects_view
                WHERE
                    code_project = v_req_project_code;

                v_req_cost_cntr_code := '';
                v_req_account := '1196000';
            END IF;
        END IF;

        v_req_account := p_req_acc_number;
        IF v_req_project_code IS NULL THEN
            v_req_project_code := '0';
        END IF;

       /*END ROK*/

        rec_spare_trans.date_requirement := sysdate;
        exe_point := '1.0 - SYSDATE ';
        rec_spare_trans.store_code := p_store_code;
        exe_point := '1.0 - ls_store_code ';
        rec_spare_trans.cost_centre := v_cst_cntr_code;
        exe_point := '1.0 - ls_cost_centre ';
        rec_spare_trans.work_order_no := '';
        exe_point := '1.0 - work_order_no ';
        rec_spare_trans.stf_number := '';
        exe_point := '1.0 - stf_number ';
        rec_spare_trans.user_requesting := p_user_requesting;
        exe_point := '1.0 - user_requesting ';
        rec_spare_trans.system_origin := p_system_origin;
        exe_point := '1.0 - system_origin ';
        rec_spare_trans.store_res_no := '';
        exe_point := '1.0 - store_res_no ';
        rec_spare_trans.delivery_site := p_delivery_site;
        exe_point := '1.0 - delivery_site ';
        rec_spare_trans.transaction_type := p_transaction_type;
        exe_point := '1.0 - transaction_type ';

        /* MATERIAL OTHER THAN FUEL */
        IF v_req_article_group <> v_fuel_req_group THEN 
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
        exe_point := '1.0 - dcs_code ';
        rec_spare_trans.business_unit := v_business_unit;
        exe_point := '1.0 - business_unit ';
        rec_spare_trans.job_no := p_job_card;
        exe_point := '1.0 - job_no ';
        rec_spare_trans.project_no := '';
        exe_point := '1.0 - project_no ';
        rec_spare_trans.req_number := '';
        exe_point := '1.0 - req_number ';
                    --

        dbms_output.put_line(v_error_code);
                    --Get the detail data into the detail package
                    --
        typ_spare_items(v_line_number).material_code := v_req_matrl_code;
        exe_point := '1.0 - material_code ';
        typ_spare_items(v_line_number).quantity := v_req_quantity;
        exe_point := '1.0 - quantity ';
        typ_spare_items(v_line_number).price := v_req_artcl_price;
        exe_point := '1.0 - price ';
        typ_spare_items(v_line_number).account_no := v_req_account;
        exe_point := '1.0 - account_no ';
        typ_spare_items(v_line_number).description := v_req_artcl_description;
        exe_point := '1.0 - Description ';
        typ_spare_items(v_line_number).specification := v_req_artcl_specification;
        exe_point := '1.0 - specification ';
        typ_spare_items(v_line_number).unit_measure := v_req_articl_unit_msr;
        exe_point := '1.0 - unit_measure ';
        typ_spare_items(v_line_number).article_type := v_article_type;
        exe_point := '1.0 - article_type ' || v_req_matrl_code;
        typ_spare_items(v_line_number).project_no := v_req_project_code;
        exe_point := '1.0 - ls_project_code ' || v_req_project_code;
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
        exe_point := '1.0';
        ln_rows := typ_spare_items.last;

        /* check system of origin */
        IF rec_spare_trans.system_origin <> v_system_of_origin THEN
            ls_return := '1 '
                         || ls_program_id
                         || exe_point
                         || ': System origin not determined. 
                         Please contact support';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        exe_point := '1.2';
        /* check the number of items added in collections*/
        IF typ_spare_items.last < 1 THEN
            ls_return := '1'
                         || ls_program_id
                         || exe_point
                         || ': No item detail has been SET FOR the request';
            dbms_output.put_line(ls_return);
            RETURN ( ls_return );
        END IF;

        exe_point := '1.3';
                -- Get area info

        exe_point := '1.4';

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
                zfm_stores_view
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
                             || exe_point
                             || ': A new requisition is being created FOR the request. '
                             || 'However the detail cannot include a reduction IN quantities. Item '
                             || typ_spare_items(i).material_code
                             || ' is affected';

                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            END IF;

            IF rec_spare_trans.system_origin = v_system_of_origin THEN
             -- confirm that the quantities required are in the store for DCS only

                IF v_req_article_group <> v_fuel_req_group THEN   /*ROK ZMES-892*/
                    ls_return := f_check_quantities(rec_spare_trans.store_code, typ_spare_items(i).material_code, typ_spare_items(i).quantity
                    );
                END IF;

                dbms_output.put_line(ls_return);
            END IF;

        END LOOP;

        exe_point := '1.5';
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
            IF v_req_article_group <> v_fuel_req_group THEN
                rec_spare_trans.store_res_no := f_create_stores_reservation(rec_spare_trans, typ_spare_items);
                /* remove the preceeding 0 */
                rec_spare_trans.store_res_no := substr(rec_spare_trans.store_res_no, 2, length(rec_spare_trans.store_res_no) - 1);

            END IF;

            dbms_output.put_line(rec_spare_trans.store_res_no);
           --
        END IF;

            --
        exe_point := '1.6';
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
                    zfm_general_tables --general_tables
                WHERE
                        table_code = 'STA'
                    AND element_code = store_rsvn_status;

                ls_return := '1'
                             || ls_program_id
                             || exe_point
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
            exe_point := '1.7 UPDATE STORE RES.';
            dbms_output.put_line(exe_point);
--
        END IF;
--
        exe_point := '1.9';
        IF rec_spare_trans.system_origin = v_system_of_origin THEN --dcs
            ls_function := '';
            /* get first four characters of cost center */
            ls_location := substr(rec_spare_trans.cost_centre, 1, 4);
            ls_ace := substr(rec_spare_trans.cost_centre, 5, 4);
            v_cst_cntr_code := ls_location || ls_ace;
            IF length(ls_location) < 1 THEN
                ls_return := '1'
                             || ls_program_id
                             || exe_point
                             || ': The location for the cost centre IS invalid';
                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            ELSIF length(ls_ace) < 1 THEN
                ls_return := '1'
                             || ls_program_id
                             || exe_point
                             || ': The ACE no. for the cost centre IS invalid';
                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            END IF;

            dbms_output.put_line('LR_SPARE_TRANS.SYSTEM_ORIGIN' || rec_spare_trans.system_origin);
        END IF;

        exe_point := '2.0';
                 -- validate the company
        IF length(rec_spare_trans.business_area) < 1 THEN
            ls_return := '1'
                         || ls_program_id
                         || exe_point
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
            /* create document number */
            ls_return := fn_stores_doc_no_generator('SEQ_STORE_REQ', v_reqsnr_usr_area);
            dbms_output.put_line('SEQ_STORE_REQ' || ls_return);
            /* remove document number prefix */
            IF substr(ls_return, 1, 1) <> '0' THEN
                ls_return := substr(ls_return, 1, 1)
                             || ls_program_id
                             || exe_point
                             || ' '
                             || substr(ls_return, 2, length(ls_return) - 1);

                dbms_output.put_line(ls_return);
                RETURN ( ls_return );
            END IF;

            exe_point := '1.8';
            /* Get the sequence from the return value */
            ls_doc_no := substr(ls_return, 2, length(ls_return) - 1);
            dbms_output.put_line('LS_DOC_NO ' || ls_doc_no);

            /* added by CS to check the workshop code */
            IF v_req_article_group = v_fuel_req_group THEN
                ls_user_unit_wk := v_req_cost_cntr_code;
            ELSE
                SELECT
                    COUNT(*)
                INTO v_wrkshp_user_unit
                FROM
                    config_workshop
                WHERE
                    workshop_code = v_wkshp_code;

                IF v_wrkshp_user_unit > 0 THEN
                    SELECT
                        user_unit
                    INTO ls_user_unit_wk
                    FROM
                        config_workshop
                    WHERE
                        workshop_code = v_wkshp_code;

                END IF;

            END IF;

            exe_point := '1.80A';
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
                p_reg_no,
                v_req_cost_bearer,
                v_fuel_req_ind,
                v_req_flng_type,
                v_req_odometer
            );

            /*ROK ZMES-892*/

            dbms_output.put_line('LS_DOC_NO FIRST ROW ' || ls_doc_no);
            exe_point := '1.81';
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
            exe_point := '2.01';
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
            exe_point := '2.02';
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

            exe_point := '2.021';
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

            exe_point := '2.03';
            UPDATE gen_material_headers
            SET
                status = '02',
                st_pur = ls_doc_no,
                proc_ref = ls_doc_no,
                form_order = p_fleet_req_code,
                authorised_by = p_current_user,
                --requested_by = p_current_user,
                updated_at = sysdate
            WHERE
                    req_no = p_ref_no
                AND status IN ( '01', '21' ); --01 NEW 21-PARTIALLy AUTHORISED
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
                    '08', -- store-requisition
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
                        || exe_point;
        dbms_output.put_line(v_error_code);
    END IF;

    v_error_code := '5-'
                    || ls_program_id
                    || exe_point;
    dbms_output.put_line(v_error_code);
    dbms_output.put_line('Exiting Routine');
    COMMIT;
    dbms_output.put_line('Commiting');
    ls_return := '0' || ls_doc_no;

--Return new document
    RETURN ls_return;
EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line('Error encountered during Execution' || sqlerrm);
        ls_return := 'Error encountered during Execution '
                     || ls_program_id
                     || exe_point
                     || ': '
                     || sqlerrm;
        RETURN ( ls_return );
END;
```

```oracle
create or replace FUNCTION fn_generate_reference_number (
    p_module VARCHAR2,
    p_user   VARCHAR2
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
        --"SEQ_SPMS_STF".nextval
    ELSIF p_module = 'REQ' THEN
        v_prefix := 'ZFMREQ';
        v_next_num := "FLEETMASTER"."GENERAL_REQ_SEQ".nextval;
    ELSIF p_module = 'DRV_ONBOARD' THEN
        v_prefix := 'ZFMDOB';
        v_next_num := "FLEETMASTER"."DRV_ONBOARDING_REQ_SEQ".nextval;
    ELSIF p_module = 'VEH_ONBOARD' THEN
        v_prefix := 'ZFMVOB';
        v_next_num := "FLEETMASTER"."VEH_ONBOARDING_REQ_SEQ".nextval;
    ELSIF p_module = 'JOB_CAR' THEN
        v_prefix := 'ZFMJBC';
        v_next_num := "FLEETMASTER"."WKSH_JOBCARD_SEQ".nextval;
    ELSIF p_module = 'WAC' THEN
        v_prefix := 'ZFMWAC';
        v_next_num := "ZFM_WORKSHOP_DOC_SEQ".nextval;
    ELSIF p_module = 'ACC_RPT' THEN
        v_prefix := 'ACC';
        v_next_num := "FLEETMASTER"."ZFM_ACC_SEQ".nextval;
    END IF;

    v_reference := v_prefix
                   || lpad(to_char(v_next_num), 10, '0');
    INSERT INTO gen_system_references (
        reference,
        module,
        created_by
    ) VALUES (
        v_reference,
        p_module,
        p_user
    );

    RETURN v_reference;
EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line('Error encountered during Execution' || sqlerrm);
        v_reference := 'Error encountered during Execution ' || sqlerrm;
        RETURN ( v_reference );
END;
```

```oracle
create or replace FUNCTION FN_STORES_DOC_NO_GENERATOR(ls_type in VARCHAR2, ls_area IN VARCHAR2) RETURN STRING IS
        ls_return  VARCHAR2(255);
        BEGIN
             ls_return := (SEQUENCE_GENERATOR(ls_type,ls_area));
             RETURN ls_return;
        END;
```

# PROCEDURES

```oracle
create or replace PROCEDURE proc_syncRequisitions IS
CURSOR cur_requisitions IS
    SELECT
        h.st_pur,
        srh.status
    FROM
        gen_material_headers      h,
        store_requisitions_header srh
    WHERE
            h.st_pur = srh.document_no
        AND srh.system_origin = '04'
        AND h.status <> srh.status
        AND h.status IN ( '02', '26' );
BEGIN
    FOR item IN cur_requisitions LOOP
        UPDATE gen_material_headers
        SET
            status = item.status
        WHERE
            st_pur = item.st_pur;

        COMMIT;
    END LOOP;
EXCEPTION
    WHEN OTHERS THEN
        -- Handle exceptions, if needed
        ROLLBACK;
        RAISE;
END;
```

```oracle
create or replace PROCEDURE proc_pur_process_to_pur_req AS

    v_purchase_requisition_no VARCHAR2(40);
    v_status                  VARCHAR2(2);
    v_user_doc_no             VARCHAR2(20);
    v_count                   NUMBER;
    v_service                 VARCHAR(2) := 'SE';
    v_non_stock               VARCHAR(2) := 'NS';
 /* Get Purchase Processes where Purchase Requsition has been generated */
    CURSOR cur_purchase_processes IS
    SELECT
        pph.document_no,
        gmh.st_pur,
        pph.status,
        pph.work_order,
        gmh.item_type,
        gmh.form_order
    FROM
        purchase_process_header pph,
        gen_material_headers    gmh
    WHERE
            pph.document_no = gmh.st_pur
        --AND pph.status in ('24')
        AND pph.system_origin = '04'
        AND gmh.st_pur LIKE 'N0%'
    ORDER BY
        pph.document_no;
        
 /* Get Purchase Requisitions where Tender has been generated */
    CURSOR cur_purchase_requisitions IS
    SELECT
        prh.document_no,
        prh.status,
        gmh.item_type,
        gmh.form_order
    FROM
        purchase_requisition_header prh,
        gen_material_headers        gmh
    WHERE
            prh.document_no = gmh.st_pur
        --AND prh.status in ('08')
        AND gmh.st_pur LIKE 'A0%'
    ORDER BY
        prh.document_no;
 /* Get Tenders where Purchase Order has been generated */
    CURSOR cur_tenders IS
    SELECT
        th.document_no,
        th.purchase_requisition_no,
        th.status,
        gmh.item_type,
        gmh.form_order
    FROM
        tender_header        th,
        gen_material_headers gmh
    WHERE
            th.document_no = gmh.st_pur
        AND gmh.st_pur LIKE 'K0%'
    ORDER BY
        th.document_no;
 /* Get Purchase Order where goods receipt has been generated */
    CURSOR cur_purchase_orders IS
    SELECT
        po.document_no,
        po.status,
        po.tender_no,
        gmh.item_type,
        gmh.form_order
    FROM
        purchase_order_header po,
        gen_material_headers  gmh
    WHERE
            po.document_no = gmh.st_pur
        AND po.status IN ( '08' )
        AND gmh.st_pur LIKE 'C0%';
 /* Get Goods receipt processed */

    CURSOR cur_goods_receipt IS
    SELECT
        grh.document_no,
        grh.status,
        grh.purchase_order_no,
        gmh.item_type,
        gmh.form_order
    FROM
        goods_receipt_header grh,
        gen_material_headers gmh
    WHERE
            grh.document_no = gmh.st_pur
        AND grh.status IN ( '08' )
        AND gmh.st_pur LIKE 'D0%';

BEGIN
    -- N0 -> A0
    FOR purchase_process IN cur_purchase_processes LOOP
        SELECT
            document_no,
            status
        INTO
            v_purchase_requisition_no,
            v_status
        FROM
            purchase_requisition_header
        WHERE
            purchase_process_no = purchase_process.document_no;
            
            
 /* Check */
        SELECT
            COUNT(*)
        INTO v_count
        FROM
            gen_material_headers
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
 /* Update Request Header to show purchase process number */
            UPDATE gen_material_headers
            SET
                st_pur = v_purchase_requisition_no,
                proc_ref = v_purchase_requisition_no,
                status = v_status
            WHERE
                st_pur = purchase_process.document_no;
 /* Update Workshop Materials Link Job Card */
            --IF purchase_process.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no
                WHERE
                    form_order = purchase_process.form_order;

            --END IF;
 /*UPDATE store_requisitions_detail
            SET
                project_no = reg_no
            WHERE
                document_no = v_purchase_requisition_no;*/
            dbms_output.put_line('Purchase Requisition Number Set ' || v_purchase_requisition_no);
            COMMIT;
        ELSE
            dbms_output.put_line(v_count
                                 || ' Header '
                                 || purchase_process.document_no
                                 || ' Already Updated');
        END IF;

        SELECT
            COUNT(*)
        INTO v_count
        FROM
            wm_workshop_materials
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            --IF purchase_process.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no,
                    status = v_status
                WHERE
                    st_pur = purchase_process.document_no;

                dbms_output.put_line(v_count || ' Materials Updated');
                COMMIT;
            --END IF;
        ELSE
            dbms_output.put_line(v_count || ' Materials Was Previously Updated');
        END IF;

    END LOOP;

    ---------------------------------------------------------------------------------------------------------------
    -- A0 -> 

    FOR purchase_requisition IN cur_purchase_requisitions LOOP
        SELECT
            document_no,
            status
        INTO v_purchase_requisition_no, v_status 
        FROM
            tender_header
        WHERE
            purchase_requisition_no = purchase_requisition.document_no;
        
        /* Check */
        SELECT
            COUNT(*)
        INTO v_count
        FROM
            gen_material_headers
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            /* Update Request Header to show purchase process number */
            UPDATE gen_material_headers
            SET
                st_pur = v_purchase_requisition_no,
                proc_ref = v_purchase_requisition_no,
                status = v_status
            WHERE
                st_pur = purchase_requisition.document_no;
                
             /* Update Workshop Materials Link Job Card */
            --IF purchase_requisition.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no
                WHERE
                    form_order = purchase_requisition.form_order;

            --END IF;
            /*UPDATE store_requisitions_detail
            SET
                project_no = reg_no
            WHERE
                document_no = v_purchase_requisition_no;*/
            dbms_output.put_line('Tender Number Number Set ' || v_purchase_requisition_no);
            COMMIT;
        ELSE
            dbms_output.put_line(v_count
                                 || ' Header '
                                 || purchase_requisition.document_no
                                 || ' Already Updated');
        END IF;

        SELECT
            COUNT(*)
        INTO v_count
        FROM
            wm_workshop_materials
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            --IF purchase_requisition.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no,
                    status = v_status
                WHERE
                    st_pur = purchase_requisition.document_no;

                dbms_output.put_line(v_count || ' Materials Updated');
                COMMIT;
            --END IF;
        ELSE
            dbms_output.put_line(v_count || ' Materials Was Previously Updated');
        END IF;

    END LOOP;

    ---------------------------------------------------------------------------------------------------------------

    --  tender to po
    FOR tender IN cur_tenders LOOP
        SELECT
            document_no,
            status
        INTO v_purchase_requisition_no, v_status
        FROM
            purchase_order_header
        WHERE
            tender_no = tender.document_no;
            
        /* Check */
        SELECT
            COUNT(*)
        INTO v_count
        FROM
            gen_material_headers
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            /* Update Request Header to show purchase process number */
            UPDATE gen_material_headers
            SET
                st_pur = v_purchase_requisition_no,
                proc_ref = v_purchase_requisition_no,
                status = v_status
            WHERE
                st_pur = tender.document_no;
                
             /* Update Workshop Materials Link Job Card */
            --IF tender.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no
                WHERE
                    form_order = tender.form_order;

            --END IF;
            /*UPDATE store_requisitions_detail
            SET
                project_no = reg_no
            WHERE
                document_no = v_purchase_requisition_no;*/
            dbms_output.put_line('Purchase Number Number Set ' || v_purchase_requisition_no);
            COMMIT;
        ELSE
            dbms_output.put_line(v_count
                                 || ' Header '
                                 || tender.document_no
                                 || ' Already Updated');
        END IF;

        SELECT
            COUNT(*)
        INTO v_count
        FROM
            wm_workshop_materials
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            IF tender.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no,
                    status = v_status
                WHERE
                    st_pur = tender.document_no;

                dbms_output.put_line(v_count || ' Materials Updated');
                COMMIT;
            END IF;
        ELSE
            dbms_output.put_line(v_count || ' Materials Was Previously Updated');
        END IF;

    END LOOP;

    ---------------------------------------------------------------------------------------------------------------

    -- po - grn
    FOR po IN cur_purchase_orders LOOP
        SELECT
            document_no, 
            status
        INTO 
            v_purchase_requisition_no, 
            v_status
        FROM
            goods_receipt_header
        WHERE
            purchase_order_no = po.document_no;
        /* Check */
        SELECT
            COUNT(*)
        INTO v_count
        FROM
            gen_material_headers
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            /* Update Request Header to show purchase process number */
            UPDATE gen_material_headers
            SET
                st_pur = v_purchase_requisition_no,
                proc_ref = v_purchase_requisition_no,
                status = v_status
            WHERE
                st_pur = po.document_no;
                
             /* Update Workshop Materials Link Job Card */
            --IF po.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no
                WHERE
                    form_order = po.form_order;

            --END IF;
            /*UPDATE store_requisitions_detail
            SET
                project_no = reg_no
            WHERE
                document_no = v_purchase_requisition_no;*/
            dbms_output.put_line('Purchase Order Number Number Set ' || v_purchase_requisition_no);
            COMMIT;
        ELSE
            dbms_output.put_line(v_count
                                 || ' Header '
                                 || po.document_no
                                 || ' Already Updated');
        END IF;

        SELECT
            COUNT(*)
        INTO v_count
        FROM
            wm_workshop_materials
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            --IF po.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no
                WHERE
                    st_pur = po.document_no;

                dbms_output.put_line(v_count || ' Materials Updated');
                COMMIT;
            --END IF;
        ELSE
            dbms_output.put_line(v_count || ' Materials Was Previously Updated');
        END IF;

    END LOOP;

    ---------------------------------------------------------------------------------------------------------------

-- update state or grn
  /* FOR grn IN cur_goods_receipt LOOP
        SELECT
            document_no
        INTO v_purchase_requisition_no
        FROM
            goods_receipt_header
        WHERE
            tender_no = po.document_no;
        --Check 
        SELECT
            COUNT(*)
        INTO v_count
        FROM
            gen_material_headers
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            -- Update Request Header to show purchase process number 
            UPDATE gen_material_headers
            SET
                st_pur = v_purchase_requisition_no,
                proc_ref = v_purchase_requisition_no,
                status = po.status
            WHERE
                st_pur = purchase_requisition.document_no;
             -- Update Workshop Materials Link Job Card 
            IF po.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no
                WHERE
                    form_order = grn.form_order;

            END IF;
            --UPDATE store_requisitions_detail
            SET
                project_no = reg_no
            WHERE
                document_no = v_purchase_requisition_no;
            dbms_output.put_line('Purchase Order Number Number Set ' || v_purchase_requisition_no);
            COMMIT;
        ELSE
            dbms_output.put_line(v_count
                                 || ' Header '
                                 || grn.document_no
                                 || ' Already Updated');
        END IF;

        SELECT
            COUNT(*)
        INTO v_count
        FROM
            wm_workshop_materials
        WHERE
            st_pur = v_purchase_requisition_no;

        IF v_count = 0 THEN
            IF purchase_requisition.item_type = v_non_stock THEN
                UPDATE wm_workshop_materials
                SET
                    st_pur = v_purchase_requisition_no,
                    proc_ref = v_purchase_requisition_no
                WHERE
                    st_pur = po.document_no;

                dbms_output.put_line(v_count || ' Materials Updated');
                COMMIT;
            END IF;
        ELSE
            dbms_output.put_line(v_count || ' Materials Was Previously Updated');
        END IF;

    END LOOP;*/

EXCEPTION
    WHEN OTHERS THEN
 -- log to errors table
        RAISE;
END proc_pur_process_to_pur_req;
```

```oracle
create or replace PROCEDURE proc_reservation_to_requisition AS
-- Job Card Link When
    v_store_requisition_no VARCHAR2(40);
    v_user_doc_no          VARCHAR2(20);
    v_count                NUMBER;
    CURSOR cur_stores_reservations IS
    SELECT
        srh.*
    FROM
        store_reservations_header srh
    WHERE
            status = '62'
        AND system_origin = '04'
        AND subject LIKE 'ZFM%';

BEGIN
    FOR reservation IN cur_stores_reservations LOOP
        SELECT
            document_no,
            user_doc_no
        INTO
            v_store_requisition_no,
            v_user_doc_no
        FROM
            store_requisitions_header
        WHERE
            store_reservation_no = reservation.document_no;

        SELECT
            COUNT(*)
        INTO v_count
        FROM
            gen_material_headers
        WHERE
            st_pur = v_store_requisition_no;

        -- Requisition number already registered
         /* Update Requisition Header */
        IF v_count = 0 THEN
            UPDATE gen_material_headers
            SET
                st_pur = v_store_requisition_no,
                proc_ref = v_store_requisition_no,
                status = reservation.status
            WHERE
                st_pur = reservation.document_no;

            UPDATE wm_workshop_materials
            SET
                st_pur = v_store_requisition_no,
                proc_ref = v_store_requisition_no,
                status = reservation.status
            WHERE
                form_order = v_user_doc_no;

             /* Set Vehicle Reg No On Requisition Detail(s) */
            UPDATE store_requisitions_detail
            SET
                project_no = reg_no
            WHERE
                document_no = v_store_requisition_no;

            dbms_output.put_line('Header Updated ' || v_store_requisition_no);
            COMMIT;
        ELSE
            dbms_output.put_line(v_count
                                 || ' Header '
                                 || reservation.document_no
                                 || ' Already Updated');
        END IF;

        /* Update Workshop Materials */
        SELECT
            COUNT(*)
        INTO v_count
        FROM
            wm_workshop_materials
        WHERE
            st_pur = v_store_requisition_no;

        IF v_count = 0 THEN
            UPDATE wm_workshop_materials
            SET
                st_pur = v_store_requisition_no,
                proc_ref = v_store_requisition_no,
                status = reservation.status
            WHERE
                st_pur = reservation.document_no;

            dbms_output.put_line(v_count || ' Materials Updated');
            COMMIT;
        ELSE
            dbms_output.put_line(v_count || ' Materials Already Updated');
        END IF;

    END LOOP;
EXCEPTION
    WHEN OTHERS THEN
        -- log to errors table
        RAISE;
END proc_reservation_to_requisition;
```
# FUNCTIONS IN SPMS
```oracle
create or replace FUNCTION          F_SEND_ISSUE_INFO_TMS(
    LS_DOC_NO IN VARCHAR2,
    LS_STORE IN VARCHAR2,
    LS_TRANSACTION_TYPE IN VARCHAR2,
    LS_COST_CENTRE IN VARCHAR2,
    LS_SYSTEM_ORIGIN IN VARCHAR2,
    LS_CODEIN IN VARCHAR2,
    LS_QTYIN IN VARCHAR2,
    LS_PRICEIN IN VARCHAR2,
    LS_ACCOUNTIN IN VARCHAR2,
    LS_REQUISITION IN VARCHAR2
) RETURN STRING AS
-- wm_workshop_materials GTAWKMAT
-- sm_movement_header MOVEMENTS_HEADER
-- sm_movement_details MOVEMENTS_DETAIL
 --Variables
    I                       BINARY_INTEGER:= 0;
    LR_HEADER               PG_RECEIVE_DATA.GR_SPARE_TRANS;
    LT_DETAIL               PG_RECEIVE_DATA.GT_SPARE_ITEMS;
    LS_RETURN               VARCHAR2(255);
    LN_COUNT                NUMBER(3);
    LL_COUNT                NUMBER(3);
    LL_COUNTER              NUMBER(3);
    LN_POSN                 NUMBER(4, 1);
    LN_FOUND                NUMBER(3);
    LN_ROWCOUNT             NUMBER(3);
    LS_CODE                 VARCHAR2(2000);
    LS_QTY                  VARCHAR2(2000);
    LS_PRICE                VARCHAR2(2000);
    LS_ACCOUNT              VARCHAR2(2000);
    LS_ARTICLE              VARCHAR2(20);
    LS_C                    VARCHAR2(255);
    LS_Q                    VARCHAR2(255);
    LS_A                    VARCHAR2(255);
    LS_P                    VARCHAR2(255);
    LN_PRICE                NUMBER(17, 4);
    LS_PROGRAM_ID           VARCHAR2(20);
    LS_POINT                VARCHAR2(200);
    LS_TEST                 VARCHAR2(10);
    LS_DOCUMENT_NO          VARCHAR2(20);
    LS_ISSUE_NO             VARCHAR2(20);
    LS_CODE_STORE           VARCHAR2(4);
    LS_STATUS               VARCHAR2(2);
    LS_SUBJECT              VARCHAR2(2000);
    LS_CODE_UNIT            VARCHAR2(10);
    LS_COMPANY              VARCHAR2(2);
    LS_STORE_RESERVATION_NO VARCHAR2(40);
    LS_BUSINESS_AREA        VARCHAR2(40);
    LS_WORK_ORDER           VARCHAR2(40);
    LS_USER_REQUESTING      VARCHAR2(40);
    LS_USER_AUTHORIZER      VARCHAR2(40);
    LS_USER_DOC_NO          VARCHAR2(40);
    LS_JOB_NO               VARCHAR2(40);
    LS_CCENTRE              VARCHAR2(40);
    LS_SYS_ORIGIN           VARCHAR2(8);
    LS_DESCRIPTION          VARCHAR2(120);
    LS_MEASURE              VARCHAR2(3);
    LS_ARTICLE_TYPE         VARCHAR2(2);
    LS_PROJECT_NO           VARCHAR2(40);
    LS_WSHP_ACT_CODE        VARCHAR2(20);
    LS_WSHP_CODE            VARCHAR2(4);
    LS_SECTION              VARCHAR2(4);
    LS_DEF_NO               VARCHAR2(4);
    LS_REG_NO               VARCHAR2(10);
    LS_DELIVERY_SITE        VARCHAR2(50);
    LS_ARTICLE_GROUP        VARCHAR2(6); 
BEGIN
    LS_PROGRAM_ID := 'IPMS 1.11';
    LS_POINT := '1.0';
 --Get the header info
    SELECT
        USER_DOC_NO,
        CODE_STORE,
        BUSINESS_AREA,
        COST_CENTRE,
        JOB_NO,
        WORK_ORDER,
        WORK_ORDER,
        DOCUMENT_NO,
        USER_REQUESTING,
        SYSTEM_ORIGIN,
        BUSINESS_AREA,
        STORE_RESERVATION_NO,
        SUBJECT,
        COMPANY INTO LS_USER_DOC_NO,
        LS_CODE_STORE,
        LS_BUSINESS_AREA,
        LS_CCENTRE,
        LS_JOB_NO,
        LS_WORK_ORDER,
        LS_PROJECT_NO,
        LS_DOCUMENT_NO,
        LS_USER_REQUESTING,
        LS_SYS_ORIGIN,
        LS_BUSINESS_AREA,
        LS_STORE_RESERVATION_NO,
        LS_SUBJECT,
        LS_COMPANY
    FROM
        STORE_REQUISITIONS_HEADER
    WHERE
        DOCUMENT_NO = LS_REQUISITION;
    LS_CODE := LS_CODEIN;
    LS_PRICE := LS_PRICEIN;
    LS_ACCOUNT := LS_ACCOUNTIN;
    LS_QTY := LS_QTYIN;
    LS_ISSUE_NO := LS_DOC_NO;
 -- fill record details
    LR_HEADER.STORE_CODE := LS_STORE;
    LR_HEADER.STF_NUMBER := LS_ISSUE_NO;
    LR_HEADER.TRANSACTION_TYPE := LS_TRANSACTION_TYPE;
    LR_HEADER.COST_CENTRE := LS_COST_CENTRE;
 --extract the details from the string sent
    LS_POINT := '1.1';
    I := 0;
    LOOP
        LN_POSN := INSTR(LS_CODE, '#@#', 1, 1);
        IF LN_POSN > 0 THEN
            I :=I +1;
            LT_DETAIL(I).MATERIAL_CODE := SUBSTR(LS_CODE, 1, LN_POSN -1);
            LS_CODE := SUBSTR(LS_CODE, LN_POSN + 3, LENGTH(LS_CODE));
            LS_C := ' Code'
                || I
                || ': '
                || LT_DETAIL(I).MATERIAL_CODE
                || ' ';
        ELSE
            EXIT;
        END IF;
    END LOOP;
    LS_POINT := '1.2';
 --extract the details from the string sent
    I := 0;
    LOOP
        LN_POSN := INSTR(LS_QTY, '#@#', 1, 1);
        IF LN_POSN > 0 THEN
            I :=I +1;
            LT_DETAIL(I).QUANTITY := TO_NUMBER(SUBSTR(LS_QTY, 1, LN_POSN -1));
            LS_QTY := SUBSTR(LS_QTY, LN_POSN + 3, LENGTH(LS_QTY));
            LS_Q := ' Quantity'
                || I
                || ': '
                || LT_DETAIL(I).QUANTITY
                || ' ';
        ELSE
            EXIT;
        END IF;
    END LOOP;
 --extract the details from the string sent
    LS_POINT := '1.3';
    I := 0;
    LOOP
        LN_POSN := INSTR(LS_PRICE, '#@#', 1, 1);
        IF LN_POSN > 0 THEN
            I :=I +1;
            LT_DETAIL(I).PRICE := TO_NUMBER(SUBSTR(LS_PRICE, 1, LN_POSN -1));
            LS_PRICE := SUBSTR(LS_PRICE, LN_POSN + 3, LENGTH(LS_PRICE));
            LS_P := ' Price'
                || I
                || ': '
                || LT_DETAIL(I).PRICE
                || ' ';
        ELSE
            EXIT;
        END IF;
    END LOOP;
 --extract the details from the string sent
    LS_POINT := '1.4';
    I := 0;
    LOOP
        LN_POSN := INSTR(LS_ACCOUNT, '#@#', 1, 1);
        IF LN_POSN > 0 THEN
            I :=I +1;
            LT_DETAIL(I).ACCOUNT_NO := SUBSTR(LS_ACCOUNT, 1, LN_POSN -1);
            LS_ACCOUNT := SUBSTR(LS_ACCOUNT, LN_POSN + 3, LENGTH(LS_ACCOUNT));
            LS_A := ' Account'
                || I
                || ': '
                || LT_DETAIL(I).PRICE
                || ' ';
        ELSE
            EXIT;
        END IF;
    END LOOP;
    
    IF I < 1 THEN
        LS_RETURN := '1'
            || LS_PROGRAM_ID
            || LS_POINT
            || ': No data was found to send';
        RETURN(LS_RETURN);
    END IF;
    
    LS_POINT := '1.5';
    LS_RETURN := LS_RETURN
        || ' No of rows new '
        || TO_CHAR(LN_ROWCOUNT );
    LN_COUNT := I;
    LS_POINT := '1.7';
    IF LS_SYSTEM_ORIGIN = '04' THEN
        SELECT
            COUNT(*) INTO LL_COUNTER
        FROM
            sm_movement_header
        WHERE
            DOCUMENT_NUMBER = LS_USER_DOC_NO
            AND TRANSACTION_TYPE ='02';
        LS_POINT := '2.3';
         
        IF LL_COUNTER = 0 THEN
            LS_POINT := '2.301';
            
             -- Insert into MOVEMENT HEADER
            INSERT INTO sm_movement_header
            (
                CREATED_BY,
                CREATED_DATE,
                DOCUMENT_NUMBER,
                EXPENSE_TYPE,
                TRANSACTION_TYPE,
                VEH_REG_NO,
                MOVEMENT_DATE,
                STORE_CODE,
                BUSINESS_AREA,
                BUSINESS_UNIT,
                COST_CENTRE,
                WORK_ORDER_NO,
                STF_NUMBER,
                REQUESTED_BY,
                SYSTEM_OF_ORIGIN,
                REQUISITION_NO,
                STORES_RESRV_NO,
                DELIVERY_SITE,
                SUBJECT
            ) VALUES (
                USER,
                SYSDATE,
                LS_USER_DOC_NO,
                '01', --Spares
                '02', --Issues
                LS_REG_NO, --Reg No.
                SYSDATE,
                LS_CODE_STORE,
                LS_COMPANY,
                LS_BUSINESS_AREA,
                LS_CCENTRE,
                LS_JOB_NO,
                LS_ISSUE_NO,
                LS_USER_REQUESTING,
                LS_SYS_ORIGIN,
                LS_REQUISITION,
                LS_STORE_RESERVATION_NO,
                LS_DELIVERY_SITE,
                LS_SUBJECT
            );
            LS_POINT := '2.31';
            LN_ROWCOUNT := LT_DETAIL.LAST;

            FOR I IN LT_DETAIL.FIRST .. LN_ROWCOUNT LOOP
                SELECT
                    ARTICLES.DESCRIPTION,
                    UNITS.ABBREVIATION,
                    ARTICLES.TYPE_ARTICLE INTO LS_DESCRIPTION,
                    LS_MEASURE,
                    LS_ARTICLE_TYPE
                FROM
                    ARTICLES,
                    UNITS
                WHERE
                    CODE_ARTICLE = LT_DETAIL(I).MATERIAL_CODE
                    AND UNITS.CODE_UNIT = ARTICLES.UNIT_MEASURE;
                LS_POINT := '2.302 '
                    ||LT_DETAIL(I).MATERIAL_CODE;
                    
                INSERT INTO sm_movement_details (
                    CREATED_BY,
                    CREATED_DATE,
                    CREATED_AT,
                    DOCUMENT_NUMBER,
                    MATERIAL_CODE,
                    QUANTITY,
                    PRICE,
                    DESCRIPTION,
                    SPECIFICATION,
                    UNIT_OF_MEASURE,
                    ARTICLE_TYPE,
                    TRANSACTION_TYPE,
                    STF_NUMBER,
                    VEH_REG_NO
                ) VALUES (
                    USER,
                    SYSDATE,
                    SYSDATE,
                    LS_USER_DOC_NO,
                    LT_DETAIL(I).MATERIAL_CODE,
                    LT_DETAIL(I).QUANTITY,
                    LT_DETAIL(I).PRICE,
                    LS_DESCRIPTION,
                    '',
                    LS_MEASURE,
                    LS_ARTICLE_TYPE,
                    '02',
                    LS_ISSUE_NO,
                    LT_DETAIL(I).ACCOUNT_NO
                );
                 --Get the workshop,section,def_no and wshp_act_code
                LS_POINT := '2.306'
                    ||LT_DETAIL(I).MATERIAL_CODE
                    ||'- '
                    ||LS_USER_DOC_NO
                    ||'-'
                    ||LS_REQUISITION;
                SELECT
                    CODE_GROUP||CODE_SUBGROUP||CODE_CLASS INTO LS_ARTICLE_GROUP
                FROM
                    ARTICLES
                WHERE
                    CODE_ARTICLE=LT_DETAIL(I).MATERIAL_CODE; /*ROK ZMES-892*/
                IF LS_ARTICLE_GROUP <> '300101' THEN /*ROK ZMES-892*/
                    SELECT
                        WSHP_ACT_CODE,
                        WORKSHOP_CODE,
                        SECTION,
                        DEFECT_NO 
                    INTO 
                        LS_WSHP_ACT_CODE,
                        LS_WSHP_CODE,
                        LS_SECTION,
                        LS_DEF_NO
                    FROM
                       GTAWKMAT -- wm_workshop_materials
                    WHERE
                        FORM_ORDER = LS_USER_DOC_NO
                        AND ST_PUR = LS_REQUISITION
                        AND MAT_CODE = LT_DETAIL(I).MATERIAL_CODE
                        AND ROWNUM < 2;

                    LS_POINT := '2.307'
                        ||LT_DETAIL(I).MATERIAL_CODE
                        ||'- '
                        ||LS_USER_DOC_NO
                        ||'-'
                        ||LS_REQUISITION;
                     
                    INSERT INTO GTAWKMAT --wm_workshop_materials
                    (
                        CREATED_BY,
                        CREATED_AT,
                        WSHP_ACT_CODE,
                        WORKSHOP_CODE,
                        SECTION,
                        EVALUATION,
                        DATE_MAT,
                        MAT_CODE,
                        UNIT_OF_MEASURE,
                        QUANTITY,
                        AMOUNT,
                        DEFECT_NO,
                        ST_PUR,
                        FORM_ORDER,
                        PRICE,
                        STORE_CODE,
                        IND,
                        VEH_REG_NO
                    ) VALUES(
                        USER,
                        SYSDATE,
                        LS_WSHP_ACT_CODE,
                        LS_WSHP_CODE,
                        LS_SECTION,
                        'N',
                        SYSDATE,
                        LT_DETAIL(I).MATERIAL_CODE,
                        LS_MEASURE,
                        LT_DETAIL(I).QUANTITY,
                        LT_DETAIL(I).QUANTITY * LT_DETAIL(I).PRICE,
                        LS_DEF_NO,
                        LS_ISSUE_NO,
                        LS_USER_DOC_NO,
                        LT_DETAIL(I).PRICE,
                        LS_CODE_STORE,
                        'Y',
                        LT_DETAIL(I).ACCOUNT_NO
                    );
                END IF; /*ROK ZMES-892*/
            END LOOP;
        ELSE /*if in movements header*/
            LS_POINT := '2.33';
            LN_ROWCOUNT := LT_DETAIL.LAST;
            FOR I IN LT_DETAIL.FIRST .. LN_ROWCOUNT LOOP
                SELECT
                    COUNT(*) INTO LL_COUNT
                FROM
                    sm_movement_details
                WHERE
                    DOCUMENT_NUMBER = LS_USER_DOC_NO
                    AND MATERIAL_CODE = LT_DETAIL(I).MATERIAL_CODE
                    AND VEH_REG_NO = LT_DETAIL(I).ACCOUNT_NO;

                SELECT
                    ARTICLES.DESCRIPTION,
                    UNITS.ABBREVIATION,
                    ARTICLES.TYPE_ARTICLE INTO LS_DESCRIPTION,
                    LS_MEASURE,
                    LS_ARTICLE_TYPE
                FROM
                    ARTICLES,
                    UNITS
                WHERE
                    CODE_ARTICLE = LT_DETAIL(I).MATERIAL_CODE
                    AND UNITS.CODE_UNIT = ARTICLES.UNIT_MEASURE;
                SELECT
                    CODE_GROUP||CODE_SUBGROUP||CODE_CLASS INTO LS_ARTICLE_GROUP
                FROM
                    ARTICLES
                WHERE
                    CODE_ARTICLE=LT_DETAIL(I).MATERIAL_CODE; /*ROK ZMES-892*/

                IF LL_COUNT = 0 THEN
                    LS_POINT := '2.34';
                    LS_POINT := '2.35';

                    INSERT INTO sm_movement_details (
                        CREATED_BY,
                        CREATED_AT,
                        DOCUMENT_NUMBER,
                        MATERIAL_CODE,
                        QUANTITY,
                        PRICE,
                        DESCRIPTION,
                        SPECIFICATION,
                        UNIT_OF_MEASURE,
                        ARTICLE_TYPE,
                        TRANSACTION_TYPE,
                        STF_NUMBER,
                        VEH_REG_NO
                    ) VALUES (
                        USER,
                        SYSDATE,
                        LS_USER_DOC_NO,
                        LT_DETAIL(I).MATERIAL_CODE,
                        LT_DETAIL(I).QUANTITY,
                        LT_DETAIL(I).PRICE,
                        LS_DESCRIPTION,
                        '',
                        LS_MEASURE,
                        LS_ARTICLE_TYPE,
                        '02',
                        LS_ISSUE_NO,
                        LT_DETAIL(I).ACCOUNT_NO
                    );
                    LS_POINT := '2.36';
                ELSE
                    LS_POINT := '2.38';
                    UPDATE sm_movement_details
                    SET
                        QUANTITY = QUANTITY + LT_DETAIL(
                            I
                        ).QUANTITY,
                        STF_NUMBER = STF_NUMBER
                            ||'-'
                            ||LS_ISSUE_NO
                    WHERE
                        DOCUMENT_NUMBER = LS_USER_DOC_NO
                        AND MATERIAL_CODE = LT_DETAIL(
                            I
                        ).MATERIAL_CODE
                        AND VEH_REG_NO = LT_DETAIL(
                            I
                        ).ACCOUNT_NO
                        AND TRANSACTION_TYPE = '02';

                    IF LS_ARTICLE_GROUP <> '300101' THEN /*ROK ZMES-892*/
                     -- Get the workshop,section,def_no and wshp_act_code
                        SELECT
                            WSHP_ACT_CODE,
                            WORKSHOP_CODE,
                            SECTION,
                            DEFECT_NO 
                        INTO 
                            LS_WSHP_ACT_CODE,
                            LS_WSHP_CODE,
                            LS_SECTION,
                            LS_DEF_NO
                        FROM
                         GTAWKMAT --wm_workshop_materials
                        WHERE
                            FORM_ORDER = LS_USER_DOC_NO
                            AND ST_PUR = LS_REQUISITION
                            AND MAT_CODE = LT_DETAIL(I).MATERIAL_CODE
                            AND ROWNUM < 2;
                        LS_POINT := '2.37'
                            ||LT_DETAIL(I).MATERIAL_CODE
                            ||'- '
                            ||LS_USER_DOC_NO
                            ||'-'
                            ||LS_REQUISITION;
                            
                         -- Insert/ update into 
                        /*UPDATE  wm_workshop_materials SET
                            CREATED_BY = USER,
                            CREATED_AT = SYSDATE,
                            WSHP_ACT_CODE=LS_WSHP_ACT_CODE,
                            WORKSHOP_CODE=,
                            SECTION=LS_SECTION,
                            EVALUATION='N',
                            DATE_MAT=SYSDATE,
                            MAT_CODE=LT_DETAIL(I).MATERIAL_CODE,
                            UNIT_OF_MEASURE=LS_MEASURE,
                            QUANTITY=LT_DETAIL(I).QUANTITY,
                            AMOUNT=LT_DETAIL(I).QUANTITY * LT_DETAIL(I).PRICE,
                            DEFECT_NO=LS_DEF_NO,
                            ST_PUR=LS_ISSUE_NO,
                            FORM_ORDER=LS_USER_DOC_NO,
                            PRICE=LT_DETAIL(I).PRICE,
                            STORE_CODE=LS_CODE_STORE,
                            IND='Y',
                            VEH_REG_NO=LT_DETAIL(I).ACCOUNT_NO
                            WHERE ;*/

                        INSERT INTO GTAWKMAT --wm_workshop_materials
                        (
                            CREATED_BY,
                            CREATED_AT,
                            WSHP_ACT_CODE,
                            WORKSHOP_CODE,
                            SECTION,
                            EVALUATION,
                            DATE_MAT,
                            MAT_CODE,
                            UNIT_OF_MEASURE,
                            QUANTITY,
                            AMOUNT,
                            DEFECT_NO,
                            ST_PUR,
                            FORM_ORDER,
                            PRICE,
                            STORE_CODE,
                            IND,
                            VEH_REG_NO
                        ) VALUES(
                            USER,
                            SYSDATE,
                            LS_WSHP_ACT_CODE,
                            LS_WSHP_CODE,
                            LS_SECTION,
                            'N',
                            SYSDATE,
                            LT_DETAIL(I).MATERIAL_CODE,
                            LS_MEASURE,
                            LT_DETAIL(I).QUANTITY,
                            LT_DETAIL(I).QUANTITY * LT_DETAIL(I).PRICE,
                            LS_DEF_NO,
                            LS_ISSUE_NO,
                            LS_USER_DOC_NO,
                            LT_DETAIL(I).PRICE,
                            LS_CODE_STORE,
                            'Y',
                            LT_DETAIL(I).ACCOUNT_NO
                        );
                    END IF;
                END IF;
            END LOOP;
        END IF;
        LS_POINT := '2.4';
 --------------------------------------------------------------------------------------
        LS_RETURN := '0';
        RETURN(LS_RETURN);
    ELSE
        LS_RETURN := '1'
            || LS_PROGRAM_ID
            || LS_POINT
            || ' The system of origin could not be found';
        RETURN(LS_RETURN);
    END IF;
EXCEPTION
    WHEN OTHERS THEN
        LS_RETURN := '2'
            || LS_PROGRAM_ID
            || LS_POINT
            || ' '
            || SQLERRM
            || LS_TEST
            || ' qty: '
            || LS_QTY
            || LS_RETURN;
        RETURN(LS_RETURN);
END;
```
