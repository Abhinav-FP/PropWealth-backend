<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class TwilioService
{
  protected $client;
  protected $from;
  protected $countryCode;
  protected $messagingServiceSid;
  protected $statusCallbackUrl;

  public function __construct()
  {
    $sid = config('services.twilio.sid');
    $token = config('services.twilio.token');
    $this->from = config('services.twilio.from');
    $this->countryCode = config('services.twilio.country_code');
    $this->messagingServiceSid = config('services.twilio.messaging_service_sid');
    $this->statusCallbackUrl = config('services.twilio.status_callback_url');
    $this->client = new Client($sid, $token);
  }

  public function sendSms($to, $message)
  {
    try {
      Log::info('TwilioService: Attempting to send SMS', ['to' => $to]);
      
      // Check Twilio configuration
      if ((empty($this->from) && empty($this->messagingServiceSid)) || empty(config('services.twilio.sid')) || empty(config('services.twilio.token'))) {
        Log::error('TwilioService: Missing Twilio configuration', [
          'from' => $this->from,
          'messaging_service_sid_present' => !empty($this->messagingServiceSid),
          'sid_present' => !empty(config('services.twilio.sid')),
          'token_present' => !empty(config('services.twilio.token'))
        ]);
        return false;
      }
      
      // Format phone number
      $formattedNumber = $this->formatPhoneNumber($to);
      
      // Validate formatted number - more strict validation
      if (strlen($formattedNumber) < 10 || !str_starts_with($formattedNumber, '+')) {
        Log::error('TwilioService: Invalid phone number format', [
          'original' => $to,
          'formatted' => $formattedNumber,
          'length' => strlen($formattedNumber)
        ]);
        return false;
      }
      
      // Additional validation for Indian numbers
      if (str_starts_with($formattedNumber, '+91')) {
        $digitsAfter91 = substr($formattedNumber, 3);
        if (strlen($digitsAfter91) !== 10) {
          Log::error('TwilioService: Indian number must have exactly 10 digits after +91', [
            'original' => $to,
            'formatted' => $formattedNumber,
            'digits_after_91' => $digitsAfter91,
            'digit_count' => strlen($digitsAfter91)
          ]);
          return false;
        }
      }
      
      $options = [
        'body' => $message,
      ];

      if (!empty($this->messagingServiceSid)) {
        $options['messagingServiceSid'] = $this->messagingServiceSid;
      } else {
        $options['from'] = $this->from;
      }

      if (!empty($this->statusCallbackUrl)) {
        $options['statusCallback'] = $this->statusCallbackUrl;
      }

      Log::info('TwilioService: Sending SMS via Twilio', [
        'to' => $formattedNumber,
        'from' => $options['from'] ?? null,
        'messagingServiceSid' => $options['messagingServiceSid'] ?? null,
        'statusCallback' => $options['statusCallback'] ?? null,
        'message_length' => strlen($message)
      ]);

      $result = $this->client->messages->create($formattedNumber, $options);

      Log::info('TwilioService: SMS sent successfully', [
        'to' => $formattedNumber,
        'sid' => $result->sid,
        'status' => $result->status
      ]);
      return true;
      
    } catch (\Twilio\Exceptions\TwilioException $e) {
      Log::error('TwilioService: Twilio API Error', [
        'to' => $to ?? 'unknown',
        'formatted_to' => $formattedNumber ?? 'unknown',
        'error_code' => $e->getCode(),
        'error_message' => $e->getMessage(),
        'error_details' => $e->getDetails()
      ]);
      return false;
      
    } catch (\Exception $e) {
      Log::error('TwilioService: General Error', [
        'to' => $to ?? 'unknown',
        'formatted_to' => $formattedNumber ?? 'unknown',
        'error' => $e->getMessage(),
        'trace' => $e->getTraceAsString()
      ]);
      return false;
    }
  }

  public function sendOtp($to, $otp)
  {
    $message = "Your verification code is: {$otp}. This code will expire in 10 minutes. Do not share this code with anyone.";
    return $this->sendSms($to, $message);
  }


  private function formatPhoneNumber($phoneNumber)
  {
    // Log the original number for debugging
    Log::info('Formatting phone number', ['original' => $phoneNumber]);
    
    // If already in E.164 format (starts with +), validate and return
    if (str_starts_with($phoneNumber, '+')) {
      $cleanNumber = preg_replace('/[^\d+]/', '', $phoneNumber);
      Log::info('Already E.164 format', ['formatted' => $cleanNumber]);
      return $cleanNumber;
    }
    
    // Remove any non-numeric characters except +
    $cleanNumber = preg_replace('/[^\d]/', '', $phoneNumber);
    
    // Handle Indian numbers specifically
    if (strlen($cleanNumber) === 10) {
      // 10 digit number, add India country code
      $formatted = '+91' . $cleanNumber;
    } elseif (strlen($cleanNumber) === 12 && str_starts_with($cleanNumber, '91')) {
      // 12 digit number starting with 91, add +
      $formatted = '+' . $cleanNumber;
    } elseif (strlen($cleanNumber) === 11 && str_starts_with($cleanNumber, '0')) {
      // 11 digit number starting with 0, remove 0 and add +91
      $formatted = '+91' . substr($cleanNumber, 1);
    } else {
      // For other formats, assume it needs +91 prefix
      $formatted = '+91' . ltrim($cleanNumber, '0');
    }
    
    Log::info('Phone number formatted', ['original' => $phoneNumber, 'formatted' => $formatted]);
    return $formatted;
  }
}
