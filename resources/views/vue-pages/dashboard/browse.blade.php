@extends('vue-pages.layout.master')
@section('content')
    <router-view
    :categories="{{ json_encode($categories) }}"
    :users="{{ json_encode($users) }}"
    :admins="{{ json_encode($admins) }}"
    :problems="{{ json_encode($problems) }}"
    :problem-options="{{ json_encode($problemOptions) }}"
    ></router-view>
@endsection
