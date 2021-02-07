<div class="is-280 main-menu" id="main-menu" v-if="showMenu">

    <b-menu-list label="Menu">
    <b-menu-item icon="information-outline" label="Home" tag="a" href="{{ route('home') }}"></b-menu-item>
    <b-menu-item icon="cog" label="Settings" tag="a" href="{{ route('user.settings') }}"></b-menu-item>
    </b-menu-list>
    <b-tooltip label="Create new Project" class="menu-add-project">
        <b-button tag="a" href="/project/create" size="is-small" icon-left="plus-thick" ></b-button>
    </b-tooltip>
    <b-menu-list label="Projects">
        @foreach($projects as $project)
            <b-menu-item icon="chart-gantt" label="{{$project->name}}" {{$projectid == $project->id ? 'active' : '' }}
            tag="a" href="/project/{{$project->id}}"></b-menu-item>
        @endforeach
    </b-menu-list>
    <div class="bs-footer">
        <p class="has-text-grey">
        &copy; {{ env('APP_NAME', 'BearSchedule') }}, {{ \Illuminate\Support\Facades\Date::now()->year }}
        </p>
        <p class="has-text-grey">
            @if(file_exists(storage_path('app/disclaimer.txt')))
                <a href="{{ route('disclaimer') }}" target="_blank">Disclaimer</a>
            @endif
            @if(file_exists(storage_path('app/privacy.txt')))
                &nbsp;·&nbsp;<a href="{{ route('privacy') }}" target="_blank">Privacy Policy</a>
            @endif
            @if(env('SHOW_GITHUB', false))
                &nbsp;·&nbsp;<a href="https://github.com/santwer/BearSchedule"
                     target="_blank">Github</a>
            @endif
        </p>
    </div>
</div>

