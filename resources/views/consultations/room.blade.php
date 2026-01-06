<x-layouts.app>
    <h1 class="text-2xl font-bold mb-4">Salle de consultation #{{ $consultation->id }}</h1>

    <!-- Video Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Local Video -->
        <div class="bg-gray-900 rounded-lg shadow-lg overflow-hidden relative">
            <video id="localVideo" autoplay muted playsinline class="w-full h-full object-cover"></video>
            <div class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-sm px-2 py-1 rounded">
                Vous: {{ $currentUser->name }}
            </div>
        </div>
        <!-- Remote Video -->
        <div class="bg-gray-800 rounded-lg shadow-lg overflow-hidden relative">
            <video id="remoteVideo" autoplay playsinline class="w-full h-full object-cover"></video>
            <div class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-sm px-2 py-1 rounded">
                Participant distant
            </div>
        </div>
    </div>

    <!-- Controls -->
    <div class="bg-gray-50 p-4 rounded-lg shadow-md">
        <div class="flex flex-wrap items-center justify-center gap-4">
            <button id="startCallBtn" class="px-4 py-2 font-semibold text-white bg-green-500 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-75 disabled:opacity-50 disabled:cursor-not-allowed">Démarrer Appel</button>
            <button id="stopCallBtn" class="px-4 py-2 font-semibold text-white bg-red-500 rounded-md hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-75 disabled:opacity-50 disabled:cursor-not-allowed">Quitter Appel</button>
            <span class="h-8 border-l border-gray-300 mx-2"></span>
            <button id="startRecordingBtn" class="px-4 py-2 font-semibold text-white bg-blue-500 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 disabled:opacity-50 disabled:cursor-not-allowed">Démarrer Enregistrement</button>
            <button id="stopRecordingBtn" disabled class="px-4 py-2 font-semibold text-white bg-yellow-500 rounded-md hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-75 disabled:opacity-50 disabled:cursor-not-allowed">Terminer Enregistrement</button>
        </div>
        <div class="text-center mt-4">
            <p id="status" class="text-sm text-gray-700 font-medium"><strong>Status:</strong> En attente</p>
            <p id="recordingStatus" class="text-sm text-blue-700 font-medium h-5"></p>
        </div>
    </div>

<script>
// Le script JS reste le même, il est déjà bien structuré.
/* ================= CONFIG ================= */
const consultationId = {{ $consultation->id }};
const signalUrl = "{{ route('consultation.signal') }}";
const uploadUrl = "{{ route('consultations.uploadAudio', $consultation->id) }}";
const iceServers = [{ urls: 'stun:stun.l.google.com:19302' }];

/* ================= DOM ================= */
const localVideo  = document.getElementById('localVideo');
const remoteVideo = document.getElementById('remoteVideo');
const statusEl    = document.getElementById('status');
const recordingStatusEl = document.getElementById('recordingStatus');

// --- Buttons ---
const startCallBtn = document.getElementById('startCallBtn');
const stopCallBtn = document.getElementById('stopCallBtn');
const startRecordingBtn = document.getElementById('startRecordingBtn');
const stopRecordingBtn = document.getElementById('stopRecordingBtn');


let localStream = null;
let peerConnection = null;
let iceCandidateQueue = [];
let isInitiator = false;

// --- Audio Recording ---
let mediaRecorder;
let recordedChunks = [];


/* ================= UI ================= */
startCallBtn.onclick = startCall;
stopCallBtn.onclick  = stopCall;
startRecordingBtn.onclick = startRecording;
stopRecordingBtn.onclick = stopRecording;


/* ================= MAIN ================= */
async function startCall() {
    try {
        statusEl.innerText = "Caméra...";
        localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
        localVideo.srcObject = localStream;

        statusEl.innerText = "Connexion...";
        window.Echo.join(`consultation.${consultationId}`)
            .here(users => {
                console.log("Présents:", users);
                if (users.length === 1) {
                    statusEl.innerText = "En attente d’un participant...";
                }
            })
            .joining(async user => {
                console.log("Participant rejoint:", user.name);
                isInitiator = true;

                createPeerConnection();

                const offer = await peerConnection.createOffer();
                await peerConnection.setLocalDescription(offer);

                sendSignal('offer', {
                    type: offer.type,
                    sdp: btoa(offer.sdp) // ✅ BASE64
                });

                statusEl.innerText = "Offre envoyée";
            })
            .leaving(() => stopCall())
            .listen('.WebRTCSignal', handleSignal);

    } catch (e) {
        console.error(e);
        statusEl.innerText = "Erreur accès caméra";
    }
}

