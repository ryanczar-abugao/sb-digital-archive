document.querySelectorAll('input[type="file"]').forEach((input, index) => {
  const fileNameSpan = document.getElementById(`fileName_${index}`);

  input.addEventListener('change', () => {
    if (input.files.length > 0) {
      const fileNames = Array.from(input.files).map(file => file.name).join(', ');
      fileNameSpan.textContent = fileNames;
    } else {
      fileNameSpan.textContent = '';
    }
  });
});

document.addEventListener('trix-attachment-add', function (event) {
  const attachment = event.attachment;

  // Check if the attachment is a file
  if (attachment.file) {
    const formData = new FormData();
    formData.append('attachment', attachment.file);
    formData.append('historyId', document.querySelector('input[name="historyId"]').value);

    // Send the file to the server
    fetch('/attachments/create', {
      method: 'GET',
      body: formData
    }).then(response => response.json()).then(data => {
      if (data.url) {
        attachment.setAttributes({
          url: data.url, href: data.url // Update the attachment URL in Trix
        });
      } else { // Handle error if the upload fails
        attachment.remove();
        alert('Upload failed');
      }
    }).catch(() => { // Handle network error
      attachment.remove();
      alert('Network error');
    });
  }
});