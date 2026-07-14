<?php
// app/Http/Controllers/ProjectController.php
namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\PurchaseOrder;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    
    public function index(Request $request)
    {
    $filter = $request->get('filter', 'Berjalan');
    $search = $request->get('search');
    
    $query = Project::with('purchaseOrders');
    
    // Filter status
    if ($filter === 'Berjalan') {
        $query->where('status_project', 'Berjalan');
    } elseif ($filter === 'Selesai') {
        $query->where('status_project', 'Selesai');
    }
    
    // Search
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('no_project', 'like', "%{$search}%")
                ->orWhere('keterangan', 'like', "%{$search}%");
        });
    }
    
    $projects = $query->paginate(10); 
    
    return view('admin.project.index', compact('projects', 'filter', 'search'));
    }

    public function create()
    {
        return view('admin.project.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_project' => 'required|unique:project',
            'tanggal_project' => 'required|date',
            'deadline_project' => 'required|date|after:tanggal_project',
            'status_project' => 'required|in:Berjalan,Selesai'
        ]);

        Project::create($request->all());
        return redirect()->route('admin.project.index')->with('success', 'Project berhasil ditambahkan');
    }

    public function edit(Project $project)
    {
        return view('admin.project.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'no_project' => 'required|unique:project,no_project,' . $project->id,
            'tanggal_project' => 'required|date',
            'deadline_project' => 'required|date|after:tanggal_project',
            'status_project' => 'required|in:Berjalan,Selesai'
        ]);
    
    if ($request->status_project === 'Selesai') {
        $activePOs = PurchaseOrder::where('project_id', $project->id)
            ->where('status_po', '!=', 'Selesai')
            ->count();
        
        if ($activePOs > 0) {
            return back()->withErrors([
                'status_project' => "Tidak dapat mengubah status ke Selesai. Masih ada {$activePOs} PO yang belum selesai."
            ])->withInput();
        }
    }

        $project->update($request->all());
        return redirect()->route('admin.project.index')->with('success', 'Project berhasil diupdate');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('admin.project.index')->with('success', 'Project berhasil dihapus');
    }
}