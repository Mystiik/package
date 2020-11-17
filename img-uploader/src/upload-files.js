var upload_zone = document.getElementById("upload-zone");
var upload_preview = document.getElementById("upload-preview");
var input_file = document.getElementById("input-file");
var upload_number = document.getElementById("upload-number");
var modal = new bootstrap.Modal(document.getElementById('upload-modal'), {
  backdrop: 'static',
  keyboard: false,
  show: false
});
var file_list = [];

upload_zone.addEventListener("click", () => {
  input_file.click();
});

input_file.addEventListener("change", (e) => {
  add_files(e);
  // set_preview();
  change_nb_files_selected();
});

// upload_form.addEventListener('submit', (e) => {
function upload_files() {
  if (file_list.length > 0) {
    modal.show();
    upload_number.innerHTML = '0';
    file_list.forEach((file) => {
      var formData = new FormData();
      var request = new XMLHttpRequest();

      formData.set('file', file);
      request.open("POST", '/vendor/gnicolas/package/img-uploader/src/upload.php');
      request.onreadystatechange = function() { //Appelle une fonction au changement d'état.
        if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
          upload_number.innerHTML = (parseInt(upload_number.innerHTML) + 1);
        }
      };
      request.send(formData);
    });
    unselect();
  }
};


// Dropbox handling
upload_zone.addEventListener("dragenter", dragenter, false);
upload_zone.addEventListener("dragleave", dragleave, false);
upload_zone.addEventListener("mouseleave", dragleave, false);
upload_zone.addEventListener("dragover", dragover, false);
upload_zone.addEventListener("drop", drop, false);

function dragenter(e) {
  e.target.classList.add('upload-zone-border-black');
  e.stopPropagation();
  e.preventDefault();
}

function dragleave(e) {
  e.target.classList.remove('upload-zone-border-black');
}

function dragover(e) {
  e.stopPropagation();
  e.preventDefault();
}

function drop(e) {
  e.stopPropagation();
  e.preventDefault();

  input_file.files = e.dataTransfer.files;
  input_file.dispatchEvent(new Event('change'));
}

function add_files(e) {
  files = e.target.files;
  for (var i = 0; i < files.length; i++) {
    var file = files[i];
    var imageType = /^image\//;

    if (!imageType.test(file.type)) {
      continue;
    }
    set_preview(file);
    file_list.push(file);
  }
}

function set_preview(file) {

  // Container
  var container = document.createElement("div");
  container.classList.add("preview_element", "col-12", "col-md-6", "col-lg-4", "col-xxl-3", "p-2", "flex-flow-wrap");
  // container.id = i;
  container.id = file_list.length;
  // Card
  var card = document.createElement("div");
  card.classList.add("card", "h-100", "d-flex", "flex-flow-wrap");
  // Img
  var img = document.createElement("img");
  img.classList.add("object-fit-cover", "img-preview", "m-2");
  img.file = file;
  // Text_container
  var text_container = document.createElement("div");
  text_container.classList.add("col-5", "m-2");
  text_container.style.alignSelf = "flex-end";
  // Filename
  var filename = document.createElement("h6");
  filename.innerHTML = file.name;
  // Filesize
  var filesize = document.createElement("h6");
  filesize.classList.add("mb-2", "fs--1", "text-400", "lh-1");
  var sOutput = file.size + " o";
  for (var aMultiples = ["Ko", "Mo", "Go", "To", "Po", "Eo", "Zo", "Yo"], nMultiple = 0, nApprox = file.size / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) sOutput = nApprox.toFixed(2) + " " + aMultiples[nMultiple];
  filesize.innerHTML = sOutput;
  // RemoveFile
  var removefile = document.createElement("a");
  removefile.classList.add("mb-2", "dropdown-item");
  removefile.style.textAlign = "center";
  removefile.style.backgroundColor = "aliceblue";
  removefile.style.cursor = "pointer";
  removefile.innerHTML = "Remove File";
  removefile.setAttribute('onclick', 'remove_element(this);');

  // Construct DOM element
  text_container.appendChild(filename);
  text_container.appendChild(filesize);
  text_container.appendChild(removefile);

  card.appendChild(img);
  card.appendChild(text_container);
  container.appendChild(card);

  upload_preview.appendChild(container);

  var reader = new FileReader();
  reader.onload = (function(aImg) { return function(e) { aImg.src = e.target.result; }; })(img);
  reader.readAsDataURL(file);
  // }
}

function remove_element(e) {
  container = e.parentNode.parentNode.parentNode;
  file_list.splice(container.id, 1);

  var elements = document.getElementsByClassName('preview_element');
  for (var i = 0; i < elements.length; i++) {
    element = elements[i];
    if (element.id > container.id) { element.id--; }
  }

  container.remove();
  change_nb_files_selected();
}

function change_nb_files_selected() {
  upload_zone.innerHTML = file_list.length + " file(s) selected";
}

function unselect() {
  file_list = [];
  upload_preview.innerHTML = "";
  change_nb_files_selected();
}