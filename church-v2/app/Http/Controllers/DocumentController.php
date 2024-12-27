<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function index()
    {
        $search = request('search');
        $documents = Document::query()
            ->when($search, fn($query, $search) => $query->where('document_type', 'like', "%{$search}%"))
            ->orderByDesc('created_at')
            ->paginate(10);

        $counts = Document::selectRaw('document_type, COUNT(*) as count')
            ->groupBy('document_type')
            ->get();

        $baptismal = Document::where('document_type', 'Baptismal Certificate')->count();
        $marriage = Document::where('document_type', 'Marriage Certificate')->count();
        $death = Document::where('document_type', 'Death Certificate')->count();
        $confirmation = Document::where('document_type', 'Confirmation Certificate')->count();

        return view('admin.documents', compact('documents', 'counts', 'baptismal', 'marriage', 'death', 'confirmation'));
    }

    public function store(Request $request, DocumentService $documentService)
    {
        $result = $documentService->store($request);

        return redirect()->back()->with($result);
    }

    public function update(Request $request, $id, DocumentService $documentService)
    {
        $result = $documentService->update($request, $id);

        return redirect()->back()->with($result);
    }

    public function destroy($id, DocumentService $documentService)
    {
        $result = $documentService->destroy($id);

        return redirect()->back()->with($result);
    }
}
