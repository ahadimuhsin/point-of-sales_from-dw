<?php

namespace App\Http\Controllers;

use App\Category;
use App\Product;
use Illuminate\Http\Request;
use File;
use Illuminate\Support\Facades\DB;
use Image;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categories = Category::orderBy('name', 'asc')->get();

        return view('products.create', compact('categories'));
    }

    /*
     * Method untuk menangani penyimpanan file
     */

    public function saveFile($name, $photo)
    {
        //nama file disimpan dengan nama produk, waktu, dan ekstensi file
       $product_slug = Str::of($name)->slug('-');
        $images = $product_slug. time(). '.'.$photo->getClientOriginalExtension();
        //folder tempat menyimpan file tersebut
        $path = public_path('uploads/product');
        //jika folder uploads/product tidak ada
        if(!File::isDirectory($path)){
            //buat folder tersebut
            File::makeDirectory($path, 0777, true,true);
        }

        //simpan gambar ke folder uploads/product
        Image::make($photo)->save($path.'/'.$images);

        return $images;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validasi data
        $this->validate($request, [
            'code' => 'required|string|max:20|unique:products',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id_category',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        try {
            $photo = null;

            //jika ada file photo yg dikirim
            if($request->hasFile('photo')){
                //jalankan method saveFile
                $photo = $this->saveFile($request->name, $request->file('photo'));
            }

            $product = Product::create([
                'code' => Str::of($request->code)->slug('-'),
                'name' => $request->name,
                'description' => $request->description,
                'stock' => $request->stock,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'photo' => $photo
            ]);

            return redirect(route('product.index'))
                ->with(['success' => 'Produk '.$product->name. ' berhasil ditambah']);
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
        $product = Product::findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
//        $product->price = number_format($product->price, 2);

        return view('products.edit', compact('product', 'categories'));
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
            'code' => 'required|string|max:10|exists:products,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string|max:100',
            'stock' => 'required|integer',
            'price' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpg,png,jpeg'
        ]);

        try{
            $product = Product::findOrFail($id);
            $photo = $product->photo;

            //cek jika da file photo yang dikirim dari form
            if($request->hasFile('photo')){
                //cek, jika photo tidak kosong maka file yang ada di folder uploads/product akan dihapus
                !empty($photo) ? File::delete(public_path('uploads/product/'.$photo)):null;

                $photo = $this->saveFile($request->name, $request->file('photo'));
            }
            $product->update([
                'name' => $request->name,
                'description' => $request->description,
                'stock' => $request->stock,
                'price' => $request->price,
                'category_id' => $request->category_id,
                'photo' => $photo
            ]);

            return redirect()->route('product.index')
                ->with(['success' => 'Produk ' .$product->name. ' berhasil diperbarui']);
        }
        catch (\Exception $e){
            return redirect()->back()
                ->with(['error' => $e->getMessage()]);
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
        $product = Product::findOrFail($id);
        //jika photo ada
        if(!empty($product->photo)){
            //hapus file
            File::delete(public_path('uploads/product'.$products->photo));
        }

        DB::select(DB::raw("ALTER TABLE products AUTO_INCREMENT = 1"));

        $product->delete();
        return redirect()->back()->with(['success' => "Data ".$product->name. " telah dihapus"]);
    }
}
