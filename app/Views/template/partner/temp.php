<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <?= csrf_meta(); ?>

    <title><?= $title; ?> &mdash; Partner Panel sewadekor.id</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>

    <!-- CSS Libraries -->
    <?php if (in_array("jqvmap", $lib)) : ?>
        <link rel="stylesheet" href="/assets/stisla/node_modules/jqvmap/dist/jqvmap.min.css">
    <?php endif; ?>
    <?php if (in_array("summernote", $lib)) : ?>
        <link rel="stylesheet" href="/assets/stisla/node_modules/summernote/dist/summernote-bs4.css">
    <?php endif; ?>
    <?php if (in_array("owlCarousel", $lib)) : ?>
        <link rel="stylesheet" href="/assets/stisla/node_modules/owl.carousel/dist/assets/owl.carousel.min.css">
        <link rel="stylesheet" href="/assets/stisla/node_modules/owl.carousel/dist/assets/owl.theme.default.min.css">
    <?php endif; ?>
    <?php if (in_array("iziToast", $lib)) : ?>
        <link rel="stylesheet" href="/assets/stisla/node_modules/izitoast/dist/iziToast.min.css">
    <?php endif; ?>
    <?php if (in_array("select2", $lib)) : ?>
        <link rel="stylesheet" href="/assets/stisla/node_modules/select2/dist/css/select2.min.css">
    <?php endif; ?>
    <?php if (in_array("dropzone", $lib)) : ?>
        <link rel="stylesheet" href="/assets/stisla/node_modules/dropzone/dist/min/dropzone.min.css">
    <?php endif; ?>

    <!-- Template CSS -->
    <link rel="stylesheet" href="/assets/stisla/assets/css/style.css">
    <link rel="stylesheet" href="/assets/stisla/assets/css/components.css">
    <link rel="stylesheet" href="/assets/css/custom.css">
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <?= $this->include('template/partner/navbar'); ?>
            <?= $this->include('template/partner/sidebar'); ?>
            <!-- Main Content -->
            <div class="main-content">
                <section class="section">
                    <?php if (!isset($sectionHeader)) : ?>
                        <div class="section-header">
                            <h1><?= $title; ?></h1>
                            <!-- Breadcrumb -->
                            <div class="section-header-breadcrumb">
                                <?php foreach ($breadcrumb as $subtitle) : ?>
                                    <div class="breadcrumb-item"><?= isset($subtitle['href']) ? '<a href="/admin/add">' . $subtitle['title'] . '</a>' : $subtitle['title']; ?></div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="section-body">
                        <?= $this->renderSection('content'); ?>
                    </div>
                </section>
            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2022 <div class="bullet"></div> Created By <a href="https://www.instagram.com/roiyan_r/">Khaylila</a>
                </div>
                <div class="footer-right">
                    1.0.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script src="/assets/stisla/assets/js/stisla.js"></script>

    <!-- JS Libraies -->
    <?php if (in_array("jquerySparkline", $lib)) : ?>
        <script src="/assets/stisla/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <?php endif; ?>
    <?php if (in_array("chartJs", $lib)) : ?>
        <script src="/assets/stisla/node_modules/chart.js/dist/Chart.min.js"></script>
    <?php endif; ?>
    <?php if (in_array("owlCarousel", $lib)) : ?>
        <script src="/assets/stisla/node_modules/owl.carousel/dist/owl.carousel.min.js"></script>
    <?php endif; ?>
    <?php if (in_array("summernote", $lib)) : ?>
        <script src="/assets/stisla/node_modules/summernote/dist/summernote-bs4.js"></script>
    <?php endif; ?>
    <?php if (in_array("jqueryChocolat", $lib)) : ?>
        <script src="/assets/stisla/node_modules/chocolat/dist/js/jquery.chocolat.min.js"></script>
    <?php endif; ?>
    <script src="/assets/stisla/node_modules/sweetalert2/dist/sweetalert2.all.js"></script>
    <?php if (in_array("select2", $lib)) : ?>
        <script src="/assets/stisla/node_modules/select2/dist/js/select2.full.min.js"></script>
    <?php endif; ?>
    <?php if (in_array("cleave", $lib)) : ?>
        <script src="/assets/stisla/node_modules/cleave.js/dist/cleave.min.js"></script>
        <script src="/assets/stisla/node_modules/cleave.js/dist/addons/cleave-phone.id.js"></script>
    <?php endif; ?>
    <?php if (in_array("dropzone", $lib)) : ?>
        <script src="/assets/stisla/node_modules/dropzone/dist/min/dropzone.min.js"></script>
    <?php endif; ?>

    <!-- <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->

    <!-- Template JS File -->
    <script src="/assets/stisla/assets/js/scripts.js"></script>
    <script src="/assets/stisla/assets/js/custom.js"></script>
</body>

</html>