<?= $this->extend('template/partner/temp') ?>
<?= $this->section('content') ?>

<div class="card">
    <div class="card-body">
        <div class="table-responsive-md">
            <table class="table">
                <thead>
                    <tr>
                        <th style="width: 5%;" scope="col">#</th>
                        <th scope="col" class="text-center">Nama Produk</th>
                        <th style="width: 15%;" scope="col">Terjual</th>
                        <th style="width: 20%;" scope="col">Harga</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="py-2">
                        <th scope="row">1</th>
                        <td>
                            <div class="d-flex justify-content-start align-items-center">
                                <div style="width: 20%;">
                                    <img src="img/1663203837_4572787acda07c63b71a.jpg" alt="HujanTurun" class="img-thumb">
                                </div>
                                <a href="#" class="pl-3 mb-0 line-clamp-2" title="Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta veniam fuga officia consequatur culpa explicabo voluptatem qui id voluptatum, odio aliquam vitae reprehenderit reiciendis fugiat et. Unde iste voluptatem ab!">Lorem ipsum dolor sit amet consectetur adipisicing elit. Dicta veniam fuga officia consequatur culpa explicabo voluptatem qui id voluptatum, odio aliquam vitae reprehenderit reiciendis fugiat et. Unde iste voluptatem ab!</a>
                            </div>
                        </td>
                        <td>Otto</td>
                        <td>@mdo</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>