/* ================= AUDIO RECORDING ================= */
function startRecording() {
    if (!localStream) {
        alert("Vous devez d'abord démarrer l'appel pour pouvoir enregistrer.");
        return;
    }
    // On n'enregistre que l'audio pour ne pas surcharger
    const audioStream = new MediaStream(localStream.getAudioTracks());
    mediaRecorder = new MediaRecorder(audioStream, { mimeType: 'audio/webm' });

    mediaRecorder.ondataavailable = (event) => {
        if (event.data.size > 0) {
            recordedChunks.push(event.data);
        }
    };

    mediaRecorder.onstop = () => {
        const audioBlob = new Blob(recordedChunks, { type: 'audio/webm' });
        uploadAudio(audioBlob);
        recordedChunks = []; // Reset chunks
    };

    mediaRecorder.start();
    recordingStatusEl.innerText = "🔴 Enregistrement en cours...";
    startRecordingBtn.disabled = true;
    stopRecordingBtn.disabled = false;
}

function stopRecording() {
    if (mediaRecorder && mediaRecorder.state === 'recording') {
        mediaRecorder.stop();
        recordingStatusEl.innerText = "Enregistrement terminé. Upload en cours...";
        startRecordingBtn.disabled = false;
        stopRecordingBtn.disabled = true;
    }
}

async function uploadAudio(blob) {
    const formData = new FormData();
    formData.append('audio', blob, `consultation-${consultationId}.webm`);

    try {
        const response = await fetch(uploadUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                // 'Content-Type': 'multipart/form-data' is not needed, browser sets it with boundary
            },
            body: formData
        });

        const result = await response.json();

        if (result.success) {
            recordingStatusEl.innerText = "Upload terminé avec succès !";
            console.log('Upload success:', result.message);
        } else {
            throw new Error(result.message || 'Upload failed');
        }

    } catch (error) {
        console.error('Error uploading audio:', error);
        recordingStatusEl.innerText = "⚠️ Erreur lors de l'upload.";
    }
}


/* ================= SIGNAL HANDLER ================= */
async function handleSignal(event) {
    console.log("📡 Signal reçu:", event.type);

    if (!peerConnection) createPeerConnection();

    if (event.type === 'offer') {
        await peerConnection.setRemoteDescription({
            type: 'offer',
            sdp: atob(event.data.sdp) // ✅ DECODE
        });

        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);

        sendSignal('answer', {
            type: answer.type,
            sdp: btoa(answer.sdp) // ✅ BASE64
        });

        await flushIceQueue();
    }

    if (event.type === 'answer') {
        await peerConnection.setRemoteDescription({
            type: 'answer',
            sdp: atob(event.data.sdp)
        });

        await flushIceQueue();
    }

    if (event.type === 'candidate') {
        const candidate = new RTCIceCandidate(event.data);

        if (peerConnection.remoteDescription) {
            await peerConnection.addIceCandidate(candidate);
        } else {
            iceCandidateQueue.push(candidate);
        }
    }
}

/* ================= PEER ================= */
function createPeerConnection() {
    if (peerConnection) return;

    console.log("Création RTCPeerConnection");
    peerConnection = new RTCPeerConnection({ iceServers });

    peerConnection.onicecandidate = e => {
        if (e.candidate) {
            sendSignal('candidate', e.candidate);
        }
    };

    peerConnection.ontrack = e => {
        console.log("🎥 Flux distant reçu");
        remoteVideo.srcObject = e.streams[0];
    };

    localStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localStream);
    });
}

/* ================= UTILS ================= */
async function flushIceQueue() {
    while (iceCandidateQueue.length > 0) {
        await peerConnection.addIceCandidate(iceCandidateQueue.shift());
    }
}

async function sendSignal(type, data) {
    await fetch(signalUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'X-Socket-ID': window.Echo.socketId()
        },
        body: JSON.stringify({
            consultationId,
            type,
            data
        })
    });
}

function stopCall() {
    // Also stop recording if it's running
    stopRecording();

    localStream?.getTracks().forEach(t => t.stop());
    peerConnection?.close();
    peerConnection = null;
    remoteVideo.srcObject = null;

    window.Echo.leave(`consultation.${consultationId}`);
    statusEl.innerText = "Appel terminé";
}
</script>
</x-layouts.app>
