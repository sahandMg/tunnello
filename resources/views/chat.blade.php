@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row col-md-6 m-auto">
            <div>
                <input id="msg-text-box" type="text" class="form-control"  placeholder="Type here ..." required style="direction: rtl; text-align: right; font-family: IRANSans">
                <div id="btn-box">
                    <button id="send-btn" class="btn btn-primary mt-3 col-md-4" onclick="send()" disabled>Send</button>
                    <button id="send-btn" class="btn btn-success mt-3 col-md-4" onclick="startFCM()">Allow Notifications</button>
                </div>

            </div>
        </div>
        <div class="row col-md-6 mx-auto">
            <ul id="user-box" style="list-style: none; font-weight: bold; font-family: IRANSans-bold; font-size: 20px;">
                @foreach($users as $user)
                        <input type="radio" id="{{$user->id}}" value="{{$user->id}}"  name="username" onclick="selectRecipient(event)"><label for="{{$user->id}}">{{$user->name}}</label>
                @endforeach
            </ul>
        </div>
        <div class="row card col-md-6 mt-3 mx-auto p-0">
            <div class="card-body" style="height: 500px; overflow: scroll; background: #242853;">
                <ul id="msg-box" class="px-1" style="list-style: none; color: white; font-weight: bold; font-family: IRANSans-bold; font-size: 20px; text-align: right; direction: rtl">
                    @foreach($auth_user_messages as $msg)
                        <li>
                            <p style="color: yellowgreen; display: inline-block; padding: 0; margin: 0;">{{$msg->recipient->name}} <- {{$msg->sender->name}}</p>
                            <p style="padding: 0; margin: 0">{{$msg->body}}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <script>
        let channels = JSON.parse('{!! $user_channels !!}');
        for (let i = 0; i < channels.length; i++) {
            // console.log(channels[i]);
            Echo.channel(channels[i])
                .listen('NewMessageEvent', (e) => {
                    console.log(e);
                    document.querySelector('#send-btn').disabled = false;
                    updateMessageList();
                    sendNotif(e.sender_name, e.message, e.recipient_id);
                });
        }
        function selectRecipient(e) {
            let recipient_id = e.target.id;
            // leaveChannel(localStorage.getItem('recipient_id'));
            localStorage.setItem('recipient_id', recipient_id);
            document.querySelector('#send-btn').disabled = false;
            // createChannel(recipient_id);
        }

        {{--function leaveChannel(channelId) {--}}
            {{--Echo.disconnect();--}}
        {{--}--}}

        {{--function createChannel(recipient_id) {--}}
            {{--axios.post('channelid',{from: "{!! \Illuminate\Support\Facades\Auth::id() !!}", to:recipient_id}).then(function (resp) {--}}
                {{--channelId = resp.data;--}}
                {{--Echo.connect();--}}
                {{--Echo.channel(channelId)--}}
                    {{--.listen('NewMessageEvent', (e) => {--}}
                        {{--console.log(e);--}}
                        {{--document.querySelector('#send-btn').disabled = false;--}}
                        {{--sendNotif(e.sender_name, e.message);--}}
                        {{--updateMessageList();--}}
                    {{--});--}}
            {{--}).catch(function (err) {--}}

            {{--});--}}
        {{--}--}}

        function sendNotif(sender_name, message, recp_id) {
            axios.post('/send-web-notification', {title: 'New Message From '+ sender_name, body:message, recipient:recp_id}).catch(function (err) {
                alert(err.response);
            })
        }

        function send() {
            let msg = document.querySelector('#msg-text-box').value;
            axios.post('/send', {'msg':msg, from: "{!! \Illuminate\Support\Facades\Auth::id() !!}", to: localStorage.getItem('recipient_id')}).then(function (resp) {
                console.log('ajax sent.');
                    document.querySelector('#msg-text-box').value = '';
                    document.querySelector('#send-btn').disabled = true;
            }).catch(function (err) {
                console.log(err);
            })
        }

        function updateMessageList() {
            axios.post('/message-list', {from: "{!! \Illuminate\Support\Facades\Auth::id() !!}", to: localStorage.getItem('recipient_id')}).then(function (resp) {
                const node_li = document.createElement("li");
                const name_node = document.createElement("p");
                const body_node = document.createElement("p");
                name_node.setAttribute('style', 'color: yellowgreen; padding:0; margin:0');
                body_node.setAttribute('style', 'padding:0; margin:0');
                const textnode_body = document.createTextNode(resp.data.body);
                const textnode_name = document.createTextNode(resp.data.recipient.name + ' <- ' + resp.data.sender.name);
                name_node.appendChild(textnode_name);
                body_node.appendChild(textnode_body);
                // node_li.insertBefore(node2, node.firstChild);
                node_li.appendChild(name_node);
                node_li.appendChild(body_node);
                document.getElementById("msg-box").insertBefore(node_li, document.getElementById("msg-box").firstChild);
                // document.getElementById("msg-box").insertBefore(node2, node)
            })
        }
    </script>
    <script src="https://www.gstatic.com/firebasejs/8.3.2/firebase.js"></script>
    <script>
        // Import the functions you need from the SDKs you need
        // import { initializeApp } from "https://www.gstatic.com/firebasejs/9.6.8/firebase-app.js";
        // TODO: Add SDKs for Firebase products that you want to use
        // https://firebase.google.com/docs/web/setup#available-libraries

        // Your web app's Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyB_TBEcGdBzO4enoT8DNeD34OV2Yc8UABs",
            authDomain: "tunnello-aef38.firebaseapp.com",
            projectId: "tunnello-aef38",
            storageBucket: "tunnello-aef38.appspot.com",
            messagingSenderId: "731744527215",
            appId: "1:731744527215:web:de0d2a5df091152d743e9a"
        };
        firebase.initializeApp(firebaseConfig);
        const messaging = firebase.messaging();
        function startFCM() {
            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function (response) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route("store.token") }}',
                        type: 'POST',
                        data: {
                            token: response
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            alert('Token stored.');
                        },
                        error: function (error) {
                            alert(error);
                        },
                    });
                }).catch(function (error) {
                alert(error);
            });
        }
        messaging.onMessage(function (payload) {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.body,
                icon: payload.notification.icon,
            };
            new Notification(title, options);
        });

    </script>
@endsection