<?php

namespace App\Http\Controllers\Cabinet;

use App\Entity\Adverts\Advert\Advert;
use App\UseCases\Adverts\FavoriteService;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FavoriteController extends Controller
{
    /**
     * @var FavoriteService
     */
    private $service;

    public function __construct(FavoriteService $service)
    {
        $this->service = $service;
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $adverts = Advert::favoredByUser(Auth::user())
            ->orderByDesc('id')
            ->paginate(20);

        return view('cabinet.favorites.index', compact('adverts'));
    }

    public function remove(Advert $advert)
    {
        try {
            $this->service->remove(Auth::id(), $advert->id);
        } catch (\DomainException $e){
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cabinet.favorites.index');
    }

}
