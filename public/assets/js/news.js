function validateFiles() {
  const files = document.getElementById('image').files;
  const maxFiles = 2;
  const maxSize = 2 * 1024 * 1024; // 3MB in bytes
  const errorElement = document.getElementById('imageError');

  // Check if the number of files exceeds the limit
  if (files.length > maxFiles) {
    errorElement.style.display = 'block';
    errorElement.innerHTML = 'You can only upload a maximum of 3 images.';
    return false;
  }

  // Check the size of each file
  for (let i = 0; i < files.length; i++) {
    if (files[i].size > maxSize) {
      errorElement.style.display = 'block';
      errorElement.innerHTML = 'Each image must be less than 2MB.';
      return false;
    }
  }

  // If everything is fine, hide the error message
  errorElement.style.display = 'none';
  return true;
}

// Bind validation on form submission
document.querySelector('form').onsubmit = function (event) {
  if (!validateFiles()) {
    event.preventDefault(); // Prevent form submission if validation fails
  }
}
