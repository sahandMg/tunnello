@extends('layouts.app')
@section('content')
<!-- partial:index.partial.html -->
<div id="chatbox">
    <div id="friendslist">
        <div id="topmenu">
            <span class="friends"></span>
            <span class="chats"></span>
            <span class="history"></span>
        </div>

        <div id="friends">
            @foreach($friends as $user)
                {{--<div class="form-check col-6 col-md-4">--}}
                    {{--<input class="form-check-input" meta="solo" type="radio" id="{{$user->id}}" value="{{$user->id}}"  name="username" onclick="selectRecipient(event)">--}}
                    {{--<label class="form-check-label" for="{{$user->id}}">{{$user->name}}</label>--}}
                {{--</div>--}}
                <div class="friend" onclick="loadMessages('{!! $user->id !!}', 'solo')">
                    <img src="{{asset('images/avatars/avatar1.png')}}" />
                    <p>
                        <strong>{{$user->name}}</strong>
                        <br>
                        <span>{{$user->email}}</span>
                    </p>
                    <div class="status available"></div>
                </div>
            @endforeach
            @foreach($user_groups as $group)
                {{--<div class="form-check col-6 col-md-4">--}}
                    {{--<input class="form-check-input" meta="group" atr="{{$group->name}}" type="radio" id="{{$group->id}}" value="{{$group->id}}"  name="username" onclick="selectRecipient(event)">--}}
                    {{--<label class="form-check-label" for="{{$group->id}}">{{$group->name}}</label>--}}
                {{--</div>--}}
                <div class="friend" onclick="selectRecipient(event)">
                    <img src="{{asset('images/avatars/avatar2.png')}}" />
                    <p>
                        <strong>{{$group->name}} <span style="font-size: 12px">(Group)</span></strong>
                    </p>
                    <div class="status available"></div>
                </div>
            @endforeach
        </div>
    </div>

    <div id="chatview" class="p1">
        <div id="profile">

            <div id="close">
                <div class="cy"></div>
                <div class="cx"></div>
            </div>

            <p>Miro Badev</p>
            <span>miro@badev@gmail.com</span>
        </div>
        <div id="chat-messages" style="margin-top: 10px">

            {{-- fills with js --}}

        </div>

        <div id="sendmessage">
            <input id="msg-text-box" type="text" value="Send message..." />
            <button onclick="send()" id="send-btn"></button>
        </div>

    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<!-- partial -->
