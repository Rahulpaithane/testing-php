 $(document).ready(function() {
// Get the drop area element
var dropArea = document.getElementById('dropArea');
// Get the image preview element
var previewImage = document.getElementById('previewImage');

// Handle the dragover event
dropArea.addEventListener('dragover', function(e) {
  e.preventDefault();
  dropArea.classList.add('dragover');
});

// Handle the dragleave event
dropArea.addEventListener('dragleave', function() {
  dropArea.classList.remove('dragover');
});

// Handle the drop event
dropArea.addEventListener('drop', function(e) {
  e.preventDefault();
  dropArea.classList.remove('dragover');

  var files = e.dataTransfer.files;
  // Handle the dropped files here (e.g., upload to server, display thumbnails, etc.)
  previewImage.src = URL.createObjectURL(files[0]);
  console.log(files);
});

// Event handler for file input change
$('#uploadImage').on('change', function(e) {
  var files = e.target.files;
  // Handle the uploaded files here (e.g., upload to server, display thumbnails, etc.)
  previewImage.src = URL.createObjectURL(files[0]);
  console.log(files);
});

// Trigger file input click when drop area is clicked
dropArea.addEventListener('click', function() {
  $('#uploadImage').click();
});
});


$(document).ready(function() {
  // Get the drop area element
  var dropArea = document.getElementById('dropArea2');
  // Get the image preview element
  var previewImage = document.getElementById('previewImage2');
  
  // Handle the dragover event
  dropArea.addEventListener('dragover', function(e) {
    e.preventDefault();
    dropArea.classList.add('dragover');
  });
  
  // Handle the dragleave event
  dropArea.addEventListener('dragleave', function() {
    dropArea.classList.remove('dragover');
  });
  
  // Handle the drop event
  dropArea.addEventListener('drop', function(e) {
    e.preventDefault();
    dropArea.classList.remove('dragover');
  
    var files = e.dataTransfer.files;
    // Handle the dropped files here (e.g., upload to server, display thumbnails, etc.)
    previewImage.src = URL.createObjectURL(files[0]);
    console.log(files);
  });
  
  // Event handler for file input change
  $('#uploadImage2').on('change', function(e) {
    var files = e.target.files;
    // Handle the uploaded files here (e.g., upload to server, display thumbnails, etc.)
    previewImage.src = URL.createObjectURL(files[0]);
    console.log(files);
  });
  
  // Trigger file input click when drop area is clicked
  dropArea.addEventListener('click', function() {
    $('#uploadImage2').click();
  });
  });
  