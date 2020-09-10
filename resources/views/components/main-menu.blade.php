<div class="is-280 main-menu" id="main-menu" v-if="showMenu">

    <b-menu-list label="Menu">
    <b-menu-item icon="information-outline" label="Home" tag="a" href="/"></b-menu-item>
    </b-menu-list>
    <b-menu-list label="Projects">
        @foreach($projects as $project)
            <b-menu-item icon="chart-gantt" label="{{$project->name}}" tag="a" href="/project/{{$project->id}}"></b-menu-item>
        @endforeach
    </b-menu-list>
</div>
