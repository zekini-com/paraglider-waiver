<?php

namespace App\Mail;

use App\Models\Waiver;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WaiverConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Waiver $waiver) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Liability Waiver Confirmation - '.config('waiver.company_name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.waiver-confirmation',
        );
    }

    /** @return array<int, Attachment> */
    public function attachments(): array
    {
        if ($this->waiver->pdf_path) {
            $pdfPath = storage_path('app/'.$this->waiver->pdf_path);
            if (file_exists($pdfPath)) {
                return [
                    Attachment::fromPath($pdfPath)
                        ->as("liability-waiver-{$this->waiver->id}.pdf")
                        ->withMime('application/pdf'),
                ];
            }
        }

        return [];
    }
}
