<div>
    <x-slot:header>Produk</x-slot:header>

    <div class="card">
        <div class="card-header bg-inv-secondary text-inv-primary border-0">
            <h5>Buat Produk Baru</h5>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Kategori Produk</label>
                        <select wire:model.live='product.product_category_id' class="form-select " name=""
                            id="">
                            <option selected>Pilih Kategori</option>
                            @foreach ($productCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('product.product_category_id')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Merk</label>
                        <select wire:model.live='product.brand_id' class="form-select " name="" id="">
                            <option selected>Pilih Merk</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                            @endforeach
                        </select>
                        @error('product.brand_id')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU (5 Digit)</label>
                        <div class="input-group">
                            <input wire:model.live='product.sku' type="text" maxlength="5" class="form-control" name="sku"
                                id="sku" aria-describedby="sku" placeholder="Auto-generated" readonly/>
                            <button class="btn btn-outline-secondary" type="button" wire:click="regenerateSKU" title="Generate SKU Baru">
                                <i class="bi bi-arrow-clockwise"></i>
                            </button>
                        </div>
                        @error('product.sku')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="image" class="form-label">Gambar Produk (Opsional)</label>
                        <input wire:model.live='image_file' type="file" accept="image/*" class="form-control" name="image"
                            id="image" aria-describedby="image" />
                        @error('image_file')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="name" class="form-label">Nama</label>
                    <input wire:model.live='product.name' type="text" class="form-control" name="name"
                        id="name" aria-describedby="name" placeholder="Enter your Product's Name" />
                    @error('product.name')
                        <small id="" class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="" class="form-label">Deskripsi Produk</label>
                    <textarea wire:model.live='product.description' class="form-control" name="" id="" rows="3"></textarea>
                </div>

                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Satuan Produk</label>
                        <select wire:model.live='product.unit_id' class="form-select " name="" id="">
                            <option selected>Pilih Satuan</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                            @endforeach
                        </select>
                        @error('product.unit_id')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="" class="form-label">Ukuran</label>
                        <select wire:model.live='product.size_id' class="form-select " name="" id="">
                            <option selected>Pilih Ukuran</option>
                            @foreach ($sizes as $size)
                                <option value="{{ $size->id }}">{{ $size->name }}</option>
                            @endforeach
                        </select>
                        @error('product.size_id')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                        <input wire:model.live='product.purchase_price' type="number" min="0" step="0.1"
                            class="form-control" name="purchase_price" id="name" aria-describedby="purchase_price"
                            placeholder="Enter your Product's purchase price" />
                        @error('product.purchase_price')
                            <small id="" class="form-text text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label class="form-label">Margin (%)</label>
                        <input wire:model.live="margin" type="number" min="0" step="0.1"
                            class="form-control" placeholder="Masukkan margin (%)" />
                    </div>
                </div>
                <div class="col-md-6 col-12">
                    <div class="mb-3">
                        <label for="name" class="form-label">Harga Jual</label>
                        <input wire:model.live='product.sale_price' type="number" min="0" step="0.1"
                            class="form-control" name="sale_price" id="name" aria-describedby="sale_price"
                            placeholder="Enter your Product's sale price" readonly/>
                    </div>
                </div>





            </div>



            <button onclick="confirm('Are you sure you wish to create this Product')||event.stopImmediatePropagation()"
                wire:click='save' class="btn btn-dark text-inv-primary">Save</button>
        </div>
    </div>
</div>
