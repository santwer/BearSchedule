<div class="is-280 main-menu" v-bind:class="{ visible: showMenu }" id="main-menu">

    <b-menu-list label="@lang('menu.menu')">
    <b-menu-item icon="information-outline" label="@lang('menu.home')" tag="a" href="{{ locale_route('home') }}"></b-menu-item>
    <b-menu-item icon="cog" label="@lang('menu.settings')" tag="a" href="{{ locale_route('user.settings') }}"></b-menu-item>
    </b-menu-list>
    <b-tooltip label="@lang('menu.create_new_project')" class="menu-add-project">
        <b-button tag="a" href="{{ locale_route('project.create') }}" size="is-small" icon-left="plus-thick" ></b-button>
    </b-tooltip>
    <b-menu-list label="@lang('general.projects')">
        @foreach($projects as $project)
            <b-menu-item icon="chart-gantt" label="{{$project->name}}" {{$projectid == $project->id ? 'active' : '' }}
            tag="a" href="{{ locale_route('project.open', ['project' => $project->id]) }}"></b-menu-item>
        @endforeach
    </b-menu-list>
    <div style="height: 72px;"></div>
    <div class="bs-footer">
        <p class="has-text-grey">
        &copy; {{ env('APP_NAME', 'BearSchedule') }}, {{ \Illuminate\Support\Facades\Date::now()->year }}
            <localization-select :locals="{{ $locals }}" init="{{ user_locale() }}" :extra="true"></localization-select>
        </p>
        <p class="has-text-grey">
            @if(file_exists(storage_path('app/disclaimer.txt')))
                <a href="{{ locale_route('disclaimer') }}" target="_blank">@lang('general.disclaimer')</a>
            @endif
            @if(file_exists(storage_path('app/privacy.txt')))
                &nbsp;·&nbsp;<a href="{{ locale_route('privacy') }}" target="_blank">@lang('general.privacy_policy')</a>
            @endif
            @if(env('SHOW_GITHUB', false))
                &nbsp;·&nbsp;<a href="https://github.com/santwer/BearSchedule"
                     target="_blank">Github</a>
            @endif
        </p>

    </div>
</div>
