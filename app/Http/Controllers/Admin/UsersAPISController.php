<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Apis\UsersApi;
use App\Apis\CodeAPIS;

class UsersAPISController extends Controller
{
    private $usersApi;
    private $codesApi;

    function __construct(UsersApi $usersApi , CodeAPIS $codeAPIS )
    {
        $this->usersApi = $usersApi;
        $this->codesApi = $codeAPIS;
    }

    /**
     * Index page
     * @return \Illuminate\View\View
     * @param Request $request
     *
     */
    public function index(Request $request) : \Illuminate\View\View
    {
        $users =  $this->usersApi->UsersPaginationPages($request);

        return view('admin.userApi.index' , compact('users'));
    }

    /**
     * create page
     *
     * @return \Illuminate\View\View
     */
    public function create(): \Illuminate\View\View
    {
        $apis = $this->codesApi->createApisList();

        return view('admin.userApi.create', compact("apis"));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request) :\Illuminate\Http\RedirectResponse
    {
        $this->validate($request, [
            "name" => "required",
            "apiId" => "required",
        ]);
        $inserted = $this->usersApi->insertUserApi($request->apiId , $request->name);

        return redirect('admin/users_api')->with('message' , $inserted->message);
    }
}
