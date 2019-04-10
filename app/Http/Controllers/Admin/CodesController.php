<?php

namespace App\Http\Controllers\Admin;

use App\Apis\Codes;
use App\Apis\Packages;
use App\Apis\UsersApi;
use App\Exports\CodesExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;

class CodesController extends Controller
{
    private $codes;
    private $packages, $usersApi, $excel;

    function __construct(Codes $codes, Packages $packages, UsersApi $usersApi, Excel $excel)
    {
        $this->middleware('auth');
        $this->codes = $codes;
        $this->packages = $packages;
        $this->usersApi = $usersApi;
        $this->excel = $excel;
    }

    /**
     * Index page
     * @return \Illuminate\View\View
     * @param Request $request
     *
     */
    public function index(Request $request)
    {
        $codes = $this->codes->codePaginationPages($request);

        return view('admin.codes.index', compact("codes"));
    }

////generate code view
    public function generateCodeView()
    {
        $getPackages = $this->packages->getAllPackages();
        $packages = $this->createPackageList($getPackages);
        $users = $this->usersApi->createAllPanelUsersList();
        return view("admin.codes.create", compact("packages", "users"));
    }

////to store generate code
    public function generateCode(Request $requestedCode)
    {
        $this->validate($requestedCode,
            [
                "count" => "required|numeric",
                "packageId" => "required|numeric",
                "panelUserId" => "required",
                "userName" => "required|max:250",
            ]);
        $response = $this->codes->generateCode($requestedCode);
        return $this->codes->redirectWithMessage($response, "admin/codes", "Code Generated Successfully");
    }

    private function createPackageList($packages)
    {
        $createPackagesList = array();
        if (is_array($packages) && !empty($packages)) {
            foreach ($packages as $package)
                $createPackagesList[$package->id] = $package->name;
        }
        return $createPackagesList;
    }

    public function getActivateHMA($code, $api)
    {

        return view('admin.codes.activateCode', compact("code", "api"));
    }

    public function activateHMA(Request $request)
    {
        $this->validate($request,
            [
                "code" => "required",
                "mac" => "required",
                "api" => "required"
            ]);
        $response = $this->codes->activeCode($request->code, $request->mac, $request->api);

        return redirect('admin/codes')->withMessage($response->value);
    }

    public function getActivateGlobal($code)
    {

        return view('admin.codes.activateCodeGlobal', compact("code"));
    }

    public function activateGlobal(Request $request)
    {
        $this->validate($request,
            [
                "code" => "required",
                "mac" => "required",
            ]);
        $response = $this->codes->activeGlobal($request->code, $request->mac);

        return redirect('admin/codes')->withMessage($response->value);
    }

    public function filterStatusCodes(Request $request)
    {
        $codes = $this->codes->codePaginationPages($request, $request->active, $request->unActive);
        return view("admin.codes.index", compact("codes"));
    }

    public function indexExport()
    {
        $users = $this->usersApi->createAllPanelUsersList();

        return view('admin.codes.export', compact("users"));
    }

    public function exportCodes(Request $request)
    {
        $this->validate($request,
            [
                "name" => "required",
            ]);
        $stored = $this->codes->storeCodes($request);
        $downloaded = (new CodesExport($request->name))->download('codes.xls');
        $deleted = $this->codes->deleteCodes();

        return $downloaded;
    }


}
