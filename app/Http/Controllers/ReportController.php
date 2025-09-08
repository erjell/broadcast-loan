<?php

namespace App\Http\Controllers;

use App\Models\LoanItem;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function damageLogs(Request $request)
    {
        $q = $request->string('q')->toString();
        $condition = $request->string('condition')->toString();
        $dateFrom = $request->date('from');
        $dateTo = $request->date('to');

        $logs = LoanItem::query()
            ->with(['item', 'loan.partner'])
            // Hanya yang sudah dikembalikan dan memiliki catatan/kerusakan
            ->whereNotNull('return_condition')
            ->where(function ($w) {
                $w->whereIn('return_condition', ['rusak_ringan','rusak_berat'])
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
            ->when($condition && in_array($condition, ['baik','rusak_ringan','rusak_berat']), function ($query) use ($condition) {
                $query->where('return_condition', $condition);
            })
            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('updated_at', '>=', $dateFrom);
            })
            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('updated_at', '<=', $dateTo);
            })
            ->latest('updated_at')
            ->paginate(1000)
            ->withQueryString();

        return view('reports.damage_logs', compact('logs', 'q', 'condition', 'dateFrom', 'dateTo'));
    }
}

