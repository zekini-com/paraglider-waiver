<?php

namespace App\Services;

use App\Models\Waiver;
use App\Repositories\Contracts\WaiverRepositoryInterface;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfService
{
    public function __construct(
        private WaiverRepositoryInterface $waiverRepository,
    ) {}

    public function generateForWaiver(Waiver $waiver): string
    {
        $pdf = Pdf::loadView('waiver.pdf', compact('waiver'));

        $directory = 'waivers/pdfs';
        $path = $directory."/{$waiver->id}.pdf";

        $fullDir = storage_path('app/'.$directory);
        if (! is_dir($fullDir)) {
            mkdir($fullDir, 0755, true);
        }

        $pdf->save(storage_path('app/'.$path));

        $this->waiverRepository->update($waiver, ['pdf_path' => $path]);

        return $path;
    }

    public function ensurePdfExists(Waiver $waiver): string
    {
        $pdfPath = storage_path('app/'.$waiver->pdf_path);

        if (! $waiver->pdf_path || ! file_exists($pdfPath)) {
            $this->generateForWaiver($waiver);
            $waiver->refresh();
        }

        return storage_path('app/'.$waiver->pdf_path);
    }
}
