<?= $this->extend('template/partner/temp') ?>
<?= $this->section('content') ?>
<form action="" method="POST">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="productName">Nama Produk</label>
                        <input type="text" class="form-control" name="productName" id="productName" placeholder="Contoh: Dekorasi Pelaminan 6M">
                    </div>
                    <div class="form-group">
                        <label>Kategori</label>
                        <select class="form-control" id="category" name="category" required>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Etalase</label>
                        <select class="form-control" id="etalase" name="etalase" required>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productName">Minimal Pemesanan</label>
                                <input type="number" class="form-control" name="productName" id="productName" min="1" placeholder="Contoh: 1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="itemQty">Stok(-1 = unli)</label>
                                <input type="number" class="form-control" min="-1" name="itemQty" id="itemQty" placeholder="Contoh: -1">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="productName">Harga Beli</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                            </div>
                            <input type="number" class="form-control" name="productPrice" id="productPrice" placeholder="Contoh: 200000">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="productName">Harga Jual</label>
                        <div class="input-group mb-3">
                            <div class="input-group-prepend">
                                <span class="input-group-text" id="basic-addon1">Rp</span>
                            </div>
                            <input type="number" class="form-control" name="productSell" id="productSell" placeholder="Contoh: 250000">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="productDesc">Deskripsi Produk</label>
                        <textarea name="productDec" class="form-control" id="productDesc" rows="7" style="height: 100%;" placeholder="Dekorasi Pelaminan 6m dilengkapi dengan karpet dan bunga tangan"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productVideo">Foto Produk 1</label>
                                <input type="file" class="form-control" name="productPhoto" id="productPhoto1" placeholder="Contoh: Dekorasi Pelaminan 6M">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productVideo">Foto Produk 2</label>
                                <input type="file" class="form-control" name="productPhoto" id="productPhoto1" placeholder="Contoh: Dekorasi Pelaminan 6M">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productVideo">Foto Produk 3</label>
                                <input type="file" class="form-control" name="productPhoto" id="productPhoto1" placeholder="Contoh: Dekorasi Pelaminan 6M">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productVideo">Foto Produk 4</label>
                                <input type="file" class="form-control" name="productPhoto" id="productPhoto1" placeholder="Contoh: Dekorasi Pelaminan 6M">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productVideo">Foto Produk 5</label>
                                <input type="file" class="form-control" name="productPhoto" id="productPhoto1" placeholder="Contoh: Dekorasi Pelaminan 6M">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="productVideo">Foto Produk 6</label>
                                <input type="file" class="form-control" name="productPhoto" id="productPhoto1" placeholder="Contoh: Dekorasi Pelaminan 6M">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="productVideo">Video Produk</label>
                        <input type="text" class="form-control" name="productVideo" id="productVideo" placeholder="Contoh: https://www.youtube.com/watch?v=DYM-Id0XpMg">
                    </div>
                </div>
                <div class="col-md-4">
                    <div>
                        <label>Preview Produk</label>
                        <div class="d-flex flex-wrap">
                            <div style="width: 50%;">
                                <img src="/img/uploadLogo.svg" id="previewProduk1" alt="PreviewProduk1" title="Preview Produk 1">
                            </div>
                            <div style="width: 50%;">
                                <img src="/img/uploadLogo.svg" id="previewProduk2" alt="PreviewProduk2" title="Preview Produk 2">
                            </div>
                            <div style="width: 50%;">
                                <img src="/img/uploadLogo.svg" id="previewProduk3" alt="PreviewProduk3" title="Preview Produk 3">
                            </div>
                            <div style="width: 50%;">
                                <img src="/img/uploadLogo.svg" id="previewProduk4" alt="PreviewProduk4" title="Preview Produk 4">
                            </div>
                            <div style="width: 50%;">
                                <img src="/img/uploadLogo.svg" id="previewProduk5" alt="PreviewProduk5" title="Preview Produk 5">
                            </div>
                            <div style="width: 50%;">
                                <img src="/img/uploadLogo.svg" id="previewProduk6" alt="PreviewProduk6" title="Preview Produk 6">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="enableStore" class="col-form-label">Masukkan Ke Toko?</label>
                        <div class="div">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="enableStore" id="storeEnable" value="1">
                                <label class="form-check-label" for="storeEnable">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="enableStore" id="storeDisable" value="0" checked>
                                <label class="form-check-label" for="storeDisable">
                                    Tidak
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="text-right">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Simpan</button>
    </div>
</form>


<?= $this->endSection() ?>