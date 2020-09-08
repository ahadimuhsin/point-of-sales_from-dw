<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    //user must login before access this controller
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $categories = Category::orderBy('created_at', 'DESC')->paginate(10);

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        try{
            $categories = Category::firstOrCreate([
                'name' => $request->name
            ],
            [
                'description' => $request->description
            ]);
            return redirect()->back()->with(['success' => 'Kategori: ' .$categories->name.' berhasil ditambahkan!']);
        }
        catch (\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $category = Category::findOrFail($id);

        return view('categories.edit', ['category' => $category]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string'
        ]);

        try{
            $category = Category::findOrFail($id);

            $category->update([
                'name' => $request->name,
                'description' => $request->description
            ]);

            return redirect(route('category.index'))
                ->with(['success' => 'Kategori: '.$category->name. ' berhasil ditambah']);
        }
        catch (\Exception $e){
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $category = Category::findOrFail($id);
        $category->delete();

        DB::select(DB::raw("ALTER TABLE categories AUTO_INCREMENT = 1"));

        return redirect()->back()->with(['success' => 'Kategori: ' .$category->name.' telah dihapus']);
    }
}
