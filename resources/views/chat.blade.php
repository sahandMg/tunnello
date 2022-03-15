@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row col-md-8 m-auto">
            <div>
                <input id="msg-text-box" type="text" class="form-control"  placeholder="Type here ..." required style="direction: rtl; text-align: right; font-family: IRANSans">
                <div id="btn-box">
                    <button id="send-btn" class="btn btn-primary mt-3 col-md-3" onclick="send()" disabled>Send</button>
                    <button id="allow-btn" class="btn btn-success mt-3 col-md-3" onclick="startFCM()">Allow Notifications</button>
                    <button id="group-btn" class="btn btn-info mt-3 col-md-3" data-toggle="modal" data-target="#usersModal">Create New Group</button>
                </div>

            </div>
        </div>
        <div class="row col-12 col-md-8 mx-auto mt-1">
            <ul class="row" id="user-box" style="list-style: none; font-weight: bold; font-family: IRANSans-bold; font-size: 20px;">
                @foreach($friends as $user)
                    <div class="form-check col-6 col-md-4">
                        <input class="form-check-input" meta="solo" type="radio" id="{{$user->id}}" value="{{$user->id}}"  name="username" onclick="selectRecipient(event)">
                        <label class="form-check-label" for="{{$user->id}}">{{$user->name}}</label>
                    </div>
                @endforeach
                @foreach($user_groups as $group)
                    <div class="form-check col-6 col-md-4">
                        <input class="form-check-input" meta="group" atr="{{$group->name}}" type="radio" id="{{$group->id}}" value="{{$group->id}}"  name="username" onclick="selectRecipient(event)">
                        <label class="form-check-label" for="{{$group->id}}">{{$group->name}}</label>
                    </div>
                @endforeach
            </ul>
        </div>
        <div class="row card col-md-8 mt-3 mx-auto p-0">
            <div class="card-body" style="height: 500px; overflow: scroll; background: #242853;">
                <ul id="msg-box" class="px-1" style="list-style: none; color: white; font-weight: bold; font-family: IRANSans-bold; font-size: 20px; text-align: right; direction: rtl">
                    @foreach($auth_user_messages as $msg)
                        <li>
                            @if (!$msg->group)
                                <p style="color: yellowgreen; display: inline-block; padding: 0; margin: 0;">{{$msg->recipient->name}} <- {{$msg->sender->name}}</p>
                            @else
                                <p style="color: yellowgreen; display: inline-block; padding: 0; margin: 0;">{{$msg->group->name}} <- {{$msg->sender->name}}</p>
                            @endif
                            <p style="padding: 0; margin: 0">{{$msg->body}}</p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    {{-- Modal --}}
    <div class="modal fade" id="usersModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">New Group</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="group-name">Group Name</label>
                    <input type="text" id="group-name" class="form-control" placeholder="Group name" required>
                    <hr>
                    <p>Select Members</p>
                    <div class="col-12 mt-1 row">
                        @foreach($friends as $user)
                            <div class="form-check col-4">
                                <input class="form-check-input" type="checkbox" id="group-{{$user->id}}" value="{{$user->id}}"  name="groupMember">
                                <label class="form-check-label" for="group-{{$user->id}}">{{$user->name}}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="createGroup()">Create</button>
                </div>
            </div>
        </div>
    </div>
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
                        updateMessageList(e.message, e.sender_name, null, e.recipient_name);
                        // sendNotif(e.sender_name, e.message, e.recipient_id);
                    });
            }
            for (let i = 0; i < group_channels.length; i++) {
                // console.log(channels[i]);
                Echo.channel(group_channels[i])
                    .listen('NewGroupMessageEvent', (e) => {
                        console.log(e);
                        document.querySelector('#send-btn').disabled = false;
                        updateMessageList(e.message, e.sender_name, null, e.group_name);
                        // sendNotif(e.sender_name, e.message, e.recipient_id);
                    });
            }

            Echo.channel('channels.' + {!! auth()->id() !!})
                .listen('NewChannelEvent', (e) => {
                    console.log(e);
                    updateChannels();
                });
        }

        function selectRecipient(e) {
            let recipient_id = e.target.id;
            type = e.target.getAttribute('meta');
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

        function updateMessageList(message, sender, recipient = null, group_name = null) {
            {{--axios.post('/message-list', {from: "{!! \Illuminate\Support\Facades\Auth::id() !!}", to: localStorage.getItem('recipient_id'),  type: type}).then(function (resp) {--}}
            // console.log(resp.data)
            const node_li = document.createElement("li");
            const name_node = document.createElement("p");
            const body_node = document.createElement("p");
            name_node.setAttribute('style', 'color: yellowgreen; padding:0; margin:0');
            body_node.setAttribute('style', 'padding:0; margin:0');
            const textnode_body = document.createTextNode(message);
            if (group_name !== null) {
                var textnode_name = document.createTextNode(group_name + ' <- ' + sender);
            }else {
                var textnode_name = document.createTextNode(recipient + ' <- ' + sender);
            }
            name_node.appendChild(textnode_name);
            body_node.appendChild(textnode_body);
            // node_li.insertBefore(node2, node.firstChild);
            node_li.appendChild(name_node);
            node_li.appendChild(body_node);
            document.getElementById("msg-box").insertBefore(node_li, document.getElementById("msg-box").firstChild);
                // document.getElementById("msg-box").insertBefore(node2, node)
            // })
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
@endsection