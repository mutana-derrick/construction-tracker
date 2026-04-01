<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Report - {{ $project->name }}</title>
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
        <p><strong>Monthly Report</strong> - {{ $monthName }} {{ $year }}</p>
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

    @if(count($equipmentSummary) > 0)
    <div class="section">
        <div class="section-title">Equipment Summary by Type</div>
        <table>
            <thead>
                <tr>
                    <th>Equipment Type</th>
                    <th>Total Units</th>
                    <th>Total Cost</th>
                    <th>Average Cost Per Unit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($equipmentSummary as $item)
                <tr>
                    <td>{{ $item['equipment'] }}</td>
                    <td>{{ number_format($item['totalUnits'], 2) }}</td>
                    <td>Php {{ number_format($item['totalCost'], 2) }}</td>
                    <td>Php {{ number_format($item['avgCost'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td>{{ number_format(collect($equipmentSummary)->sum('totalUnits'), 2) }}</td>
                    <td>Php {{ number_format(collect($equipmentSummary)->sum('totalCost'), 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    @if(count($labourSummary) > 0)
    <div class="section">
        <div class="section-title">Labour Summary by Classification</div>
        <table>
            <thead>
                <tr>
                    <th>Labour Classification</th>
                    <th>Total Workers</th>
                    <th>Total Cost</th>
                    <th>Average Wage Per Worker</th>
                </tr>
            </thead>
            <tbody>
                @foreach($labourSummary as $item)
                <tr>
                    <td>{{ $item['classification'] }}</td>
                    <td>{{ number_format($item['totalWorkers'], 0) }}</td>
                    <td>Php {{ number_format($item['totalCost'], 2) }}</td>
                    <td>Php {{ number_format($item['avgWage'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td>{{ number_format(collect($labourSummary)->sum('totalWorkers'), 0) }}</td>
                    <td>Php {{ number_format(collect($labourSummary)->sum('totalCost'), 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    @if(count($materialSummary) > 0)
    <div class="section">
        <div class="section-title">Material Summary by Type</div>
        <table>
            <thead>
                <tr>
                    <th>Material</th>
                    <th>Total Quantity Used</th>
                    <th>Total Cost</th>
                    <th>Average Cost Per Item</th>
                </tr>
            </thead>
            <tbody>
                @foreach($materialSummary as $item)
                <tr>
                    <td>{{ $item['material'] }}</td>
                    <td>{{ number_format($item['totalQty'], 2) }}</td>
                    <td>Php {{ number_format($item['totalCost'], 2) }}</td>
                    <td>Php {{ number_format($item['avgCost'], 2) }}</td>
                </tr>
                @endforeach
                <tr class="total-row">
                    <td>TOTAL</td>
                    <td>{{ number_format(collect($materialSummary)->sum('totalQty'), 2) }}</td>
                    <td>Php {{ number_format(collect($materialSummary)->sum('totalCost'), 2) }}</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Monthly Statistics</div>
        <table>
            <thead>
                <tr>
                    <th>Metric</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Equipment Cost Total</td>
                    <td>Php {{ number_format($equipmentCostTotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Labour Cost Total</td>
                    <td>Php {{ number_format($labourCostTotal, 2) }}</td>
                </tr>
                <tr>
                    <td>Material Cost Total</td>
                    <td>Php {{ number_format($materialCostTotal, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>TOTAL MONTHLY COST</td>
                    <td>Php {{ number_format($totalCost, 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer" style="text-align: center; margin-top: 40px; padding-top: 20px; border-top: 1px solid #ddd; font-size: 11px; color: #666;">
        <p>This is an automatically generated report for {{ $monthName }} {{ $year }}.</p>
        <p>For any discrepancies, please verify with the original entries.</p>
    </div>
</body>
</html>
