<?php

namespace App\Notifications;

use App\Models\VendorCertificate;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class VendorKycApprovedNotification extends Notification
{
    use Queueable;

    public VendorCertificate $certificate;

    public function __construct(VendorCertificate $certificate)
    {
        $this->certificate = $certificate;
    }

    public function via(object $notifiable): array
    {
        return ['database']; // Can be expanded with mail, whatsapp
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('🎉 Congratulations! You are now a Verified Channel Partner on GARUDO')
            ->greeting("Hello {$notifiable->name},")
            ->line("Your business KYC verification for **{$notifiable->company_name}** has been officially approved!")
            ->line("We have issued your official **Verified Channel Partner Digital Certificate** (Certificate No: `{$this->certificate->certificate_number}`).")
            ->line("Your 1-Year Free Premium Membership is now active through **{$this->certificate->expiry_date->format('d M, Y')}**.")
            ->action('View & Download Certificate', $this->certificate->verification_url)
            ->line('Thank you for partnering with GARUDO B2B Marketplace.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'               => 'kyc_approved',
            'title'              => 'KYC Verified & Certificate Issued',
            'message'            => "Congratulations! {$notifiable->company_name} is now a Verified Channel Partner. Digital Certificate #{$this->certificate->certificate_number} has been generated.",
            'certificate_number' => $this->certificate->certificate_number,
            'certificate_url'    => route('vendor.certificate.show'),
            'icon'               => 'bi-patch-check-fill text-success',
            'created_at'         => now()->toIso8601String(),
        ];
    }
}
