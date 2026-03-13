<?php

namespace App\Http\Controllers;

use App\DTOs\WaiverSearchFilters;
use App\Http\Requests\AdminLoginRequest;
use App\Http\Requests\StoreWaiverTextRequest;
use App\Models\Waiver;
use App\Models\WaiverText;
use App\Repositories\Contracts\WaiverRepositoryInterface;
use App\Services\AdminAuthService;
use App\Services\PdfService;
use App\Services\WaiverService;
use App\Services\WaiverTextService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AdminController extends Controller
{
    public function __construct(
        private AdminAuthService $authService,
        private WaiverRepositoryInterface $waiverRepository,
        private PdfService $pdfService,
        private WaiverService $waiverService,
        private WaiverTextService $waiverTextService,
    ) {}

    public function loginForm(): View
    {
        return view('admin.login');
    }

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        if ($this->authService->attempt($request->email, $request->password)) {
            $request->session()->put('admin_authenticated', true);

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('admin_authenticated');

        return redirect()->route('admin.login');
    }

    public function dashboard(Request $request): View
    {
        $filters = WaiverSearchFilters::fromRequest($request);
        $waivers = $this->waiverRepository->search($filters);

        return view('admin.dashboard', compact('waivers'));
    }

    public function show(Waiver $waiver): View
    {
        return view('admin.waiver-detail', compact('waiver'));
    }

    public function downloadPdf(Waiver $waiver): BinaryFileResponse
    {
        $pdfPath = $this->pdfService->ensurePdfExists($waiver);

        return response()->download($pdfPath, "waiver-{$waiver->id}.pdf");
    }

    public function resendEmail(Waiver $waiver): RedirectResponse
    {
        $success = $this->waiverService->resendConfirmationEmail($waiver);

        if ($success) {
            return back()->with('success', 'Confirmation email resent successfully.');
        }

        return back()->with('error', 'Failed to resend confirmation email. Check the logs for details.');
    }

    // Waiver Text Management
    public function waiverTexts(): View
    {
        $waiverTexts = $this->waiverTextService->all();

        return view('admin.waiver-texts.index', compact('waiverTexts'));
    }

    public function createWaiverText(): View
    {
        return view('admin.waiver-texts.form');
    }

    public function storeWaiverText(StoreWaiverTextRequest $request): RedirectResponse
    {
        $this->waiverTextService->create(
            $request->validated('version'),
            $request->validated('content'),
        );

        return redirect()->route('admin.waiver-texts.index')->with('success', 'Waiver text created successfully.');
    }

    public function editWaiverText(WaiverText $waiverText): View
    {
        return view('admin.waiver-texts.form', compact('waiverText'));
    }

    public function updateWaiverText(StoreWaiverTextRequest $request, WaiverText $waiverText): RedirectResponse
    {
        $this->waiverTextService->update(
            $waiverText,
            $request->validated('version'),
            $request->validated('content'),
        );

        return redirect()->route('admin.waiver-texts.index')->with('success', 'Waiver text updated successfully.');
    }

    public function activateWaiverText(WaiverText $waiverText): RedirectResponse
    {
        $this->waiverTextService->setActive($waiverText);

        return back()->with('success', "Waiver text v{$waiverText->version} is now active.");
    }
}
