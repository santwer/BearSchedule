@extends('layouts.main')

@section('content')
    <b-tabs position="is-right" class="block own-block" v-model="activeTabProject"
            data-tab="{{ isset($activeTab) ? $activeTab : 'timeline' }}" ref="projecttab">
        <b-tab-item label="@lang('project.timeline')" value="timeline" class="tab-timeline">
            <div class="timelineframe">
            <students-timeline project="{{$project}}" role="{{ $role }}" datapath="/ajax/timeline/getdata"  ref="sttimeline"></students-timeline>
            </div>
            <div class="timelineframe-mobil">
                <b-message type="is-warning">
                    @lang('project.mobil-display-message')
                    @lang('project.mobil-display-message-recommend')
                </b-message>
            </div>
        </b-tab-item>
        <b-tab-item label="@lang('project.items')" value="items">
            <edit-table :data="{{ $items }}" :columns="itemColumns" @click="clickItems"></edit-table>
        </b-tab-item>
        <b-tab-item label="@lang('project.groups')" value="groups">
            <edit-table :data="{{ $groups }}" :columns="groupsColumns" @click="clickGroup"></edit-table>
        </b-tab-item>
        <b-tab-item label="@lang('project.share')" value="share">
            <div class="field">
                <b-switch v-model="shareswitch"  {{ $role !== 'ADMIN' ? 'disabled' : '' }}>@lang('project.share')</b-switch>
            </div>

            <b-field label="@lang('project.weblink')" v-if="shareswitch">
                <b-input placeholder="@lang('project.url')"
                         type="search"
                         icon="link-variant"
                         icon-clickable
                         ref="sharelink" v-model="sharelink" data-project="{{$project}}" data-link="{{ $settings->shareUrl() }}"
                         @icon-click="goToUrl"></b-input>

            </b-field>
            <b-button @click="goToUrl" icon-left="link-variant">@lang('general.open')</b-button>
        </b-tab-item>
        <b-tab-item label="@lang('project.settings')" value="settings">
            <form method="POST" action="{{ locale_route('project.update', [$project]) }}">
                @csrf
                <div class="columns">
                    <div class="column">


                        <b-field label="@lang('project.project_name')">
                            <b-input name="name"
                                     value="{{ old('name', $settings->name) }}" {{ $role !== 'ADMIN' ? 'disabled' : '' }}></b-input>
                        </b-field>
                        <b-field
                            label="@lang('project.item_design')">
                            <b-select placeholder="@lang('project.select_itemtype')" name="option[template]"
                                      value="{{ old('option.template', ($settings->option('template', 'value') === null ? 'null' : $settings->option('template', 'value'))) }}"
                                      {{ $role !== 'ADMIN' ? 'disabled' : '' }}
                            expanded>
                                <option value="null">@lang('project.itemtype.default')</option>
                                <option value="timeline.item.default">@lang('project.itemtype.with_subtitle')</option>
                                @if(\App\Helper\JiraHelper::isEnabled())
                                <option value="timeline.item.jira">@lang('project.itemtype.jira_with_subtitle')</option>
                                @endif
                            </b-select>
                        </b-field>
                        <b-field
                            label="@lang('project.initial_zoom')">
                            <b-select placeholder="@lang('project.select_itemtype')" name="option[displayscale]"
                                      value="{{ old('option.displayscale', ($settings->option('displayscale', 'value') === null ? 'null' : $settings->option('displayscale', 'value'))) }}"
                                      {{ $role !== 'ADMIN' ? 'disabled' : '' }}
                                      expanded>
                                <option value="null">@lang('project.zoom_timeline.auto')</option>
                                <option value="year">@lang('project.zoom_timeline.year')</option>
                                <option value="month">@lang('project.zoom_timeline.month')</option>
                                <option value="week">@lang('project.zoom_timeline.week')</option>
                                <option value="day">@lang('project.zoom_timeline.day')</option>
                            </b-select>
                        </b-field>
                        <b-field label="@lang('project.axis_orientation')">
                            <b-select placeholder="@lang('project.select_itemtype')" name="option[orientation.axis]"
                                      value="{{ old('option.orientation.axis', ($settings->option('orientation.axis', 'value') === null ? 'null' : $settings->option('orientation.axis', 'value'))) }}"
                                      {{ $role !== 'ADMIN' ? 'disabled' : '' }}
                                      expanded>
                                <option value="null">@lang('project.axis_ori.bottom')</option>
                                <option value="top">@lang('project.axis_ori.top')</option>
                                <option value="both">@lang('project.axis_ori.both')</option>
                                <option value="none">@lang('project.axis_ori.none')</option>
                            </b-select>
                        </b-field>
                        <b-field label="Item orientation @lang('project.select_itemtype')">
                            <b-select placeholder="@lang('project.select_itemtype')" name="option[orientation.item]"
                                      value="{{ old('option.orientation.item', ($settings->option('orientation.item', 'value') === null ? 'null' : $settings->option('orientation.item', 'value'))) }}"
                                      {{ $role !== 'ADMIN' ? 'disabled' : '' }}
                                      expanded>
                                <option value="null">@lang('project.axis_ori.bottom')</option>
                                <option value="top">@lang('project.axis_ori.top')</option>
                            </b-select>
                        </b-field>

                    @if(\App\Helper\JiraHelper::isEnabled() && $role === 'ADMIN')
                            <b-field label="@lang('project.jira.jira_host')">
                                <b-input name="option[jira_host]" placeholder="https://your-jira.host.com"
                                         value="{{ old('option.jira_host', $settings->option('jira_host', 'value')) }}"></b-input>
                            </b-field>
                            <b-field label="@lang('project.jira.jira_user')">
                                <b-input name="option[jira_user]" placeholder="jira-username" autocomplete="off"
                                         value="{{ old('option.jira_user', $settings->option('jira_user', 'value')) }}"></b-input>
                            </b-field>
                            <b-field label="@lang('project.jira.apitoken')">
                                <b-input name="option[jira_password]" placeholder="jira-password" type="password" autocomplete="new-password"
                                         value="{{ old('option.jira_password', $settings->option('jira_password', 'value')) }}"></b-input>
                            </b-field>
                    @endif

                    </div>
                    <div class="column">
                        <project-users v-if="loadedLang" :users="{{ $settings->users }}"  role="{{ $role }}"></project-users>
                    </div>
                </div>
                @if($role == 'ADMIN')
                    <div class="columns">
                        <div class="column">
                            <b-button icon-left="content-save" native-type="submit">
                                @lang('general.save')
                            </b-button>
                        </div>
                        <div class="column">
                            <b-button icon-left="delete" type="is-danger" v-on:click="deleteProject">@lang('general.delete')</b-button>
                        </div>
                    </div>

                @endif
            </form>
            <form method="POST" id="delete_project" action="{{ locale_route('project.delete', [$project]) }}">
            @csrf
            </form>
        </b-tab-item>
        @if($role == 'ADMIN')
            <b-tab-item label="@lang('project.logs')" value="logs">
                <log-table :data="{{ $logdata }}" ></log-table>
            </b-tab-item>
        @endif
    </b-tabs>


@endsection
