<template>
    <PageHeader title="Reports" pageTitle="List" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">

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
                                    <h5 class="mb-0 fs-14"><span class="text-body">Order of Payments</span></h5>
                                    <p class="text-muted text-truncate-two-lines fs-12">Requests a specified payment amount
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
                                    <h5 class="mb-0 fs-14"><span class="text-body">Official Receipts</span></h5>
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
                                                <b-button @click="openExcel('or')" variant="soft-success" class="me-1" v-b-tooltip.hover title="View Excel" size="sm">
                                                    <i class="ri-file-excel-fill align-bottom"></i>
                                                </b-button>
                                                <b-button @click="openView('or')" variant="soft-info" v-b-tooltip.hover title="View PDF" size="sm">
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
            year: this.info.year
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