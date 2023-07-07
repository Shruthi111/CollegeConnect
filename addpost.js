let uploadImgInput = document.getElementById("upload_img_input");

uploadImgInput.addEventListener("change", function(event) {
  let file = event.target.files[0];
  console.log(file);
  if (file.size > 1000000) {
    alert("Please upload a file less than 1MB!");
    event.preventDefault(); 
  }
  else{
  let pImageDiv = document.getElementsByClassName('p_image')[0];

  if (file) {
    console.log(file.name);
    let reader = new FileReader();

    reader.onload = function(e) {
      let img = document.createElement("img");
      img.src = e.target.result;
      img.className="pp_full";
      // pImageDiv.innerHTML="";
      pImageDiv.appendChild(img);
    };

    reader.readAsDataURL(file);
  }
}
  
});




