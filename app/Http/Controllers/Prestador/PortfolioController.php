<?php

namespace App\Http\Controllers\Prestador;

use App\Actions\Prestador\Portfolio\CriaPortfolio;
use App\Actions\Prestador\Portfolio\EditaFotoPortfolio;
use App\Actions\Prestador\Portfolio\EditaPortfolio;
use App\Actions\Prestador\Portfolio\ExcluiFotoPortfolio;
use App\Actions\Prestador\Portfolio\ExcluiPortfolio;
use App\Actions\Prestador\Portfolio\ExibePortfolio;
use App\DTO\Prestador\NovoPortfolioDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Prestador\PortfolioResource;
use App\Models\Prestador\Portfolio;
use App\Models\Prestador\Prestador;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Response;

class PortfolioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Prestador $prestador, ExibePortfolio $action): JsonResource
    {
        return PortfolioResource::collection($action->porPrestador($prestador));
    }

    // por uuid
    public function show(string $uuid, ExibePortfolio $action): JsonResource
    {
        return PortfolioResource::make($action->porUuid($uuid));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Prestador $prestador, CriaPortfolio $action): JsonResource
    {
        $dto = new NovoPortfolioDTO(
            prestadorId: $prestador->id,
            descricao: $request->input('descricao'),
            midia: $request->file('midia'),
        );

        return PortfolioResource::make($action->executa($dto));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Portfolio $portfolio, EditaPortfolio $action): JsonResource
    {
        $dto = new NovoPortfolioDTO(
            prestadorId: $portfolio->prestador_id,
            descricao: $request->input('descricao'),
            midia: $request->file('midia'),
        );

        return PortfolioResource::make($action->executa($portfolio, $dto));
    }

    // atualiza foto individualmente
    public function updateFoto(Request $request, Portfolio $portfolio, EditaFotoPortfolio $action): JsonResource
    {
        return PortfolioResource::make($action->executa($portfolio, $request->file('midia')));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Portfolio $portfolio, ExcluiPortfolio $action): Response
    {
        $action->executa($portfolio);

        return response()->noContent();
    }

    public function destroyFoto(Portfolio $portfolio, ExcluiFotoPortfolio $action): JsonResource
    {
        return PortfolioResource::make($action->executa($portfolio));
    }
}
