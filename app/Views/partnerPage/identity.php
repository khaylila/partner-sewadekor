<?= $this->extend('template/partner/temp') ?>
<?= $this->section('content') ?>
<div class="card">
    <div class="card-header">
        <h4>Identitas Merchant</h4>
    </div>
    <form action="/account/merchant" id="saveIdentity" method="POST">
        <div class="card-body">
            <?php if ($checkIdentity) : ?>
                <input type="hidden" name="_method" value="PUT">
            <?php endif; ?>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="factoryName">Nama Perusahaan</label>
                        <input type="text" class="form-control" id="factoryName" name="factoryName" value="<?= $dataIdentity['name'] ?? ''; ?>" placeholder="Masukkan nama perusahaan" required>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="factoryNpwp">NPWP</label>
                        <input type="text" class="form-control cleave-npwp" id="factoryNPWP" name="factoryNPWP" value="<?= $dataIdentity['npwp'] ?? ''; ?>" placeholder="11.222.333.4-555.666">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="province">Provinsi</label>
                        <select type="text" class="form-control custom-select" id="province" name="province" required>
                            <option value="<?= $dataIdentity['address_desc']['province']['id'] ?? ''; ?>" selected><?= $dataIdentity['address_desc']['province']['name'] ?? ''; ?></option>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="regency">Kabupaten/Kota</label>
                        <select type="text" class="form-control custom-select" id="regency" name="regency" required <?= $checkIdentity ? "" : "disabled"; ?>>
                            <?php if ($checkIdentity) : ?>
                                <option value="<?= $dataIdentity['address_desc']['regency']['id'] ?? ''; ?>" selected><?= $dataIdentity['address_desc']['regency']['name'] ?? ''; ?></option>
                            <?php else : ?>
                                <option value="" readonly hidden>Pilih Kabupaten/Kota</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="district">Kecamatan</label>
                        <select type="text" class="form-control custom-select" id="district" name="district" required <?= $checkIdentity ? "" : "disabled"; ?>>
                            <?php if ($checkIdentity) : ?>
                                <option value="<?= $dataIdentity['address_desc']['district']['id'] ?? ''; ?>" selected><?= $dataIdentity['address_desc']['district']['name'] ?? ''; ?></option>
                            <?php else : ?>
                                <option value="" readonly hidden>Pilih Kecamatan</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="urban">Desa/Dusun</label>
                        <select type="text" class="form-control custom-select" id="urban" name="urban" required <?= $checkIdentity ? "" : "disabled"; ?>>
                            <?php if ($checkIdentity) : ?>
                                <option value="<?= $dataIdentity['address_desc']['urban']['id'] ?? ''; ?>" selected><?= $dataIdentity['address_desc']['urban']['name'] ?? ''; ?></option>
                            <?php else : ?>
                                <option value="" readonly hidden>Pilih Desa/Dusun</option>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
                        <label for="address">Alamat</label>
                        <textarea class="form-control" id="address" name="address" placeholder="Masukkan alamat perusahaan" required><?= $checkIdentity ? $dataIdentity['address'] : ''; ?></textarea>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label for="factoryPhone">Telepon</label>
                        <input type="tel" class="cleave-phone form-control" id="factoryPhone" name="factoryPhone" value="<?= $dataIdentity['phone']; ?>" required>
                    </div>
                    <?php if (!$checkIdentity) : ?>
                        <div class="form-group row">
                            <div class="col-md-9">
                                <label for="factoryLogo">Logo Perusahaan</label>
                                <input id="factoryLogo" name="factoryLogo" type="file" class="form-control" required>
                            </div>
                            <div class="col-md-3">
                                <img id="logoPreview" class="border rounded p-0" style="width: 100%;" src="/img/uploadLogo.svg" alt="">
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- removed soon -->
                    <!-- <div class="form-group files position-relative">
                        <img class="partner-logo text-center" src="https://i.imgur.com/VXWKoBD.png" alt="">
                        <label class="my-auto">Unggah Logo Perusahaan</label>
                        <input id="factoryLogo" name="factoryLogo" type="file" class="form-control" required accept="image/*" />
                    </div> -->
                    <!-- <img src="" id="imgTest" alt=""> -->
                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        <label>Cakupan Area</label>
                        <select class="form-control" id="coverageArea" name="coverageArea[]" multiple="" required>
                            <?php foreach ($dataIdentity['coverage'] as $coverage) : ?>
                                <option value="<?= $coverage['id']; ?>" selected><?= $coverage['name'] . ', ' . $coverage['province']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<?php if ($checkIdentity) : ?>
    <div class="card">
        <div class="card-header">
            <h4>Logo Merchant</h4>
        </div>
        <form action="/account/merchant/logo" id="saveLogoIdentity" method="POST">
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-3">
                        <img id="logoPreview" class="border rounded p-0" style="width: 100%;" src="/img/<?= $dataIdentity['logo']; ?>" alt="Logo Merchant">
                    </div>
                    <div class="col-md-5">
                        <label for="factoryLogo">Ubah Logo</label>
                        <input id="factoryLogo" name="factoryLogo" type="file" class="form-control" required>
                    </div>
                </div>
            </div>
            <div class="card-footer text-right">
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
<?php endif; ?>
<?php if ($checkIdentity) : ?>
    <script src=" /assets/page/partnerWithIdentity.js"></script>
    <script>
        // $(document).ready(function() {
        //     getSelect2({
        //         id: "province",
        //         option: {
        //             ajax: {
        //                 delay: 300,
        //                 url: "/api/provinces/id",
        //                 dataType: "json",
        //                 data: function(params) {
        //                     var query = {
        //                         query: 35,
        //                     };
        //                     console.log(query);
        //                     return query;
        //                 },
        //                 processResults: function(data) {
        //                     console.log(data);
        //                     return {
        //                         results: data.provinces
        //                     };
        //                 },
        //             },
        //             placeholder: "Pilih Provinsi",
        //         },
        //     });
        // });
    </script>
<?php else : ?>
    <script src=" /assets/page/partnerIdentity.js"></script>
<?php endif; ?>
<?= $this->endSection() ?>