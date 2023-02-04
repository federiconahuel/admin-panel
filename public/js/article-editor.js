//document.querySelector('.save-changes-btn').setAttribute('disabled', '');
//document.querySelector('.publish-btn').setAttribute('disabled','');
document.forms.articleForm.content.value = document.querySelector('[data-tiny-editor]').innerHTML;


document.querySelector('[data-tiny-editor]').addEventListener('keypress', e => {
    //if (e.key === 'Delete' || e.key === 'Backspace') {
      inputHasChanged()
    //}
});

/*function unpublish(id) {
    var req = new XMLHttpRequest();
    req.open("POST", '/admin-panel/articles/unpublish/'+id); 
    req.send();
}*/


function inputHasChanged() {
   // document.querySelector('.save-changes-btn').removeAttribute('disabled');
   // document.querySelector('.publish-btn').setAttribute('disabled', '');
   document.forms.articleForm.content.value = document.querySelector('[data-tiny-editor]').innerHTML;
}

function store() {
    axios.post('/api/articles/create/', {
        title: document.querySelector('.title-input').value,
        content: document.querySelector('[data-tiny-editor]').innerHTML,
    })
    .then(function (response) {
        //document.querySelector('.save-changes-btn').setAttribute('disabled', '');
        //document.querySelector('.publish-btn').removeAttribute('disabled');
        window.location.replace('/admin-panel/articles/edit/' + response.data.articleID)
    })
    .catch(function (error) {
        console.log(error);
    });
    //document.forms.articleForm.content.value = document.querySelector('[data-tiny-editor]').innerHTML;
    //document.forms.articleForm.submit();
}

function update(id, publish) {
    if(publish) {
        url = '/api/articles/publish/'
    } else {
        url = '/api/articles/save/'
    }
    axios.post(url + id , {
        title: document.querySelector('.title-input').value,
        content: document.querySelector('[data-tiny-editor]').innerHTML,
    })
    .then(()=> {
        window.location.replace('/admin-panel/articles/edit/' + id)
    })
    .catch(function (error) {
        console.log(error);
    });
}

