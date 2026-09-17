<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Map of DB setting key => config path it overrides.
     * Extend this array as you add more settings; nothing else needs to
     * change — every existing config('school.xxx') / config('services.xxx')
     * call in the app automatically picks up the DB-saved value.
     */
    protected array $map = [
        // Profile
        'school_name'          => 'school.name',
        'school_motto'         => 'school.motto',
        'school_address'       => 'school.address',
        'school_phone'         => 'school.phone',
        'school_email'         => 'school.email',
        'school_logo_path'     => 'school.logo_path',

        // Academic
        'current_academic_year' => 'school.academic_year',
        'current_term'           => 'school.term',

        // SMS (Africa's Talking)
        'at_api_key'    => 'services.africastalking.api_key',
        'at_username'   => 'services.africastalking.username',
        'at_sender_id'  => 'services.africastalking.sender_id',
        'sms_enabled'   => 'school.sms.enabled',

        // Email
        'mail_mailer'       => 'mail.default',
        'mail_host'         => 'mail.mailers.smtp.host',
        'mail_port'         => 'mail.mailers.smtp.port',
        'mail_username'     => 'mail.mailers.smtp.username',
        'mail_password'     => 'mail.mailers.smtp.password',
        'mail_encryption'   => 'mail.mailers.smtp.encryption',
        'mail_from_address' => 'mail.from.address',
        'mail_from_name'    => 'mail.from.name',

        // M-Pesa
        'mpesa_consumer_key'    => 'services.mpesa.consumer_key',
        'mpesa_consumer_secret' => 'services.mpesa.consumer_secret',
        'mpesa_shortcode'       => 'services.mpesa.shortcode',
        'mpesa_passkey'         => 'services.mpesa.passkey',
        'mpesa_enabled'         => 'school.mpesa.enabled',
    ];

    public function boot(): void
    {
        // Guard: during `migrate` on a fresh install the settings table
        // won't exist yet. Don't blow up artisan commands.
        if (!Schema::hasTable('settings')) {
            return;
        }

        $saved = Setting::allCached();

        if (empty($saved)) {
            return;
        }

        foreach ($this->map as $settingKey => $configPath) {
            if (array_key_exists($settingKey, $saved) && $saved[$settingKey] !== null && $saved[$settingKey] !== '') {
                config([$configPath => $saved[$settingKey]]);
            }
        }
    }
}
