"use strich";

$(document).ready(function () {
  loading();
  $(".files").attr("data-label", "Drag file here or click the above button");
  $("#factoryLogo").change(function (e) {
    if (e.target.files.length != 0) {
      let fileName = e.target.files[0];
      if (fileName.type.match("image.*") === null) {
        $("#logoPreview").prop("src", "/img/notImage.svg").prop("title", "Format file bukan image.");
      } else {
        const reader = new FileReader();
        reader.onload = function (e) {
          $("#logoPreview").prop("src", e.target.result).prop("title", fileName.fileName);
        };
        reader.readAsDataURL(fileName);
      }
    } else {
      $("#logoPreview").prop("src", "/img/uploadLogo.svg").prop("title", "Pilih gambar untuk diupload.");
    }
  });
  new Cleave(".cleave-npwp", {
    blocks: [2, 3, 3, 1, 3, 3],
  });
  new Cleave(".cleave-phone", {
    phone: true,
    phoneRegionCode: "ID",
  });
  $("#coverageArea").select2({
    ajax: {
      delay: 300,
      url: "/api/regencies/find",
      dataType: "json",
      data: function (params) {
        var query = {
          query: params.term,
        };
        return query;
      },
      processResults: function (data) {
        return { results: data.regencies };
      },
    },
    placeholder: "Masukkan Nama Kabupaten/Kota",
    minimumInputLength: 3,
  });
  getSelect2({
    id: "province",
    option: {
      ajax: {
        delay: 300,
        url: "/api/provinces",
        dataType: "json",
        data: function (params) {
          var query = {
            query: params.term,
          };

          return query;
        },
        processResults: function (data) {
          return { results: data.provinces };
        },
      },
      placeholder: "Pilih Provinsi",
    },
  });
  getSelect2({
    id: "regency",
    option: {
      ajax: {
        delay: 300,
        url: "/api/regencies/" + $("#province").val(),
        dataType: "json",
        data: function (params) {
          var query = {
            query: params.term,
          };
          return query;
        },
        processResults: function (data) {
          return { results: data.regencies };
        },
      },
      placeholder: "Pilih Kabupaten",
    },
  });
  getSelect2({
    id: "district",
    option: {
      ajax: {
        delay: 300,
        url: "/api/districts/" + $("#regency").val(),
        dataType: "json",
        data: function (params) {
          var query = {
            query: params.term,
          };
          return query;
        },
        processResults: function (data) {
          return { results: data.districts };
        },
      },
      placeholder: "Pilih Kecamatan",
    },
  });
  getSelect2({
    id: "urban",
    option: {
      ajax: {
        delay: 300,
        url: "/api/urban/" + $("#district").val(),
        dataType: "json",
        data: function (params) {
          var query = {
            query: params.term,
          };
          return query;
        },
        processResults: function (data) {
          return { results: data.urbans };
        },
      },
      placeholder: "Pilih Desa",
    },
  });
  $("#province").change(function () {
    getSelect2({
      id: "regency",
      option: {
        ajax: {
          delay: 300,
          url: "/api/regencies/" + $(this).val(),
          dataType: "json",
          data: function (params) {
            var query = {
              query: params.term,
            };
            return query;
          },
          processResults: function (data) {
            return { results: data.regencies };
          },
        },
        placeholder: "Pilih Kabupaten",
      },
    });
    $("#regency").prop("disabled", false).empty();
    $("#district, #urban").prop("disabled", true).empty();
  });
  $("#regency").change(function () {
    getSelect2({
      id: "district",
      option: {
        ajax: {
          delay: 300,
          url: "/api/districts/" + $(this).val(),
          dataType: "json",
          data: function (params) {
            var query = {
              query: params.term,
            };
            return query;
          },
          processResults: function (data) {
            return { results: data.districts };
          },
        },
        placeholder: "Pilih Kecamatan",
      },
    });
    $("#district").prop("disabled", false).empty();
    $("#urban").prop("disabled", true).empty();
  });
  $("#district").change(function () {
    getSelect2({
      id: "urban",
      option: {
        ajax: {
          delay: 300,
          url: "/api/urban/" + $(this).val(),
          dataType: "json",
          data: function (params) {
            var query = {
              query: params.term,
            };
            return query;
          },
          processResults: function (data) {
            return { results: data.urbans };
          },
        },
        placeholder: "Pilih Desa",
      },
    });
    $("#urban").prop("disabled", false).empty();
  });
  removeLoading();

  $("#saveIdentity").submit(function (e) {
    e.preventDefault();
    // const formData = $(this).serialize();
    const formData = new FormData($(this)[0]);
    console.log(formData);
    // $.ajax({
    //   async: true,
    //   method: "POST",
    //   url: "/account/merchant",
    //   data: formData,
    //   cache: false,
    //   processData: false,
    //   contentType: false,
    //   headers: {
    //     "X-Requested-With": "XMLHttpRequest",
    //     "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').prop("content"),
    //   },
    //   success: function (data) {
    //     console.log(data);
    //     console.table(data);
    //   },
    //   error: function (xhr) {
    //     console.log(xhr.responseText);
    //   },
    // });
    doAjax({ url: $(this).prop("action"), method: $(this).prop("method"), data: formData }, redirectUrl);
  });

  $("#saveLogoIdentity").submit(function (e) {
    e.preventDefault();
    // const formData = $(this).serialize();
    const formData = new FormData($(this)[0]);
    console.log(formData);
    // $.ajax({
    //   async: true,
    //   method: "POST",
    //   url: "/account/merchant",
    //   data: formData,
    //   cache: false,
    //   processData: false,
    //   contentType: false,
    //   headers: {
    //     "X-Requested-With": "XMLHttpRequest",
    //     "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').prop("content"),
    //   },
    //   success: function (data) {
    //     console.log(data);
    //     console.table(data);
    //   },
    //   error: function (xhr) {
    //     console.log(xhr.responseText);
    //   },
    // });
    doAjax({ url: $(this).prop("action"), method: $(this).prop("method"), data: formData }, redirectUrl);
  });
});

// comming soon
//   $(".my-dropzone").d
// const dropzone = new Dropzone(".my-dropzone",
// $(".my-dropzone").dropzone({
//   url: "/account/merchant/avatar",
//   autoProcessQueue: false,
//   uploadMultiple: false,
//   maxFiles: 1,
//   addRemoveLinks: true,
//   paramName: "avatar",
//   maxFilesize: 1,
//   headers: {
//     "X-Requested-With": "XMLHttpRequest",
//     "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').prop("content"),
//   },
// });
