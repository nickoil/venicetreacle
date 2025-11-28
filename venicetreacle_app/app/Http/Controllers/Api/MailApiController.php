<?php

namespace App\Http\Controllers\Api;

use App\Models\BlacklistedEmailAddress;
use App\Models\Email;
use App\Models\SnsNotification;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class MailApiController extends Controller
{
    public function handleMail(Request $request)
    {
        try {
            if ($request->hasHeader('x-amz-sns-message-type')) {

                $headers = json_encode($request->headers->all());
                $json = $request->getContent();

                $this->logRawNotification($headers, $json);

                $snsMessage = json_decode($json, true);
                $messageJson = json_decode($snsMessage['Message'], true);

                if (!empty($messageJson)) {
                    $notificationType = $messageJson['notificationType'];
                    $messageId = $messageJson['mail']['messageId'];
                    $status = 5; // Unhandled
                    $notes = "Unknown SNS message";

                    switch ($notificationType) {
                        case "Delivery":
                            $notes = "Delivered at " . $messageJson['delivery']['timestamp'];
                            $status = 8; // delivered
                            break;
                        case "Bounce":
                            $bounceType = $messageJson['bounce']['bounceType'];
                            $notes = $bounceType . " Bounce subtype " . $messageJson['bounce']['bounceSubType'];
                            $status = 6; // bounced
                            if ($bounceType == "Permanent") {
                                $emailAddress = $messageJson['bounce']['bouncedRecipients'][0]['emailAddress'];
                                $this->blacklistEmailAddress($emailAddress, $messageId, 6);
                            }
                            break;
                        case "Complaint":
                            $complaintType = $messageJson['complaint']['complaintFeedbackType'];
                            $notes = "Complaint feedbacktype " . $complaintType;
                            $status = 7; // complaint
                            if ($complaintType != "not-spam") {
                                $emailAddress = $messageJson['complaint']['complainedRecipients'][0]['emailAddress'];
                                $this->blacklistEmailAddress($emailAddress, $messageId, 7);
                            }
                            break;
                    }
                    $this->updateEmail($messageId, $status, $notes);
                }
            } else {
                $this->logRawNotification("Error", "Not from AWS");
            }
            return response("Email notification consumed", 200);
        } catch (\Throwable $ex) {
            $this->logRawNotification("Error", $ex->getMessage());
            return response("Notification consumption error: " . $ex->getMessage(), 500);
        }
    }

    private function logRawNotification($headers, $request)
    {
        SnsNotification::create([
            'received_time' => Carbon::now(),
            'headers' => $headers,
            'request' => $request
        ]);
    }

    private function updateEmail($messageId, $status, $notes)
    {
        Email::where('service_message_id', $messageId)
            ->update([
                'email_status_id' => $status,
                'complete_time' => Carbon::now(),
                'notes' => $notes
            ]);
    }

    private function blacklistEmailAddress($emailAddress, $messageId, $status)
    {
        BlacklistedEmailAddress::create([
            'email_address' => $emailAddress,
            'excluded_time' => Carbon::now(),
            'service_message_id' => $messageId,
            'email_status_id' => $status
        ]);
    }
}