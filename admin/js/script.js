
function confirmDelete() {
    return confirm("هل انت متأكد من حذف النص؟ سيتم حذف جميع المشاركات المتعلقة به.")
}

// Active current url link 
navLinks = document.querySelectorAll('.nav-item li a');

navLinks.forEach(element => {
    
    linkName = window.location.href.split('/')[window.location.href.split('/').length -1];

    element.classList.remove('active');
    if(element.dataset.link == linkName) {
        element.classList.add('active');
    }
    
})


// Close Message 
function closeMsg(event) {
    event.parentElement.style.display = 'none';
}

// Hide Message


alertMsg = document.querySelector('.msg'); 

if(alertMsg) {
    if(alertMsg.classList.contains('show-msg')) {
        if(alertMsg) {
            setTimeout(() => {
                alertMsg.style.display = 'none';
            }, 4000)
        }
    }else {
        alertMsg.style.display = 'none'
    }
}

