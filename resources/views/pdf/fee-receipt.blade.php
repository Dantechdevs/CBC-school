<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#1a1a1a;}
.header{background:#1a5c2a;color:white;padding:14px 24px;}
.header h1{font-size:16px;font-weight:bold;}
.header p{font-size:9px;opacity:0.85;margin-top:2px;}
.receipt-title{text-align:center;padding:10px;font-size:14px;font-weight:bold;color:#166534;border-bottom:2px solid #16a34a;background:#f0fdf4;}
.receipt-no{text-align:right;padding:8px 24px;font-size:10px;color:#6b7280;border-bottom:1px solid #e5e7eb;}
.info-grid{display:flex;border-bottom:1px solid #e5e7eb;}
.info-col{flex:1;padding:12px 20px;border-right:1px solid #e5e7eb;}
.info-col:last-child{border-right:none;}
.info-label{font-size:8px;color:#6b7280;text-transform:uppercase;margin-bottom:3px;}
.info-value{font-size:11px;font-weight:bold;color:#111827;}
table{width:100%;border-collapse:collapse;margin:0;}
th{background:#f9fafb;border:1px solid #e5e7eb;padding:8px 16px;text-align:left;font-size:9px;font-weight:bold;color:#374151;}
td{border:1px solid #e5e7eb;padding:8px 16px;font-size:10px;}
.total-row td{background:#f0fdf4;font-weight:bold;font-size:12px;color:#166534;}
.footer{text-align:center;padding:10px;font-size:8px;color:#9ca3af;border-top:1px solid #e5e7eb;margin-top:8px;}
.mpesa-badge{display:inline-block;background:#e7f3e7;color:#1a5c2a;padding:2px 10px;border-radius:12px;font-size:10px;font-weight:bold;}
</style>
</head>
<body>
<div class="header">
    <h1>{{ config('school.name') }}</h1>
    <p>{{ config('school.address') }} | {{ config('school.phone') }} | {{ config('school.email') }}</p>
</div>

<div class="receipt-title">OFFICIAL FEE PAYMENT RECEIPT</div>
<div class="receipt-no">
    Receipt No: <strong>{{ $payment->receipt_number }}</strong> &nbsp;|&nbsp;
    Date: <strong>{{ $payment->paid_at->format('d M Y, h:i A') }}</strong>
</div>

<div class="info-grid">
    <div class="info-col"><div class="info-label">Learner Name</div><div class="info-value">{{ $learner->full_name }}</div></div>
    <div class="info-col"><div class="info-label">Admission No.</div><div class="info-value">{{ $learner->admission_number }}</div></div>
    <div class="info-col"><div class="info-label">Grade</div><div class="info-value">{{ $learner->grade_level->value }}</div></div>
    <div class="info-col"><div class="info-label">Academic Year / Term</div><div class="info-value">{{ $invoice->academic_year }} — Term {{ $invoice->term }}</div></div>
</div>

<table style="margin-top:16px;">
    <thead>
        <tr>
            <th>Description</th>
            <th style="text-align:right">Amount (KES)</th>
        </tr>
    </thead>
    <tbody>
        <tr><td>Fee Payment — Invoice {{ $invoice->invoice_number }}</td><td style="text-align:right">{{ number_format($payment->amount, 2) }}</td></tr>
        <tr><td>Payment Method</td>
        <td style="text-align:right">
            @if($payment->payment_method->value === 'mpesa')
                <span class="mpesa-badge">M-Pesa ✓</span> {{ $payment->mpesa_receipt_number }}
            @else
                {{ ucfirst($payment->payment_method->value) }}
            @endif
        </td></tr>
        @if($payment->mpesa_phone)
        <tr><td>M-Pesa Phone</td><td style="text-align:right">{{ $payment->mpesa_phone }}</td></tr>
        @endif
    </tbody>
</table>

<table style="margin-top:1px;">
    <tr><td style="background:#f9fafb">Invoice Total</td><td style="text-align:right;background:#f9fafb">KES {{ number_format($invoice->total_amount, 2) }}</td></tr>
    <tr><td>Previously Paid</td><td style="text-align:right">KES {{ number_format($invoice->amount_paid - $payment->amount, 2) }}</td></tr>
    <tr><td>This Payment</td><td style="text-align:right;color:#16a34a;font-weight:bold">KES {{ number_format($payment->amount, 2) }}</td></tr>
    <tr class="total-row">
        <td>Balance Remaining</td>
        <td style="text-align:right">KES {{ number_format(max(0, $invoice->total_amount - $invoice->amount_paid), 2) }}</td>
    </tr>
</table>

@if($payment->notes)
<div style="padding:8px 16px;font-size:9px;color:#6b7280;border-top:1px solid #e5e7eb;">Notes: {{ $payment->notes }}</div>
@endif

<div style="padding:10px 16px;display:flex;justify-content:space-between;border-top:1px solid #e5e7eb;margin-top:8px;">
    <div>
        <div style="font-size:9px;color:#6b7280;margin-bottom:20px">Received by</div>
        <div style="border-top:1px solid #374151;width:120px;padding-top:3px;font-size:9px;color:#6b7280;">Signature &amp; Stamp</div>
    </div>
    @if($invoice->amount_paid >= $invoice->total_amount)
    <div style="background:#f0fdf4;border:2px solid #16a34a;padding:8px 20px;border-radius:6px;text-align:center;">
        <div style="font-size:14px;font-weight:bold;color:#166534;">PAID IN FULL</div>
        <div style="font-size:9px;color:#16a34a;">Thank you!</div>
    </div>
    @endif
</div>

<div class="footer">
    {{ config('school.name') }} · {{ now()->format('d M Y') }}<br>
    This is a computer-generated receipt. For queries call {{ config('school.phone') }}
</div>
</body>
</html>
