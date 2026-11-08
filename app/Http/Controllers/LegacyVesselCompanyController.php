<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Vessel;
use App\Models\VesselType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LegacyVesselCompanyController extends Controller
{
    /**
     * Tampilkan Halaman Daftar Perusahaan Pemilik Kapal (Vessel Company Data)
     * 1:1 format legacy kapal/views/company.php.
     */
    public function indexCompanies(Request $request): View
    {
        $companies = Company::orderBy('name', 'asc')->get();

        return view('legacy-admin.vessel-company-index', compact('companies'));
    }

    /**
     * Tampilkan Form Tambah Perusahaan Pemilik Kapal (Form Add New Company)
     * 1:1 format legacy kapal/views/form_company.php.
     */
    public function createCompany(): View
    {
        $company = new Company();
        $isEdit = false;

        return view('legacy-admin.vessel-company-form', compact('company', 'isEdit'));
    }

    /**
     * Tampilkan Form Edit Perusahaan Pemilik Kapal
     */
    public function editCompany(Company $company): View
    {
        $isEdit = true;

        return view('legacy-admin.vessel-company-form', compact('company', 'isEdit'));
    }

    /**
     * Simpan Perusahaan Pemilik Kapal Baru
     */
    public function storeCompany(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'pic' => 'nullable|string|max:255',
        ]);

        $validated['type'] = 'owner';

        Company::create($validated);

        return redirect()->route('companies.legacy.index')
            ->with('msg', '<div class="alert alert-success alert-dismissible fade show" role="alert">Data perusahaan kapal berhasil ditambahkan.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }

    /**
     * Perbarui Perusahaan Pemilik Kapal
     */
    public function updateCompany(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'pic' => 'nullable|string|max:255',
        ]);

        $company->update($validated);

        return redirect()->route('companies.legacy.index')
            ->with('msg', '<div class="alert alert-success alert-dismissible fade show" role="alert">Data perusahaan kapal berhasil diperbarui.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }

    /**
     * Hapus Perusahaan Pemilik Kapal
     */
    public function deleteCompany(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('companies.legacy.index')
            ->with('msg', '<div class="alert alert-success alert-dismissible fade show" role="alert">Data perusahaan berhasil dihapus.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }

    /**
     * Tampilkan Halaman Daftar Kapal Berdasarkan Perusahaan (Vessel Data)
     * 1:1 format legacy kapal/views/data.php (e.g. kapal/index/8.html).
     */
    public function companyVessels(Request $request, $company = null): View
    {
        if (is_string($company)) {
            $cleanId = (int) str_replace('.html', '', $company);
            $company = Company::find($cleanId);
        } elseif (is_numeric($company)) {
            $company = Company::find((int) $company);
        }

        $query = Vessel::with(['vesselType', 'ownerCompany']);

        if ($company && $company->id) {
            $query->where('owner_company_id', $company->id);
        }

        $vessels = $query->orderBy('name', 'asc')->get();

        return view('legacy-admin.vessel-data-index', compact('vessels', 'company'));
    }

    /**
     * Tampilkan Form Penambahan Kapal Baru (Vessel Management Form)
     * 1:1 format legacy kapal/views/form.php.
     */
    public function createVessel(Request $request): View
    {
        $companyId = $request->query('company_id');
        $selectedCompany = $companyId ? Company::find($companyId) : null;

        $vessel = new Vessel();
        $vesselTypes = VesselType::orderBy('name', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();
        $isEdit = false;

        return view('legacy-admin.vessel-form', compact(
            'vessel',
            'vesselTypes',
            'companies',
            'selectedCompany',
            'isEdit'
        ));
    }

    /**
     * Tampilkan Form Edit Kapal
     */
    public function editVessel(Vessel $vessel): View
    {
        $vesselTypes = VesselType::orderBy('name', 'asc')->get();
        $companies = Company::orderBy('name', 'asc')->get();
        $selectedCompany = $vessel->ownerCompany;
        $isEdit = true;

        return view('legacy-admin.vessel-form', compact(
            'vessel',
            'vesselTypes',
            'companies',
            'selectedCompany',
            'isEdit'
        ));
    }

    /**
     * Simpan Data Kapal Baru
     */
    public function storeVessel(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vessel_type_id' => 'nullable|integer',
            'owner_company_id' => 'nullable|integer',
            'year_built' => 'nullable|string|max:50',
            'tsi' => 'nullable',
            'grt' => 'nullable',
        ]);

        // Bersihkan formatting angka tsi & grt
        if (isset($validated['tsi'])) {
            $validated['tsi'] = (float) str_replace([',', ' '], '', $validated['tsi']);
        }
        if (isset($validated['grt'])) {
            $validated['grt'] = (float) str_replace([',', ' '], '', $validated['grt']);
        }

        $vessel = Vessel::create($validated);

        $redirectUrl = $vessel->owner_company_id 
            ? route('companies.legacy.vessels', $vessel->owner_company_id) 
            : route('companies.legacy.index');

        return redirect($redirectUrl)
            ->with('msg', '<div class="alert alert-success alert-dismissible fade show" role="alert">Data kapal berhasil ditambahkan.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }

    /**
     * Perbarui Data Kapal
     */
    public function updateVessel(Request $request, Vessel $vessel): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vessel_type_id' => 'nullable|integer',
            'owner_company_id' => 'nullable|integer',
            'year_built' => 'nullable|string|max:50',
            'tsi' => 'nullable',
            'grt' => 'nullable',
        ]);

        if (isset($validated['tsi'])) {
            $validated['tsi'] = (float) str_replace([',', ' '], '', $validated['tsi']);
        }
        if (isset($validated['grt'])) {
            $validated['grt'] = (float) str_replace([',', ' '], '', $validated['grt']);
        }

        $vessel->update($validated);

        $redirectUrl = $vessel->owner_company_id 
            ? route('companies.legacy.vessels', $vessel->owner_company_id) 
            : route('companies.legacy.index');

        return redirect($redirectUrl)
            ->with('msg', '<div class="alert alert-success alert-dismissible fade show" role="alert">Data kapal berhasil diperbarui.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }

    /**
     * Hapus Data Kapal
     */
    public function deleteVessel(Vessel $vessel): RedirectResponse
    {
        $companyId = $vessel->owner_company_id;
        $vessel->delete();

        $redirectUrl = $companyId 
            ? route('companies.legacy.vessels', $companyId) 
            : route('companies.legacy.index');

        return redirect($redirectUrl)
            ->with('msg', '<div class="alert alert-success alert-dismissible fade show" role="alert">Data kapal berhasil dihapus.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
    }
}
