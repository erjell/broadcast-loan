<?php

namespace App\Http\Controllers;

use App\Exports\DamageLogsExport;
use App\Models\LoanItem;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function damageLogs(Request $request)
    {
        [$query, $q, $condition, $dateFrom, $dateTo] = $this->makeDamageLogsQuery($request);

        $logs = (clone $query)
            ->latest('updated_at')
            ->paginate(1000)
            ->withQueryString();

        return view('reports.damage_logs', compact('logs', 'q', 'condition', 'dateFrom', 'dateTo'));
    }

    public function exportDamageLogs(Request $request)
    {
        [$query] = $this->makeDamageLogsQuery($request);

        $logs = (clone $query)
            ->latest('updated_at')
            ->get();

        $fileName = 'log-kerusakan-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new DamageLogsExport($logs), $fileName);
    }

    /**
     * @return array{0:\Illuminate\Database\Eloquent\Builder,1:string,2:string,3:?\Carbon\Carbon,4:?\Carbon\Carbon}
     */
    protected function makeDamageLogsQuery(Request $request): array
    {
        $q = $request->string('q')->toString();
        $condition = $request->string('condition')->toString();
        $dateFrom = $request->date('from');
        $dateTo = $request->date('to');

        $query = LoanItem::query()
            ->with(['item', 'loan.partner'])
            ->whereNotNull('return_condition')
            ->where(function ($w) {
                $w->whereIn('return_condition', ['rusak_ringan', 'rusak_berat', 'hilang'])
                  ->orWhereNotNull('return_notes');
            })
            ->when($q, function ($query) use ($q) {
                $query->where(function ($s) use ($q) {
                    $s->whereHas('item', function ($i) use ($q) {
                        $i->where('name', 'like', "%$q%")
                          ->orWhere('code', 'like', "%$q%");
                    })
                    ->orWhereHas('loan', function ($l) use ($q) {
                        $l->where('code', 'like', "%$q%")
                          ->orWhereHas('partner', function ($p) use ($q) {
                              $p->where('name', 'like', "%$q%");
                          });
                    })
                    ->orWhere('return_notes', 'like', "%$q%");
                });
            })
            ->when($condition && in_array($condition, ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'], true), function ($query) use ($condition) {
                $query->where('return_condition', $condition);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('updated_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('updated_at', '<=', $dateTo);
            });

        return [$query, $q, $condition, $dateFrom, $dateTo];
    }
}
