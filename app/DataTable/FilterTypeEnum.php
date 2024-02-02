<?php

namespace App\DataTable;

enum FilterTypeEnum: string
{
    case SEARCHABLE = "searchable";
    case SELECT     = "select";
    case DATE       = "date";
}
