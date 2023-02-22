@foreach($messages as $message)
                            @if($message->owner_type == "employee")
                                <div class="chat is-you">
                                    <div class="chat-avatar">
                                        <div class="user-avatar bg-purple">
                                            <span>{{strtoupper(substr(App\Models\Employee::get_employee_name($message_header->receiver_id), 0, 1))}}{{strtoupper(substr(App\Models\Employee::get_employee_surname($message_header->receiver_id), 0, 1))}}</span>
                                        </div>
                                    </div>
                                    <div class="chat-content">
                                        <div class="chat-bubbles">
                                            <div class="chat-bubble">
                                                <div class="chat-msg"> {{$tr->translate($message->content)}} </div>
                                            </div>
                                        </div>
                                        <ul class="chat-meta">
                                            <!-- <li>Iliash Hossain</li> -->
                                            <li>{{date_format(date_create($message->updated_at),'d/m/Y H:i')}}</li>
                                        </ul>
                                    </div>
                                </div>
                            @else
                                @if($message->is_read == 1)
                                    <div class="chat is-me">
                                        <div class="chat-content">
                                            <div class="chat-bubbles">
                                                <div class="chat-bubble">
                                                    <div class="chat-msg"> {{$tr->translate($message->content)}} </div>
                                                </div>
                                            </div>
                                            <ul class="chat-meta">
                                                <li>READ ON {{date_format(date_create($message->updated_at),'d/m/Y H:i')}}</li>
                                            </ul>
                                        </div>
                                    </div>
                                @else
                                    <div class="chat is-me">
                                        <div class="chat-content">
                                            <div class="chat-bubbles">
                                                <div class="chat-bubble">
                                                    <div class="chat-msg" style = "background : red;"> {{$tr->translate($message->content)}} </div>
                                                </div>
                                            </div>
                                            <ul class="chat-meta">
                                                <li>NOT READ</li>
                                            </ul>
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach