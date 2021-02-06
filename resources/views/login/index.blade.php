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
                        <strong>News</strong>
                    </div>
                    <div class="panel-block">
                        It's a dashboard. It will help us to have an overview of all projects.
                        GitHub entries are welcome for suggestions on how we can achieve this. We look forward to any suggestions.
                        <br><br>
                        Core functions are currently still being developed.
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
                        <strong>Changelog</strong>
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
                    <strong>Activity</strong>
                </div>
                <div class="panel-block">
                    <div class="mkdown-extras">
                        <activity :data="{{ $acivity }}" label="Own Projects"></activity>
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
                    <strong>General Activity</strong>
                </div>
                <div class="panel-block">
                    <div class="mkdown-extras">
                        <activity :data="{{ $allAcivity }}" label="all Projects"></activity>
                    </div>
                </div>
            </b-collapse>
        </div>
    </div>
    </div>

@endsection
