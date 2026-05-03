<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:DejaVu Sans,sans-serif;font-size:11px;color:#1a1a1a;}
.header{background:#1a5c2a;color:white;padding:16px 24px;display:flex;align-items:center;gap:16px;}
.header h1{font-size:17px;font-weight:bold;}
.header p{font-size:9px;opacity:0.85;margin-top:2px;}
.report-title{text-align:center;background:#f0fdf4;border-bottom:2px solid #16a34a;padding:8px;font-size:13px;font-weight:bold;color:#166534;letter-spacing:0.05em;}
.learner-bar{display:flex;border-bottom:1px solid #e5e7eb;}
.learner-field{flex:1;padding:10px 16px;border-right:1px solid #e5e7eb;}
.learner-field:last-child{border-right:none;}
.lf-label{font-size:8px;color:#6b7280;text-transform:uppercase;letter-spacing:0.05em;}
.lf-value{font-size:12px;font-weight:bold;color:#111827;margin-top:2px;}
.section-head{background:#1a5c2a;color:white;padding:5px 16px;font-size:10px;font-weight:bold;text-transform:uppercase;letter-spacing:0.08em;}
table{width:100%;border-collapse:collapse;}
th{background:#f9fafb;border:1px solid #e5e7eb;padding:6px 10px;text-align:left;font-size:9px;color:#374151;font-weight:bold;}
td{border:1px solid #e5e7eb;padding:6px 10px;font-size:10px;}
tr:nth-child(even) td{background:#fafafa;}
.badge{display:inline-block;font-weight:bold;text-align:center;border-radius:3px;padding:2px 8px;font-size:10px;}
.EE{background:#dcfce7;color:#166534;}.ME{background:#dbeafe;color:#1e40af;}.AE{background:#fef9c3;color:#92400e;}.BE{background:#fee2e2;color:#991b1b;}
.remarks-row{padding:12px 16px;border-bottom:1px solid #e5e7eb;}
.remarks-label{font-size:9px;color:#6b7280;text-transform:uppercase;display:block;margin-bottom:4px;}
.sig-row{display:flex;gap:40px;margin:12px 16px;}
.sig-block{flex:1;}
.sig-line{border-bottom:1px solid #374151;height:20px;width:160px;margin-bottom:4px;}
.sig-name{font-size:9px;color:#6b7280;}
.legend{padding:8px 16px;background:#f9fafb;display:flex;gap:16px;flex-wrap:wrap;border-top:1px solid #e5e7eb;}
.legend-item{display:flex;align-items:center;gap:6px;font-size:9px;color:#374151;}
.footer{text-align:center;padding:8px;font-size:8px;color:#9ca3af;border-top:1px solid #e5e7eb;}
.attendance-grid{display:flex;gap:0;border:1px solid #e5e7eb;}
.att-box{flex:1;text-align:center;padding:8px;border-right:1px solid #e5e7eb;}
.att-box:last-child{border-right:none;}
.att-num{font-size:16px;font-weight:bold;color:#111827;}
.att-lbl{font-size:8px;color:#6b7280;text-transform:uppercase;}
</style>
</head>
<body>
<div class="header">
    <div>
        <h1>{{ config('school.name') }}</h1>
        <p>{{ config('school.address') }} &nbsp;|&nbsp; {{ config('school.phone') }}</p>
        <p style="font-style:italic;margin-top:3px;">{{ config('school.motto') }}</p>
    </div>
</div>

<div class="report-title">LEARNER PROGRESS REPORT — {{ strtoupper($term) }} {{ $academicYear }}</div>

<div class="learner-bar">
    <div class="learner-field"><div class="lf-label">Full Name</div><div class="lf-value">{{ $learner->full_name }}</div></div>
    <div class="learner-field"><div class="lf-label">Admission No.</div><div class="lf-value">{{ $learner->admission_number }}</div></div>
    <div class="learner-field"><div class="lf-label">KEMIS UPI</div><div class="lf-value">{{ $learner->kemis_upi ?? 'N/A' }}</div></div>
    <div class="learner-field"><div class="lf-label">Grade</div><div class="lf-value">{{ $learner->grade_level->value }}</div></div>
    <div class="learner-field"><div class="lf-label">Class</div><div class="lf-value">{{ $learner->schoolClass->name ?? '—' }}</div></div>
    <div class="learner-field"><div class="lf-label">Gender</div><div class="lf-value">{{ ucfirst($learner->gender) }}</div></div>
</div>

<div class="section-head">Competency Assessment — Learning Areas</div>
<table>
    <thead>
        <tr>
            <th style="width:30%">Learning Area</th>
            <th style="width:20%">Strand</th>
            <th style="width:12%;text-align:center">Formative (40%)</th>
            <th style="width:12%;text-align:center">Summative (60%)</th>
            <th style="width:12%;text-align:center">Overall</th>
            <th>Teacher Remarks</th>
        </tr>
    </thead>
    <tbody>
        @forelse($assessments as $area => $data)
        <tr>
            <td><strong>{{ $area }}</strong></td>
            <td style="font-size:9px;color:#6b7280">{{ $data['strand'] ?? '—' }}</td>
            <td style="text-align:center">
                @if($data['formative'])<span class="badge {{ $data['formative'] }}">{{ $data['formative'] }}</span>@else <span style="color:#d1d5db">—</span>@endif
            </td>
            <td style="text-align:center">
                @if($data['summative'])<span class="badge {{ $data['summative'] }}">{{ $data['summative'] }}</span>@else <span style="color:#d1d5db">—</span>@endif
            </td>
            <td style="text-align:center">
                @if($data['overall'])<span class="badge {{ $data['overall'] }}">{{ $data['overall'] }}</span>@else <span style="color:#d1d5db">—</span>@endif
            </td>
            <td style="font-size:9px;color:#4b5563">{{ $data['remarks'] ?? '' }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:#9ca3af;font-style:italic;padding:12px">No assessment records for this term.</td></tr>
        @endforelse
    </tbody>
</table>

<div class="section-head" style="margin-top:8px">Attendance Summary</div>
<div class="attendance-grid">
    <div class="att-box"><div class="att-num">{{ $attendance['total'] }}</div><div class="att-lbl">School Days</div></div>
    <div class="att-box"><div class="att-num" style="color:#16a34a">{{ $attendance['present'] }}</div><div class="att-lbl">Present</div></div>
    <div class="att-box"><div class="att-num" style="color:#dc2626">{{ $attendance['absent'] }}</div><div class="att-lbl">Absent</div></div>
    <div class="att-box"><div class="att-num" style="color:#d97706">{{ $attendance['late'] }}</div><div class="att-lbl">Late</div></div>
    <div class="att-box"><div class="att-num" style="color:#2563eb">{{ $attendance['percentage'] }}%</div><div class="att-lbl">Attendance Rate</div></div>
</div>

<div class="remarks-row" style="margin-top:8px">
    <span class="remarks-label">Class Teacher's Remark</span>
    <div style="border:1px solid #e5e7eb;border-radius:4px;padding:8px;min-height:32px;font-size:10px;color:#374151">
        {{ $classTeacherRemark ?: '...........................................................................................................................' }}
    </div>
</div>

<div class="remarks-row">
    <span class="remarks-label">Principal's Remark</span>
    <div style="border:1px solid #e5e7eb;border-radius:4px;padding:8px;min-height:32px;font-size:10px;color:#374151">
        ...................................................................................................................................................
    </div>
</div>

<div class="sig-row">
    <div class="sig-block"><div class="sig-line"></div><div class="sig-name">Class Teacher's Signature &amp; Date</div></div>
    <div class="sig-block"><div class="sig-line"></div><div class="sig-name">Principal's Signature &amp; Date</div></div>
    <div class="sig-block"><div class="sig-line"></div><div class="sig-name">Parent/Guardian's Signature &amp; Date</div></div>
</div>

<div class="legend">
    <strong style="font-size:9px;color:#374151">Key: </strong>
    @foreach(config('school.rubric_levels') as $code => $info)
    <div class="legend-item"><span class="badge {{ $code }}">{{ $code }}</span> {{ $info['label'] }}</div>
    @endforeach
</div>

<div class="footer">
    Generated on {{ now()->format('d M Y') }} &nbsp;|&nbsp; {{ config('school.name') }} School Management System
    <br>This is a computer-generated report. No signature required if generated from the school portal.
</div>
</body>
</html>
