<template lang="">
    <Head title="Tagging"/>
    <PageHeader title="Tagging" pageTitle="Menu" />
    <div class="chat-wrapper d-lg-flex gap-1 mx-n4 mt-n4 p-1">
        <div class="file-manager-sidebar">
            
        </div>
        <div class="file-manager-content w-100 p-4 pb-0" style="height: calc(100vh - 180px); overflow: auto;" ref="box">
            <b-row class="g-2 mb-2 mt-n2">
                <b-col lg>
                    <div class="input-group mb-1">
                        <span class="input-group-text"> <i class="ri-search-line search-icon"></i></span>
                        <input type="text" v-model="filter.keyword" placeholder="Search Request" class="form-control" style="width: 40%;">
                        <Multiselect class="white" style="width: 15%;" :options="months" v-model="filter.month" label="name" :searchable="true" placeholder="Select Month" />
                        <b-button @click="refresh()" type="button" variant="primary">
                            <i class="bx bx-refresh"></i> 
                        </b-button>
                    </div>
                </b-col>
            </b-row>

            <div class="row g-3 mt-3">
                <div class="col-md-4 mt-0">
                    <BCard class="mb-3 shadow-none border" no-body>
                        <BLink class="card-header bg-warning-subtle" role="button" style="border-bottom: 0;">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0" style="height: 2.1rem; width: 2.1rem;">
                                    <span class="avatar-title bg-warning text-primary rounded-circle fs-4">
                                        <i class="ri-add-circle-fill text-light align-middle"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0"><span class="counter-value fs-13">Pending Tests</span></h4>
                                    <p class="fs-11 text-muted mb-1">{{pendings.length}} Sample ready for test.</p>
                                </div>
                            </div>
                        </BLink>
                    </BCard>
                    <simplebar data-simplebar style="height: calc(100vh - 402px);">
                        <BRow v-if="pendings.length > 0">
                            <BCol lg="12" class="project-card mb-n3" v-for="(item, index) of pendings" :key="index">
                                <div class="card bg-light-subtle shadow-none border" style="cursor: pointer;" @click="openShow(item,'Pending')">
                                    <div class="card-header bg-light-subtle">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 text-muted">
                                                <h6 class="card-title mb-n1 fs-14 fw-semibold"><span class="text-primary">{{item.code}}</span></h6>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i>{{item.tsr.due_at}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column h-100">
                                            <div class="mt-auto">
                                                <div class="d-flex mb-2">
                                                    <div class="flex-grow-1">
                                                        <div class="text-muted">{{item.name}}</div>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div>
                                                            <i class="ri-list-check align-bottom me-1 text-muted"></i>
                                                            {{(item.ongoing)}}/{{(item.count-item.completed)}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress progress-sm animated-progess">
                                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" :style="`width: ${item.progressBar};`"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </BCol>
                        </BRow>
                        <div v-else class="alert alert-light mb-0 text-center" role="alert"><span class="fs-12 text-muted">No test available</span></div>
                    </simplebar>
                </div>
                <div class="col-md-4 mt-0">
                    <BCard class="mb-3 shadow-none border" no-body>
                        <BLink class="card-header bg-info-subtle" role="button" style="border-bottom: 0;">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0" style="height: 2.1rem; width: 2.1rem;">
                                    <span class="avatar-title bg-info text-primary rounded-circle fs-4">
                                        <i class="ri-record-circle-fill text-light align-middle"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0"><span class="counter-value fs-13">Ongoing Tests</span></h4>
                                    <p class="fs-11 text-muted mb-1">{{ongoings.length}} ongoing analyzation.</p>
                                </div>
                            </div>
                        </BLink>
                    </BCard>
                    <simplebar data-simplebar style="height: calc(100vh - 402px);">
                        <BRow v-if="ongoings.length > 0">
                            <BCol lg="12" class="project-card mb-n3" v-for="(item, index) of ongoings" :key="index">
                                <div class="card bg-light-subtle shadow-none border" style="cursor: pointer;" @click="openShow(item,'Ongoing')">
                                    <div class="card-header  bg-light-subtle">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-grow-1 text-muted">
                                                <h6 class="card-title mb-n1 fs-14 fw-semibold"><span class="text-primary">{{item.code}}</span></h6>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i>{{item.tsr.due_at}}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex flex-column h-100">
                                            <div class="mt-auto">
                                                <div class="d-flex mb-2">
                                                    <div class="flex-grow-1">
                                                        <div class="text-muted">{{item.name}}</div>
                                                    </div>
                                                    <div class="flex-shrink-0">
                                                        <div>
                                                            <i class="ri-list-check align-bottom me-1 text-muted"></i>{{item.ongoing}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="progress progress-sm animated-progess">
                                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" :style="`width: ${item.progressBar};`"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </BCol>
                        </BRow>
                        <div v-else class="alert alert-light mb-0 text-center" role="alert"><span class="fs-12 text-muted">No test available</span></div>
                    </simplebar>
                </div>
                <div class="col-md-4 mt-0">
                    <BCard class="mb-3 shadow-none border" no-body>
                        <BLink class="card-header bg-success-subtle" role="button" style="border-bottom: 0;">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0" style="height: 2.1rem; width: 2.1rem;">
                                    <span class="avatar-title bg-success text-primary rounded-circle fs-4">
                                        <i class="ri-checkbox-circle-fill text-light align-middle"></i>
                                    </span>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h4 class="mb-0"><span class="counter-value fs-13">Samples Completed</span></h4>
                                    <p class="fs-11 text-muted mb-1">{{completeds.length}} samples completed.</p>
                                </div>
                            </div>
                        </BLink>
                    </BCard>
                    <simplebar data-simplebar style="height: calc(100vh - 402px);">
                        <BRow v-if="completeds.length > 0">
                        <BCol lg="12" class="project-card mb-n3" v-for="(item, index) of completeds" :key="index">
                            <div class="card bg-light-subtle shadow-none border" style="cursor: pointer;" @click="openShow(item,'Completed')">
                                <div class="card-header  bg-light-subtle">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-grow-1 text-muted">
                                            <h6 class="card-title mb-n1 fs-14 fw-semibold"><span class="text-primary">{{item.code}}</span></h6>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <div class="text-muted"><i class="ri-calendar-event-fill me-1 align-bottom"></i>{{item.tsr.due_at}}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-column h-100">
                                        <div class="mt-auto">
                                            <div class="d-flex mb-2">
                                                <div class="flex-grow-1">
                                                    <div class="text-muted">{{item.name}}</div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div>
                                                        <i class="ri-list-check align-bottom me-1 text-muted"></i>{{item.completed}}/{{item.count}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="progress progress-sm animated-progess">
                                                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="34" aria-valuemin="0" aria-valuemax="100" :style="`width: ${(item.completed/item.count)*100}%;`"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            </div>
                        </BCol>
                    </BRow>
                    <div v-else class="alert alert-light mb-0 text-center" role="alert"><span class="fs-12 text-muted">No test available</span></div>
                    </simplebar>
                </div>
            </div>
        </div>
    </div>
    <Show ref="show"/>
</template>
<script>
import Show from './Modals/Show.vue';
import simplebar from "simplebar-vue";
import Multiselect from "@vueform/multiselect";
import PageHeader from '@/Shared/Components/PageHeader.vue';
export default {
    components: { PageHeader, Multiselect, simplebar, Show },
    data(){
        return {
            pendings: [],
            ongoings: [],
            completeds: [],
            filter: {
                keyword: null,
                month: null
            },
            months: [
                { value: '1', name: 'January' },
                { value: '2', name: 'February' },
                { value: '3', name: 'March' },
                { value: '4', name: 'April' },
                { value: '5', name: 'May' },
                { value: '6', name: 'June' },
                { value: '7', name: 'July' },
                { value: '8', name: 'August' },
                { value: '9', name: 'September' },
                { value: '10', name: 'October' },
                { value: '11', name: 'November' },
                { value: '12', name: 'December' }
            ]
        }
    },
    created(){
        this.fetch();
    },
    methods: {
        fetch(page_url){
            page_url = page_url || '/tagging';
            axios.get(page_url,{
                params : {
                    keyword: this.filter.keyword,
                    month: this.filter.month,
                    reminder: this.filter.reminder,
                    option: 'lists'
                }
            })
            .then(response => {
                if(response){
                    this.pendings = response.data.pendings;
                    this.ongoings = response.data.ongoings;
                    this.completeds = response.data.completeds;          
                }
            })
            .catch(err => console.log(err));
        },
        openShow(data,status){
            this.$refs.show.show(data,status);
        },  
    }
}
</script>