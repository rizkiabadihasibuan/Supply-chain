<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Port;
use App\Models\Country;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Display the Admin Control Panel with tabbed management cards.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        $countries = Country::orderBy('name')->get();
        $ports = Port::with('country')->orderBy('id', 'desc')->get();
        $articles = Article::with('author')->orderBy('id', 'desc')->get();

        return view('admin.dashboard', compact('users', 'roles', 'countries', 'ports', 'articles'));
    }

    /**
     * Update a user's role.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateUserRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => 'required|exists:roles,id',
        ]);

        // Prevent admin from removing their own admin role
        if ($user->id === $request->user()->id && $user->role->name === 'Admin') {
            $adminRole = Role::where('name', 'Admin')->first();
            if ($request->role_id != $adminRole->id) {
                return redirect()->back()->with('error', 'Anda tidak dapat menghapus peran Admin Anda sendiri demi keamanan.');
            }
        }

        $user->role_id = $request->role_id;
        $user->save();

        return redirect()->back()->with('success', "Peran pengguna '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Store a newly created port in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storePort(Request $request)
    {
        $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_code' => 'required|string|unique:ports,port_code',
            'name' => 'required|string|max:255',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'waiting_time_hours' => 'required|integer|min:0',
            'congestion_rate' => 'required|numeric|between:0,100',
        ]);

        Port::create([
            'country_id' => $request->country_id,
            'port_code' => strtoupper($request->port_code),
            'name' => $request->name,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'waiting_time_hours' => $request->waiting_time_hours,
            'congestion_rate' => $request->congestion_rate,
        ]);

        return redirect()->back()->with('success', "Pelabuhan '{$request->name}' berhasil ditambahkan ke dataset.");
    }

    /**
     * Delete a port from the database.
     *
     * @param Port $port
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyPort(Port $port)
    {
        $portName = $port->name;
        $port->delete();

        return redirect()->back()->with('success', "Pelabuhan '{$portName}' berhasil dihapus dari dataset.");
    }

    /**
     * Store a newly created analysis article in the database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $slug = Str::slug($request->title);
        
        // Ensure unique slug
        $count = Article::where('slug', 'like', "{$slug}%")->count();
        if ($count > 0) {
            $slug .= '-' . ($count + 1);
        }

        Article::create([
            'user_id' => $request->user()->id,
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'status' => 'published',
        ]);

        return redirect()->back()->with('success', "Artikel analisis '{$request->title}' berhasil dipublikasikan.");
    }

    /**
     * Delete an article from the database.
     *
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyArticle(Article $article)
    {
        $title = $article->title;
        $article->delete();

        return redirect()->back()->with('success', "Artikel '{$title}' berhasil dihapus.");
    }
}
