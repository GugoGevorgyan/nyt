/** @format */

import axios from "axios";
import Snackbar from "../../../facades/Snackbar";

export default {
    props: {
        countryCode: {
            required: true,
        },
        subPhone: {
            type: Object,
            required: true,
        },
        phoneShow: {
            type: Boolean,
            required: true,
        },
        login: {
            type: Boolean,
            required: true,
        },
        callToNumber: {
            required: true,
        },
    },

    data() {
        return {
            phoneClock: null,
            phonePages: [
                {
                    alias: "home",
                },
                {
                    alias: "calls",
                },
                {
                    alias: "phone",
                },
                {
                    alias: "messages",
                },
            ],
            phoneActivePage: "home",
            phoneLastPage: "home",
            phoneCalls: [],
            phoneCallNumber: null,
            phoneInfoNumber: null,
            calls: [],

            callOverlay: false,
            bwPhone: undefined,
            session: undefined,
            callConfig: {
                pcConfig: {
                    hackStripTcp: true,
                    rtcpMuxPolicy: "negotiate",
                    iceServers: [],
                },
                mediaConstraints: {
                    audio: true,
                    video: false,
                },
                rtcOfferConstraints: {
                    offerToReceiveAudio: 1,
                    offerToReceiveVideo: 0,
                },
            },
            snackbar: false,
            audioChunks: [],
            audioRinging: new Audio("/storage/media/ringtone.mp3"),
            callObj: {
                number: null,
                answered: false,
                incoming: undefined,
                seconds: 0,
                interval: false,
                time: "00:00:00",
            },
            callData: null,
        };
    },

    methods: {
        getCallColor(call) {
            if (call.incoming) {
                return call.answered ? "primary" : "error";
            } else {
                return "success";
            }
        },

        groupPhoneCalls() {
            this.phoneCalls = [];
            for (let i = 0; i < this.calls.length; i++) {
                let pushed = false;

                for (let j = 0; j < this.phoneCalls.length; j++) {
                    if (this.phoneCalls[j][0].client_phone === this.calls[i].client_phone) {
                        this.phoneCalls[j].push(this.calls[i]);
                        pushed = true;
                    }
                }

                if (!pushed) {
                    this.phoneCalls.push([this.calls[i]]);
                }
            }
        },

        callPadBtn(value) {
            this.phoneCallNumber = this.phoneCallNumber ? this.phoneCallNumber + value : value;
        },

        callPadPlus() {
            if (this.phoneCallNumber) {
                this.phoneCallNumber =
                    this.phoneCallNumber.charAt(0) !== "+" ? "+" + this.phoneCallNumber : this.phoneCallNumber;
            } else {
                this.phoneCallNumber = "+";
            }
        },

        callPadBackspace() {
            this.phoneCallNumber = this.phoneCallNumber
                ? this.phoneCallNumber.substring(0, this.phoneCallNumber.length - 1)
                : null;
        },

        callObjDefault() {
            clearInterval(this.callObj.interval);
            this.callObj = {
                number: null,
                answered: false,
                incoming: undefined,
                seconds: 0,
                interval: false,
                time: "00:00:00",
            };
        },

        ringtoneOn() {
            this.audioRinging.play();
        },

        ringtoneOff() {
            this.audioRinging.pause();
            this.audioRinging.currentTime = 0;
        },

        incomingCall() {
            this.$emit("phoneShow", true);
            this.callObj.number = this.session._request.from.display_name;
            this.callObj.incoming = true;
            this.callOverlay = true;
        },

        outgoingCall(number) {
            this.callObj.number = number;
            this.callObj.incoming = false;
            this.callOverlay = true;
        },

        disconnectedCall() {
            this.session = undefined;
            this.callOverlay = false;
            this.ringtoneOff();
            this.callObj.number ? this.callEnded(this.callObj.number) : this.$emit("phoneShow", false);
            this.callObjDefault();
        },

        terminateCall() {
            this.session.terminate();
        },

        acceptCall() {
            this.ringtoneOff();
            // Answer call
            this.session.answer(this.callConfig);

            let self = this;
            this.session.connection.addEventListener("addstream", function (e) {
                let remoteAudio = document.getElementById("remoteAudio");
                remoteAudio.srcObject = e.stream;
                remoteAudio.play();

                self.callObj.answered = true;
                self.callObj.interval = setInterval(() => {
                    self.callObj.seconds++;
                    self.callObj.time = new Date(self.callObj.seconds * 1000).toISOString().substr(11, 8);
                }, 1000);
            });

            this.callAccepted(this.callObj.number);
        },

        callRecording() {
            const audioBlob = new Blob(self.audioChunks, {
                type: "audio/wav",
            });

            let fd = new FormData();
            fd.append("call_gvoice", audioBlob);
            self.audioChunks = [];
        },

        sipLogin() {
            let self = this;
            let socket = new this.$sip.WebSocketInterface("wss://taxipbx.nyt.ru:8089/ws");

            this.bwPhone = new this.$sip.UA({
                uri: `sip:${self.subPhone.number}@taxipbx.nyt.ru:5060`,
                password: self.subPhone.password,
                display_name: self.subPhone.number,
                sockets: [socket],
            });

            // let socket = new JsSIP.WebSocketInterface('wss://taxi.itadvice.am:8089/ws');
            //
            // this.bwPhone = new JsSIP.UA({
            //     uri: 'sip:100@taxi.itadvice.am:7478',
            //     password: '1286adc18c663b796a7c0805bfb925fe',
            //     display_name: '100',
            //     sockets: [socket],
            // });

            this.bwPhone.on("connecting", () => {
                console.log("UA connecting");
            });

            this.bwPhone.on("connected", () => {
                console.log("UA connected");
            });

            this.bwPhone.on("registered", () => {
                this.updateOperatorAtcLogged(1);
                console.log("UA registered");
            });

            // navigator.mediaDevices.getUserMedia({ audio: true }).then(stream => {
            //     const mediaRecorder = new MediaRecorder(stream);

            this.bwPhone.on("newRTCSession", function (data) {
                let session = data.session;
                if ("outgoing" !== session._direction) {
                    self.ringtoneOn();

                    session.on("accepted", function () {
                        console.log("the call has answered");
                    });

                    session.on("confirmed", function () {
                        console.log("this handler will be called for incoming calls too");
                        // this handler will be called for incoming calls too
                    });

                    session.on("ended", function () {
                        console.log("the call has ended");
                        self.disconnectedCall();
                        //self.callRecording();
                    });

                    session.on("failed", function (e) {
                        self.disconnectedCall();
                        //self.callRecording();
                        // unable to establish the call
                    });

                    self.session = session;
                    self.incomingCall();
                }
            });
            // });

            this.bwPhone.start();
        },

        sipLogout() {
            this.bwPhone.stop();
            this.updateOperatorAtcLogged(0);
        },

        sipCall(number) {
            let self = this;
            if (!this.login) {
                this.sipLogin();
            }
            this.session = this.bwPhone.call(number, this.callConfig);
            this.session.on("connecting", () => {
                console.log("UA session connecting");
                this.outgoingCall(number);
                this.callStart(number);
                //playSound("ringback.ogg", true);

                // Тут мы подключаемся к микрофону и цепляем к нему поток, который пойдёт в астер
                let peerConnection = this.session.connection;
                let localStream = peerConnection.getLocalStreams()[0];

                // Handle local stream
                if (localStream) {
                    // Clone local stream
                    this._localClonedStream = localStream.clone();

                    console.log("UA set local stream");

                    // let localAudioControl = document.getElementById("localAudio");
                    // localAudioControl.srcObject = this._localClonedStream;
                }

                // Как только астер отдаст нам поток абонента, мы его засунем к себе в наушники
                peerConnection.addEventListener("addstream", e => {
                    console.log("UA session addstream");

                    let remoteAudio = document.getElementById("remoteAudio");
                    remoteAudio.srcObject = e.stream;
                    remoteAudio.play();
                });
            });

            // В процессе дозвона
            this.session.on("progress", () => {
                console.log("UA session progress");
                //playSound("ringback.ogg", true);
            });

            // Дозвон завершился неудачно, например, абонент сбросил звонок
            this.session.on("failed", data => {
                console.log("UA session failed");
                this.disconnectedCall();
                !self.callObj.number ? Snackbar.error("Гарнитура не подключена!") : null;
                console.log(data);
                //stopSound("ringback.ogg");
                //playSound("rejected.mp3", false);
            });

            // Поговорили, разбежались
            this.session.on("ended", () => {
                console.log("UA session ended");
                this.disconnectedCall();
                //playSound("rejected.mp3", false);
                //JsSIP.Utils.closeMediaStream(this._localClonedStream);
            });

            // Звонок принят, моно начинать говорить
            this.session.on("accepted", data => {
                self.callObj.answered = true;
                self.callObj.interval = setInterval(() => {
                    self.callObj.seconds++;
                    self.callObj.time = new Date(self.callObj.seconds * 1000).toISOString().substr(11, 8);
                }, 1000);
                this.callAnswered();
                console.log("UA session accepted");
            });
        },

        /*requests*/
        getCalls() {
            axios
                .post("call-center/get-calls", { subPhone: this.subPhone.number })
                .then(response => {
                    this.calls = response.data;
                })
                .catch(error => {
                    if (error.response && 422 === error.response.status) {
                        Snackbar.error(error.response.data.message);
                    }
                });
        },
        updateOperatorAtcLogged(value) {
            this.$emit("loginLoading", true);
            axios
                .put("call-center/update-atc-logged", { logged: value, subPhone: this.subPhone.number })
                .then(response => {
                    this.$emit("loginLoading", false);
                    this.$emit("logged", response.data.logged);
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        callAccepted(number) {
            axios
                .post("call-center/connect-worker", { cellNumber: number, subPhone: this.subPhone.number })
                .then(response => {
                    this.$emit("call", response.data);
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        callAnswered() {
            if (this.callData) {
                axios
                    .post("call-center/call-answered", { call_id: this.callData.client_call_id })
                    .then(response => {
                        this.getCalls();
                    })
                    .catch(error => {
                        Snackbar.error(error.response.data.message);
                    });
            }
        },
        callStart(number) {
            axios
                .post("call-center/call-start", { cellNumber: number, subPhone: this.subPhone.number })
                .then(response => {
                    this.getCalls();
                    this.callData = response.data.call;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
        callEnded(number) {
            this.$emit("phoneShow", false);
            axios
                .post("call-center/call-end", { cellNumber: number, subPhone: this.subPhone.number })
                .then(() => {
                    this.getCalls();
                    this.$emit("ended");
                    this.callData = null;
                })
                .catch(error => {
                    Snackbar.error(error.response.data.message);
                });
        },
    },

    watch: {
        login: function () {
            this.login ? this.sipLogin() : this.sipLogout();
        },

        callToNumber: function () {
            if (this.callToNumber) {
                this.sipCall(this.callToNumber);
            }
        },
        calls: function () {
            this.groupPhoneCalls();
        },
    },

    computed: {
        phoneInfoCalls: function () {
            if (this.phoneInfoNumber) {
                return this.phoneCalls.find(item => {
                    if (item[0].client_phone === this.phoneInfoNumber) {
                        return item;
                    }
                });
            }
        },

        callNumberWithCode() {
            return this.phoneCallNumber; /*this.countryCode+this.phoneCallNumber*/
        },
    },

    created() {
        this.getCalls();
        let self = this;
        setInterval(function () {
            self.phoneClock = new Date().getHours() + " : " + new Date().getMinutes();
        }, 1000);
    },
};
