<?php

namespace App\Constants;

class Articles
{
    const SERVICE_GROUP_CODE = "41";

    const NON_STOCK_CODE_GROUP = "40";

    const ARTICLE_CODE = "@articleCode";

    const STOCK_ITEMS_GROUP = ["01", "04", "30"];

    const ARTICLE_FIELD = '@article';

    const REG_FIELD = '@reg';

    const REQ_NO = "@req_no";

    const ITEM_TYPE = '@itemType';

    // 45 -cancelled, 03 -rejected , 01 - new , 02 - authorised
    const OPEN_STATUS_GROUP = ['45', '03', '01', '02'];

}
