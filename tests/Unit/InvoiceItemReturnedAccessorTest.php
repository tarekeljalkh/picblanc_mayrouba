<?php

namespace Tests\Unit;

use App\Models\InvoiceItem;
use PHPUnit\Framework\TestCase;

class InvoiceItemReturnedAccessorTest extends TestCase
{
    public function test_returned_append_reports_whether_all_item_units_are_returned(): void
    {
        $partiallyReturned = new InvoiceItem([
            'quantity' => 3,
            'returned_quantity' => 2,
        ]);
        $fullyReturned = new InvoiceItem([
            'quantity' => 3,
            'returned_quantity' => 3,
        ]);

        $this->assertFalse($partiallyReturned->toArray()['returned']);
        $this->assertTrue($fullyReturned->toArray()['returned']);
    }
}
