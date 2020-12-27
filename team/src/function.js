//---------------------------------------------------------------------------------------------
// add_member
//---------------------------------------------------------------------------------------------
function add_member() {
  var container = document.getElementById('member_container');

  // MaxId
  var array = document.getElementsByClassName('member');
  var maxId = 0;
  for (var i = 0; i < array.length; i++) {
    var element = array.item(i);
    maxId = Math.max(parseInt(element.style.order), maxId);
  }

  // Member
  var member = document.createElement("div");
  member.classList.add("member", "col-12", "col-md-6", "col-lg-4", "col-xxl-3", "p-2");
  member.style.order = maxId + 1;
  member.innerHTML = '<div class="card"><div class="d-flex flex-wrap card-body"><div class="col-12 p-2"><label class="form-label">Aperçu</label><img class="object-fit-cover img-team m-auto mt-2" id="img" src=""></div><div class="col-12 p-2"><label class="form-label">Image ID</label><input class="form-control" type="text" id="imageId" maxlength="50" onchange="update_image_id(this);" value=""></div><div class="col-12 p-2"><label class="form-label">Prénom NOM</label><input class="form-control" type="text" id="name" maxlength="50" value=""></div><div class="col-12 p-2"><label class="form-label">Fonction</label><input class="form-control" type="text" id="function" maxlength="50" value=""></div><div class="d-flex flex-flow-wrap m-3" style="position: absolute; right: 0; top: 0;"><button class="btn-close" onclick="remove_member(this);"></button></div></div></div>';
  container.appendChild(member);
};

//---------------------------------------------------------------------------------------------
// remove_member
//---------------------------------------------------------------------------------------------
function remove_member(e) {
  member = e.parentNode.parentNode.parentNode.parentNode;
  member.parentNode.removeChild(member);
};

//---------------------------------------------------------------------------------------------
// update_image_id
//---------------------------------------------------------------------------------------------
function update_image_id(e) {
  e.parentNode.parentNode.querySelector('[name="img"]').src = "/assets/img-@generated/upload/500/" + e.value + ".jpg";
};

//---------------------------------------------------------------------------------------------
// update_id
//---------------------------------------------------------------------------------------------
function update_id(e) {
  var parent = e.parentNode.parentNode.parentNode.parentNode;
  var realValue = e.value;
  var currentValue = parent.style.order;

  if (parseInt(realValue) > 0) {
    var array = document.getElementsByClassName('member');

    for (var i = 0; i < array.length; i++) {
      var element = array.item(i);
      var elementStyleOrder = parseInt(element.style.order);

      // DOWN - everybody up
      if (currentValue > realValue) {
        if ((realValue <= elementStyleOrder) && (elementStyleOrder < currentValue)) {
          element.style.order = (elementStyleOrder + 1).toString();
          element.querySelector('[name="order"]').value = element.style.order;
        }
      }

      // UP - everybody down
      if (currentValue < realValue) {
        if ((currentValue < elementStyleOrder) && (elementStyleOrder <= realValue)) {
          element.style.order = (elementStyleOrder - 1).toString();
          element.querySelector('[name="order"]').value = element.style.order;
        }
      }

    }
    parent.style.order = parseInt(realValue);
  }
};

//---------------------------------------------------------------------------------------------
// submit_member
//---------------------------------------------------------------------------------------------
function submit_member() {
  let memberList = [];

  var array = document.getElementsByClassName('member');
  for (var i = 0; i < array.length; i++) {
    var element = array.item(i);

    let obj = {
      id: element.style.order,
      imageId: element.querySelector('[name="imageId"]').value,
      name: element.querySelector('[name="name"]').value,
      function: element.querySelector('[name="function"]').value,
    }

    memberList.push(obj);
  }

  var formData = new FormData();
  var request = new XMLHttpRequest();

  formData.set('function', 'submit_member');
  formData.set('data', JSON.stringify(memberList));
  request.open("POST", '/vendor/gnicolas/package/team/src/function.php');
  request.onreadystatechange = function() { //Appelle une fonction au changement d'état.
    if (this.readyState === XMLHttpRequest.DONE && this.status === 200) {
      // upload_number.innerHTML = (parseInt(upload_number.innerHTML) + 1);
    }
  };
  request.send(formData);

  alert('Team saved !');
};