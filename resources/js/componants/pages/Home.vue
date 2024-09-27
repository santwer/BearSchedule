<template>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $t('menu.home') }}</h1>
        <a href="/project/create" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <mdicon name="plus" size="16"/>
            {{ $t('menu.new_project') }}</a>
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ $t('general.projects') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ projects.length }}</div>
                        </div>
                        <div class="col-auto text-primary ">
                            <mdicon name="chart-gantt" size="32"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                {{ $t('general.active_projects') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ projects.length }}</div>
                        </div>
                        <div class="col-auto text-warning">
                            <mdicon name="progress-star" size="32"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                {{ $t('collaboration') }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ projects.length }}</div>
                        </div>
                        <div class="col-auto text-info">
                            <mdicon name="account-group" size="32"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <a class="card-body" href="/project/create">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ $t('menu.create_new_project') }}
                            </div>

                            <div class="h5 mb-0 font-weight-bold text-gray-800"></div>
                        </div>
                        <div class="col-auto">
                            <a class="btn btn-success btn-circle btn-xl float-end"
                               href="/project/create">
                                <mdicon name="plus" size="32"/>
                            </a>

                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>


    <!-- Content Row -->
    <div class="row">

        <!-- Content Column -->
        <div class="col-lg-6 mb-4">

            <!-- Project Card Example -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <mdicon name="chart-gantt" size="24" class="position-absolute" style="right: 10px"/>
                        {{ $t('general.projects') }}
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    <a class="list-group-item list-group-item-action" style="cursor: pointer;"
                       v-for="project in projects"
                       @click="goToProject(project.id)">
                        {{ project.name }}
                    </a>
                </div>
            </div>


        </div>

        <div class="col-lg-6 mb-4">

            <!-- Approach -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <mdicon name="github" size="24" class="position-absolute" style="right: 10px"/>
                        Find us on GitHub
                    </h6>
                </div>
                <div class="card-body">
                    <p>
                        This theme is open source and available on GitHub. You can get the source
                        code and contribute to the project to make it even better.
                    </p>
                    <p class="mb-0">
                        <a target="_blank" rel="nofollow" href="https://github.com/santwer/BearSchedule">Browse to Github &rarr;</a>
                    </p>
                </div>
            </div>

        </div>
    </div>

</template>

<script>
import RouteMixin from "@/mixins/RouteMixin";
import ThemeMixin from "@/mixins/ThemeMixin";
import {mapGetters} from "vuex";

export default {
    mixins: [RouteMixin, ThemeMixin],
    computed: {
        ...mapGetters(['archivedProjects', 'projects', 'user']),
    }
}
</script>

<style scoped>

</style>
