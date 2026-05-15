<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Report - {{ $project->name }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; margin: 24px; color: #1F2937; font-size: 12px; }
        .header { border-bottom: 3px solid #E1AD01; padding-bottom: 14px; margin-bottom: 22px; }
        .brand { font-size: 18px; font-weight: bold; color: #1F2937; }
        .subtitle { font-size: 11px; color: #6B7280; margin-top: 3px; }
        .report-title { margin-top: 16px; font-size: 22px; font-weight: bold; color: #1F2937; }
        .meta { width: 100%; margin-top: 12px; border-collapse: collapse; }
        .meta td { padding: 6px 8px; border: 1px solid #E5E7EB; }
        .meta .label { background: #F8FAFC; font-weight: bold; width: 25%; }
        .summary { margin: 20px 0; width: 100%; border-collapse: collapse; }
        .summary td { border: 1px solid #E5E7EB; padding: 10px; }
        .summary .label { background: #F8FAFC; font-weight: bold; }
        .summary .grand { background: #FFEA9D; font-weight: bold; }
        .section-title { background: #1F2937; color: white; padding: 10px; font-weight: bold; margin-top: 18px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 0; }
        table.data th { background: #FFEA9D; color: #1F2937; padding: 9px; border: 1px solid #E5E7EB; text-align: left; }
        table.data td { padding: 8px 9px; border: 1px solid #E5E7EB; }
        .text-right { text-align: right; }
        .total-row { background: #F8FAFC; font-weight: bold; }
        .empty { padding: 18px; border: 1px solid #E5E7EB; color: #6B7280; }
        .footer { margin-top: 40px; border-top: 1px solid #E5E7EB; padding-top: 12px; font-size: 10px; color: #6B7280; }
        .signatures { width: 100%; margin-top: 45px; }
        .signatures td { width: 50%; padding-top: 35px; border-top: 1px solid #9CA3AF; text-align: center; font-size: 11px; }
    </style>
</head>
<body>
    <div class="header">
        <div class="brand">Construction Productivity Tracking System</div>
        <div class="subtitle">Professional Site Cost and Productivity Report</div>

        <div class="report-title">Daily Site Report</div>

        <table class="meta">
            <tr>
                <td class="label">Project</td>
                <td>{{ $project->name }}</td>
                <td class="label">Location</td>
                <td>{{ $project->location ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Report Date</td>
                <td>{{ date('F j, Y', strtotime($date)) }}</td>
                <td class="label">Generated At</td>
                <td>{{ now()->format('F j, Y g:i A') }}</td>
            </tr>
        </table>
    </div>

    <table class="summary">
        <tr>
            <td class="label">Total Equipment Cost</td>
            <td class="text-right">Rwf {{ number_format($equipmentCostTotal, 0) }}</td>
            <td class="label">Total Labour Cost</td>
            <td class="text-right">Rwf {{ number_format($labourCostTotal, 0) }}</td>
        </tr>
        <tr>
            <td class="label">Total Material Cost</td>
            <td class="text-right">Rwf {{ number_format($materialCostTotal, 0) }}</td>
            <td class="grand">Grand Total</td>
            <td class="grand text-right">Rwf {{ number_format($totalCost, 0) }}</td>
        </tr>
    </table>

    <div class="section-title">Activity Cost Summary</div>

    @if($activities->count() > 0)
        <table class="data">
            <thead>
                <tr>
                    <th>Activity</th>
                    <th class="text-right">Equipment Cost</th>
                    <th class="text-right">Labour Cost</th>
                    <th class="text-right">Material Cost</th>
                    <th class="text-right">Total Cost</th>
                </tr>
            </thead>
            <tbody>
                @foreach($activities as $activity)
                    <tr>
                        <td>{{ $activity['activity'] }}</td>
                        <td class="text-right">Rwf {{ number_format($activity['equipment_cost'], 0) }}</td>
                        <td class="text-right">Rwf {{ number_format($activity['labour_cost'], 0) }}</td>
                        <td class="text-right">Rwf {{ number_format($activity['material_cost'], 0) }}</td>
                        <td class="text-right">Rwf {{ number_format($activity['total_cost'], 0) }}</td>
                    </tr>
                @endforeach
                <tr class="total-row">
                    <td>Total</td>
                    <td class="text-right">Rwf {{ number_format($equipmentCostTotal, 0) }}</td>
                    <td class="text-right">Rwf {{ number_format($labourCostTotal, 0) }}</td>
                    <td class="text-right">Rwf {{ number_format($materialCostTotal, 0) }}</td>
                    <td class="text-right">Rwf {{ number_format($totalCost, 0) }}</td>
                </tr>
            </tbody>
        </table>
    @else
        <div class="empty">No cost records found for this project on the selected date.</div>
    @endif

    <table class="signatures">
        <tr>
            <td>Prepared By</td>
            <td>Checked / Approved By</td>
        </tr>
    </table>

    <div class="footer">
        This report was generated automatically from system records.
    </div>
</body>
</html>