function modalcarga(parametro) {
  if (parametro) {
    $("#carga").modal("show");
    $("body").addClass("carga");
  } else {
    setTimeout(function () {
      $("#carga").modal("hide");
      $("body").removeClass("carga");
    }, 100);
  }
}