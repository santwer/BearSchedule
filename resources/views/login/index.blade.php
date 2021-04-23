@extends('layouts.main')
@section('content')

    <div style="margin: 30px;">
        <div class="columns">
            <div class="column">
                <b-collapse
                    aria-id="contentIdForA11y2"
                    class="panel"
                    animation="slide">
                    <div
                        slot="trigger"
                        class="panel-heading"
                        role="button"
                        aria-controls="contentIdForA11y2">
                        <strong>@lang('general.dashboard.news')</strong>
                    </div>
                    <div class="panel-block">
                        @lang('general.dashboard.overview_info')
                        @lang('general.dashboard.github_support')
                        <br><br>
                        @lang('general.dashboard.in_dev')
                    </div>
                </b-collapse>
            </div>
            <div class="column">
                <b-collapse
                    aria-id="contentIdForA11y2"
                    class="panel"
                    animation="slide">
                    <div
                        slot="trigger"
                        class="panel-heading"
                        role="button"
                        aria-controls="contentIdForA11y2">
                        <strong>@lang('general.dashboard.changelog')</strong>
                    </div>
                    <div class="panel-block">
                        <div class="mkdown-extras">
                            {{ \App\Helper\ChangelogHelper::getHtml()  }}
                        </div>
                    </div>
                </b-collapse>
            </div>
        </div>
    <div class="columns">
        <div class="column">
            <b-collapse
                aria-id="contentIdForA11y2"
                class="panel"
                animation="slide">
                <div
                    slot="trigger"
                    class="panel-heading"
                    role="button"
                    aria-controls="contentIdForA11y2">
                    <strong>@lang('general.dashboard.activity')</strong>
                </div>
                <div class="panel-block">
                    <div class="mkdown-extras">
                        <activity :data="{{ $acivity }}" label="@lang('general.dashboard.own_projects')"></activity>
                    </div>
                </div>
            </b-collapse>
        </div>
        <div class="column">
            <b-collapse
                aria-id="contentIdForA11y2"
                class="panel"
                animation="slide">
                <div
                    slot="trigger"
                    class="panel-heading"
                    role="button"
                    aria-controls="contentIdForA11y2">
                    <strong>@lang('general.dashboard.general_activity')</strong>
                </div>
                <div class="panel-block">
                    <div class="mkdown-extras">
                        <activity :data="{{ $allAcivity }}" label="@lang('general.dashboard.all_projects')"></activity>
                    </div>
                </div>
            </b-collapse>
        </div>
    </div>
    </div>

@endsection
