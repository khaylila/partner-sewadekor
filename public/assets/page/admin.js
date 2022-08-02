"use strich";

// function refreshCsrfHash(hash) {
//   $('meta[name="X-CSRF-TOKEN"]').prop("content", hash);
// }

$(".remove-admin").click(function (e) {
  e.preventDefault();
  Swal.fire({
    title: "Yakin?",
    text: "User akan dihapus permanen!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Hapus!",
  }).then((result) => {
    if (result.isConfirmed) {
      doAjax({
        url: "/admin",
        method: "POST",
        data: {
          _method: "DELETE",
          user: $(this).parents("tr").prop("id"),
          // "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').prop("content"),
        },
        redirectUrl,
      });
    }
  });
});

function reqAjax(data) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: data.url,
      method: data.method,
      headers: { "X-Requested-With": "XMLHttpRequest", "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').prop("content") },
      data: data.data,
      success: function (data) {
        resolve(data);
      },
      error: function (xhr) {
        reject(xhr);
      },
    });
  });
}

async function doAjax(data) {
  loading();
  try {
    const ajaxRes = await reqAjax(data);
    Swal.fire({
      title: "Success!",
      text: ajaxRes.message,
      icon: "success",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        console.log(ajaxRes);
        // refreshCsrfHash(ajaxRes.hash);
        redirectUrl(ajaxRes);
        // success.apply(this, [ajaxRes]);
      }
    });
  } catch (err) {
    // refreshCsrfHash(err.hash);
    console.log(err);
    errorhandle(err);
  }
  removeLoading();
}

// addUser
$("#btnAdminAdd").click(function (e) {
  e.preventDefault();
  checkValidation({ type: "POST", url: "/admin", data: $(this).parents("form").serialize() }, redirectUrl);
});

function isInvalid(id, value) {
  $(`#${id}`).addClass("is-invalid").after(`<div id="${id}Feedback" class="invalid-feedback">${value}</div>`);
}

function redirectUrl(data) {
  console.log(data);
  window.location.href = data.url;
}

function checkValidation(ajax, success) {
  $(".is-valid,.is-invalid").removeClass("is-valid is-invalid");
  $("[id$='Feedback']").remove();
  $.ajax({
    method: ajax.type,
    url: ajax.url,
    headers: { "X-Requested-With": "XMLHttpRequest", "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').prop("content") },
    data: ajax.data + "&X-CSRF-TOKEN=" + $('meta[name="X-CSRF-TOKEN"]').prop("content"),
    success: function (data) {
      Swal.fire({
        title: "Success!",
        text: data.message,
        icon: "success",
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
          console.log(success);
          success.apply(this, [data]);
        }
      });
    },
    error: function (xhr) {
      console.log(xhr);
      if (xhr.status == 400) {
        $.each(xhr.responseJSON.errors, function (index, value) {
          isInvalid(index, value);
        });
      } else {
        Swal.fire("Error " + xhr.status, xhr.statusText, "error");
      }
    },
  });
}

function loading() {
  $("#app").prepend(`<div id="loading" class="position-fixed top-0 left-0" style="z-index: 900;">
      <div class="vw-100 vh-100 position-relative d-flex align-items-center justify-content-center">
          <div style="background-color: #eee; opacity: .4; z-index: 10;" class="position-absolute vw-100 vh-100"></div>
          <div class="d-flex flex-column justify-content-center position-absolute" style="z-index: 20;">
              <img src="/img/sewadekorLogo.png" alt="logo" width="300" class="shadow-light rounded-circle mb-3">
              <span class="h3 text-center text-dark" style="z-index: 30;">Loading...</span>
          </div>
      </div>
    </div>`);
}

function removeLoading() {
  $("#loading").remove();
}

function errorhandle(xhr) {
  if (xhr.status == 400) {
    $.each(xhr.responseJSON.errors, function (index, value) {
      isInvalid(index, value);
    });
  } else {
    Swal.fire("Error " + xhr.status, xhr.statusText, "error");
  }
}

// function ajaxRequest(data, success) {
//   const reqAjax = new Promise((dataAjax, success) => {
//     $.ajax({
//       method: "POST",
//       headers: { "X-Requested-With": "XMLHttpRequest" },
//       url: data.url,
//       data: "X-CSRF-TOKEN=" + $('meta[name="X-CSRF-TOKEN"]').prop("content") + "&_method=DELETE",
//       success: function (data) {
//         console.log(data);
//         Swal.fire({
//           title: "Success!",
//           text: data.message,
//           icon: "success",
//           allowOutsideClick: false,
//         }).then((result) => {
//           if (result.isConfirmed) {
//             console.log(success);
//             success.apply(this, [data]);
//           }
//         });
//       },
//       error: function (xhr) {
//         console.log(xhr);
//         if (xhr.status == 400) {
//           $.each(xhr.responseJSON.errors, function (index, value) {
//             isInvalid(index, value);
//           });
//         } else {
//           Swal.fire("Error " + xhr.status, xhr.statusText, "error");
//         }
//       },
//     });
//     console.log("asdf");
//   });
// }
