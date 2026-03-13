<?php

namespace App\Http\Controllers;

use App\DTOs\WaiverData;
use App\Enums\EmergencyContactRelationship;
use App\Http\Requests\StoreWaiverRequest;
use App\Services\WaiverService;
use App\Services\WaiverTextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class WaiverController extends Controller
{
    public function __construct(
        private WaiverService $waiverService,
        private WaiverTextService $waiverTextService,
    ) {}

    public function landing(): View
    {
        return view('landing');
    }

    public function create(): View
    {
        $relationships = EmergencyContactRelationship::cases();
        $activeWaiverText = $this->waiverTextService->getActive();

        return view('waiver.form', compact('relationships', 'activeWaiverText'));
    }

    public function store(StoreWaiverRequest $request): RedirectResponse
    {
        $data = WaiverData::fromRequest($request);

        $waiver = $this->waiverService->submitWaiver($data);

        return redirect()->route('waiver.confirm', [
            'token' => $waiver->download_token,
        ]);
    }

    public function confirm(Request $request): View
    {
        $token = $request->query('token');
        abort_unless(is_string($token) && $token !== '', Response::HTTP_NOT_FOUND);
        $waiver = $this->waiverService->getByDownloadToken($token);

        return view('waiver.confirm', compact('waiver'));
    }

    public function downloadPdf(Request $request): BinaryFileResponse
    {
        $token = $request->query('token');
        abort_unless(is_string($token) && $token !== '', Response::HTTP_NOT_FOUND);
        $waiver = $this->waiverService->getByDownloadToken($token);

        $pdfPath = $this->waiverService->downloadPdf($waiver);

        return response()->download($pdfPath, "waiver-{$waiver->id}.pdf");
    }
}
