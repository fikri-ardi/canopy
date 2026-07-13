<?php

namespace App\Livewire\Dashboard;

use App\Models\InvestmentMovement;
use App\Models\Spend;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;

class SummaryCards extends Component
{
    public $totalIncome;

    public $totalExpense;

    public $remainingBalance;

    public $selectedInvestmentKey;

    public $showOnboardingWelcome;

    #[Computed]
    public function selectedInvestmentName()
    {
        return $this->investmentOptions->first()['key'];
    }

    #[Computed]
    public function totalInvestment(): int
    {
        $selectedInvestment = $this->selectedInvestmentOption($this->investmentOptions);

        return (int) $selectedInvestment['amount'];
    }

    public function selectInvestment(string $investmentName): void
    {
        $option = $this->investmentOptions->firstWhere('key', $investmentName);
        $this->selectedInvestmentName = $option['key'];
    }

    private function selectedInvestmentOption($options): ?array
    {
        $selected = $this->selectedInvestmentName
            ? $options->firstWhere('key', $this->selectedInvestmentName)
            : $options->first();

        return $selected;
    }

    #[Computed]
    private function totalSavings(): int
    {
        return (int) Spend::query()
            ->join('budgets', 'spends.budget_id', '=', 'budgets.id')
            ->join('labels', 'spends.label_id', '=', 'labels.id')
            ->where('budgets.user_id', auth()->id())
            ->whereIn(DB::raw('lower(trim(labels.name))'), ['investment', 'investasi'])
            ->sum('spends.amount');
    }

    #[Computed]
    public function savingsRate()
    {
        if ($this->totalIncome <= 0) {
            return 0;
        }

        return (int) round(($this->totalSavings / $this->totalIncome) * 100);
    }

    #[Computed]
    public function investmentOptions()
    {
        // Get all investment principals grouped by investment name (case-insensitive)
        $principals = Spend::query()
            ->join('budgets', 'spends.budget_id', '=', 'budgets.id')
            ->join('labels', 'spends.label_id', '=', 'labels.id')
            ->where('budgets.user_id', auth()->id())
            ->whereIn(DB::raw('lower(trim(labels.name))'), ['investment', 'investasi'])
            ->selectRaw('
                lower(trim(spends.name)) as investment_key, 
                min(spends.name) as name, 
                sum(spends.amount) as principal, 
                count(*) as transactions, 
                count(distinct spends.budget_id) as budgets_count
            ')
            ->groupByRaw('lower(trim(spends.name))')
            ->get()
            ->keyBy('investment_key');

        // Get all investment movements grouped by investment name (case-insensitive)
        $movementTotals = InvestmentMovement::query()
            ->where('user_id', auth()->id())
            ->selectRaw("
                investment_key, 
                sum(
                    case
                        when type = 'withdrawal' 
                        then amount 
                        else 0 
                    end
                ) as withdrawn, 
                sum(
                    case 
                        when type = 'deposit' 
                        then amount 
                        else 0 
                    end
                ) as deposit, 
                count(*) as movements_count
            ")
            ->groupBy('investment_key')
            ->get()
            ->keyBy('investment_key');

        return $principals
            ->map(function ($spend, $key) use ($movementTotals) {
                $movement = $movementTotals->get($key);
                $principal = (int) $spend->principal;
                $withdrawn = (int) ($movement->withdrawn ?? 0);
                $deposit = (int) ($movement->deposit ?? 0);

                return [
                    'key' => $spend->investment_key,
                    'name' => $spend->name,
                    'amount' => $principal + $deposit - $withdrawn,
                    'principal' => $principal,
                    'withdrawn' => $withdrawn,
                    'deposit' => $deposit,
                    'movements' => (int) ($movement->movements_count ?? 0),
                    'transactions' => (int) $spend->transactions,
                    'budgets' => (int) $spend->budgets_count,
                ];
            })
            ->sortByDesc('amount')
            ->values();
    }

    public function render()
    {
        return view('livewire.dashboard.summary-cards');
    }
}