<script>
    var solo_channels = JSON.parse('{!! $user_solo_channels !!}');
    var group_channels = JSON.parse('{!! $user_group_channels !!}');
    var type = 'solo';
    init_listeners();
    setListenerForEnter();

    function setListenerForEnter() {
        var input = document.querySelector("#msg-text-box");
        input.addEventListener("keyup", function(event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                document.querySelector("#send-btn").click();
            }
        });
    }

    function init_listeners() {
        for (let i = 0; i < solo_channels.length; i++) {
            Echo.channel(solo_channels[i])
                .listen('NewMessageEvent', (e) => {
                    console.log(e);
                    document.querySelector('#send-btn').disabled = false;
                    updateMessageList(e.message, e.sender_name, null, e.recipient_name, e);
                    // sendNotif(e.sender_name, e.message, e.recipient_id);
                });
        }
        for (let i = 0; i < group_channels.length; i++) {
            // console.log(channels[i]);
            Echo.channel(group_channels[i])
                .listen('NewGroupMessageEvent', (e) => {
                    console.log(e);
                    document.querySelector('#send-btn').disabled = false;
                    updateMessageList(e.message, e.sender_name, null, e.group_name, e);
                    // sendNotif(e.sender_name, e.message, e.recipient_id);
                });
        }

        Echo.channel('channels.' + {!! auth()->id() !!})
            .listen('NewChannelEvent', (e) => {
                console.log(e);
                updateChannels();
            }).listen('NewGroupEvent', (e) => {
            console.log(e);
            location.reload()
        }).listen('NewFriendEvent', (e) => {
            console.log(e);
            location.reload()
        })
    }

    function updateRecipients(name, id) {

    }

    function selectRecipient(recipient_id, type) {
        localStorage.setItem('recipient_id', recipient_id);
        document.querySelector('#send-btn').disabled = false;
        createChannelRecord(recipient_id, type);
    }

    function createChannelRecord(recipient_id, type) {
        axios.post("{!! route('channel.create') !!}", {recipient_id:recipient_id, type: type}).then((resp) => {
            solo_channels = resp.data.solo;
            group_channels = resp.data.group;
            reset_channels();
            init_listeners();
        }).catch((err)=>{})
    }

    function reset_channels() {
        Echo.disconnect();
        Echo.connect();
    }

    function updateChannels() {
        axios.get("{!! route('channel.read') !!}").then((resp) => {
            solo_channels = resp.data.solo;
            group_channels = resp.data.group;
            reset_channels();
            init_listeners();
        }).catch((err)=>{})
    }

    function selectMember(e) {
        if (members === undefined) {
            let members = [];
        }
        let member_id = e.target.value;
        members.push(member_id);
        localStorage.setItem('members', JSON.stringify(members));
    }

    function createGroup() {
        let name = document.querySelector('#group-name').value;
        let members = [];
        // let members = JSON.parse(localStorage.getItem('members'));
        let nodes = document.querySelectorAll('input[name = "groupMember"]');
        for (let i = 0; i < nodes.length; i++) {
            nodes[i].checked === true ? members.push(nodes[i].value) : null;
        }
        axios.post("{!! route('group.create') !!}",{name: name, members: members}).then((resp) => {
            location.reload();
        }).catch((err) => {
            console.dir(err.response);
            alert(err.response.data);
        })
    }

    function sendNotif(sender_name, message, recp_id) {
        axios.post('{!! route("send.web-notification") !!}', {title: 'New Message From '+ sender_name, body:message, recipient:recp_id}).catch(function (err) {
            console.dir(err.response);
        })
    }

    function send() {
        let msg = document.querySelector('#msg-text-box').value;
        if (type === 'solo') {
            axios.post('{!! route("publish") !!}', {'msg':msg, from: "{!!auth()->id()!!}", to: localStorage.getItem('recipient_id'), type: type}).then(function (resp) {
                console.log('ajax sent.');
                document.querySelector('#msg-text-box').value = '';
                document.querySelector('#send-btn').disabled = true;
            }).catch(function (err) {
                console.log(err);
            })
        } if (type === 'group') {
            axios.post('{!! route("publish") !!}', {'msg':msg, from: "{!! auth()->id() !!}", to: localStorage.getItem('recipient_id'), type: type}).then(function (resp) {
                console.log('ajax sent.');
                document.querySelector('#msg-text-box').value = '';
                document.querySelector('#send-btn').disabled = true;
            }).catch(function (err) {
                console.log(err);
            })
        }

    }

    function updateMessageList(message, sender, recipient = null, group_name = null, echo_object) {
        let main_node = document.querySelector('#chat-messages');
        let auth_id = "{!! auth()->id() !!}";
        if (echo_object.sender_id == auth_id) {
            let sender_box = createSenderBox(message);
            main_node.insertAdjacentHTML('afterbegin', sender_box);
        }else {
            let recipient_box = createRecipientBox(message);
            main_node.insertAdjacentHTML('afterbegin', recipient_box);
        }
    }
    function loadMessages(recipient_id, type) {
        selectRecipient(recipient_id, type);
        axios.post("{{route('pairMessages')}}", {'recipient_id':recipient_id}).then((resp) => {
            console.dir(resp.data);
            let messages = resp.data;
            let auth_id = "{!! auth()->id() !!}";
            let main_node = document.querySelector('#chat-messages');
            for (let i = 0; i < messages.length; i++) {
                if (messages[i].sender_id == auth_id) {
                    let sender_box = createSenderBox(messages[i].body);
                    main_node.insertAdjacentHTML('beforeEnd', sender_box);
                }else {
                    let recipient_box = createRecipientBox(messages[i].body);
                    main_node.insertAdjacentHTML('beforeEnd', recipient_box);
                }
            }
        })
    }

    function createRecipientBox(body) {
        return '<div class="message">\n' +
            '                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/245657/1_copy.jpg" />\n' +
            '                <div class="bubble">\n' + body +
            '                    <div class="corner"></div>\n' +
            '                    <span>3 min</span>\n' +
            '                </div>\n' +
            '            </div>';
    }

    function createSenderBox(body) {
        return '<div class="message right">\n' +
            '                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/245657/2_copy.jpg" />\n' +
            '                <div class="bubble">\n' + body +
            '                    <div class="corner"></div>\n' +
            '                    <span>1 min</span>\n' +
            '                </div>\n' +
            '            </div>';
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
                        console.dir(error);
                    },
                });
            }).catch(function (error) {
            console.dir(error);
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
<script  src="{{asset('js/chat.js')}}"></script>
@endsection