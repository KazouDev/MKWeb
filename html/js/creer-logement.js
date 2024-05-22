const previewDiv = document.getElementById("image-preview");
const imageInput = document.getElementById("image-input");
const logementForm = document.getElementById("nv-logement");

const btnAddAmenagement = document.getElementById("ajouter__amenagement");
const divAmenagement = document.getElementById("list__amenagement");
const nameAmenagement = document.getElementById("name__amenagement");
const distanceAmenagement = document.getElementById("distance__amenagement");

const inputCheckbox = document.querySelectorAll(".input__checkbox");

inputCheckbox.forEach((check) => {
  check.addEventListener("click", (e) => {
    if (e.target.matches("input")) return;
    let checkbox = e.currentTarget.querySelector("input");
    checkbox.checked = !checkbox.checked;
  });
});

var imageList = [];

btnAddAmenagement.addEventListener("click", () => {
  if (nameAmenagement.value.length < 3) {
    nameAmenagement.focus();
    return;
  }

  let amenagement = {
    name: nameAmenagement.value,
    distanceID: distanceAmenagement[distanceAmenagement.selectedIndex].value,
    distance:
      distanceAmenagement[distanceAmenagement.selectedIndex].textContent,
  };

  let div = document.createElement("div");
  let btnDel = document.createElement("button");
  let p = document.createElement("p");
  let span = document.createElement("span");
  let input = document.createElement("input");

  btnDel.type = "button";
  btnDel.classList.add("btn__remove");
  btnDel.textContent = "X";

  btnDel.addEventListener("click", () => {
    div.remove();
  });

  input.type = "hidden";
  input.name = "amenagement[]";
  input.value = amenagement.name + ";;" + amenagement.distanceID;

  p.textContent = amenagement.name;
  span.textContent = amenagement.distance;

  div.appendChild(p);
  div.appendChild(span);
  div.append(input);

  div.append(btnDel);

  divAmenagement.appendChild(div);
});

imageInput.addEventListener("change", (e) => {
  const files = e.target.files;
  const preview = document.getElementById("image-preview");

  if (imageList.length == 0) previewDiv.innerHTML = "";

  Array.from(files).forEach((file) => {
    imageList.push(file);

    const reader = new FileReader();
    reader.onload = function (e) {
      const btnDel = document.createElement("button");
      const div = document.createElement("div");
      const img = document.createElement("img");

      btnDel.type = "button";
      btnDel.classList.add("btn__remove");
      btnDel.textContent = "X";

      btnDel.addEventListener("click", () => {
        div.remove();
        imageList = imageList.filter((f) => f.name != file.name);
        console.log(imageList);
      });

      img.src = e.target.result;
      div.append(img);
      div.append(btnDel);
      preview.appendChild(div);
    };
    reader.readAsDataURL(file);
  });

  e.target.value = "";
});

const latitudeInput = document.getElementById("latitude");
const longitudeInput = document.getElementById("longitude");

const villeInput = document.getElementById("commune");
const regionInput = document.getElementById("region");
const departementInput = document.getElementById("departement");
const paysInput = document.getElementById("pays");
const voieInput = document.getElementById("voie");
const numVoieInput = document.getElementById("num_voie");

document.getElementById("form__submit").addEventListener("click", async (e) => {
  console.log("click form submit");
  e.preventDefault();
  if (!logementForm.reportValidity()) return;
  if (imageList.length > 0) {
    const data = new DataTransfer();

    imageList.forEach((img) => {
      data.items.add(img);
    });

    imageInput.files = data.files;

    imageInput.name = "images[]";
  }

  let adresse =
    numVoieInput.value +
    " " +
    voieInput.value +
    " " +
    villeInput.value +
    " " +
    departementInput.value +
    " " +
    regionInput.value +
    " " +
    paysInput.value;

  const url =
    "https://api.opencagedata.com/geocode/v1/json?q=" +
    encodeURIComponent(adresse) +
    "&key=90a3f846aa9e490d927a787facf78c7e";

  const response = await fetch(url);
  const data = await response.json();

  if (data.results.length > 0) {
    latitudeInput.value = data.results[0].geometry.lat;
    longitudeInput.value = data.results[0].geometry.lng;
    console.log(data);
  } else {
    console.error("Adresse invalide.");
    document.getElementById("voie").focus();
    return;
  }

  paysInput.disabled = false;
  regionInput.disabled = false;

  logementForm.submit();
});