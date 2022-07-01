<?php

namespace App\Services\Layouts;

interface ILayoutRow
{
    /**
     * Returns its row(s) to append in the layout file. Should be appended as
     * new lines.
     */
    function toLayoutRow(): string;
}