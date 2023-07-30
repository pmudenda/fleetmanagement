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

    RETURN v_return_message;
EXCEPTION
    WHEN OTHERS THEN
        dbms_output.put_line('Error encountered during Execution Of Routine' || sqlerrm);
        v_return_message := 'Error encountered during Execution ' || sqlerrm;
        RETURN ( v_return_message );
END fn_cancel_stores_req;
```

```oracle

```
