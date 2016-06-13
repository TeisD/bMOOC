@extends('admin.master')

@section('title', 'Actions')

@section('nav_secondary')
    <li class="@menu('thumbnails')">
        <a href="/admin/actions/thumbnails">thumbnails</a>
    </li>
    <li class="@menu('tags')">
        <a href="/admin/actions/tags">tags</a>
    </li>
    <li class="@menu('migrate')">
        <a href="/admin/actions/migrate">migrate</a>
    </li>
@endsection

@section('content')
    @parent
@endsection


@section('scripts')
    @parent
@endsection
