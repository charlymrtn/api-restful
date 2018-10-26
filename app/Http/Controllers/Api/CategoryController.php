<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

use App\Models\Category;
use App\Transformers\CategoryTransformer;

class CategoryController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index','show','products']);
        $this->middleware('client.credentials')->only(['index','show','products']);
        $this->middleware('transform.input:'. CategoryTransformer::class)->only(['store','update']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return $this->showAll($categories);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
          'name' => 'required|string|min:5',
          'description' => 'required|string|min:10',
        ];

        $this->validate($request,$rules);

        $category = Category::create($request->all());

        return $this->showOne($category,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        return $this->showOne($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
      $category->fill($request->only([
        'name', 'description'
      ]));

      if ($category->isClean()) {
        return $this->error('al least one attribute must be different for update',422);
      }

      $category->save();

      return $this->showOne($category,201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return $this->showOne($category);
    }

    public function products(Category $category)
    {
        return $this->showAll($category->products);
    }

    public function sellers(Category $category)
    {
        $sellers = $category->products()
                            ->with('seller')
                            ->get()
                            ->pluck('seller')
                            ->unique()
                            ->values();

        return $this->showAll($sellers);
    }

    public function transactions(Category $category)
    {
        $transactions = $category->products()
                                 ->whereHas('transactions')
                                 ->with('transactions')
                                 ->get()
                                 ->pluck('transactions')
                                 ->collapse();

        return $this->showAll($transactions);
    }

    public function buyers(Category $category)
    {
        $buyers = $category->products()
                           ->whereHas('transactions')
                           ->with('transactions.buyer')
                           ->get()
                           ->pluck('transactions')
                           ->collapse()
                           ->pluck('buyer')
                           ->unique()
                           ->values();


        return $this->showAll($buyers);
    }
}
