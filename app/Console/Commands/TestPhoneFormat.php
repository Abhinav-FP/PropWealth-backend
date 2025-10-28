<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\TwilioService;
use Illuminate\Support\Facades\Log;

class TestPhoneFormat extends Command
{
    protected $signature = 'test:phone {number}';
    protected $description = 'Test phone number formatting';

    public function handle()
    {
        $number = $this->argument('number');
        
        $this->info("Testing phone number: {$number}");
        
        $twilioService = new TwilioService();
        
        // Use reflection to access private method
        $reflection = new \ReflectionClass($twilioService);
        $method = $reflection->getMethod('formatPhoneNumber');
        $method->setAccessible(true);
        
        $formatted = $method->invokeArgs($twilioService, [$number]);
        
        $this->info("Formatted result: {$formatted}");
        
        // Test actual SMS sending (dry run)
        $this->info("Testing SMS send (will fail if Twilio not configured properly):");
        $result = $twilioService->sendOtp($number, '123456');
        
        $this->info("SMS send result: " . ($result ? 'SUCCESS' : 'FAILED'));
        
        return 0;
    }
}