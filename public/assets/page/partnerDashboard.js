"use strich";
$(document).ready(() => {
  doAjax({ url: "/account/merchant/check", method: "POST" }, null, redirectUrl);
});
// $(document).ready(() => {
//   loading();
//   $("body").fireModal({
//     addOnClass: "modalPartnerIdentity",
//     size: "modal-lg",
//     title: "Tambah identitas perusahaan",
//     body: `<form action="/partner/identity" method="POST">
//               <div class="row">
//                   <div class="col-lg-6">
//                       <div class="form-group">
//                           <label for="factoryName">Nama Perusahaan</label>
//                           <input type="text" class="form-control" id="factoryName" placeholder="Masukkan nama perusahaan" required>
//                       </div>
//                   </div>
//                   <div class="col-lg-6">
//                       <div class="form-group">
//                           <label for="factoryNpwp">NPWP</label>
//                           <input type="text" class="form-control cleave-npwp" id="factoryNpwp" placeholder="11.222.333.4-555.666" required>
//                       </div>
//                   </div>
//               </div>
//               <div class="row">
//                   <div class="col-lg-6">
//                       <div class="form-group">
//                           <label for="province">Provinsi</label>
//                           <input type="text" class="form-control" id="province" required>
//                       </div>
//                   </div>
//                   <div class="col-lg-6">
//                       <div class="form-group">
//                           <label for="regency">Kabupaten</label>
//                           <input type="text" class="form-control" id="regency" required>
//                       </div>
//                   </div>
//               </div>
//               <div class="row">
//                   <div class="col-lg-6">
//                       <div class="form-group">
//                           <label for="district">Kecamatan</label>
//                           <input type="text" class="form-control" id="district" required>
//                       </div>
//                   </div>
//                   <div class="col-lg-6">
//                       <div class="form-group">
//                           <label for="urban">Desa/Dusun</label>
//                           <input type="text" class="form-control" id="urban" required>
//                       </div>
//                   </div>
//               </div>
//               <div class="form-group">
//                   <label for="address">Alamat</label>
//                   <textarea class="form-control" id="address" placeholder="Masukkan alamat perusahaan" required></textarea>
//               </div>
//               <div class="row">
//                   <div class="col-lg-6">
//                       <div class="form-group">
//                           <label for="factoryPhone">Telepon</label>
//                           <input type="text" class="form-control" id="factoryPhone" required>
//                       </div>
//                   </div>
//                   <div class="col-lg-6">
//                       <div class="form-group">
//                         <label>Jangkauan Area</label>
//                         <select class="form-control" id="coverageArea" multiple="" required></select>
//                       </div>
//                   </div>
//               </div>
//           </form>`,
//     autoShow: true,
//     backdrop: true,
//     created: createModal,
//     closeButton: false,
//     buttons: [
//       {
//         submit: true,
//         class: "btn btn-primary",
//         id: "savePartnerIdentity",
//         text: '<i class="fas fa-save"> Simpan',
//         handler: () => {
//           alert("Clicked");
//         },
//       },
//     ],
//   });
// });

// function createModal() {
//   // var cleaveNPWP = new Cleave(".cleave-npwp", {
//   //   blocks: [2, 3, 3, 1, 3, 3],
//   // });
//   $("#coverageArea").select2({
//     ajax: {
//       delay: 300,
//       url: "/api/regencies/find",
//       dataType: "json",
//       data: function (params) {
//         var query = {
//           query: params.term,
//         };
//         return query;
//       },
//       processResults: function (data) {
//         console.log(data);
//         return { results: data.regencies };
//       },
//     },
//     placeholder: "Masukkan Nama Kabupaten/Kota",
//     minimumInputLength: 3,
//   });
//   getSelect2({
//     id: "province",
//     option: {
//       dropdownParent: $(".modalPartnerIdentity"),
//       ajax: {
//         delay: 300,
//         url: "/api/provinces",
//         dataType: "json",
//         data: function (params) {
//           var query = {
//             query: params.term,
//           };
//           return query;
//         },
//         processResults: function (data) {
//           console.log(data);
//           return { results: data.provinces };
//         },
//       },
//       placeholder: "Pilih Provinsi",
//     },
//   });
//   $("#province").change(function () {
//     getSelect2({
//       id: "regency",
//       option: {
//         ajax: {
//           delay: 300,
//           url: "/api/regencies/" + $(this).val(),
//           dataType: "json",
//           data: function (params) {
//             var query = {
//               query: params.term,
//             };
//             return query;
//           },
//           processResults: function (data) {
//             return { results: data.regencies };
//           },
//         },
//         placeholder: "Pilih Kabupaten",
//       },
//     });
//     $("#regency").prop("disabled", false).empty();
//     $("#district, #urban").prop("disabled", true).empty();
//   });
//   $("#regency").change(function () {
//     getSelect2({
//       id: "district",
//       option: {
//         ajax: {
//           delay: 300,
//           url: "/api/districts/" + $(this).val(),
//           dataType: "json",
//           data: function (params) {
//             var query = {
//               query: params.term,
//             };
//             return query;
//           },
//           processResults: function (data) {
//             return { results: data.districts };
//           },
//         },
//         placeholder: "Pilih Kecamatan",
//       },
//     });
//     $("#district").prop("disabled", false).empty();
//     $("#urban").prop("disabled", true).empty();
//   });
//   $("#district").change(function () {
//     getSelect2({
//       id: "urban",
//       option: {
//         ajax: {
//           delay: 300,
//           url: "/api/urban/" + $(this).val(),
//           dataType: "json",
//           data: function (params) {
//             var query = {
//               query: params.term,
//             };
//             return query;
//           },
//           processResults: function (data) {
//             return { results: data.urbans };
//           },
//         },
//         placeholder: "Pilih Desa",
//       },
//     });
//     $("#urban").prop("disabled", false).empty();
//   });
//   removeLoading();
// }

// $(document).on("#coverageArea", "click", function (e) {
//   e.preventDefault();
// });
// $(document).ready(function () {
// doAjax({ url: "/partner/identity/check", method: "POST", data: {} }, checkIdentity);
// });

// function checkIdentity(data) {}
