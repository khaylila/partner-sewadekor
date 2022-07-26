<?= $this->extend('template/temp') ?>
<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Admin</h4>
            </div>
            <form method="POST" action="/admin">
                <div class="card-body">
                    <div class="form-group">
                        <label>Nama</label>
                        <input type="text" class="form-control" id="nameAdmin" name="nameAdmin" value="" placeholder="Masukkan nama admin" tabindex="1" required autofocus>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="emailAdmin" id="emailAdmin" class="form-control" required value="" placeholder="Masukkan email admin" tabindex="2">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="roleAdmin" id="roleAdmin" tabindex="3">
                            <option value="admin" selected>Admin</option>
                        </select>
                    </div>
                </div>
                <div class="card-footer text-right">
                    <button class="btn btn-primary" id="btnAdminAdd" tabindex="4">Tambah Admin</button>
                </div>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Dafar Admin</h4>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;" scope="col">#</th>
                            <th class="text-center" style="width: 35%;" scope="col">Nama</th>
                            <th class="text-center" style="width: 35%;" scope="col">Email</th>
                            <th class="text-center" style="width: 20%;" scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($listUserAdmin) > 0) : ?>
                            <?php $i = 1; ?>
                            <?php foreach ($listUserAdmin as $admin) : ?>
                                <tr id="user-<?= $admin['user_id']; ?>">
                                    <th class="text-center" scope="row"><?= $i++; ?></th>
                                    <td class="text-left"><?= $admin['name']; ?></td>
                                    <td><?= $admin['email']; ?></td>
                                    <td class="text-center"><a class="reset-password" href="#"><i class="fas fa-key"></i></a><?= strtolower($admin['name']) !== 'admin' ? '<a class="remove-user" href="#"><i class="fas fa-trash"></i></a>' : ''; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="4" class="text-center">Admin tidak ditemukan!</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script src="/assets/page/admin.js"></script>
<?= $this->endSection() ?>