@extends('admin.actions.master')

@section('content')
    @parent

    <div class="row">
        <div class="columns">
            <em>Version 2.0 introduced some changes to the database structure. This script can be used to migrate topics made in v1 to the new database structure. <strong>You should never use this script, except if you really know what you're doing.</strong> This function will alter the existing tables to match the new structure, add migration entries to the <pre style="display:inline">migrations</pre> table, run  <pre style="display:inline">php artisan migrate</pre> to create the missing topics table and finally copy the topic titles to the topic table.</em>
        </div>
    </div>
    <div class="row">
        <div class="columns">
           <form method="post" onsubmit="return confirm('Do you really want to submit the form?');">
           {!! Form::token(); !!}
            <input type="submit" class="button" value="Migrate"/>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
@endsection
