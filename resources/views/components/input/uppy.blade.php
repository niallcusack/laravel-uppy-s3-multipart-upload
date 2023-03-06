<div
    wire:ignore
    x-data
    x-init="
        onUploadSuccess = (elForUploadedFiles) =>
          (file, response) => {

            const url = response.uploadURL;
            const fileName = file.name;
            fileType = file.extension;

            const uploadedFileData = JSON.stringify(response.body);

            const li = document.createElement('li');
            const a = document.createElement('a');
            a.href = url;
            a.target = '_blank';
            a.appendChild(document.createTextNode(fileName));
            li.appendChild(a);

            document.querySelector(elForUploadedFiles).appendChild(li);

            var inputElementUrlUploadFile = document.getElementById('{{ $hiddenField }}');
            inputElementUrlUploadFile.value = '';
            inputElementUrlUploadFile.dispatchEvent(new Event('input'));

            reset();

            {{ $extraJSForOnUploadSuccess }}

          };

        const uppyUpload{{ $hiddenField }} = new Uppy({{ $options }});

        uppyUpload{{ $hiddenField }}
          .use(DragDrop, {{ $dragDropOptions }})
          .use(AwsS3Multipart, {
              companionUrl: '/',
              companionHeaders:
              {
                  'X-CSRF-TOKEN': window.csrfToken,
              },
          })
          .use(StatusBar, {{ $statusBarOptions }})
          .on('cancel-all', () => {
              let element = document.getElementById('uploadStatus').innerText = '';
              uploadCancelled();
          })
          .on('upload-success', onUploadSuccess('.{{ $uploadElementClass }} .uploaded-files ol'))
          .on('file-added', (file, reason) => {             
              // Remove any previously staged files
              uppyUpload{{ $hiddenField }}.getFiles().forEach((f) => {
                if (f !== file) {
                  uppyUpload{{ $hiddenField }}.removeFile(f.id)
                }
              });

              // Set the target to null to replace the currently staged file
              uppyUpload{{ $hiddenField }}.setFileMeta(file.id, {
                target: null,
              });
              
              let element = document.getElementById('uploadStatus');
              element.innerText = file.name + ' is ready for upload';
              element.classList.add('text-green-700');
          });

        startUpload = () => {
          if(uppyUpload{{ $hiddenField }}.getFiles().length > 0) {
            uppyUpload{{ $hiddenField }}.upload();
            let element = document.getElementById('uploadStatus');
            element.innerText = uppyUpload{{ $hiddenField }}.getFiles()[0].name + ' is uploading';
          }
          else {
            noFileUpload();
          }
          
        }

        reset = () => {
          uppyUpload{{ $hiddenField }}.cancelAll();
        }
    "
>
    <section class="{{ $uploadElementClass }}">
      <div class="for-DragDrop" x-ref="input"></div>

      <div x-show="isUploading" class="for-ProgressBar"></div>

      <div  x-show="isUploading" class="uploaded-files">
        <h5>{{ __('Uploaded file:') }}</h5>
        <ol></ol>
      </div>
      <button class="hidden" id="startUpload" x-on:click="startUpload()"></button>
    </section>
    <p id="uploadStatus" class="py-2"></p>
</div>
