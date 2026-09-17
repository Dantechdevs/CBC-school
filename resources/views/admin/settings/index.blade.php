@extends('layouts.admin')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h2 class="text-xl font-bold text-gray-800">Settings</h2>
</div>

<div class="bg-blue-50 border border-blue-200 text-blue-800 text-sm rounded-lg px-4 py-3 mb-6">
    These settings are currently read from your <code class="bg-blue-100 px-1 rounded">.env</code> file and
    <code class="bg-blue-100 px-1 rounded">config/school.php</code>. Editing them here isn't wired up yet —
    there's no settings table in the database. To change a value, update <code class="bg-blue-100 px-1 rounded">.env</code>
    and run <code class="bg-blue-100 px-1 rounded">php artisan config:clear</code>.
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">School Profile</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">School Name</dt><dd class="font-medium text-gray-800">{{ config('school.name') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Motto</dt><dd class="font-medium text-gray-800">{{ config('school.motto') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Type</dt><dd class="font-medium text-gray-800 capitalize">{{ config('school.type') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Address</dt><dd class="font-medium text-gray-800">{{ config('school.address') ?: '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Phone</dt><dd class="font-medium text-gray-800">{{ config('school.phone') ?: '—' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-800">{{ config('school.email') ?: '—' }}</dd></div>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Academic Calendar</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between"><dt class="text-gray-500">Current Academic Year</dt><dd class="font-medium text-gray-800">{{ config('school.academic_year') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Current Term</dt><dd class="font-medium text-gray-800">{{ config('school.terms.'.config('school.current_term')) }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Term Start Date</dt><dd class="font-medium text-gray-800">{{ config('school.current_term_start') ?: 'Not set (CURRENT_TERM_START in .env)' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Term End Date</dt><dd class="font-medium text-gray-800">{{ config('school.current_term_end') ?: 'Not set (CURRENT_TERM_END in .env)' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Next Term Starts</dt><dd class="font-medium text-gray-800">{{ config('school.next_term_start') ?: 'Not set (NEXT_TERM_START in .env)' }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Formative Weight</dt><dd class="font-medium text-gray-800">{{ config('school.assessment_weights.formative') }}%</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Summative Weight</dt><dd class="font-medium text-gray-800">{{ config('school.assessment_weights.summative') }}%</dd></div>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Integrations</h3>
        <dl class="space-y-3 text-sm">
            <div class="flex justify-between items-center">
                <dt class="text-gray-500">M-Pesa (Daraja)</dt>
                <dd>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ config('school.fees.mpesa_enabled') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ config('school.fees.mpesa_enabled') ? 'Configured' : 'Not configured' }}
                    </span>
                </dd>
            </div>
            <div class="flex justify-between items-center">
                <dt class="text-gray-500">SMS (Africa's Talking)</dt>
                <dd>
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ config('school.sms.enabled') ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                        {{ config('school.sms.enabled') ? 'Configured' : 'Not configured' }}
                    </span>
                </dd>
            </div>
            <div class="flex justify-between"><dt class="text-gray-500">SMS Sender ID</dt><dd class="font-medium text-gray-800">{{ config('school.sms.sender_id') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Invoice Prefix</dt><dd class="font-medium text-gray-800 font-mono">{{ config('school.fees.invoice_prefix') }}</dd></div>
            <div class="flex justify-between"><dt class="text-gray-500">Receipt Prefix</dt><dd class="font-medium text-gray-800 font-mono">{{ config('school.fees.receipt_prefix') }}</dd></div>
        </dl>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Grade Levels Offered</h3>
        <div class="space-y-2 text-sm">
            @foreach(config('school.grade_levels') as $band => $grades)
            <div class="flex justify-between">
                <dt class="text-gray-500">{{ ucwords(str_replace('_', ' ', $band)) }}</dt>
                <dd class="font-medium text-gray-800">{{ implode(', ', $grades) }}</dd>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
