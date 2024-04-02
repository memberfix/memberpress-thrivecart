const form = document.getElementById('wppl-mapping');
const noticeWrapper = document.getElementsByClassName('wppl-message-container-2');
const notice = document.getElementsByClassName('wppl-notice-2');
const noticeMessage = document.getElementById('wppl-notice-message-2');
const btn = document.getElementById('mptc-settings-save')
const loader = document.getElementById('wppl-loader')

const showMessage = (type, text) => {
    if(type == 200){
        notice[0].classList.add('wppl-success');
    }else{
        notice[0].classList.add('wppl-error');
    }

    noticeMessage.innerText = text;
    noticeWrapper[0].classList.remove('wppl-d-none');
}

const getFormData = () => {
    let formData = new FormData(form);
    let returnable = {};

    for(const pair of formData.entries()) {
        returnable[pair[0]] = pair[1];
    }

    return JSON.stringify(returnable)
}

const resetNotificationPanel = () => {
    if(notice[0].classList.contains('wppl-success')){
        notice[0].classList.remove('wppl-success')
    }

    if(notice[0].classList.contains('wppl-error')){
        notice[0].classList.remove('wppl-error')
    }

    noticeWrapper[0].classList.add('wppl-d-none');
}

const startLoader = () => {
    btn.setAttribute('disabled', '')
    loader.classList.remove('wppl-d-none')
}

const resetLoader = () => {
    btn.removeAttribute('disabled')
    loader.classList.add('wppl-d-none')
}

const handleAjaxResponse = (res) => {
    res = JSON.parse(res)

    if(res.status == 'success'){
        showMessage(200, res.message)
    }

    if(res.status == 'error'){
        showMessage(400, res.message)
    }

    resetLoader();
}

form.addEventListener('submit', function (e){
    e.preventDefault()
    resetNotificationPanel()
    startLoader()

    const nonce = document.getElementById('wppl-mapping-nonce').value;

    let data = {
        action: 'wppl_products_mapping',
        nonce: nonce,
        data: getFormData()
    }

    jQuery.ajax(ajaxurl, {
        data: data
    }).success((res) => {
        handleAjaxResponse(res)
    }).error((res) => {
        handleAjaxResponse(res)
    });
});