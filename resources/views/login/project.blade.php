@extends('layouts.main')

@section('content')
    <b-tabs position="is-right" class="block" v-model="activeTabProject"
            data-tab="{{ isset($activeTab) ? $activeTab : 'timeline' }}" ref="projecttab">
        <b-tab-item label="Timeline" value="timeline">

            <students-timeline project="{{$project}}" role="{{ $role }}" datapath="/ajax/timeline/getdata"  ref="sttimeline"></students-timeline>

        </b-tab-item>
        <b-tab-item label="Items" value="items">
            <edit-table :data="{{ $items }}" :columns="itemColumns" @click="clickItems"></edit-table>
        </b-tab-item>
        <b-tab-item label="Groups" value="groups">
            <edit-table :data="{{ $groups }}" :columns="groupsColumns" @click="clickGroup"></edit-table>
        </b-tab-item>
        <b-tab-item label="Share" value="share">
            <div class="field">
                <b-switch v-model="shareswitch"  {{ $role !== 'ADMIN' ? 'disabled' : '' }}>Share</b-switch>
            </div>

            <b-field label="Link" v-if="shareswitch">
                <b-input placeholder="URL"
                         type="search"
                         icon="link-variant"
                         icon-clickable
                         ref="sharelink" v-model="sharelink" data-project="{{$project}}" data-link="{{ $settings->shareUrl() }}"
                         @icon-click="goToUrl"></b-input>

            </b-field>
            <b-button @click="goToUrl" icon-left="link-variant">Open</b-button>
        </b-tab-item>
        <b-tab-item label="Settings" value="settings">
            <form method="POST" action="{{ route('project.update', [$project]) }}">
                @csrf
                <div class="columns">
                    <div class="column">


                        <b-field label="Project Name">
                            <b-input name="name"
                                     value="{{ old('name', $settings->name) }}" {{ $role !== 'ADMIN' ? 'disabled' : '' }}></b-input>
                        </b-field>
                        <b-field
                            label="Item Design">
                            <b-select placeholder="Select Itemtype" name="option[template]"
                                      value="{{ old('option.template', ($settings->option('template', 'value') === null ? 'null' : $settings->option('template', 'value'))) }}"
                                      {{ $role !== 'ADMIN' ? 'disabled' : '' }}
                            expanded>
                                <option value="null">Default</option>
                                <option value="timeline.item.default">with Subtitle</option>
                            </b-select>
                        </b-field>
                        <b-field
                            label="Initial Zoom">
                            <b-select placeholder="Select Itemtype" name="option[displayscale]"
                                      value="{{ old('option.displayscale', ($settings->option('displayscale', 'value') === null ? 'null' : $settings->option('displayscale', 'value'))) }}"
                                      {{ $role !== 'ADMIN' ? 'disabled' : '' }}
                                      expanded>
                                <option value="null">Auto</option>
                                <option value="year">Year</option>
                                <option value="month">Month</option>
                                <option value="week">Week</option>
                                <option value="day">Day</option>
                            </b-select>
                        </b-field>



                    </div>
                    <div class="column">
                        <project-users :users="{{ $settings->users }}"  role="{{ $role }}"></project-users>
                    </div>
                </div>
                @if($role == 'ADMIN')
                    <div class="columns">
                        <div class="column">
                            <b-button icon-left="content-save" native-type="submit">
                                Save
                            </b-button>
                        </div>
                        <div class="column">
                            <b-button icon-left="delete" type="is-danger" v-on:click="deleteProject">Delete</b-button>
                        </div>
                    </div>

                @endif
            </form>
            <form method="POST" id="delete_project" action="{{ route('project.delete', [$project]) }}">
            @csrf
            </form>
        </b-tab-item>
        @if($role == 'ADMIN')
            <b-tab-item label="Logs" value="logs">
                <log-table :data="{{ $logdata }}" ></log-table>
            </b-tab-item>
        @endif
    </b-tabs>


@endsection
