<?php $active = explode('.', $active); ?>
<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="/">Partner Panel</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="/">PSD</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="nav-item <?= $active[0] == 'dashboard' ? "active" : ""; ?>">
                <a href="/" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a>
            </li>

            <li class="menu-header">Account</li>
            <li class="nav-item dropdown <?= $active[0] == 'account' ? "active" : ""; ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Account</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= $active[1] ?? null == 'merchant' ? "active" : ""; ?>"><a class="nav-link" href="/account/merchant">Merchant</a></li>
                </ul>
            </li>

            <!-- <li class="menu-header">Admin</li>
            <li class="nav-item dropdown <?= $active[0] == 'adminPage' ? "active" : ""; ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Admin</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= $active[0] == 'adminPage' ? "active" : ""; ?>"><a class="nav-link" href="/admin">List</a></li>
                </ul>
            </li>

            <li class="menu-header">Partner</li>
            <li class="nav-item dropdown <?= $active[0] == 'partnerPage' ? "active" : ""; ?>">
                <a href="#" class="nav-link has-dropdown"><i class="fas fa-users"></i><span>Partner</span></a>
                <ul class="dropdown-menu">
                    <li class="<?= $active[0] == 'partnerPage' ? "active" : ""; ?>"><a class="nav-link" href="/partner">List</a></li>
                </ul>
            </li>

            <li class="menu-header">Tentang Kami</li>
            <li>
                <a class="nav-link active" href="/credits"><i class="fas fa-pencil-ruler"></i> <span>Credits</span></a>
            </li> -->
        </ul>
    </aside>
</div>