<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Cupom;  

class CupomController extends Controller
{
    public function new(Request $request) {
        return view('cupom.create');
    }

    public function index(Request $request)
    {
        try {
            // Iniciar query base
            $cupons = getCupons();

            // Calcular estatísticas
            $estatisticas = $this->calcularEstatisticas();

            return view('cupom.index', compact('cupons', 'estatisticas'));

        } catch (\Exception $e) {
            // Log do erro
            \Log::error('Erro ao listar cupons: ' . $e->getMessage());
            
            return view('cupom.index')
                ->with('error', 'Erro ao carregar os cupons. Tente novamente.')
                ->with('cupons', collect())
                ->with('estatisticas', [
                    'ativos' => 0,
                    'proximos_vencimento' => 0,
                    'expirados' => 0,
                    'total_usos' => 0
                ]);
        }
    }

    /**
     * Calcular estatísticas dos cupons
     *
     * @return array
     */
    private function calcularEstatisticas()
    {
        try {
            $now = now();
            
            // Cupons ativos (status true e não expirados)
            $ativos = Cupom::where('validade', '>', $now)
                          ->count();

            // Cupons próximos ao vencimento (próximos 7 dias)
            $proximosVencimento = Cupom::where('validade', '>', $now)
                                      ->where('validade', '<=', $now->copy()->addDays(7))
                                      ->count();

            // Cupons expirados
            $expirados = Cupom::where('validade', '<=', $now)
                             ->count();

            // Total de cupons (simulando total de usos - você pode criar uma tabela de usos futuramente)
            $totalCupons = Cupom::count();

            return [
                'ativos' => $ativos,
                'proximos_vencimento' => $proximosVencimento,
                'expirados' => $expirados,
                'total_usos' => $totalCupons * 5 // Simulação: média de 5 usos por cupom
            ];

        } catch (\Exception $e) {
            \Log::error('Erro ao calcular estatísticas: ' . $e->getMessage());
            
            return [
                'ativos' => 0,
                'proximos_vencimento' => 0,
                'expirados' => 0,
                'total_usos' => 0
            ];
        }
    }

    public function store(Request $request)
    {
        // Validação dos dados
        /*$validator = Validator::make($request->all(), [
            'codigo' => [
                'required',
                'string',
                'max:255',
                'unique:cupons,codigo',
                'regex:/^[A-Z0-9]+$/' // Apenas letras maiúsculas e números
            ],
            'valor_minimo' => [
                'nullable',
                'numeric',
                'min:0',
                'max:999999.99'
            ],
            'valor_desconto' => [
                'required',
                'numeric',
                'min:0.01',
                'max:100' // Máximo 100% de desconto
            ],
            'validade' => [
                'nullable',
                'date',
                'after:now' // Data deve ser no futuro
            ],
            'status' => [
                'boolean'
            ]
        ], [
            // Mensagens customizadas
            'codigo.required' => 'O código do cupom é obrigatório.',
            'codigo.unique' => 'Este código já existe. Escolha outro código.',
            'codigo.regex' => 'O código deve conter apenas letras maiúsculas e números.',
            'codigo.max' => 'O código não pode ter mais de 255 caracteres.',
            'valor_minimo.numeric' => 'O valor mínimo deve ser um número válido.',
            'valor_minimo.min' => 'O valor mínimo não pode ser negativo.',
            'valor_minimo.max' => 'O valor mínimo não pode ser maior que R$ 999.999,99.',
            'valor_desconto.required' => 'O valor do desconto é obrigatório.',
            'valor_desconto.numeric' => 'O valor do desconto deve ser um número válido.',
            'valor_desconto.min' => 'O valor do desconto deve ser maior que zero.',
            'valor_desconto.max' => 'O valor do desconto não pode ser maior que 100%.',
            'validade.date' => 'A data de validade deve ser uma data válida.',
            'validade.after' => 'A data de validade deve ser no futuro.'
        ]);

        // Se a validação falhar, retorna com erros
        dd($validator->fails());

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }*/


        try {
            $cupom = new Cupom();
            $cupom->codigo = strtoupper(trim($request->codigo));
            $cupom->valor_minimo = $request->valor_minimo ? 
                number_format((float)$request->valor_minimo, 2, '.', '') : null;
            $cupom->valor_desconto = number_format((float)$request->valor_desconto, 2, '.', '');
            $cupom->validade = $request->validade ? 
                Carbon::parse($request->validade) : null;
            $salvou = $cupom->save();

            // Verificar se o cupom foi salvo com sucesso
            if ($salvou) {
                return redirect()
                    ->route('cupom.index')
                    ->with('success', 'Cupom criado com sucesso!');
            } else {
                return redirect()->back()
                    ->with('error', 'Erro ao criar o cupom. Tente novamente.')
                    ->withInput();
            }

        } catch (\Exception $e) {
            \Log::error('Erro ao criar cupom: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Erro interno do servidor. Tente novamente.')
                ->withInput();        
        }
    }

    /**
     * Gerar código único para cupom
     *
     * @param int $length
     * @return string
     */
    private function gerarCodigoUnico($length = 8)
    {
        $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $codigo = '';
        
        do {
            $codigo = '';
            for ($i = 0; $i < $length; $i++) {
                $codigo .= $caracteres[random_int(0, strlen($caracteres) - 1)];
            }
        } while (Cupom::where('codigo', $codigo)->exists());
        
        return $codigo;
    }

    /**
     * Função auxiliar para gerar código via AJAX
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function gerarCodigo()
    {
        try {
            $codigo = $this->gerarCodigoUnico();
            
            return response()->json([
                'success' => true,
                'codigo' => $codigo
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar código'
            ], 500);
        }
    }

    /**
     * Validar se código é único via AJAX
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function validarCodigo(Request $request)
    {
        $codigo = strtoupper(trim($request->codigo));
        $existe = Cupom::where('codigo', $codigo)->exists();
        
        return response()->json([
            'disponivel' => !$existe,
            'message' => $existe ? 'Este código já existe' : 'Código disponível'
        ]);
    }
}
