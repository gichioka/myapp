<?php

namespace App\Livewire\Product;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;

class ProductManager extends Component
{
    use WithPagination;

    public $search = '';

    public $name;
    public $brand;
    public $sku;
    public $category;
    public $cpu;
    public $ram;
    public $storage;
    public $quantity;
    public $unit_price;
    public $description;

    public $isEdit = false;
    public $editingProductId = null;

    public function render()
    {
        $products = Product::query()
            ->with('user')
            ->when($this->search, function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('sku', 'like', "%{$this->search}%")
                  ->orWhere('cpu', 'like', "%{$this->search}%")
                  ->orWhere('ram', 'like', "%{$this->search}%");
            })
            ->latest()
            ->paginate(10);

        return view('livewire.product.product-manager', [
            'products' => $products
        ]);
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string',
            'sku' => 'required|string',
        ]);

        Product::create([
            'name' => $this->name,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'category' => $this->category,
            'cpu' => $this->cpu,
            'ram' => $this->ram,
            'storage' => $this->storage,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'description' => $this->description,
            'user_id' => auth()->id(),
        ]);

        $this->resetForm();
    }

    public function edit($id)
    {
        $p = Product::findOrFail($id);

        $this->editingProductId = $id;
        $this->isEdit = true;

        $this->name = $p->name;
        $this->brand = $p->brand;
        $this->sku = $p->sku;
        $this->category = $p->category;
        $this->cpu = $p->cpu;
        $this->ram = $p->ram;
        $this->storage = $p->storage;
        $this->quantity = $p->quantity;
        $this->unit_price = $p->unit_price;
        $this->description = $p->description;
    }

    public function update()
    {
        if (!$this->editingProductId) return;

        $p = Product::findOrFail($this->editingProductId);

        $p->update([
            'name' => $this->name,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'category' => $this->category,
            'cpu' => $this->cpu,
            'ram' => $this->ram,
            'storage' => $this->storage,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'description' => $this->description,
        ]);

        $this->resetForm();
    }

    public function delete($id)
    {
        Product::findOrFail($id)->delete();
    }

    public function resetForm()
    {
        $this->reset([
            'name','brand','sku','category','cpu',
            'ram','storage','quantity','unit_price',
            'description','isEdit','editingProductId'
        ]);
    }
}