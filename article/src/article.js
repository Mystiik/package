// var upload_zone = document.getElementById("upload-zone");
// var upload_preview = document.getElementById("upload-preview");
// var input_file = document.getElementById("input-file");
// var upload_number = document.getElementById("upload-number");
// var modal = new bootstrap.Modal(document.getElementById('upload-modal'), {
//   backdrop: 'static',
//   keyboard: false,
//   show: false
// });
// var file_list = [];

function modify_article(id) {
  var formData = new FormData();
  var request = new XMLHttpRequest();

  formData.set('id', id);
  request.open("POST", '/vendor/gnicolas/package/img-uploader/src/upload.php');
  request.onreadystatechange = function() { //Appelle une fonction au changement d'état.
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      upload_number.innerHTML = (parseInt(upload_number.innerHTML) + 1);
    }
  };
  request.send(formData);
};