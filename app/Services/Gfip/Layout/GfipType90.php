<?php

namespace App\Services\Gfip\Layout;

use App\Services\Layouts\ILayoutRow;

/**
 * Represents a footer line in the layout.
 */
class GfipType90 implements ILayoutRow
{
    public function toLayoutRow(): string
    {
        $layout = [
            '90',
            str_repeat('9', 51),
            str_repeat(' ', 306),
            '*',
        ];

        return implode('', $layout);
    }
}