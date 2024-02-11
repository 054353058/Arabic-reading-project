
// Close Message 
function closeMsg(event) {
    event.parentElement.classList.remove('show-msg')
}

// Active current url link 
navLinks = document.querySelectorAll('.navbar-left a');

navLinks.forEach(element => {
    linkName = window.location.href.split('/')[window.location.href.split('/').length -1];

    element.classList.remove('active');
    if(element.dataset.link == linkName) {
        element.classList.add('active');
    }
    
})


// input error 
function inputsError() {
    $errors = document.querySelectorAll('.input-error')

    $errors.forEach(error => {

        
        if(error.classList.contains('show-msg')) {
            setTimeout(() => {
                error.classList.remove('show-msg');
                error.innerHTML = '';
            }, 4000)
        }else {
            error.style.display = 'none';
        }

    });
}

inputsError();

// Hide Message
alertMsg = document.querySelector('.msg'); 

function showAlert() {
    if(alertMsg) {
        if(alertMsg.classList.contains('show-msg')) {
            setTimeout(() => {
                alertMsg.classList.remove('show-msg');
                alertMsg.innerHTML = '';
            }, 4000)
        }else {
            alertMsg.style.display = 'none';
        }
    }
}

showAlert()


// Record Setup

const mic_btn  = document.getElementById('mic');
const playback = document.querySelector('.playback');

    
    function deleteRecord() {
        playback.src = '';
        playback.removeAttribute('src')
    }    

    mic_btn.addEventListener('click', toggleMic); 

    let can_record = false; 
    let is_recording = false;

    let recorder = null ; 
    let chunks = [];

    function setupAudio( ) {
        if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
            navigator.mediaDevices.getUserMedia({
                audio: true
            })
            .then(setupStream)
            .catch(error => alert("الرجاء إعطاء صلاحية الوصول للميكروفون"))
        }else {
            alert("متصفحك لا يدعم هذا الخيار، ارجو المحاولة بمتصفح آخر.")
        }
    }

    setupAudio();


    function setupStream(stream) {

        recorder = new MediaRecorder(stream);

        recorder.ondataavailable = e => {
            chunks.push(e.data);
        }

        recorder.onstop = e => {
            blob = new Blob(chunks, {type: "audio/ogg; codes=opus"});
            chunks = [];
            let audioUrl = window.URL.createObjectURL(blob);
            let recordingForm = document.querySelector(".recording-sec form");
            playback.src = audioUrl;
            recordingForm.style.display = 'block';
        }

        can_record = true
    }

    function toggleMic() {
        if(!can_record) return;

        is_recording = !is_recording;

        if(is_recording) {
            recorder.start(); 
            mic_btn.classList.add('is_recording');
        }else {
            recorder.stop(); 
            mic_btn.classList.remove('is_recording');
        }
    }

    async function saveAudio(event) {
        event.preventDefault();
        checkBox = document.getElementById("checkBox_permission");
        if(checkBox.checked) {
            if(playback.src && playback.src != "") {
                const audioBlob = await fetch(playback.src).then(response => response.blob());
                
                // استخدم FormData لإعداد البيانات للإرسال
                const formData = new FormData();
                formData.append('audio', audioBlob, `${Date.now()}_recorded_audio.wav`);
                formData.append('textid', window.location.href.split('=')[1]);
            
                try {
                    // استخدم fetch لإرسال البيانات إلى الخادم
                    const response = await fetch('store.php', {
                        method: 'POST',
                        body: formData,
                    });
            
                    if (response.ok) {
                        alertMsg.innerHTML =  `تم إرسال التسجيل بنجاح
                        <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
                        `;
                        alertMsg.className = 'msg show-msg success';
                        showAlert();
                        deleteRecord()
                        checkBox.checked = false
                    } else {
                        alertMsg.innerHTML =  `فشل في حفظ الملف، يرجى المحاولة مجدداً
                        <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
                        `;
                        alertMsg.className = 'msg show-msg error';
                        showAlert();
                    }
                } catch (error) {
                    console.error('حدث خطأ أثناء الحفظ:', error);
                }    
            }else {
                alertMsg.innerHTML =  `يجب تسجيل الصوت اولًا.
                <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
                `;
                alertMsg.className = 'msg show-msg error';
                showAlert();
            }
        }else {
            alertMsg.innerHTML =  `يرجى الموافقة على الشرط أولاً.
                                    <i class="fa-solid fa-xmark" onclick="closeMsg(this)"></i>
                                    `;
                        alertMsg.className = 'msg show-msg error';
            showAlert();
        }
    }



