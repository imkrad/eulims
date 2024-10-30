<template>
    <Head title="Reports"/>
    <PageHeader title="Reports" pageTitle="List" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <b-row class="g-2 mb-2 mt-n2">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" placeholder="Search Request" class="form-control" style="width: 40%;">
                        <Multiselect class="white" style="width: 15%;" :options="months" v-model="month" label="name" :allow-empty="false" :searchable="true" placeholder="Select Month" />
                        <Multiselect class="white" style="width: 15%;" :options="years" v-model="year" label="name" :searchable="true" placeholder="Select Year" />
                        <b-button @click="refresh()" type="button" variant="primary">
                            <i class="bx bx-refresh"></i> 
                        </b-button>
                    </div>
                </b-col>
            </b-row>
            <hr class="text-muted"/>
            <div class="row">
                <div class="col-md-3">
                    <div class="card bg-light-subtle shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height: 2.5rem; width: 2.5rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="bx bxs-calendar-week text-primary fs-24"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-14"><span class="text-body">List of OP and OR</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">Data Combined from OP and OR
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <div class="d-flex flex-column">
                                <div class="mt-auto">
                                    <div class="d-flex mb-0">
                                        <div class="flex-grow-1">
                                            <div class="text-muted fs-13"><i class="ri-calendar-event-fill me-1 align-bottom"></i>{{info.month}} {{info.year}}</div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="mb-n1 mt-n1">
                                                <b-button @click="openExcel('op')" variant="soft-success" class="me-1" v-b-tooltip.hover title="View Excel" size="sm">
                                                    <i class="ri-file-excel-fill align-bottom"></i>
                                                </b-button>
                                                <b-button @click="openView('op')" variant="soft-info" v-b-tooltip.hover title="View PDF" size="sm">
                                                    <i class="ri-printer-fill align-bottom"></i>
                                                </b-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-light-subtle shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height: 2.5rem; width: 2.5rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="bx bxs-calendar-week text-primary fs-24"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-14"><span class="text-body">RSTL Data</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">A document confirming payment received
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <div class="d-flex flex-column">
                                <div class="mt-auto">
                                    <div class="d-flex mb-0">
                                        <div class="flex-grow-1">
                                            <div class="text-muted fs-13"><i class="ri-calendar-event-fill me-1 align-bottom"></i>{{month}} {{year}}</div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="mb-n1 mt-n1">
                                                <b-button @click="openExcel('rstl')" variant="soft-success" class="me-1" v-b-tooltip.hover title="View Excel" size="sm">
                                                    <i class="ri-file-excel-fill align-bottom"></i>
                                                </b-button>
                                                <b-button @click="openView('rstl')" variant="soft-info" v-b-tooltip.hover title="View PDF" size="sm">
                                                    <i class="ri-printer-fill align-bottom"></i>
                                                </b-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card bg-light-subtle shadow-none border">
                        <div class="card-header bg-light-subtle">
                            <div class="d-flex mb-n3">
                                <div class="flex-shrink-0 me-3">
                                    <div style="height: 2.5rem; width: 2.5rem;">
                                        <span class="avatar-title bg-primary-subtle rounded p-2 mt-n1">
                                            <i class="bx bxs-calendar-week text-primary fs-24"></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="mb-0 fs-14"><span class="text-body">Reconciliation of RSTL and Finance</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">Identifying and resolving discrepancies
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body bg-white">
                            <div class="d-flex flex-column">
                                <div class="mt-auto">
                                    <div class="d-flex mb-0">
                                        <div class="flex-grow-1">
                                            <div class="text-muted fs-13"><i class="ri-calendar-event-fill me-1 align-bottom"></i>{{month}} {{year}}</div>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="mb-n1 mt-n1">
                                                <b-button @click="openExcel('recon')" variant="soft-success" class="me-1" v-b-tooltip.hover title="View Excel" size="sm">
                                                    <i class="ri-file-excel-fill align-bottom"></i>
                                                </b-button>
                                                <b-button @click="openView('recon')" variant="soft-info" v-b-tooltip.hover title="View PDF" size="sm">
                                                    <i class="ri-printer-fill align-bottom"></i>
                                                </b-button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            
        </div>
    </div>
</template>
<script>
import _ from 'lodash';
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
import Pagination from "@/Shared/Components/Pagination.vue";
export default {
    components: { PageHeader, Pagination, Multiselect },
    props:['years','info'],
    data(){
        return {
            currentUrl: window.location.origin,
            month: this.info.month,
            year: this.info.year,
            months: ['January','February','March','April','May','June','July','August','September','October','November','December']
        }
    },
    methods: {
        openView(type){
            window.open('/reports?type='+type+'&month='+this.month+'&year='+this.year+'&option=accounting&subtype=pdf');
        },
        openExcel(type){
            window.open('/reports?type='+type+'&month='+this.month+'&year='+this.year+'&option=accounting&subtype=excel');
        },
    }
}
</script>