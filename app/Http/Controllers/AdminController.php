<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Agendamento;
use App\Models\Servico;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function dashboard()
    {
        $agendamentos = Agendamento::all();
        $servicos = Servico::all();

        $agendamentosSemana = $agendamentos->whereBetween('data_agendamento', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $ganhosSemana = $agendamentos->whereBetween('data_agendamento', [now()->startOfWeek(), now()->endOfWeek()])
            ->sum(function ($agendamento) {
                return $agendamento->servico ? $agendamento->servico->valor : 0;
            });

        $agendamentosMes = $agendamentos->whereBetween('data_agendamento', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $ganhosMes = $agendamentos->whereBetween('data_agendamento', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum(function ($agendamento) {
                return $agendamento->servico ? $agendamento->servico->valor : 0;
            });

        $agendamentosAno = $agendamentos->whereBetween('data_agendamento', [now()->startOfYear(), now()->endOfYear()])->count();
        $ganhosAno = $agendamentos->whereBetween('data_agendamento', [now()->startOfYear(), now()->endOfYear()])
            ->sum(function ($agendamento) {
                return $agendamento->servico ? $agendamento->servico->valor : 0;
            });

        return Inertia::render('Admin/Dashboard', [
            'agendamentosSemana' => $agendamentosSemana,
            'ganhosSemana' => $ganhosSemana,
            'agendamentosMes' => $agendamentosMes,
            'ganhosMes' => $ganhosMes,
            'agendamentosAno' => $agendamentosAno,
            'ganhosAno' => $ganhosAno,
            'servicos' => $servicos
        ]);
    }



    public function index(Request $request)
    {
        $this->authorize('viewAny', Agendamento::class);

        $servicos = Servico::all();
        $horasDisponiveis = $this->getTodasHoras();

        $de = $request->input('de');
        $ate = $request->input('ate');

        $agendamentosQuery = Agendamento::with(['servico', 'user'])
            ->orderBy('data_agendamento', 'desc')
            ->orderBy('hora', 'desc');

        if ($de && $ate) {
            $agendamentosQuery->whereBetween('data_agendamento', [$de, $ate]);
        }

        $agendamentos = $agendamentosQuery->paginate(10);

        return Inertia::render('Admin/Agendamento', [
            'agendamentos' => $agendamentos,
            'servicos' => $servicos,
            'horasDisponiveis' => $horasDisponiveis
        ]);
    }

    public function update(Request $request, Agendamento $agendamento)
    {
        $this->authorize('update', $agendamento);

        $request->validate([
            'servico_id' => 'required|exists:servicos,id',
            'nome_cliente' => 'required|string|max:80',
            'celular' => 'required|string|max:20',
            'data_agendamento' => 'required|date',
            'hora' => 'required|in:' . implode(',', $this->getTodasHoras()),
        ], [
            'servico_id.required' => 'O campo serviço é obrigatório.',
            'nome_cliente.required' => 'O campo nome do cliente é obrigatório.',
            'nome_cliente.string' => 'O nome do cliente deve ser uma string válida.',
            'nome_cliente.max' => 'O nome do cliente não pode ter mais de 80 caracteres.',
            'celular.required' => 'O campo celular é obrigatório.',
            'celular.string' => 'O celular deve ser uma string válida.',
            'celular.max' => 'O celular não pode ter mais de 20 caracteres.',
            'data_agendamento.required' => 'A data do agendamento é obrigatória.',
            'data_agendamento.date' => 'A data fornecida não é válida.',
            'hora.required' => 'A hora do agendamento é obrigatória.',
            'hora.in' => 'A hora selecionada não é válida.',
        ]);

        $agendamentoExistente = Agendamento::whereDate('data_agendamento', $request->data_agendamento)
            ->where('hora', $request->hora)
            ->where('id', '!=', $agendamento->id)
            ->exists();

        if ($agendamentoExistente) {
            return redirect()->back()->with('error', 'Já existe um agendamento nesse horário.');
        }

        $agendamento->update([
            'servico_id' => $request->servico_id,
            'nome_cliente' => $request->nome_cliente,
            'celular' => $request->celular,
            'data_agendamento' => $request->data_agendamento,
            'hora' => $request->hora,
            'status' => $request->status
        ]);

        $horasDisponiveis = $this->getHorasDisponiveis($request->data_agendamento);

        return redirect()->back()->with('success', 'Agendamento atualizado com sucesso!')
            ->with('horasDisponiveis', $horasDisponiveis);
    }

    public function destroy(Agendamento $agendamento)
    {
        $this->authorize('delete', $agendamento);

        $agendamento->delete();

        return redirect()->route('admin.agendamento')->with('success', 'Agendamento excluído com sucesso!');
    }

    private function getTodasHoras()
    {
        return [
            '08:00',
            '09:00',
            '10:00',
            '11:00',
            '12:00',
            '13:00',
            '14:00',
            '15:00',
            '16:00',
            '17:00'
        ];
    }

    private function getHorasDisponiveis($dataAgendamento)
    {
        $todasHoras = $this->getTodasHoras();

        $agendamentos = Agendamento::where('data_agendamento', $dataAgendamento)
            ->pluck('hora')
            ->toArray();

        $horasDisponiveis = array_diff($todasHoras, $agendamentos);

        return array_values($horasDisponiveis);
    }

    public function getHorasDisponiveisPorData(Request $request)
    {
        $request->validate([
            'data_agendamento' => 'required|date',
        ]);

        $dataAgendamento = $request->input('data_agendamento');
        $horasDisponiveis = $this->getHorasDisponiveis($dataAgendamento);

        return response()->json($horasDisponiveis);
    }
}