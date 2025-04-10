<?php

namespace App\Http\Controllers;

use FPDF;
use Illuminate\Http\Request;
use App\Models\Device;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function generateReport()
    {
        // Create new PDF document
        $pdf = new FPDF();
        $pdf->AddPage();
        
        // Set theme colors
        $primaryColor = [34, 197, 94]; // Emerald color
        $textColor = [15, 23, 42]; // Slate-900
        $lightColor = [241, 245, 249]; // Slate-100
        
        // Add header with background
        $pdf->SetFillColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Rect(0, 0, 210, 15, 'F'); // Keep header height at 15mm
        
        // Add logo
        $logoPath = public_path('assets/img/logo/logo-white.png');
        if (file_exists($logoPath)) {
            // Position the logo in the center-left of the header with margins
            $pdf->Image($logoPath, 10, 1, 60, 12); // Increased logo size to 60mm width and adjusted vertical position
        }
        
        // Add title and subtitle below header
        $pdf->SetY(30); // Position below header
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->SetFont('Arial', 'B', 20);
        $pdf->Cell(0, 10, 'WiFi from Waste', 0, 1, 'C');
        
        $pdf->SetFont('Arial', '', 16);
        $pdf->Cell(0, 10, 'System Report', 0, 1, 'C');
        
        // Add timestamp
        $pdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetY(60); // Position below title
        $pdf->Cell(0, 10, 'Generated on: ' . Carbon::now()->format('Y-m-d H:i:s'), 0, 1, 'R');
        $pdf->Ln(5);
        
        // Statistics Section
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(0, 10, 'Collection Statistics', 0, 1, 'L');
        
        // Get statistics from the view
        $plasticTotal = request('plastic_total', 0);
        $canTotal = request('can_total', 0);
        $routerUsage = request('router_usage', 0);
        $estimatedRevenue = request('estimated_revenue', 0);
        
        // Create statistics table
        $pdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        $pdf->SetFont('Arial', 'B', 12);
        
        // Table header
        $pdf->SetFillColor($lightColor[0], $lightColor[1], $lightColor[2]);
        $pdf->Cell(60, 10, 'Metric', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Value', 1, 0, 'C', true);
        $pdf->Cell(60, 10, 'Details', 1, 1, 'C', true);
        
        // Table data
        $pdf->SetFont('Arial', '', 11);
        $pdf->Cell(60, 10, 'Total Plastic Bottles', 1);
        $pdf->Cell(60, 10, $plasticTotal, 1, 0, 'C');
        $pdf->Cell(60, 10, 'Collected Items', 1, 1);
        
        $pdf->Cell(60, 10, 'Total Cans', 1);
        $pdf->Cell(60, 10, $canTotal, 1, 0, 'C');
        $pdf->Cell(60, 10, 'Collected Items', 1, 1);
        
        $pdf->Cell(60, 10, 'Router Usage', 1);
        $pdf->Cell(60, 10, $routerUsage, 1, 0, 'C');
        $pdf->Cell(60, 10, 'Data Transferred', 1, 1);
        
        $pdf->Cell(60, 10, 'Estimated Revenue', 1);
        $pdf->Cell(60, 10, 'PHP ' . number_format((float)str_replace(',', '', $estimatedRevenue), 2), 1, 0, 'C');
        $pdf->Cell(60, 10, 'Total Earnings', 1, 1);
        
        $pdf->Ln(10);
        
        // Devices Section
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(0, 10, 'Connected Devices', 0, 1, 'L');
        
        // Table header
        $pdf->SetTextColor($textColor[0], $textColor[1], $textColor[2]);
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->SetFillColor($lightColor[0], $lightColor[1], $lightColor[2]);
        
        $pdf->Cell(40, 10, 'Name', 1, 0, 'C', true);
        $pdf->Cell(50, 10, 'MAC Address', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Status', 1, 0, 'C', true);
        $pdf->Cell(40, 10, 'Bandwidth', 1, 0, 'C', true);
        $pdf->Cell(30, 10, 'Last Seen', 1, 1, 'C', true);
        
        // Table data
        $pdf->SetFont('Arial', '', 10);
        $devices = Device::all();
        
        foreach ($devices as $device) {
            $pdf->Cell(40, 10, $device->name, 1);
            $pdf->Cell(50, 10, $device->mac_address, 1);
            $pdf->Cell(30, 10, $device->status, 1);
            $pdf->Cell(40, 10, $device->bandwidth_used, 1);
            $pdf->Cell(30, 10, $device->last_seen ? Carbon::parse($device->last_seen)->diffForHumans() : 'Never', 1, 1);
        }
        
        // Footer
        $pdf->SetY(-30); // Increased space for footer
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        
        // Add footer line
        $pdf->SetDrawColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Line(20, $pdf->GetY() - 5, 190, $pdf->GetY() - 5);
        
        // Add footer message
        $pdf->SetY(-25);
        $pdf->SetFont('Arial', 'I', 10);
        $pdf->SetTextColor($primaryColor[0], $primaryColor[1], $primaryColor[2]);
        $pdf->Cell(0, 10, 'WiFi from Waste - Transforming Waste into Connectivity', 0, 1, 'C');
        
        // Add page number
        $pdf->SetY(-15);
        $pdf->SetFont('Arial', 'I', 8);
        $pdf->SetTextColor(100, 100, 100);
        $pdf->Cell(0, 10, 'Page ' . $pdf->PageNo() . '/{nb}', 0, 0, 'C');
        
        // Output the PDF
        return response($pdf->Output('S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="wifi-from-waste-report.pdf"');
    }
} 