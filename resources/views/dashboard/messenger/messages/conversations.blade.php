@extends('dashboard.messenger.layouts.chat')

@section('content')
    <ul id="talkMessages">
         @foreach($messages as $message)
             @if($message->reassignment_message)
                <li class="alert alert-warning" role="alert" style="text-align: center; direction: rtl;">
                    <p class="text-center"> تم إعادة إسناد هذه المحادثة الى {{$message->sender->name}}</p>
                </li>
             @else
                @if($message->sender->id != $conversationData->user_one)
                <li class="sent" id="message-{{$message->id}}" style="text-align: right; direction: rtl;">
                    <img src="{{url('/images/support-avatar.png')}}" alt="" data-toggle="tooltip" data-placement="top" title="{{$message->sender->name}}"/>
                    @if($message->media_url)
                    <div style="margin: 0px 10px;" class="pull-right"><audio controls="" src="{{\Storage::get($message->media_url)}}"></audio></div>
                    {{-- <div class="pull-right"> <p>{{$message->message}}</p></div> --}}
                    @else
                      <div class="pull-right"> {!! $message->message!!}</div>
                        @endif


                     <span class="message-data-time" > {{$message->created_at->diffForHumans()}}</span> &nbsp; &nbsp;
{{--                    <a href="javascript:;" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fa fa-close"></i></a>--}}
                </li>
                @else
                <li class="replies" style="text-align: right;">
                    <img src="{{url('/images/customer-avatar.png')}}" alt="" />
                    @if($message->media_url)
                    <p><audio controls="" src="{{$message->media_url}}"></audio></p>
                        @else


                        <div class="pull-left"> {!! $message->message!!}</div>

                        @endif


                     <span class="message-data-time" > {{$message->created_at->diffForHumans()}}</span> &nbsp; &nbsp;
                    {{-- <a href="javascript:;" class="talkDeleteMessage" data-message-id="{{$message->id}}" title="Delete Message"><i class="fa fa-close"></i></a> --}}
                </li>
                @endif
            @endif {{--if reassignment_message--}}
                @endforeach

            </ul>
@endsection
