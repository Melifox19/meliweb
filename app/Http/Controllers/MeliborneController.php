<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMeliborneRequest;
use App\Http\Requests\UpdateMeliborneRequest;
use App\Repositories\MeliborneRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

//Ajout des dépendances perso
use Illuminate\Support\Facades\DB;
use App\Models\Rucher;
use App\Models\User;
use Auth;

class MeliborneController extends AppBaseController
{
    /** @var  MeliborneRepository */
    private $meliborneRepository;

    public function __construct(MeliborneRepository $meliborneRepo)
    {
        $this->meliborneRepository = $meliborneRepo;
    }

    /**
     * Display a listing of the Meliborne.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->meliborneRepository->pushCriteria(new RequestCriteria($request));

        if ( Auth::user()->role == 'admin' )
        {
            $melibornes = $this->meliborneRepository->all(); // On affiche toutes les melibornes
        }
        else
        {
          $melibornes = User::find(Auth::user()->id)->melibornes; // On affiche les mélibornes de l'utilisateur connecté
        }

        $ruchers = Rucher::all();

        return view('melibornes.index')
            ->with('melibornes', $melibornes)
            ->with('ruchers', $ruchers);
    }

    /**
     * Show the form for creating a new Meliborne.
     *
     * @return Response
     */
    public function create()
    {
            if ( Auth::user()->role == "admin") // Si admin on affiche tout les ruchers
            {
                $ruchers = Rucher::all();
            }
            else
            {
                $ruchers = User::find(Auth::user()->id)->ruchers; // Si utilisateur on affiche seulement ses ruchers
            }

            return view('melibornes.create')
            ->with('ruchers', $ruchers);
    }

    /**
     * Store a newly created Meliborne in storage.
     *
     * @param CreateMeliborneRequest $request
     *
     * @return Response
     */
    public function store(CreateMeliborneRequest $request)
    {
        $input = $request->all();

        $meliborne = $this->meliborneRepository->create($input);

        Flash::success('Meliborne créé avec succès');

        return redirect(route('melibornes.index'));
    }

    /**
     * Display the specified Meliborne.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $meliborne = $this->meliborneRepository->findWithoutFail($id);
        $rucher = Rucher::find($meliborne->idRucher);

        if (empty($meliborne)) {
            Flash::error('Meliborne introuvable');

            return redirect(route('melibornes.index'));
        }

        return view('melibornes.show')
        ->with('meliborne', $meliborne)
        ->with('rucher', $rucher);
    }

    /**
     * Show the form for editing the specified Meliborne.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $meliborne = $this->meliborneRepository->findWithoutFail($id);

        if (empty($meliborne)) {
            Flash::error('Meliborne introuvable');

            return redirect(route('melibornes.index'));
        }

        if ( Auth::user()->role == "admin") // Si Admin on affiche tous les ruchers
        {
            $ruchers = Rucher::all();
        }
        else
        {
          $ruchers = User::find(Auth::user()->id)->ruchers; // Si utilisateur on affiche seulement ses ruchers
        }

        return view('melibornes.edit')
        ->with('meliborne', $meliborne)
        ->with('ruchers', $ruchers);
    }

    /**
     * Update the specified Meliborne in storage.
     *
     * @param  int              $id
     * @param UpdateMeliborneRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMeliborneRequest $request)
    {
        $meliborne = $this->meliborneRepository->findWithoutFail($id);

        if (empty($meliborne)) {
            Flash::error('Meliborne not found');

            return redirect(route('melibornes.index'));
        }

        $meliborne = $this->meliborneRepository->update($request->all(), $id);

        Flash::success('Meliborne updated successfully.');

        return redirect(route('melibornes.index'));
    }

    /**
     * Remove the specified Meliborne from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $meliborne = $this->meliborneRepository->findWithoutFail($id);

        if (empty($meliborne)) {
            Flash::error('Meliborne not found');

            return redirect(route('melibornes.index'));
        }

        $this->meliborneRepository->delete($id);

        Flash::success('Meliborne deleted successfully.');

        return redirect(route('melibornes.index'));
    }
}
