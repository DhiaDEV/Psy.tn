'use strict';



//button
let callBtn    = $('#callBtn');
let callBox    = $('#callBox');
let answerBtn  = $('#answerBtn');
let declineBtn = $('#declineBtn');
let alertBox   = $('#alertBox');





let pc ;
let sendTo = callBtn.data('user');
let localStream; 


//video elements 
const localVideo  = document.querySelector("#localVideo");
const remoteVideo = document.querySelector("#remoteVideo"); 

//media info 
const mediaConst = {
    video:true,
    audio:true,
};

// info about stun servers
const config = {
    iceServers: [
        {urls:'stun:stun1.l.google.com:19302'},
    ]
}

//what to receive from other client 
const options = {
    offerToReceiveVideo: 1, 
    offerToReceiveAudio: 1 

} 

function getConn() {
    if(!pc){
        pc = new RTCPeerConnection(config);
    }   
}

//ask for media input 
async function getCam() {
    let mediaStream;

    try{
        if(!pc){
           getConn();
        }
        mediaStream = await navigator.mediaDevices.getUserMedia(mediaConst);
        localVideo.srcObject = mediaStream;
        localStream = mediaStream;
        localStream.getTracks().forEach( track => pc.addTrack(track, localStream) );

    }catch(error){
        console.log(error);
    }
}

async function createOffer(sendTo){
    await sendIceCandidate(sendTo);
    await pc.createOffer(options);
    await pc.setLocalDescription(pc.localDescription);
    send('client-offer', pc.localDescription, sendTo);
}

async function createAnswer(sendTo, data){
    if(!pc){
        await getConn();
    }

    if(!localStream){
        await getCam();
    }
    
    await sendIceCandidate(sendTo);
    await pc.setRemoteDescription(data);
    await pc.createAnswer();
    await pc.setLocalDescription(pc.localDescription);
    send('client-answer', pc.localDescription, sendTo);
}

function sendIceCandidate(sendTo){
    pc.onicecandidate = e =>{
        if(e.candidate !== null){
            // send ice candidate to other client
            send('client-candidate', e.candidate, sendTo);
        }
    }

    pc.ontrack = e =>{
        $('#video').removeClass('hidden');
        $('#profile').addClass('hidden');
        remoteVideo.srcObject = e.streams[0];
    }
}

function hungup(){
    send('client-hungup', null, sendTo);
    pc.close();
    pc = null 
}

$('#callBtn').on('click', () =>{
    getCam();
    send('is-client-ready', null, sendTo);

});

$('#hangupBtn').on('click', () =>{
    hungup();
    location.reload(true);

});

conn.onopen = e =>{
    console.log('connected to websocket');
}

conn.onmessage = async e =>{
    let message      = JSON.parse(e.data);
    let by           = message.by ;
    let data         = message.data ;
    let type         = message.type ;
    // let profileImage = message.profileImage;
    let username     = message.username;
    $('#username').text(username);
    // $('#profileImage').attr('src', profileImage);

    switch(type){
        case 'client-candidate':
            if (pc.localDescription){
               await pc.addIceCandidate(new RTCIceCandidate(data));
            }
        break;
        case 'is-client-ready':
            if(!pc){
               await getConn();
            }

            if(pc.iceConnectionState === "connected"){
                send('client-already-oncall', null, by);
            }else{
                // display popup
                displayCall();
                if(window.location.href.indexOf(username) > -1){
                    answerBtn.on('click', () =>{
                        callBox.addClass('hidden');
                        $('.wrapper').removeClass('glass');
                        send('client-is-ready', null, sendTo);
                    });
                }else{
                    answerBtn.on('click', () =>{
                        callBox.addClass('hidden');
                        redirectToCall(username, by);
                    });
                }
                declineBtn.on('click', () =>{
                    send('client-rejected', null ,sendTo);
                    location.reload(true);
                });
            }

        break; 
        case 'client-answer':
            if(pc.localDescription){
                await pc.setRemoteDescription(data);
                $('#callTimer').timer({format: '%m:%s'});
            }
        break;

        case 'client-offer':
            createAnswer(sendTo, data);
            $('#callTimer').timer({format: '%m:%s'});

        break;

        case 'client-is-ready':
            createOffer(sendTo);
        break;   

        case 'client-already-oncall':
            //display popup right here 
            displayAlert(username, 'Il est en ligne sur un autre appel');
            setTimeout('window.location.reload(true)', 2000);
        break;

        case 'client-rejected':
            displayAlert(username, "C'est occupé");
            setTimeout('window.location.reload(true)', 2000);

        break;

        case 'client-hungup':
            //display popup right here 
            displayAlert(username, 'La communication a été interrompue');
            setTimeout('window.location.reload(true)', 2000);
        break;
    }
}

function send(type, data, sendTo){
    conn.send(JSON.stringify({
        sendTo:sendTo,
        type: type,
        data:data
    }));

}
function displayCall() {
    callBox.removeClass('hidden');
    $('.wrapper').addClass('glass');
    
}
function displayAlert(username, message){
    alertBox.find('#alertName').text(username);
    // alertBox.find('#alertImage').attr('src',profileImage);
    alertBox.find('#alertMessage').text(message);

    alertBox.removeClass('hidden');
    $('.wrapper').addClass('glass');
    $('#video').addClass('hidden');
    $('profile').removeClass('hidden');
}
function redirectToCall(username, sendTo){
    if(window.location.href.indexOf(username) == -1){
        sessionStorage.setItem('redirect', true);
        sessionStorage.setItem('sendTo', sendTo);
        window.location.href = '/Psy.tn/Espace%20Patients/ChatLive_WebRTC/'+username;
    }
}
if(sessionStorage.getItem('redirect')){
    sendTo =sessionStorage.getItem('sendTo');

    let waitForWs = setInterval(() => {
        if(conn.readyState === 1){
            send('client-is-ready', null, sendTo);
            clearInterval(waitForWs);
        }
    }, 500 );
    sessionStorage.removeItem('redirect');
    sessionStorage.removeItem('sendTo');
}