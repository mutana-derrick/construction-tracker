<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Daily Report - {{ $project->name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #1F4E78;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #1F4E78;
        }
        .header p {
            margin: 3px 0;
            font-size: 13px;
            color: #666;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            background-color: #1F4E78;
            color: white;
            padding: 10px 15px;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            font-size: 12px;
        }
        table th {
            background-color: #EAF06A;
            color: #1F4E78;
            padding: 10px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        table td {
            padding: 8px 10px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .total-row {
            background-color: #EAF06A;
            font-weight: bold;
        }
        .summary-box {
            background-color: #f0f0f0;
            border-left: 4px solid #1F4E78;
            padding: 15px;
            margin-bottom: 15px;
            display: inline-block;
            margin-right: 20px;
            min-width: 150px;
        }
        .summary-box-title {
            color: #1F4E78;
            font-weight: bold;
            font-size: 11px;
        }
        .summary-box-value {
            color: #333;
            font-size: 18px;
            font-weight: bold;
            margin-top: 5px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $project->name }}</h1>
        <p><strong>Daily Report</strong> - {{ date('F j, Y', strtotime($date)) }}</p>
        <p>Report Generated: {{ date('M d, Y H:i:s') }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="section">
        <div class="summary-box">
            <div class="summary-box-title">Equipment Cost</div>
            <div class="summary-box-value">Php {{ number_format($equipmentCostTotal, 2) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-box-title">Labour Cost</div>
            <div class="summary-box-value">Php {{ number_format($labourCostTotal, 2) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-box-title">Material Cost</div>
            <div class="summary-box-value">Php {{ number_format($materialCostTotal, 2) }}</div>
        </div>
        <div class="summary-box">
            <div class="summary-box-title">TOTAL COST</div>
            <div class="summary-box-value">Php {{ number_format($totalCost, 2) }}</div>
        </div>
    </div>

    @if($equipmentLogs->count() > 0)
    <div class="section">
        <div class="section-title">Equipment Logs</div>
        <table>
            <thead>
                <tr>
                    <th>Equipment Type</th>
                    <th>Activity</th>
                    <th>Hours Done</th>
                    <th>Output</th>
                    <th>Comment</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipmentLogs as $log)
                <tr>
                    <td>{{ $log->equipment_type }}</td>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->hours_done }}</td>
                    <td>{{ $log->output }}</td>
                    <td>{{ $log->comment ?? '—' }}</td>
                    <td>{{ $log->user->name ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($equipmentCosts->count() > 0)
    <div class="section">
        <div class="section-title">Equipment Costs</div>
        <table>
            <thead>
                <tr>
                    <th>Equipment Type</th>
                    <th>Activity</th>
                    <th>Units Done</th>
                    <th>Cost Per Unit</th>
                    <th>Total Cost</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipmentCosts as $cost)
                <tr>
                    <td>{{ $cost->equipment_type }}</td>
                    <td>{{ $cost->activity }}</td>
                    <td>{{ number_format($cost->units_done, 2) }}</td>
                    <td>Php {{ number_format($cost->cost_per_unit, 2) }}</td>
                    <td>Php {{ number_format($cost->total_cost, 2) }}</td>
                    <td>{{ $cost->user->name ?? '—' }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4">TOTAL</td>
                    <td>Php {{ number_format($equipmentCostTotal, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    @if($productivityLogs->count() > 0)
    <div class="section">
        <div class="section-title">Productivity Logs</div>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Equipment</th>
                    <th>Workers</th>
                    <th>Output</th>
                    <th>Output Per Worker</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productivityLogs as $log)
                <tr>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->equipment }}</td>
                    <td>{{ $log->workers }}</td>
                    <td>{{ $log->output }}</td>
                    <td>{{ number_format($log->output_per_worker, 2) }}</td>
                    <td>{{ $log->user->name ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($labourLogs->count() > 0)
    <div class="section">
        <div class="section-title">Labour Logs</div>
        <table>
            <thead>
                <tr>
                    <th>Activity</th>
                    <th>Classification</th>
                    <th>Workers</th>
                    <th>Wage Per Worker</th>
                    <th>Total Cost</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labourLogs as $log)
                <tr>
                    <td>{{ $log->activity }}</td>
                    <td>{{ $log->labour_classification }}</td>
                    <td>{{ $log->number_of_workers }}</td>
                    <td>Php {{ number_format($log->wage_per_worker, 2) }}</td>
                    <td>Php {{ number_format($log->total_cost, 2) }}</td>
                    <td>{{ $log->user->name ?? '—' }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="4">TOTAL</td>
                    <td>Php {{ number_format($labourCostTotal, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    @if($materialUsage->count() > 0)
    <div class="section">
        <div class="section-title">Material Usage</div>
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Activity</th>
                    <th>Planned Qty</th>
                    <th>Used Qty</th>
                    <th>Difference</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materialUsage as $usage)
                <tr>
                    <td>{{ $usage->material_name }}</td>
                    <td>{{ $usage->activity }}</td>
                    <td>{{ number_format($usage->planned_qty, 2) }}</td>
                    <td>{{ number_format($usage->used_qty, 2) }}</td>
                    <td>
                        @if($usage->difference > 0)
                            <span style="color: #28a745;">+{{ number_format($usage->difference, 2) }}</span>
                        @elseif($usage->difference < 0)
                            <span style="color: #dc3545;">{{ number_format($usage->difference, 2) }}</span>
                        @else
                            <span style="color: #17a2b8;">{{ number_format($usage->difference, 2) }}</span>
                        @endif
                    </td>
                    <td>{{ $usage->user->name ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($materialCosts->count() > 0)
    <div class="section">
        <div class="section-title">Material Costs</div>
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Quantity Used</th>
                    <th>Cost Per Item</th>
                    <th>Total Cost</th>
                    <th>Recorded By</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materialCosts as $cost)
                <tr>
                    <td>{{ $cost->material_name }}</td>
                    <td>{{ number_format($cost->used_qty, 2) }}</td>
                    <td>Php {{ number_format($cost->cost_per_item, 2) }}</td>
                    <td>Php {{ number_format($cost->total, 2) }}</td>
                    <td>{{ $cost->user->name ?? '—' }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td colspan="3">TOTAL</td>
                    <td>Php {{ number_format($materialCostTotal, 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="footer" style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 11px; color: #666;">
        <p>This is an automatically generated report. For any discrepancies, please verify with the original entries.</p>
    </div>
</body>
</html>
