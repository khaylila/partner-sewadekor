"use strich";

$(".reset-password").click(function (e) {
  e.preventDefault();
  Swal.fire({
    title: "Yakin?",
    text: "Password baru akan dikirimkan langsung ke email user",
    icon: "warning",
    showCancelButton: true,
  }).then((result) => {
    if (result.isConfirmed) {
      doAjax(
        {
          url: "/partner",
          method: "POST",
          data: {
            _method: "PUT",
            user: $(this).parents("tr").prop("id"),
          },
        },
        redirectUrl
      );
    }
  });
});

$(".remove-user").click(function (e) {
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
      doAjax(
        {
          url: "/partner",
          method: "POST",
          data: {
            _method: "DELETE",
            user: $(this).parents("tr").prop("id"),
          },
        },
        redirectUrl
      );
    }
  });
});

// addUser
$("#btnAdd").click(function (e) {
  e.preventDefault();
  doAjax(
    {
      url: "/partner",
      method: "POST",
      data: $(this).parents("form").serialize(),
    },
    redirectUrl
  );
});
