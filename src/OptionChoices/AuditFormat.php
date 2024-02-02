<?php

declare(strict_types=1);

namespace Manychois\Composery\OptionChoices;

/**
 * Represents the format of the audit output.
 */
enum AuditFormat : string
{
    case Table = 'table';
    case Plain = 'plain';
    case Json = 'json';
    case Summary = 'summary';
}
