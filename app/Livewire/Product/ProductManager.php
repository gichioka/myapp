<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $search = '';

    public $productId = null;

    // 利用者
    public $user_id = '';

    // PC情報
    public $name = '';
    public $brand = '';
    public $sku = '';
    public $category = '';
    public $cpu = '';
    public $ram = '';
    public $storage = '';
    public $quantity = 0;
    public $unit_price = 0;
    public $description = '';

    public $isEdit = false;

    protected function rules()
    {
        return [
            'user_id'     => 'nullable|exists:users,id',

            'name'        => 'required|string|max:255',
            'brand'       => 'nullable|string|max:255',
            'sku'         => 'required|string|max:255',

            'category'    => 'nullable|string|max:255',
            'cpu'         => 'nullable|string|max:255',

            'ram'         => 'nullable|integer|min:1',
            'storage'     => 'nullable|string|max:255',

            'quantity'    => 'required|integer|min:0',
            'unit_price'  => 'required|integer|min:0',

            'description' => 'nullable|string',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function save()
    {
        $this->validate();

        Product::create([
            'user_id'     => $this->user_id ?: null,

            'name'        => $this->name,
            'brand'       => $this->brand,
            'sku'         => $this->sku,
            'category'    => $this->category,
            'cpu'         => $this->cpu,
            'ram'         => $this->ram,
            'storage'     => $this->storage,
            'quantity'    => $this->quantity,
            'unit_price'  => $this->unit_price,
            'description' => $this->description,
        ]);

        session()->flash('message', 'PCを登録しました');

        $this->resetForm();
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);

        $this->productId = $product->id;

        $this->user_id = $product->user_id;

        $this->name = $product->name;
        $this->brand = $product->brand;
        $this->sku = $product->sku;
        $this->category = $product->category;
        $this->cpu = $product->cpu;
        $this->ram = $product->ram;
        $this->storage = $product->storage;
        $this->quantity = $product->quantity;
        $this->unit_price = $product->unit_price;
        $this->description = $product->description;

        $this->isEdit = true;
    }

    public function update()
    {
        $this->validate();

        Product::findOrFail($this->productId)->update([

            'user_id'     => $this->user_id ?: null,

            'name'        => $this->name,
            'brand'       => $this->brand,
            'sku'         => $this->sku,
            'category'    => $this->category,
            'cpu'         => $this->cpu,
            'ram'         => $this->ram,
            'storage'     => $this->storage,
            'quantity'    => $this->quantity,
            'unit_price'  => $this->unit_price,
            'description' => $this->description,
        ]);

        session()->flash('message', 'PC情報を更新しました');

        $this->resetForm();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();

        session()->flash('message', 'PCを削除しました');
    }

    public function resetForm()
    {
        $this->reset([
            'productId',

            'user_id',

            'name',
            'brand',
            'sku',
            'category',
            'cpu',
            'ram',
            'storage',
            'quantity',
            'unit_price',
            'description',
        ]);

        $this->quantity = 0;
        $this->unit_price = 0;

        $this->isEdit = false;
    }

    public function render()
    {
        $products = Product::with('user')

            ->when($this->search, function ($query) {

                $query->where(function ($q) {

                    $q->where('name', 'like', '%' . $this->search . '%')

                        ->orWhere('cpu', 'like', '%' . $this->search . '%');

                    if (is_numeric($this->search)) {
                        $q->orWhere('ram', (int) $this->search);
                    }
                });
            })

            ->latest()
            ->paginate(10);

        return view('livewire.product.product-manager', [

            'products' => $products,

            'users' => User::orderBy('name')->get(),

        ]);
    }
}