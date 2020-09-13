@extends('layouts.main')

@section('content')
    <b-tabs position="is-right" class="block" v-model="activeTabProject"
            data-tab="{{ isset($activeTab) ? $activeTab : 'timeline' }}" ref="projecttab">
        <b-tab-item label="Timeline" value="timeline">

            <students-timeline project="{{$project}}" role="{{ $role }}" datapath="/ajax/timeline/getdata"></students-timeline>

        </b-tab-item>
        <b-tab-item label="Items" value="items">
            <b-table :data="{{ $items }}" :columns="itemColumns"></b-table>

        </b-tab-item>
        <b-tab-item label="Groups" value="groups">
            <b-table :data="{{ $groups }}" :columns="groupsColumns"></b-table>

        </b-tab-item>
        <b-tab-item label="Share" value="share">
            <div class="field">
                <b-switch v-model="shareswitch">Share</b-switch>
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
                            label="Item">
                            <b-select placeholder="Select Itemtype" name="option[template]"
                                      value="{{ old('option.template', ($settings->option('template', 'value') === null ? 'null' : $settings->option('template', 'value'))) }}"
                                      {{ $role !== 'ADMIN' ? 'disabled' : '' }}
                            expanded>
                                <option value="null">Default</option>
                                <option value="timeline.item.default">with Subtitle</option>
                            </b-select>
                        </b-field>

                    </div>
                    <div class="column">
                        <project-users :users="{{ $settings->users }}"  role="{{ $role }}"></project-users>
                    </div>
                </div>
                @if($role == 'ADMIN')
                    <b-button icon-left="content-save" native-type="submit">
                        Save
                    </b-button>
                @endif
            </form>
        </b-tab-item>
    </b-tabs>


@endsection
