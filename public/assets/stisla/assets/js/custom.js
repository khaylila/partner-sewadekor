/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

function refreshTokenHash(hash) {
  $('meta[name="X-CSRF-TOKEN"]').prop("content", hash);
}

function reqAjax(data) {
  return new Promise((resolve, reject) => {
    $.ajax({
      url: data.url,
      method: data.method,
      headers: {
        "X-Requested-With": "XMLHttpRequest",
        "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').prop("content"),
      },
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

async function doAjax(data, success) {
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
        refreshTokenHash(ajaxRes.hash);
        success.apply(this, [ajaxRes]);
      }
    });
  } catch (err) {
    refreshTokenHash(err.hash);
    console.log(err);
    errorHandler(err);
  }
  removeLoading();
}
function isInvalid(id, value) {
  $(`#${id}`)
    .addClass("is-invalid")
    .after(`<div id="${id}Feedback" class="invalid-feedback">${value}</div>`);
}

function redirectUrl(data) {
  console.log(data);
  window.location.href = data ? data.url : window.location.href;
}

function checkValidation(ajax, success) {
  $(".is-valid,.is-invalid").removeClass("is-valid is-invalid");
  $("[id$='Feedback']").remove();
  $.ajax({
    method: ajax.type,
    url: ajax.url,
    headers: {
      "X-Requested-With": "XMLHttpRequest",
      "X-CSRF-TOKEN": $('meta[name="X-CSRF-TOKEN"]').prop("content"),
    },
    data:
      ajax.data +
      "&X-CSRF-TOKEN=" +
      $('meta[name="X-CSRF-TOKEN"]').prop("content"),
    success: function (data) {
      Swal.fire({
        title: "Success!",
        text: data.message,
        icon: "success",
        allowOutsideClick: false,
      }).then((result) => {
        if (result.isConfirmed) {
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
  $("#app")
    .prepend(`<div id="loading" class="position-fixed top-0 left-0" style="z-index: 900;">
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

function errorHandler(xhr) {
  if (xhr.status === 400) {
    $.each(xhr.responseJSON.errors, function (index, value) {
      isInvalid(index, value);
    });
    refreshTokenHash(xhr.responseJSON.tokenHash);
  } else {
    Swal.fire({
      title: "Error " + xhr.status,
      text: xhr.statusText,
      icon: "error",
      allowOutsideClick: false,
    }).then((result) => {
      if (result.isConfirmed) {
        redirectUrl();
      }
    });
  }
}
