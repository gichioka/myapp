<?php

namespace App\Livewire\Product;

use App\Models\Product;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ProductManager extends Component
{
    use WithPagination;
    
    public $search = '';
    public $name, $brand, $sku, $category, $cpu, $ram, $storage, $quantity, $user_id, $description;
    public $isEdit = false;
    public $editId;
    public $users;  // ← このプロパティを追加
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'sku' => 'required|string|max:50',
        'ram' => 'nullable|integer|min:1|max:256',
        'quantity' => 'required|integer|min:0',
    ];
    
    public function mount()
    {
        // ユーザー一覧を取得
        $this->users = User::orderBy('name')->get();
        $this->resetForm();
    }
    
    public function resetForm()
    {
        $this->name = '';
        $this->brand = '';
        $this->sku = '';
        $this->category = '';
        $this->cpu = '';
        $this->ram = '';
        $this->storage = '';
        $this->quantity = '';
        $this->user_id = '';
        $this->description = '';
        $this->isEdit = false;
        $this->editId = null;
    }
    
    public function save()
    {
        $this->validate();
        
        Product::create([
            'name' => $this->name,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'category' => $this->category,
            'cpu' => $this->cpu,
            'ram' => $this->ram,
            'storage' => $this->storage,
            'quantity' => $this->quantity,
            'user_id' => $this->user_id ?: null,
            'description' => $this->description,
        ]);
        
        session()->flash('message', '登録しました');
        $this->resetForm();
        $this->dispatch('pc-saved');
    }
    
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->editId = $id;
        $this->name = $product->name;
        $this->brand = $product->brand;
        $this->sku = $product->sku;
        $this->category = $product->category;
        $this->cpu = $product->cpu;
        $this->ram = $product->ram;
        $this->storage = $product->storage;
        $this->quantity = $product->quantity;
        $this->user_id = $product->user_id;
        $this->description = $product->description;
        $this->isEdit = true;
    }
    
    public function update()
    {
        $this->validate();
        
        $product = Product::findOrFail($this->editId);
        $product->update([
            'name' => $this->name,
            'brand' => $this->brand,
            'sku' => $this->sku,
            'category' => $this->category,
            'cpu' => $this->cpu,
            'ram' => $this->ram,
            'storage' => $this->storage,
            'quantity' => $this->quantity,
            'user_id' => $this->user_id ?: null,
            'description' => $this->description,
        ]);
        
        session()->flash('message', '更新しました');
        $this->resetForm();
    }
    
    public function cancelEdit()
    {
        $this->resetForm();
    }
    
    public function delete($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('message', '削除しました');
    }
    
    public function resetSearch()
    {
        $this->search = '';
        $this->resetPage();
    }
    
    public function render()
    {
        $products = Product::with('user')
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('sku', 'like', '%' . $this->search . '%')
                      ->orWhere('cpu', 'like', '%' . $this->search . '%')
                      ->orWhere('ram', 'like', '%' . $this->search . '%');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        
        return view('livewire.product.product-manager', [
            'products' => $products,
            'users' => $this->users,  // ← ビューにusersを渡す
        ])->layout('layouts.admin');
    }
}