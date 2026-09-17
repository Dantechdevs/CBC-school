<div class="max-w-4xl mx-auto py-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800">Settings</h1>
    </div>

    @if ($flash)
        <div class="mb-4 rounded-xl border px-4 py-3 text-sm
            {{ $flashType === 'success' ? 'bg-green-50 border-green-200 text-green-700' : 'bg-red-50 border-red-200 text-red-700' }}">
            {{ $flash }}
        </div>
    @endif

    {{-- Tabs --}}
    <div class="flex gap-1 border-b border-gray-100 mb-6 overflow-x-auto">
        @foreach ([
            'profile'  => 'School Profile',
            'academic' => 'Academic Calendar',
            'sms'      => 'SMS',
            'email'    => 'Email',
            'mpesa'    => 'M-Pesa',
        ] as $key => $label)
            <button
                wire:click="setTab('{{ $key }}')"
                class="px-4 py-2.5 text-sm font-medium whitespace-nowrap border-b-2 -mb-px transition
                    {{ $activeTab === $key ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                {{ $label }}
            </button>
        @endforeach
    </div>

    {{-- Profile --}}
    @if ($activeTab === 'profile')
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">School Name</label>
                    <input type="text" wire:model="school_name" class="w-full rounded-lg border-gray-200 text-sm">
                    @error('school_name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Motto</label>
                    <input type="text" wire:model="school_motto" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                    <input type="text" wire:model="school_phone" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" wire:model="school_email" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                    <input type="text" wire:model="school_address" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
            </div>
            <div class="pt-2">
                <button wire:click="saveProfile" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Save Profile
                </button>
            </div>
        </div>
    @endif

    {{-- Academic --}}
    @if ($activeTab === 'academic')
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Academic Year</label>
                    <input type="text" wire:model="current_academic_year" placeholder="2025/2026" class="w-full rounded-lg border-gray-200 text-sm">
                    @error('current_academic_year') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Term</label>
                    <select wire:model="current_term" class="w-full rounded-lg border-gray-200 text-sm">
                        <option value="">Select term</option>
                        <option value="Term 1">Term 1</option>
                        <option value="Term 2">Term 2</option>
                        <option value="Term 3">Term 3</option>
                    </select>
                </div>
            </div>
            <div class="pt-2">
                <button wire:click="saveAcademic" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Save Academic Settings
                </button>
            </div>
        </div>
    @endif

    {{-- SMS --}}
    @if ($activeTab === 'sms')
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-medium px-2 py-1 rounded-full
                    {{ $this->atConfigured ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $this->atConfigured ? 'Configured' : 'Not configured' }}
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Africa's Talking API Key</label>
                    <input type="password" wire:model="at_api_key" placeholder="Paste your live/sandbox API key" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" wire:model="at_username" class="w-full rounded-lg border-gray-200 text-sm">
                    <p class="text-xs text-gray-400 mt-1">Use "sandbox" for testing.</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sender ID</label>
                    <input type="text" wire:model="at_sender_id" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
            </div>
            <div class="pt-2 flex items-center gap-3">
                <button wire:click="saveSms" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Save SMS Settings
                </button>
            </div>

            <div class="pt-4 mt-4 border-t border-gray-100">
                <label class="block text-sm font-medium text-gray-700 mb-1">Send a test SMS</label>
                <div class="flex gap-2">
                    <input type="text" wire:model="testPhone" placeholder="07XXXXXXXX" class="flex-1 rounded-lg border-gray-200 text-sm">
                    <button wire:click="sendTestSms" wire:loading.attr="disabled" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendTestSms">Send Test</span>
                        <span wire:loading wire:target="sendTestSms">Sending…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Email --}}
    @if ($activeTab === 'email')
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-medium px-2 py-1 rounded-full
                    {{ $this->mailConfigured ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $this->mailConfigured ? 'Configured' : 'Not configured (logging only)' }}
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Mailer</label>
                    <select wire:model="mail_mailer" class="w-full rounded-lg border-gray-200 text-sm">
                        <option value="log">Log only (dev)</option>
                        <option value="smtp">SMTP</option>
                        <option value="sendmail">Sendmail</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Encryption</label>
                    <select wire:model="mail_encryption" class="w-full rounded-lg border-gray-200 text-sm">
                        <option value="tls">TLS</option>
                        <option value="ssl">SSL</option>
                        <option value="">None</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
                    <input type="text" wire:model="mail_host" placeholder="smtp.mailgun.org" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
                    <input type="text" wire:model="mail_port" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
                    <input type="text" wire:model="mail_username" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" wire:model="mail_password" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Address</label>
                    <input type="email" wire:model="mail_from_address" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">From Name</label>
                    <input type="text" wire:model="mail_from_name" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
            </div>
            <div class="pt-2">
                <button wire:click="saveEmail" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Save Email Settings
                </button>
            </div>

            <div class="pt-4 mt-4 border-t border-gray-100">
                <label class="block text-sm font-medium text-gray-700 mb-1">Send a test email</label>
                <div class="flex gap-2">
                    <input type="email" wire:model="testEmail" placeholder="you@example.com" class="flex-1 rounded-lg border-gray-200 text-sm">
                    <button wire:click="sendTestEmail" wire:loading.attr="disabled" class="px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-lg hover:bg-gray-900 disabled:opacity-50">
                        <span wire:loading.remove wire:target="sendTestEmail">Send Test</span>
                        <span wire:loading wire:target="sendTestEmail">Sending…</span>
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- M-Pesa --}}
    @if ($activeTab === 'mpesa')
        <div class="bg-white rounded-xl border border-gray-100 p-6 space-y-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-medium px-2 py-1 rounded-full
                    {{ $mpesa_enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ $mpesa_enabled ? 'Configured' : 'Not configured' }}
                </span>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Consumer Key</label>
                    <input type="password" wire:model="mpesa_consumer_key" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Consumer Secret</label>
                    <input type="password" wire:model="mpesa_consumer_secret" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Shortcode / Paybill</label>
                    <input type="text" wire:model="mpesa_shortcode" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Passkey</label>
                    <input type="password" wire:model="mpesa_passkey" class="w-full rounded-lg border-gray-200 text-sm">
                </div>
            </div>
            <div class="pt-2">
                <button wire:click="saveMpesa" class="px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700">
                    Save M-Pesa Settings
                </button>
            </div>
        </div>
    @endif

</div>
