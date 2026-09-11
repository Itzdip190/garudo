<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorKycRejectedNotification extends Notification
{
    use Queueable;

    public string $reason;

    public function __construct(string $reason)
    {
        $this->reason = $reason;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠️ Action Required: KYC Verification Update for GARUDO')
            ->greeting("Hello {$notifiable->name},")
            ->line("Our compliance verification team reviewed the KYC documents for **{$notifiable->company_name}**.")
            ->line("Unfortunately, we could not verify your application due to the following reason:")
            ->line("> *{$this->reason}*")
            ->action('Update KYC Documents', route('vendor.kyc.index'))
            ->line('Please re-upload the corrected documents to complete your verification and claim your 1-Year Free Membership.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'       => 'kyc_rejected',
            'title'      => 'KYC Verification Needs Correction',
            'message'    => "Your KYC documents require attention: {$this->reason}",
            'reason'     => $this->reason,
            'action_url' => route('vendor.kyc.index'),
            'icon'       => 'bi-exclamation-triangle-fill text-danger',
            'created_at' => now()->toIso8601String(),
        ];
    }
}
