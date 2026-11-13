<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('properties:expire')]
#[Description('Command description')]
class ExpireProperties extends Command
{
    protected $signature = 'properties:expire';
    protected $description = 'Chuyển các tin đã quá hạn sang trạng thái hết hạn';

    public function handle(): int
    {
        $count = Property::where('status', Property::STATUS_APPROVED)
            ->where('expires_at', '<=', now())
            ->update(['status' => Property::STATUS_EXPIRED]);

        $this->info("Đã chuyển {$count} tin sang hết hạn.");
        return self::SUCCESS;
    }
}
