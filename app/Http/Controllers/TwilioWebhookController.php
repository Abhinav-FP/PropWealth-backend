<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TwilioWebhookController extends Controller
{
    /**
     * Handle Twilio message status callbacks.
     */
    public function statusCallback(Request $request)
    {
        // Twilio sends fields like MessageSid, MessageStatus, ErrorCode, To, From
        $payload = $request->all();

        Log::info('Twilio Status Callback received', [
            'MessageSid' => $payload['MessageSid'] ?? null,
            'MessageStatus' => $payload['MessageStatus'] ?? null,
            'ErrorCode' => $payload['ErrorCode'] ?? null,
            'To' => $payload['To'] ?? null,
            'From' => $payload['From'] ?? null,
        ]);

        // Respond 200 OK so Twilio does not retry unnecessarily
        return response()->json(['status' => 'ok']);
    }
}