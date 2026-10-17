<?php
namespace App\Http\Controllers;
use App\Models\Listing;
use Illuminate\Http\Request;
class MarketController extends Controller {
    public function index(Request $r) {
        $q = Listing::active()->orderByDesc('is_featured')->latest()
            ->when($r->q, fn ($x, $v) => $x->where(fn ($y) => $y->where('title','like',"%$v%")->orWhere('address','like',"%$v%")->orWhere('district','like',"%$v%")))
            ->when($r->city, fn ($x, $v) => $x->where('city','like',"%$v%"))
            ->when($r->type, fn ($x, $v) => $x->where('transaction_type',$v));
        return view('market.index', ['listings' => $q->paginate(12)->withQueryString()]);
    }
    public function show(Listing $listing) {
        abort_unless($listing->status === 'active', 404);
        $listing->increment('views');
        return view('market.show', compact('listing'));
    }
}
