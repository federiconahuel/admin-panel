function saveChanges() {
    document.querySelectorAll('[data-tiny-editor]').forEach(editor => {
            console.log(editor.innerHTML)
     })
    
     document.querySelector('.save-changes-btn').setAttribute('disabled', '');
     document.querySelector('.publish-btn').removeAttribute('disabled');

}

function inputHasChanged() {
    document.querySelector('.save-changes-btn').removeAttribute('disabled');
    document.querySelector('.publish-btn').setAttribute('disabled', '');
}


document.getElementById("editor").addEventListener("keypress", e => {
        inputHasChanged()
    })

document.getElementById("editor").addEventListener('keydown', e => {
        if (e.key === 'Delete' || e.key === 'Backspace') {
          inputHasChanged()
        }
      });

