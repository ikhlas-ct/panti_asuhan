<script>
    $(document).ready(function(){


      $('.summernote').summernote({
        height: 500,
        toolbar: [
          ['style', ['style']],
          ['font', ['bold','italic','underline','clear']],
          ['fontname', ['fontname']],
          ['fontsize',['fontsize']],
          ['color',['color']],
          ['para',['ul','ol','paragraph']],
          ['height',['height']],
          ['insert',['link','picture','video']],
        ],
        callbacks: {
          onImageUpload: function(files) {
            uploadImage(files[0]);
          },
          onMediaDelete: function(target) {
            // target adalah elemen <img> yang dihapus
            let src = $(target).attr('src');
            console.log('onMediaDelete src:', src);
            deleteImage(src);
          }
        }
      });

      // Tambahkan MutationObserver untuk mendeteksi penghapusan gambar via backspace
      const editable = $('.summernote').next('.note-editor .note-editable')[0];
      const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
          if (mutation.removedNodes.length > 0) {
            Array.from(mutation.removedNodes).forEach(function(node) {
              if (node.nodeName === 'IMG') {
                const src = node.getAttribute('src');
                if (src) {
                  console.log('Detected removal via MutationObserver:', src);
                  deleteImage(src);
                }
              } else if (node.querySelectorAll) {
                const imgs = node.querySelectorAll('img');
                imgs.forEach(function(img) {
                  const src = img.getAttribute('src');
                  if (src) {
                    console.log('Detected removal via MutationObserver:', src);
                    deleteImage(src);
                  }
                });
              }
            });
          }
        });
      });

      observer.observe(editable, { childList: true, subtree: true });

      // Fungsi upload gambar
      function uploadImage(file) {
        let data = new FormData();
        data.append('image', file);

        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ route('blog.upload.image') }}",
          method: 'POST',
          data: data,
          contentType: false,
          processData: false,
          success: function(res) {
            if (res.url) {
              $('.summernote').summernote('insertImage', res.url);
            } else {
              console.warn('Upload berhasil, tapi tidak ada res.url:', res);
            }
          },
          error: function(xhr) {
            console.error('Upload gagal:', xhr.responseText);
          }
        });
      }

      // Fungsi hapus gambar
      function deleteImage(src) {
        console.log('deleteImage src:', src);
        $.ajax({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          url: "{{ route('blog.delete.image') }}",
          method: 'POST',
          data: { src: src },
          success: function(res) {
            console.log('=> Delete sukses:', res);
          },
          error: function(xhr) {
            console.error('=> Delete gagal:', xhr.status, xhr.responseText);
          }
        });
      }

    });
    </script>
