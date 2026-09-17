<?php

namespace App\Livewire\Settings;

use App\Models\Setting;
use App\Services\AfricasTalkingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class SettingsPage extends Component
{
    public string $activeTab = 'profile'; // profile | academic | sms | email | mpesa

    // Profile
    public string $school_name = '';
    public string $school_motto = '';
    public string $school_address = '';
    public string $school_phone = '';
    public string $school_email = '';

    // Academic
    public string $current_academic_year = '';
    public string $current_term = '';

    // SMS
    public string $at_api_key = '';
    public string $at_username = 'sandbox';
    public string $at_sender_id = 'SCHOOL';
    public bool   $sms_enabled = false;

    // Email
    public string $mail_mailer = 'log';
    public string $mail_host = '';
    public string $mail_port = '587';
    public string $mail_username = '';
    public string $mail_password = '';
    public string $mail_encryption = 'tls';
    public string $mail_from_address = '';
    public string $mail_from_name = '';

    // M-Pesa
    public string $mpesa_consumer_key = '';
    public string $mpesa_consumer_secret = '';
    public string $mpesa_shortcode = '';
    public string $mpesa_passkey = '';
    public bool   $mpesa_enabled = false;

    // Test-send state
    public string $testPhone = '';
    public string $testEmail = '';
    public string $flash = '';
    public string $flashType = 'success'; // success | error

    protected array $encryptedKeys = [
        'at_api_key', 'mail_password', 'mpesa_consumer_secret', 'mpesa_passkey',
    ];

    protected array $typeMap = [
        'sms_enabled'   => 'bool',
        'mpesa_enabled' => 'bool',
        'mail_port'     => 'int',
    ];

    public function mount(): void
    {
        $saved = Setting::allCached();

        foreach (get_object_vars($this) as $prop => $current) {
            if (array_key_exists($prop, $saved) && $saved[$prop] !== null) {
                $this->{$prop} = $saved[$prop];
            }
        }
    }

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
        $this->flash = '';
    }

    /** Is the real (non-placeholder) AT key actually set — used in the UI badge. */
    public function getAtConfiguredProperty(): bool
    {
        return filled($this->at_api_key) && $this->at_api_key !== 'your_api_key_here';
    }

    public function getMailConfiguredProperty(): bool
    {
        return $this->mail_mailer !== 'log' && filled($this->mail_host);
    }

    public function saveProfile(): void
    {
        $this->validate([
            'school_name'    => 'required|string|max:150',
            'school_motto'   => 'nullable|string|max:200',
            'school_address' => 'nullable|string|max:255',
            'school_phone'   => 'nullable|string|max:20',
            'school_email'   => 'nullable|email|max:150',
        ]);

        Setting::setMany([
            'school_name'    => $this->school_name,
            'school_motto'   => $this->school_motto,
            'school_address' => $this->school_address,
            'school_phone'   => $this->school_phone,
            'school_email'   => $this->school_email,
        ], 'profile');

        $this->notify('Profile settings saved.');
    }

    public function saveAcademic(): void
    {
        $this->validate([
            'current_academic_year' => 'required|string|max:9',
            'current_term'          => 'required|string|max:20',
        ]);

        Setting::setMany([
            'current_academic_year' => $this->current_academic_year,
            'current_term'          => $this->current_term,
        ], 'academic');

        $this->notify('Academic calendar saved.');
    }

    public function saveSms(): void
    {
        $this->validate([
            'at_api_key'   => 'nullable|string',
            'at_username'  => 'required|string|max:50',
            'at_sender_id' => 'required|string|max:20',
        ]);

        Setting::setMany([
            'at_api_key'   => $this->at_api_key,
            'at_username'  => $this->at_username,
            'at_sender_id' => $this->at_sender_id,
            'sms_enabled'  => $this->atConfigured,
        ], 'sms', $this->typeMap, $this->encryptedKeys);

        $this->notify('SMS settings saved.');
    }

    public function saveEmail(): void
    {
        $this->validate([
            'mail_mailer'       => 'required|in:log,smtp,sendmail',
            'mail_host'         => 'nullable|string|max:150',
            'mail_port'         => 'nullable|numeric',
            'mail_username'     => 'nullable|string|max:150',
            'mail_encryption'   => 'nullable|in:tls,ssl,',
            'mail_from_address' => 'nullable|email',
            'mail_from_name'    => 'nullable|string|max:100',
        ]);

        Setting::setMany([
            'mail_mailer'       => $this->mail_mailer,
            'mail_host'         => $this->mail_host,
            'mail_port'         => $this->mail_port,
            'mail_username'     => $this->mail_username,
            'mail_password'     => $this->mail_password,
            'mail_encryption'   => $this->mail_encryption,
            'mail_from_address' => $this->mail_from_address,
            'mail_from_name'    => $this->mail_from_name,
        ], 'email', $this->typeMap, $this->encryptedKeys);

        $this->notify('Email settings saved.');
    }

    public function saveMpesa(): void
    {
        $this->validate([
            'mpesa_consumer_key'    => 'nullable|string',
            'mpesa_consumer_secret' => 'nullable|string',
            'mpesa_shortcode'       => 'nullable|string|max:20',
            'mpesa_passkey'         => 'nullable|string',
        ]);

        Setting::setMany([
            'mpesa_consumer_key'    => $this->mpesa_consumer_key,
            'mpesa_consumer_secret' => $this->mpesa_consumer_secret,
            'mpesa_shortcode'       => $this->mpesa_shortcode,
            'mpesa_passkey'         => $this->mpesa_passkey,
            'mpesa_enabled'         => filled($this->mpesa_consumer_key) && filled($this->mpesa_shortcode),
        ], 'mpesa', $this->typeMap, $this->encryptedKeys);

        $this->notify('M-Pesa settings saved.');
    }

    public function sendTestSms(): void
    {
        $this->validate(['testPhone' => 'required|string|min:9']);

        if (!$this->atConfigured) {
            $this->notify('Save a real Africa\'s Talking API key first.', false);
            return;
        }

        try {
            // Re-resolve so it picks up the value just saved, not the
            // config snapshot cached at request boot.
            $service = new AfricasTalkingService();
            $result = $service->sendSms($this->testPhone, 'This is a test message from your school system settings page.');

            $success = collect($result['SMSMessageData']['Recipients'] ?? [])
                ->contains(fn ($r) => in_array($r['status'] ?? '', ['Success', 'Sent'], true));

            $success
                ? $this->notify("Test SMS sent to {$this->testPhone}.")
                : $this->notify('Africa\'s Talking rejected the message — check the API key and sender ID.', false);
        } catch (\Throwable $e) {
            $this->notify('SMS send failed: ' . $e->getMessage(), false);
        }
    }

    public function sendTestEmail(): void
    {
        $this->validate(['testEmail' => 'required|email']);

        if (!$this->mailConfigured) {
            $this->notify('Set MAIL mailer to smtp and fill in the host first.', false);
            return;
        }

        try {
            Mail::raw('This is a test email from your school system settings page.', function ($msg) {
                $msg->to($this->testEmail)
                    ->subject('Test email — ' . ($this->school_name ?: config('school.name')));
            });

            $this->notify("Test email sent to {$this->testEmail}.");
        } catch (\Throwable $e) {
            $this->notify('Email send failed: ' . $e->getMessage(), false);
        }
    }

    protected function notify(string $message, bool $success = true): void
    {
        $this->flash = $message;
        $this->flashType = $success ? 'success' : 'error';
    }

    public function render()
    {
        return view('livewire.settings.settings-page')->layout('layouts.admin');
    }
}
