<?php

namespace App\Services;

use App\DTOs\WaiverData;
use App\Mail\WaiverConfirmation;
use App\Models\Waiver;
use App\Repositories\Contracts\WaiverRepositoryInterface;
use App\Repositories\Contracts\WaiverTextRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class WaiverService
{
    public function __construct(
        private WaiverRepositoryInterface $waiverRepository,
        private WaiverTextRepositoryInterface $waiverTextRepository,
        private PdfService $pdfService,
    ) {}

    public function submitWaiver(WaiverData $data): Waiver
    {
        $activeWaiverText = $this->waiverTextRepository->getActive();

        $waiver = $this->waiverRepository->create([
            ...$data->toArray(),
            'waiver_version' => $activeWaiverText !== null ? $activeWaiverText->version : config('waiver.version', '1.0'),
            'waiver_text_id' => $activeWaiverText?->id,
            'download_token' => Str::random(64),
        ]);

        $this->pdfService->generateForWaiver($waiver);

        $this->sendConfirmationEmail($waiver);

        return $waiver;
    }

    public function getByDownloadToken(string $token): Waiver
    {
        return $this->waiverRepository->findByDownloadToken($token);
    }

    public function downloadPdf(Waiver $waiver): string
    {
        return $this->pdfService->ensurePdfExists($waiver);
    }

    public function resendConfirmationEmail(Waiver $waiver): bool
    {
        $this->pdfService->ensurePdfExists($waiver);

        try {
            Mail::to($waiver->email)->send(new WaiverConfirmation($waiver));
            $this->waiverRepository->update($waiver, ['email_sent_at' => now()]);

            Log::info('Waiver confirmation email resent', [
                'waiver_id' => $waiver->id,
                'email' => $waiver->email,
            ]);

            return true;
        } catch (TransportExceptionInterface $e) {
            Log::error('Failed to resend waiver confirmation email', [
                'waiver_id' => $waiver->id,
                'email' => $waiver->email,
                'error' => $e->getMessage(),
            ]);

            report($e);

            return false;
        }
    }

    private function sendConfirmationEmail(Waiver $waiver): void
    {
        try {
            Mail::to($waiver->email)->send(new WaiverConfirmation($waiver));
            $this->waiverRepository->update($waiver, ['email_sent_at' => now()]);

            Log::info('Waiver confirmation email sent', [
                'waiver_id' => $waiver->id,
                'email' => $waiver->email,
            ]);
        } catch (TransportExceptionInterface $e) {
            Log::error('Failed to send waiver confirmation email', [
                'waiver_id' => $waiver->id,
                'email' => $waiver->email,
                'error' => $e->getMessage(),
            ]);

            report($e);
        }
    }
}
