<?php

declare(strict_types=1);

namespace Manychois\Composery\ArgumentOptions;

enum AuditFormat : string
{
    case Table = 'table';
    case Plain = 'plain';
    case Json = 'json';
    case Summary = 'summary';
}
